<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OnboardingProcess;
use App\Models\OnboardingDocument;
use App\Models\TrainingModule;
use App\Models\EmployeeTrainingAssignment;
use App\Models\JoiningChecklist;
use App\Models\JoiningChecklistTemplate;
use App\Models\Candidate;
use App\Models\EmployeeProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class OnboardingController extends Controller
{
    /**
     * Display onboarding processes list
     */
    public function process(Request $request)
    {
        return view('onboarding.process.index');
    }

    /**
     * Get onboarding processes for DataTables
     */
    public function getProcesses(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        $status = $request->input('status');

        $query = OnboardingProcess::with(['candidate', 'employeeProfile.user', 'user', 'assignedTo']);

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->whereHas('candidate', function($candidateQuery) use ($search) {
                    $candidateQuery->where('first_name', 'like', '%' . $search . '%')
                                   ->orWhere('last_name', 'like', '%' . $search . '%')
                                   ->orWhere('email', 'like', '%' . $search . '%');
                })
                ->orWhereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%')
                              ->orWhere('email', 'like', '%' . $search . '%');
                });
            });
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $totalRecords = OnboardingProcess::count();
        $filteredRecords = $query->count();

        $processes = $query->orderBy('joining_date', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $processes->map(function ($process) {
            $statusBadges = [
                'pending' => '<span class="badge bg-secondary">Pending</span>',
                'in-progress' => '<span class="badge bg-info">In Progress</span>',
                'completed' => '<span class="badge bg-success">Completed</span>',
                'on-hold' => '<span class="badge bg-warning">On Hold</span>',
                'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
            ];

            $name = $process->user ? $process->user->name : ($process->candidate ? $process->candidate->full_name : 'N/A');

            return [
                'id' => $process->id,
                'name' => $name,
                'joining_date' => $process->joining_date ? \Carbon\Carbon::parse($process->joining_date)->format('d M Y') : 'N/A',
                'status' => $statusBadges[$process->status] ?? '<span class="badge bg-secondary">' . $process->status . '</span>',
                'assigned_to' => $process->assignedTo->name ?? 'N/A',
            ];
        });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->values()->toArray()
        ]);
    }

    /**
     * Show the form for creating a new onboarding process
     */
    public function createProcess()
    {
        $candidates = Candidate::where('status', 'hired')->get();
        $employees = EmployeeProfile::with('user')->get();
        $users = User::all();
        $checklistTemplates = JoiningChecklistTemplate::where('status', 'active')->orderBy('sort_order')->get();
        return view('onboarding.process.create', compact('candidates', 'employees', 'users', 'checklistTemplates'));
    }

    /**
     * Store a newly created onboarding process
     */
    public function storeProcess(Request $request)
    {
        $validated = $request->validate([
            'candidate_id' => 'nullable|exists:candidates,id',
            'employee_profile_id' => 'nullable|exists:employee_profile,id',
            'user_id' => 'nullable|exists:users,id',
            'joining_date' => 'required|date',
            'status' => 'nullable|in:pending,in-progress,completed,on-hold,cancelled',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        try {
            $validated['created_by'] = auth()->id();
            $validated['status'] = $validated['status'] ?? 'pending';

            $process = OnboardingProcess::create($validated);

            // Create checklists from selected templates
            $selectedTemplates = $request->input('checklist_templates', []);
            if (!empty($selectedTemplates)) {
                $templates = JoiningChecklistTemplate::whereIn('id', $selectedTemplates)->orderBy('sort_order')->get();
                foreach ($templates as $template) {
                    JoiningChecklist::create([
                        'onboarding_process_id' => $process->id,
                        'checklist_template_id' => $template->id,
                        'task_name' => $template->task_name,
                        'description' => $template->description,
                        'task_category' => $template->task_category,
                        'status' => 'pending',
                        'sort_order' => $template->sort_order,
                    ]);
                }
            }

            return redirect()->route('onboarding.process')
                ->with('success', 'Onboarding process created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating onboarding process: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing an onboarding process
     */
    public function editProcess($id)
    {
        $process = OnboardingProcess::with(['candidate', 'employeeProfile.user', 'user', 'documents', 'checklists', 'trainingAssignments'])->findOrFail($id);
        $candidates = Candidate::where('status', 'hired')->get();
        $employees = EmployeeProfile::with('user')->get();
        $users = User::all();
        $checklistTemplates = JoiningChecklistTemplate::where('status', 'active')->orderBy('sort_order')->get();
        $selectedTemplates = $process->checklists()->whereNotNull('checklist_template_id')->pluck('checklist_template_id')->toArray();
        return view('onboarding.process.edit', compact('process', 'candidates', 'employees', 'users', 'checklistTemplates', 'selectedTemplates'));
    }

    /**
     * Update the specified onboarding process
     */
    public function updateProcess(Request $request, $id)
    {
        $process = OnboardingProcess::findOrFail($id);

        $validated = $request->validate([
            'candidate_id' => 'nullable|exists:candidates,id',
            'employee_profile_id' => 'nullable|exists:employee_profile,id',
            'user_id' => 'nullable|exists:users,id',
            'joining_date' => 'required|date',
            'completion_date' => 'nullable|date',
            'status' => 'nullable|in:pending,in-progress,completed,on-hold,cancelled',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        try {
            $validated['updated_by'] = auth()->id();
            if ($validated['status'] === 'completed' && !$process->completion_date) {
                $validated['completion_date'] = now();
            }

            $process->update($validated);

            // Handle checklist templates - add new ones if selected
            $selectedTemplates = $request->input('checklist_templates', []);
            if (!empty($selectedTemplates)) {
                $existingTemplateIds = $process->checklists()->whereNotNull('checklist_template_id')->pluck('checklist_template_id')->toArray();
                $newTemplateIds = array_diff($selectedTemplates, $existingTemplateIds);
                
                if (!empty($newTemplateIds)) {
                    $templates = JoiningChecklistTemplate::whereIn('id', $newTemplateIds)->orderBy('sort_order')->get();
                    foreach ($templates as $template) {
                        JoiningChecklist::create([
                            'onboarding_process_id' => $process->id,
                            'checklist_template_id' => $template->id,
                            'task_name' => $template->task_name,
                            'description' => $template->description,
                            'task_category' => $template->task_category,
                            'status' => 'pending',
                            'sort_order' => $template->sort_order,
                        ]);
                    }
                }
            }

            return redirect()->route('onboarding.process')
                ->with('success', 'Onboarding process updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating onboarding process: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified onboarding process
     */
    public function destroyProcess($id)
    {
        try {
            $process = OnboardingProcess::findOrFail($id);
            $process->delete();

            return response()->json(['success' => true, 'message' => 'Onboarding process deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting onboarding process: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display onboarding documents list
     */
   public function documents(Request $request)
{
    $processId = $request->input('process_id');

    $process = $processId
        ? OnboardingProcess::with('candidate')->find($processId)
        : null;

    // Get unique candidates and users from documents for filter dropdown
    $candidates = collect();
    
    // Get candidates
    $candidateDocs = \App\Models\OnboardingDocument::with('onboardingProcess.candidate')
        ->whereHas('onboardingProcess.candidate')
        ->get();
    
    foreach ($candidateDocs as $doc) {
        if ($doc->onboardingProcess->candidate) {
            $candidate = $doc->onboardingProcess->candidate;
            $candidates->push([
                'id' => 'candidate_' . $candidate->id,
                'name' => $candidate->first_name . ' ' . $candidate->last_name,
                'type' => 'candidate',
                'candidate_id' => $candidate->id,
            ]);
        }
    }
    
    // Get users
    $userDocs = \App\Models\OnboardingDocument::with('onboardingProcess.user')
        ->whereHas('onboardingProcess.user')
        ->get();
    
    foreach ($userDocs as $doc) {
        if ($doc->onboardingProcess->user) {
            $user = $doc->onboardingProcess->user;
            $candidates->push([
                'id' => 'user_' . $user->id,
                'name' => $user->name,
                'type' => 'user',
                'user_id' => $user->id,
            ]);
        }
    }
    
    $candidates = $candidates->unique('id')->values()->sortBy('name')->values();

    return view('onboarding.documents.index', compact('process', 'candidates'));
}

    /**
     * Get onboarding documents for DataTables
     */
  public function getDocuments(Request $request)
{
    $draw = $request->input('draw');
    $start = $request->input('start', 0);
    $length = $request->input('length', 25);
    $search = $request->input('search.value', '');
    $processId = $request->input('process_id');
    $status = $request->input('status');
    $candidateId = $request->input('candidate_id');

    // Load documents with related onboarding process and candidate
    $query = OnboardingDocument::with(['onboardingProcess.candidate', 'verifier']);

    // Filter by process ID
    if ($processId) {
        $query->where('onboarding_process_id', $processId);
    }

    // Filter by candidate/user ID
    if (!empty($candidateId)) {
        if (strpos($candidateId, 'candidate_') === 0) {
            $id = str_replace('candidate_', '', $candidateId);
            $query->whereHas('onboardingProcess', function ($q) use ($id) {
                $q->where('candidate_id', $id);
            });
        } elseif (strpos($candidateId, 'user_') === 0) {
            $id = str_replace('user_', '', $candidateId);
            $query->whereHas('onboardingProcess', function ($q) use ($id) {
                $q->where('user_id', $id);
            });
        }
    }

    // Filter by status
    if (!empty($status)) {
        $query->where('status', $status);
    }

    // Global search (document + candidate names)
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('document_name', 'like', "%{$search}%")
              ->orWhere('document_type', 'like', "%{$search}%")
              ->orWhereHas('onboardingProcess.candidate', function ($q2) use ($search) {
                  $q2->where('first_name', 'like', "%{$search}%")
                     ->orWhere('last_name', 'like', "%{$search}%");
              });
        });
    }

    $totalRecords = OnboardingDocument::count();
    $filteredRecords = $query->count();

    $documents = $query->orderBy('created_at', 'desc')
        ->skip($start)
        ->take($length)
        ->get();

    $statusBadges = [
        'pending' => '<span class="badge bg-secondary">Pending</span>',
        'submitted' => '<span class="badge bg-info">Submitted</span>',
        'verified' => '<span class="badge bg-success">Verified</span>',
        'rejected' => '<span class="badge bg-danger">Rejected</span>',
    ];

    $data = $documents->map(function ($doc) use ($statusBadges) {
        $candidate = $doc->onboardingProcess?->candidate;

        return [
            'id' => $doc->id,
            'candidate_name' => $candidate ? ($candidate->first_name . ' ' . $candidate->last_name) : 'N/A',
            'document_name' => $doc->document_name,
            'document_type' => ucfirst(str_replace('_', ' ', $doc->document_type)),
            'status' => $statusBadges[$doc->status] ?? '<span class="badge bg-secondary">' . $doc->status . '</span>',
            'status_raw' => $doc->status,
            'submitted_date' => $doc->submitted_date ? $doc->submitted_date->format('d M Y') : 'N/A',
            'verified_by' => $doc->verifier?->name ?? 'N/A',
        ];
    });

    return response()->json([
        'draw' => intval($draw),
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $filteredRecords,
        'data' => $data->values()->toArray(),
    ]);
}


    /**
     * Show the form for creating a new document
     */
    public function createDocument(Request $request)
    {
        $processId = $request->input('process_id');
        $processes = OnboardingProcess::with(['user', 'candidate'])->get();
        $process = $processId ? OnboardingProcess::find($processId) : null;
        return view('onboarding.documents.create', compact('processes', 'process'));
    }

    /**
     * Store a newly created document(s)
     */
    public function storeDocument(Request $request)
    {
        $validated = $request->validate([
            'onboarding_process_id' => 'required|exists:onboarding_processes,id',
            'documents' => 'required|array|min:1',
            'documents.*.include' => 'nullable',
            'documents.*.document_type' => 'required_with:documents.*.include|string|max:255',
            'documents.*.document_name' => 'required_with:documents.*.include|string|max:255',
            'documents.*.file_path' => 'required_with:documents.*.include|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'documents.*.status' => 'nullable|in:pending,submitted,verified,rejected',
        ]);

        try {
            $uploadedCount = 0;
            $documents = $request->input('documents', []);
            
            foreach ($documents as $index => $document) {
                // Only process if checkbox is checked (checkbox sends 'on' when checked)
                if (!isset($document['include']) || empty($document['include'])) {
                    continue;
                }
                
                $file = $request->file("documents.{$index}.file_path");
                if (!$file || !$file->isValid()) {
                    continue;
                }
                
                $docData = [
                    'onboarding_process_id' => $validated['onboarding_process_id'],
                    'document_type' => $document['document_type'],
                    'document_name' => $document['document_name'],
                    'file_path' => $file->store('onboarding-documents', 'public'),
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'submitted_date' => now(),
                    'status' => $document['status'] ?? 'submitted',
                    'created_by' => auth()->id(),
                ];
                
                OnboardingDocument::create($docData);
                $uploadedCount++;
            }
            
            if ($uploadedCount === 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Please select at least one document to upload.');
            }

            return redirect()->route('onboarding.documents', ['process_id' => $validated['onboarding_process_id']])
                ->with('success', $uploadedCount . ' document(s) uploaded successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error uploading document(s): ' . $e->getMessage());
        }
    }

    /**
     * Update document verification status
     */
    public function updateDocumentStatus(Request $request, $id)
    {
        $document = OnboardingDocument::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,submitted,verified,rejected',
            'verification_notes' => 'nullable|string',
        ]);

        try {
            $validated['verified_by'] = auth()->id();
            if ($validated['status'] === 'verified' || $validated['status'] === 'rejected') {
                $validated['verified_date'] = now();
            }

            $document->update($validated);

            return response()->json(['success' => true, 'message' => 'Document status updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error updating document status: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified document
     */
    public function destroyDocument($id)
    {
        try {
            $document = OnboardingDocument::findOrFail($id);
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }
            $document->delete();

            return response()->json(['success' => true, 'message' => 'Document deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting document: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display training modules list
     */
    public function training(Request $request)
    {
        return view('onboarding.training.index');
    }

    /**
     * Get training modules for DataTables
     */
    public function getTrainingModules(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        $status = $request->input('status');

        $query = TrainingModule::with(['department', 'designation']);

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $totalRecords = TrainingModule::count();
        $filteredRecords = $query->count();

        $modules = $query->orderBy('sort_order')->orderBy('created_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $modules->map(function ($module) {
            $statusBadges = [
                'draft' => '<span class="badge bg-secondary">Draft</span>',
                'published' => '<span class="badge bg-success">Published</span>',
                'archived' => '<span class="badge bg-dark">Archived</span>',
            ];

            return [
                'id' => $module->id,
                'title' => $module->title,
                'module_type' => ucfirst($module->module_type ?? 'N/A'),
                'duration_minutes' => $module->duration_minutes ? $module->duration_minutes . ' mins' : 'N/A',
                'department' => $module->department->name ?? 'All',
                'status' => $statusBadges[$module->status] ?? '<span class="badge bg-secondary">' . $module->status . '</span>',
            ];
        });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->values()->toArray()
        ]);
    }

    /**
     * Show the form for creating a new training module
     */
    public function createTraining()
    {
        $departments = \App\Models\Department::all();
        $designations = \App\Models\Designation::all();
        return view('onboarding.training.create', compact('departments', 'designations'));
    }

    /**
     * Store a newly created training module
     */
    public function storeTraining(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'module_type' => 'nullable|string|max:255',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,mp4,avi,mov|max:102400',
            'video_url' => 'nullable|url',
            'duration_minutes' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'nullable|in:draft,published,archived',
            'department_id' => 'nullable|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
        ]);

        try {
            if ($request->hasFile('file_path')) {
                $validated['file_path'] = $request->file('file_path')->store('training-modules', 'public');
            }

            $validated['created_by'] = auth()->id();
            $validated['status'] = $validated['status'] ?? 'draft';
            $validated['sort_order'] = $validated['sort_order'] ?? 0;

            TrainingModule::create($validated);

            return redirect()->route('onboarding.training')
                ->with('success', 'Training module created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating training module: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing a training module
     */
    public function editTraining($id)
    {
        $module = TrainingModule::findOrFail($id);
        $departments = \App\Models\Department::all();
        $designations = \App\Models\Designation::all();
        return view('onboarding.training.edit', compact('module', 'departments', 'designations'));
    }

    /**
     * Update the specified training module
     */
    public function updateTraining(Request $request, $id)
    {
        $module = TrainingModule::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'module_type' => 'nullable|string|max:255',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,mp4,avi,mov|max:102400',
            'video_url' => 'nullable|url',
            'duration_minutes' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'nullable|in:draft,published,archived',
            'department_id' => 'nullable|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
        ]);

        try {
            if ($request->hasFile('file_path')) {
                if ($module->file_path) {
                    Storage::disk('public')->delete($module->file_path);
                }
                $validated['file_path'] = $request->file('file_path')->store('training-modules', 'public');
            }

            $validated['updated_by'] = auth()->id();
            $module->update($validated);

            return redirect()->route('onboarding.training')
                ->with('success', 'Training module updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating training module: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified training module
     */
    public function destroyTraining($id)
    {
        try {
            $module = TrainingModule::findOrFail($id);
            if ($module->file_path) {
                Storage::disk('public')->delete($module->file_path);
            }
            $module->delete();

            return response()->json(['success' => true, 'message' => 'Training module deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting training module: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display joining checklist
     */
    public function checklist(Request $request)
    {
        $processId = $request->input('process_id');
        $process = $processId ? OnboardingProcess::with(['checklists', 'user', 'candidate'])->find($processId) : null;
        
        // Get all processes for dropdown
        $processes = OnboardingProcess::with(['user', 'candidate'])
            ->orderBy('joining_date', 'desc')
            ->get()
            ->map(function ($p) {
                $name = $p->user ? $p->user->name : ($p->candidate ? $p->candidate->full_name : 'N/A');
                return [
                    'id' => $p->id,
                    'name' => $name,
                    'joining_date' => $p->joining_date ? \Carbon\Carbon::parse($p->joining_date)->format('d M Y') : 'N/A',
                ];
            });
        
        return view('onboarding.checklist.index', compact('process', 'processes'));
    }

    /**
     * Get joining checklists for DataTables
     */
    public function getChecklists(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $processId = $request->input('process_id');
        $status = $request->input('status');

        if (!$processId) {
            return response()->json([
                'draw' => intval($draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }

        $query = JoiningChecklist::with(['onboardingProcess', 'assignedTo', 'completedBy'])
            ->where('onboarding_process_id', $processId);

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $totalRecords = JoiningChecklist::where('onboarding_process_id', $processId)->count();
        $filteredRecords = $query->count();

        $checklists = $query->orderBy('sort_order')->orderBy('created_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $checklists->map(function ($checklist) {
            $statusBadges = [
                'pending' => '<span class="badge bg-secondary">Pending</span>',
                'in-progress' => '<span class="badge bg-info">In Progress</span>',
                'completed' => '<span class="badge bg-success">Completed</span>',
                'skipped' => '<span class="badge bg-warning">Skipped</span>',
            ];

            return [
                'id' => $checklist->id,
                'task_name' => $checklist->task_name,
                'description' => $checklist->description,
                'task_category' => ucfirst($checklist->task_category ?? 'General'),
                'status' => $statusBadges[$checklist->status] ?? '<span class="badge bg-secondary">' . $checklist->status . '</span>',
                'status_raw' => $checklist->status,
                'assigned_to' => $checklist->assignedTo->name ?? 'N/A',
                'assigned_to_id' => $checklist->assigned_to,
                'due_date' => $checklist->due_date ? \Carbon\Carbon::parse($checklist->due_date)->format('d M Y') : 'N/A',
                'due_date_raw' => $checklist->due_date ? \Carbon\Carbon::parse($checklist->due_date)->format('Y-m-d') : '',
                'completed_date' => $checklist->completed_date ? \Carbon\Carbon::parse($checklist->completed_date)->format('d M Y') : 'N/A',
            ];
        });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->values()->toArray()
        ]);
    }

    /**
     * Get checklist details
     */
    public function getChecklist($id)
    {
        try {
            $checklist = JoiningChecklist::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $checklist->id,
                    'task_name' => $checklist->task_name,
                    'description' => $checklist->description,
                    'task_category' => $checklist->task_category,
                    'assigned_to' => $checklist->assigned_to,
                    'due_date' => $checklist->due_date ? $checklist->due_date->format('Y-m-d') : '',
                    'status' => $checklist->status,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Checklist not found.'], 404);
        }
    }

    /**
     * Update checklist status
     */
    public function updateChecklistStatus(Request $request, $id)
    {
        $checklist = JoiningChecklist::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,in-progress,completed,skipped',
            'completion_notes' => 'nullable|string',
        ]);

        try {
            if ($validated['status'] === 'completed' && !$checklist->completed_date) {
                $validated['completed_date'] = now();
                $validated['completed_by'] = auth()->id();
            }

            $checklist->update($validated);

            return response()->json(['success' => true, 'message' => 'Checklist status updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error updating checklist status: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a new checklist manually
     */
    public function storeChecklist(Request $request)
    {
        $validated = $request->validate([
            'onboarding_process_id' => 'required|exists:onboarding_processes,id',
            'task_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'task_category' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'status' => 'nullable|in:pending,in-progress,completed,skipped',
        ]);

        try {
            $validated['status'] = $validated['status'] ?? 'pending';
            $validated['sort_order'] = JoiningChecklist::where('onboarding_process_id', $validated['onboarding_process_id'])->max('sort_order') + 1 ?? 0;

            JoiningChecklist::create($validated);

            return response()->json(['success' => true, 'message' => 'Checklist added successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error adding checklist: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update checklist
     */
    public function updateChecklist(Request $request, $id)
    {
        $checklist = JoiningChecklist::findOrFail($id);

        $validated = $request->validate([
            'task_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'task_category' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'status' => 'nullable|in:pending,in-progress,completed,skipped',
        ]);

        try {
            $checklist->update($validated);

            return response()->json(['success' => true, 'message' => 'Checklist updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error updating checklist: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Assign checklist to user
     */
    public function assignChecklist(Request $request, $id)
    {
        $checklist = JoiningChecklist::findOrFail($id);

        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'due_date' => 'nullable|date',
        ]);

        try {
            $checklist->update($validated);

            return response()->json(['success' => true, 'message' => 'Checklist assigned successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error assigning checklist: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete checklist
     */
    public function destroyChecklist($id)
    {
        try {
            $checklist = JoiningChecklist::findOrFail($id);
            $checklist->delete();

            return response()->json(['success' => true, 'message' => 'Checklist deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting checklist: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display checklist templates list
     */
    public function checklistTemplates()
    {
        return view('onboarding.checklist-templates.index');
    }

    /**
     * Get checklist templates for DataTables
     */
    public function getChecklistTemplates(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        $status = $request->input('status');

        $query = JoiningChecklistTemplate::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('task_name', 'like', "%{$search}%")
                  ->orWhere('task_category', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $totalRecords = JoiningChecklistTemplate::count();
        $filteredRecords = $query->count();

        $templates = $query->orderBy('sort_order')->orderBy('created_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $templates->map(function ($template) {
            $statusBadges = [
                'active' => '<span class="badge bg-success">Active</span>',
                'inactive' => '<span class="badge bg-secondary">Inactive</span>',
            ];

            return [
                'id' => $template->id,
                'task_name' => $template->task_name,
                'task_category' => ucfirst($template->task_category ?? 'General'),
                'description' => $template->description ? substr($template->description, 0, 50) . '...' : 'N/A',
                'is_mandatory' => $template->is_mandatory ? '<span class="badge bg-danger">Mandatory</span>' : '<span class="badge bg-info">Optional</span>',
                'sort_order' => $template->sort_order,
                'status' => $statusBadges[$template->status] ?? '<span class="badge bg-secondary">' . $template->status . '</span>',
            ];
        });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->values()->toArray()
        ]);
    }

    /**
     * Show form for creating checklist template
     */
    public function createChecklistTemplate()
    {
        return view('onboarding.checklist-templates.create');
    }

    /**
     * Store checklist template
     */
    public function storeChecklistTemplate(Request $request)
    {
        $validated = $request->validate([
            'task_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'task_category' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_mandatory' => 'nullable|boolean',
            'status' => 'nullable|in:active,inactive',
        ]);

        try {
            $validated['created_by'] = auth()->id();
            $validated['status'] = $validated['status'] ?? 'active';
            $validated['is_mandatory'] = $validated['is_mandatory'] ?? true;
            $validated['sort_order'] = $validated['sort_order'] ?? 0;

            JoiningChecklistTemplate::create($validated);

            return redirect()->route('onboarding.checklist-templates')
                ->with('success', 'Checklist template created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating checklist template: ' . $e->getMessage());
        }
    }

    /**
     * Show form for editing checklist template
     */
    public function editChecklistTemplate($id)
    {
        $template = JoiningChecklistTemplate::findOrFail($id);
        return view('onboarding.checklist-templates.edit', compact('template'));
    }

    /**
     * Update checklist template
     */
    public function updateChecklistTemplate(Request $request, $id)
    {
        $template = JoiningChecklistTemplate::findOrFail($id);

        $validated = $request->validate([
            'task_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'task_category' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_mandatory' => 'nullable|boolean',
            'status' => 'nullable|in:active,inactive',
        ]);

        try {
            $validated['updated_by'] = auth()->id();
            $template->update($validated);

            return redirect()->route('onboarding.checklist-templates')
                ->with('success', 'Checklist template updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating checklist template: ' . $e->getMessage());
        }
    }

    /**
     * Delete checklist template
     */
    public function destroyChecklistTemplate($id)
    {
        try {
            $template = JoiningChecklistTemplate::findOrFail($id);
            $template->delete();

            return response()->json(['success' => true, 'message' => 'Checklist template deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting checklist template: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display training assignments list
     */
    public function trainingAssignments(Request $request)
    {
        $processes = OnboardingProcess::with(['candidate', 'employeeProfile.user', 'user'])->get();
        return view('onboarding.training-assignments.index', compact('processes'));
    }

    /**
     * Get training assignments for DataTables
     */
    public function getTrainingAssignments(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $search = $request->input('search.value');
        $processId = $request->input('process_id');

        Log::info('Training Assignment DataTable - Request received', [
            'draw' => $draw,
            'start' => $start,
            'length' => $length,
            'search' => $search,
            'process_id' => $processId,
        ]);

        $query = EmployeeTrainingAssignment::with(['trainingModule', 'onboardingProcess', 'employeeProfile.user', 'assigner']);

        if ($processId) {
            $query->where('onboarding_process_id', $processId);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('trainingModule', function($q2) use ($search) {
                    $q2->where('title', 'like', '%' . $search . '%');
                })->orWhereHas('onboardingProcess', function($q2) use ($search) {
                    $q2->whereHas('candidate', function($q3) use ($search) {
                        $q3->where('full_name', 'like', '%' . $search . '%');
                    })->orWhereHas('employeeProfile.user', function($q3) use ($search) {
                        $q3->where('name', 'like', '%' . $search . '%');
                    });
                });
            });
        }

        $totalRecords = EmployeeTrainingAssignment::count();
        $filteredRecords = $query->count();

        Log::info('Training Assignment DataTable - Query counts', [
            'total_records' => $totalRecords,
            'filtered_records' => $filteredRecords,
        ]);

        $assignments = $query->orderBy('created_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        Log::info('Training Assignment DataTable - Assignments fetched', [
            'count' => $assignments->count(),
            'assignments' => $assignments->pluck('id')->toArray(),
        ]);

        $statusBadges = [
            'assigned' => '<span class="badge bg-info">Assigned</span>',
            'in-progress' => '<span class="badge bg-primary">In Progress</span>',
            'completed' => '<span class="badge bg-success">Completed</span>',
            'overdue' => '<span class="badge bg-danger">Overdue</span>',
        ];

        $data = $assignments->map(function($assignment) use ($statusBadges) {
            $processName = 'N/A';
            if ($assignment->onboardingProcess) {
                if ($assignment->onboardingProcess->candidate) {
                    $processName = $assignment->onboardingProcess->candidate->full_name;
                } elseif ($assignment->onboardingProcess->employeeProfile && $assignment->onboardingProcess->employeeProfile->user) {
                    $processName = $assignment->onboardingProcess->employeeProfile->user->name;
                } elseif ($assignment->onboardingProcess->user) {
                    $processName = $assignment->onboardingProcess->user->name;
                }
            }

            $actions = '<a href="' . route('onboarding.training-assignments.edit', $assignment->id) . '" class="btn btn-sm btn-link text-info p-1" title="Edit">' .
                '<i class="bi bi-pencil"></i>' .
                '</a>' .
                '<button onclick="deleteAssignment(' . $assignment->id . ')" class="btn btn-sm btn-link text-danger p-1 delete-assignment" data-id="' . $assignment->id . '" title="Delete">' .
                '<i class="bi bi-trash"></i>' .
                '</button>';

            return [
                'id' => $assignment->id,
                'training_module' => $assignment->trainingModule->title ?? 'N/A',
                'process_name' => $processName,
                'assigned_date' => $assignment->assigned_date ? \Carbon\Carbon::parse($assignment->assigned_date)->format('d M Y') : 'N/A',
                'due_date' => $assignment->due_date ? \Carbon\Carbon::parse($assignment->due_date)->format('d M Y') : 'N/A',
                'status' => $statusBadges[$assignment->status] ?? '<span class="badge bg-secondary">' . $assignment->status . '</span>',
                'progress' => $assignment->progress_percentage . '%',
                'actions' => $actions,
            ];
        });

        $response = [
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->values()->toArray()
        ];

        Log::info('Training Assignment DataTable - Response prepared', [
            'draw' => $response['draw'],
            'recordsTotal' => $response['recordsTotal'],
            'recordsFiltered' => $response['recordsFiltered'],
            'data_count' => count($response['data']),
        ]);

        return response()->json($response);
    }

    /**
     * Show the form for assigning training
     */
    public function createTrainingAssignment()
    {
        $processes = OnboardingProcess::with(['candidate', 'employeeProfile.user', 'user'])
            ->whereNotNull('employee_profile_id')
            ->get();
        $trainingModules = TrainingModule::where('status', 'published')->orderBy('title')->get();
        $users = User::all();
        return view('onboarding.training-assignments.create', compact('processes', 'trainingModules', 'users'));
    }

    /**
     * Store a newly created training assignment
     */
    public function storeTrainingAssignment(Request $request)
    {
        Log::info('Training Assignment Store - Request received', [
            'user_id' => auth()->id(),
            'request_data' => $request->all(),
            'ip' => $request->ip(),
        ]);

        try {
            $validated = $request->validate([
                'training_module_id' => 'required|exists:training_modules,id',
                'onboarding_process_id' => 'required|exists:onboarding_processes,id',
                'assigned_date' => 'required|date',
                'due_date' => 'nullable|date|after_or_equal:assigned_date',
                'assigned_by' => 'nullable|exists:users,id',
            ]);

            Log::info('Training Assignment Store - Validation passed', [
                'validated_data' => $validated,
            ]);

            $process = OnboardingProcess::findOrFail($validated['onboarding_process_id']);
            
            Log::info('Training Assignment Store - Process found', [
                'process_id' => $process->id,
                'employee_profile_id' => $process->employee_profile_id,
                'process_data' => $process->toArray(),
            ]);
            
            // Check if employee_profile_id exists
            if (!$process->employee_profile_id) {
                Log::warning('Training Assignment Store - No employee profile', [
                    'process_id' => $process->id,
                ]);
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'The selected onboarding process does not have an employee profile. Please assign an employee profile to the onboarding process first.');
            }
            
            $validated['employee_profile_id'] = $process->employee_profile_id;
            $validated['assigned_by'] = $validated['assigned_by'] ?? auth()->id();
            $validated['status'] = 'assigned';

            Log::info('Training Assignment Store - Creating assignment', [
                'final_data' => $validated,
            ]);

            $assignment = EmployeeTrainingAssignment::create($validated);

            Log::info('Training Assignment Store - Assignment created successfully', [
                'assignment_id' => $assignment->id,
                'assignment_data' => $assignment->toArray(),
            ]);

            return redirect()->route('onboarding.training-assignments')
                ->with('success', 'Training assignment created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Training Assignment Store - Validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all(),
            ]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Training Assignment Store - Model not found', [
                'message' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'The selected onboarding process or training module was not found.');
        } catch (\Exception $e) {
            Log::error('Training Assignment Store - Exception occurred', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating training assignment: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing a training assignment
     */
    public function editTrainingAssignment($id)
    {
        $assignment = EmployeeTrainingAssignment::with(['trainingModule', 'onboardingProcess', 'employeeProfile'])->findOrFail($id);
        $processes = OnboardingProcess::with(['candidate', 'employeeProfile.user', 'user'])
            ->whereNotNull('employee_profile_id')
            ->get();
        $trainingModules = TrainingModule::where('status', 'published')->orderBy('title')->get();
        $users = User::all();
        return view('onboarding.training-assignments.edit', compact('assignment', 'processes', 'trainingModules', 'users'));
    }

    /**
     * Update the specified training assignment
     */
    public function updateTrainingAssignment(Request $request, $id)
    {
        Log::info('Training Assignment Update - Request received', [
            'assignment_id' => $id,
            'user_id' => auth()->id(),
            'request_data' => $request->all(),
            'ip' => $request->ip(),
        ]);

        try {
            $assignment = EmployeeTrainingAssignment::findOrFail($id);

            Log::info('Training Assignment Update - Assignment found', [
                'assignment_id' => $assignment->id,
                'current_data' => $assignment->toArray(),
            ]);

            $validated = $request->validate([
                'training_module_id' => 'required|exists:training_modules,id',
                'onboarding_process_id' => 'required|exists:onboarding_processes,id',
                'assigned_date' => 'required|date',
                'due_date' => 'nullable|date|after_or_equal:assigned_date',
                'status' => 'nullable|in:assigned,in-progress,completed,overdue',
                'progress_percentage' => 'nullable|integer|min:0|max:100',
                'completion_notes' => 'nullable|string',
                'completed_date' => 'nullable|date',
            ]);

            Log::info('Training Assignment Update - Validation passed', [
                'validated_data' => $validated,
            ]);

            $process = OnboardingProcess::findOrFail($validated['onboarding_process_id']);
            $validated['employee_profile_id'] = $process->employee_profile_id;
            
            if ($validated['status'] === 'completed' && !$assignment->completed_date) {
                $validated['completed_date'] = $validated['completed_date'] ?? now();
                $validated['progress_percentage'] = 100;
            }

            Log::info('Training Assignment Update - Updating assignment', [
                'final_data' => $validated,
            ]);

            $assignment->update($validated);

            Log::info('Training Assignment Update - Assignment updated successfully', [
                'assignment_id' => $assignment->id,
                'updated_data' => $assignment->fresh()->toArray(),
            ]);

            return redirect()->route('onboarding.training-assignments')
                ->with('success', 'Training assignment updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Training Assignment Update - Validation failed', [
                'assignment_id' => $id,
                'errors' => $e->errors(),
                'request_data' => $request->all(),
            ]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Training Assignment Update - Model not found', [
                'assignment_id' => $id,
                'message' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'The training assignment or related record was not found.');
        } catch (\Exception $e) {
            Log::error('Training Assignment Update - Exception occurred', [
                'assignment_id' => $id,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating training assignment: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified training assignment
     */
    public function destroyTrainingAssignment($id)
    {
        try {
            $assignment = EmployeeTrainingAssignment::findOrFail($id);
            $assignment->delete();

            return response()->json(['success' => true, 'message' => 'Training assignment deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting training assignment: ' . $e->getMessage()], 500);
        }
    }
}