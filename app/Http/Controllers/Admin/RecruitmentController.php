<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\JobOpening;
use App\Models\Interview;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RecruitmentController extends Controller
{
    /**
     * Display candidates list
     */
    public function candidates(Request $request)
    {
        return view('recruitment.candidates.index');
    }

    /**
     * Get candidates for DataTables
     */
    public function getCandidates(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        $status = $request->input('status');

        $query = Candidate::with(['jobOpening']);

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $totalRecords = Candidate::count();
        $filteredRecords = $query->count();

        $candidates = $query->orderBy('created_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $candidates->map(function ($candidate) {
            $statusBadges = [
                'applied' => '<span class="badge bg-info">Applied</span>',
                'screening' => '<span class="badge bg-warning">Screening</span>',
                'shortlisted' => '<span class="badge bg-primary">Shortlisted</span>',
                'interviewed' => '<span class="badge bg-secondary">Interviewed</span>',
                'offered' => '<span class="badge bg-success">Offered</span>',
                'hired' => '<span class="badge bg-success">Hired</span>',
                'rejected' => '<span class="badge bg-danger">Rejected</span>',
                'withdrawn' => '<span class="badge bg-dark">Withdrawn</span>',
            ];

            return [
                'id' => $candidate->id,
                'name' => $candidate->full_name,
                'email' => $candidate->email,
                'phone' => $candidate->phone ?? 'N/A',
                'job_title' => $candidate->jobOpening->job_title ?? 'N/A',
                'status' => $statusBadges[$candidate->status] ?? '<span class="badge bg-secondary">' . $candidate->status . '</span>',
                'applied_date' => $candidate->created_at->format('d M Y'),
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
     * Show the form for creating a new candidate
     */
    public function createCandidate()
    {
        $jobOpenings = JobOpening::where('status', 'published')->get();
        return view('recruitment.candidates.create', compact('jobOpenings'));
    }

    /**
     * Store a newly created candidate
     */
    public function storeCandidate(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:candidates,email',
            'phone' => 'nullable|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:10',
            'resume_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'cover_letter' => 'nullable|string',
            'job_opening_id' => 'nullable|exists:job_openings,id',
            'status' => 'nullable|in:applied,screening,shortlisted,interviewed,offered,hired,rejected,withdrawn',
            'notes' => 'nullable|string',
        ]);

        try {
            if ($request->hasFile('resume_file')) {
                $validated['resume_file'] = $request->file('resume_file')->store('resumes', 'public');
            }

            $validated['created_by'] = auth()->id();
            $validated['status'] = $validated['status'] ?? 'applied';

            Candidate::create($validated);

            return redirect()->route('recruitment.candidates')
                ->with('success', 'Candidate created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating candidate: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing a candidate
     */
    public function editCandidate($id)
    {
        $candidate = Candidate::findOrFail($id);
        $jobOpenings = JobOpening::where('status', 'published')->get();
        return view('recruitment.candidates.edit', compact('candidate', 'jobOpenings'));
    }

    /**
     * Update the specified candidate
     */
    public function updateCandidate(Request $request, $id)
    {
        $candidate = Candidate::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:candidates,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:10',
            'resume_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'cover_letter' => 'nullable|string',
            'job_opening_id' => 'nullable|exists:job_openings,id',
            'status' => 'nullable|in:applied,screening,shortlisted,interviewed,offered,hired,rejected,withdrawn',
            'notes' => 'nullable|string',
        ]);

        try {
            if ($request->hasFile('resume_file')) {
                if ($candidate->resume_file) {
                    Storage::disk('public')->delete($candidate->resume_file);
                }
                $validated['resume_file'] = $request->file('resume_file')->store('resumes', 'public');
            }

            $validated['updated_by'] = auth()->id();
            $candidate->update($validated);

            return redirect()->route('recruitment.candidates')
                ->with('success', 'Candidate updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating candidate: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified candidate
     */
    public function destroyCandidate($id)
    {
        try {
            $candidate = Candidate::findOrFail($id);
            if ($candidate->resume_file) {
                Storage::disk('public')->delete($candidate->resume_file);
            }
            $candidate->delete();

            return response()->json(['success' => true, 'message' => 'Candidate deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting candidate: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display job openings list
     */
    public function jobOpenings(Request $request)
    {
        return view('recruitment.job-openings.index');
    }

    /**
     * Get job openings for DataTables
     */
    public function getJobOpenings(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        $status = $request->input('status');

        $query = JobOpening::with(['department', 'designation', 'store']);

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('job_title', 'like', '%' . $search . '%')
                  ->orWhere('job_description', 'like', '%' . $search . '%');
            });
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $totalRecords = JobOpening::count();
        $filteredRecords = $query->count();

        $jobOpenings = $query->orderBy('created_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $jobOpenings->map(function ($job) {
            $statusBadges = [
                'draft' => '<span class="badge bg-secondary">Draft</span>',
                'published' => '<span class="badge bg-success">Published</span>',
                'closed' => '<span class="badge bg-warning">Closed</span>',
                'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
            ];

            return [
                'id' => $job->id,
                'job_title' => $job->job_title,
                'department' => $job->department->name ?? 'N/A',
                'designation' => $job->designation->name ?? 'N/A',
                'no_of_vacancies' => $job->no_of_vacancies,
                'status' => $statusBadges[$job->status] ?? '<span class="badge bg-secondary">' . $job->status . '</span>',
                'posted_date' => $job->posted_date ? \Carbon\Carbon::parse($job->posted_date)->format('d M Y') : 'N/A',
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
     * Show the form for creating a new job opening
     */
    public function createJobOpening()
    {
        $departments = Department::all();
        $designations = Designation::all();
        $stores = Store::all();
        return view('recruitment.job-openings.create', compact('departments', 'designations', 'stores'));
    }

    /**
     * Store a newly created job opening
     */
  public function storeJobOpening(Request $request)
{
    $validated = $request->validate([
        'job_title' => 'required|string|max:255',
        'job_description' => 'required|string',
        'requirements' => 'nullable|string',
        'responsibilities' => 'nullable|string',
        'department_id' => 'nullable|exists:departments,id',
        'designation_id' => 'nullable|exists:designations,id',
        'store_id' => 'nullable|exists:stores,id',
        'employment_type' => 'nullable|in:full-time,part-time,contract,internship,temporary',
        'min_salary' => 'nullable|numeric|min:0',
        'max_salary' => 'nullable|numeric|min:0',
        'experience_required' => 'nullable|string|max:255',
        'qualification_required' => 'nullable|string|max:255',
        'no_of_vacancies' => 'required|integer|min:1',
        'application_deadline' => 'nullable|date',
        'status' => 'nullable|in:draft,published,closed,cancelled',
        'posted_date' => 'nullable|date',
    ]);

    $validated['created_by'] = auth()->check() ? auth()->id() : null;
    $validated['status'] = $validated['status'] ?? 'draft';

    if ($validated['status'] === 'published' && empty($validated['posted_date'])) {
        $validated['posted_date'] = now()->toDateString();
    }
// dd($validated);
    JobOpening::create($validated);

    return redirect()
        ->route('recruitment.job-openings')
        ->with('success', 'Job opening created successfully.');
}


    /**
     * Show the form for editing a job opening
     */
    public function editJobOpening($id)
    {
        $jobOpening = JobOpening::findOrFail($id);
        $departments = Department::all();
        $designations = Designation::all();
        $stores = Store::all();
        return view('recruitment.job-openings.edit', compact('jobOpening', 'departments', 'designations', 'stores'));
    }

    /**
     * Update the specified job opening
     */
    public function updateJobOpening(Request $request, $id)
    {
        $jobOpening = JobOpening::findOrFail($id);

        $validated = $request->validate([
            'job_title' => 'required|string|max:255',
            'job_description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
            'store_id' => 'nullable|exists:stores,id',
            'employment_type' => 'nullable|in:full-time,part-time,contract,internship,temporary',
            'min_salary' => 'nullable|numeric|min:0',
            'max_salary' => 'nullable|numeric|min:0',
            'experience_required' => 'nullable|string|max:255',
            'qualification_required' => 'nullable|string|max:255',
            'no_of_vacancies' => 'required|integer|min:1',
            'application_deadline' => 'nullable|date',
            'status' => 'nullable|in:draft,published,closed,cancelled',
            'posted_date' => 'nullable|date',
        ]);

        try {
            $validated['updated_by'] = auth()->id();
            if ($validated['status'] === 'published' && !$jobOpening->posted_date) {
                $validated['posted_date'] = now();
            }

            $jobOpening->update($validated);

            return redirect()->route('recruitment.job-openings')
                ->with('success', 'Job opening updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating job opening: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified job opening
     */
    public function destroyJobOpening($id)
    {
        try {
            $jobOpening = JobOpening::findOrFail($id);
            $jobOpening->delete();

            return response()->json(['success' => true, 'message' => 'Job opening deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting job opening: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display interviews list
     */
    public function interviews(Request $request)
    {
        return view('recruitment.interviews.index');
    }

    /**
     * Get interviews for DataTables
     */
    public function getInterviews(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        $status = $request->input('status');

        $query = Interview::with(['candidate', 'jobOpening', 'interviewer']);

        if (!empty($search)) {
            $query->whereHas('candidate', function($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $totalRecords = Interview::count();
        $filteredRecords = $query->count();

        $interviews = $query->orderBy('scheduled_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $interviews->map(function ($interview) {
            $statusBadges = [
                'scheduled' => '<span class="badge bg-info">Scheduled</span>',
                'in-progress' => '<span class="badge bg-warning">In Progress</span>',
                'completed' => '<span class="badge bg-success">Completed</span>',
                'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
                'rescheduled' => '<span class="badge bg-secondary">Rescheduled</span>',
                'no-show' => '<span class="badge bg-dark">No Show</span>',
            ];

            return [
                'id' => $interview->id,
                'candidate_name' => $interview->candidate->full_name ?? 'N/A',
                'job_title' => $interview->jobOpening->job_title ?? 'N/A',
                'interviewer' => $interview->interviewer->name ?? 'N/A',
                'scheduled_at' => $interview->scheduled_at ? \Carbon\Carbon::parse($interview->scheduled_at)->format('d M Y h:i A') : 'N/A',
                'status' => $statusBadges[$interview->status] ?? '<span class="badge bg-secondary">' . $interview->status . '</span>',
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
     * Show the form for creating a new interview
     */
    public function createInterview()
    {
        $candidates = Candidate::whereIn('status', ['applied', 'shortlisted'])->get();
        $jobOpenings = JobOpening::where('status', 'published')->get();
        $interviewers = User::all();
        return view('recruitment.interviews.create', compact('candidates', 'jobOpenings', 'interviewers'));
    }

    /**
     * Store a newly created interview
     */
    public function storeInterview(Request $request)
    {
        $validated = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'job_opening_id' => 'nullable|exists:job_openings,id',
            'interview_type' => 'nullable|string|max:255',
            'scheduled_at' => 'required|date',
            'location' => 'nullable|string|max:255',
            'meeting_link' => 'nullable|url',
            'interviewer_id' => 'nullable|exists:users,id',
            'status' => 'nullable|in:scheduled,in-progress,completed,cancelled,rescheduled,no-show',
        ]);

        try {
            $validated['created_by'] = auth()->id();
            $validated['status'] = $validated['status'] ?? 'scheduled';
            $validated['scheduled_at'] = \Carbon\Carbon::parse($validated['scheduled_at']);

            Interview::create($validated);

            return redirect()->route('recruitment.interviews')
                ->with('success', 'Interview scheduled successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error scheduling interview: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing an interview
     */
    public function editInterview($id)
    {
        $interview = Interview::findOrFail($id);
        $candidates = Candidate::whereIn('status', ['applied', 'shortlisted'])->get();
        $jobOpenings = JobOpening::where('status', 'published')->get();
        $interviewers = User::all();
        return view('recruitment.interviews.edit', compact('interview', 'candidates', 'jobOpenings', 'interviewers'));
    }

    /**
     * Update the specified interview
     */
    public function updateInterview(Request $request, $id)
    {
        $interview = Interview::findOrFail($id);

        $validated = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'job_opening_id' => 'nullable|exists:job_openings,id',
            'interview_type' => 'nullable|string|max:255',
            'scheduled_at' => 'required|date',
            'location' => 'nullable|string|max:255',
            'meeting_link' => 'nullable|url',
            'interviewer_id' => 'nullable|exists:users,id',
            'interview_notes' => 'nullable|string',
            'feedback' => 'nullable|string',
            'rating' => 'nullable|integer|min:1|max:5',
            'status' => 'nullable|in:scheduled,in-progress,completed,cancelled,rescheduled,no-show',
            'cancellation_reason' => 'nullable|string',
        ]);

        try {
            $validated['updated_by'] = auth()->id();
            $validated['scheduled_at'] = \Carbon\Carbon::parse($validated['scheduled_at']);
            
            if ($validated['status'] === 'completed' && !$interview->completed_at) {
                $validated['completed_at'] = now();
            }

            $interview->update($validated);

            return redirect()->route('recruitment.interviews')
                ->with('success', 'Interview updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating interview: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified interview
     */
    public function destroyInterview($id)
    {
        try {
            $interview = Interview::findOrFail($id);
            $interview->delete();

            return response()->json(['success' => true, 'message' => 'Interview deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting interview: ' . $e->getMessage()], 500);
        }
    }
}