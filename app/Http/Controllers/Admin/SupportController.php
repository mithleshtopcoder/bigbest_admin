<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    public function tickets()
    {
        $users = User::where('status', true)->orderBy('name')->get(['id', 'name', 'email']);
        return view('admin.support.tickets', compact('users'));
    }

    /**
     * AJAX endpoint for fetching tickets with pagination (DataTables)
     */
    public function getTickets(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        $status = $request->input('status');
        $priority = $request->input('priority');
        $assignedTo = $request->input('assigned_to');

        // Build query
        $query = SupportTicket::with([
            'customer:id,first_name,last_name,email,phone',
            'assignedUser:id,name,email',
            'assignedByUser:id,name,email'
        ]);

        // Apply search filter
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('ticket_number', 'like', '%' . $search . '%')
                  ->orWhere('subject', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('first_name', 'like', '%' . $search . '%')
                                   ->orWhere('last_name', 'like', '%' . $search . '%')
                                   ->orWhere('email', 'like', '%' . $search . '%')
                                   ->orWhere('phone', 'like', '%' . $search . '%');
                  });
            });
        }

        // Filter by status
        if (!empty($status)) {
            $query->where('status', $status);
        }

        // Filter by priority
        if (!empty($priority)) {
            $query->where('priority', $priority);
        }

        // Filter by assigned user
        if (!empty($assignedTo)) {
            if ($assignedTo === 'unassigned') {
                $query->whereNull('assigned_to');
            } else {
                $query->where('assigned_to', $assignedTo);
            }
        }

        // Get total count before pagination
        $totalRecords = SupportTicket::count();
        $filteredRecords = $query->count();

        // Apply pagination and ordering
        $tickets = $query->orderBy('created_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $tickets->map(function ($ticket) {
            $customerName = $ticket->customer 
                ? trim($ticket->customer->first_name . ' ' . $ticket->customer->last_name)
                : 'N/A';
            
            $priorityBadge = match($ticket->priority) {
                'urgent' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Urgent</span>',
                'high' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-800">High</span>',
                'medium' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Medium</span>',
                'low' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Low</span>',
                default => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">' . ucfirst($ticket->priority) . '</span>',
            };

            $statusBadge = match($ticket->status) {
                'open' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Open</span>',
                'in_progress' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">In Progress</span>',
                'resolved' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Resolved</span>',
                'closed' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Closed</span>',
                'cancelled' => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Cancelled</span>',
                default => '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">' . ucfirst($ticket->status) . '</span>',
            };

            return [
                'id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'customer' => $customerName,
                'customer_email' => $ticket->customer->email ?? 'N/A',
                'customer_phone' => $ticket->customer->phone ?? 'N/A',
                'subject' => $ticket->subject,
                'priority' => $priorityBadge,
                'priority_raw' => $ticket->priority,
                'status' => $statusBadge,
                'status_raw' => $ticket->status,
                'assigned_to' => $ticket->assignedUser ? $ticket->assignedUser->name : 'Unassigned',
                'assigned_to_id' => $ticket->assigned_to,
                'created_at' => $ticket->created_at->format('Y-m-d H:i'),
                'created_at_raw' => $ticket->created_at->toDateTimeString(),
            ];
        });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    /**
     * Get ticket details
     */
    public function showTicket($id)
    {
        $ticket = SupportTicket::with([
            'customer:id,first_name,last_name,email,phone',
            'assignedUser:id,name,email',
            'assignedByUser:id,name,email',
            'replies.customer:id,first_name,last_name',
            'replies.admin:id,name,email'
        ])->findOrFail($id);

        // Manually order replies
        $ticket->replies = $ticket->replies->sortBy('created_at')->values();

        return response()->json([
            'success' => true,
            'data' => $ticket
        ]);
    }

    /**
     * Assign ticket to a user
     */
    public function assignTicket(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'assigned_to' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $ticket = SupportTicket::findOrFail($id);
        
        $ticket->update([
            'assigned_to' => $request->assigned_to,
            'assigned_by' => Auth::id(),
            'assigned_at' => now(),
        ]);

        // Update status to in_progress if it was open
        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        $ticket->load(['assignedUser:id,name,email', 'assignedByUser:id,name,email']);

        return response()->json([
            'success' => true,
            'message' => 'Ticket assigned successfully',
            'data' => $ticket
        ]);
    }

    /**
     * Update ticket status
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:open,in_progress,resolved,closed,cancelled',
            'resolution_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $ticket = SupportTicket::findOrFail($id);
        
        $updateData = ['status' => $request->status];
        
        if ($request->status === 'resolved') {
            $updateData['resolved_at'] = now();
            if ($request->resolution_notes) {
                $updateData['resolution_notes'] = $request->resolution_notes;
            }
        } elseif ($request->status === 'closed') {
            $updateData['closed_at'] = now();
        }

        $ticket->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Ticket status updated successfully',
            'data' => $ticket
        ]);
    }

    /**
     * Reply to ticket (admin)
     */
    public function replyTicket(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'string',
            'is_internal' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $ticket = SupportTicket::findOrFail($id);

        // Update ticket status if it was closed/resolved
        if (in_array($ticket->status, ['resolved', 'closed'])) {
            $ticket->update(['status' => 'in_progress']);
        } elseif ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        $reply = SupportTicketReply::create([
            'ticket_id' => $ticket->id,
            'replied_by_type' => 'admin',
            'replied_by_id' => Auth::id(),
            'message' => $request->message,
            'attachments' => $request->attachments ?? [],
            'is_internal' => $request->is_internal ?? false,
        ]);

        // Refresh and load the relationship
        $reply->refresh();
        $reply->load(['admin:id,name,email']);

        return response()->json([
            'success' => true,
            'message' => 'Reply added successfully',
            'data' => $reply
        ], 201);
    }

    public function faqs()
    {
        return view('support.faqs.index');
    }

    public function announcements()
    {
        return view('support.system-announcements.index');
    }
}

