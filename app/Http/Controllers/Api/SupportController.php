<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    /**
     * Get list of FAQs
     */
    public function faqs(Request $request)
    {
        $category = $request->query('category');
        
        $query = Faq::where('is_active', true);
        
        if ($category) {
            $query->where('category', $category);
        }
        
        $faqs = $query->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $faqs
        ], 200);
    }

    /**
     * Get FAQ categories
     */
    public function faqCategories()
    {
        $categories = Faq::where('is_active', true)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return response()->json([
            'success' => true,
            'data' => $categories
        ], 200);
    }

    /**
     * Get list of customer's support tickets with status filter
     */
    public function tickets(Request $request)
    {
        $customer = Auth::guard('sanctum')->user();
        
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $status = $request->query('status'); // Filter by status: open, in_progress, resolved, closed, cancelled
        $perPage = $request->query('per_page', 15);
        $page = $request->query('page', 1);

        $query = SupportTicket::where('customer_id', $customer->id)
            ->with([
                'assignedUser:id,name,email',
                'publicReplies' => function($query) {
                    $query->with(['customer:id,first_name,last_name', 'admin:id,name,email'])
                        ->orderBy('created_at', 'asc');
                }
            ])
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($status) {
            $query->where('status', $status);
        }

        $tickets = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $tickets->items(),
            'pagination' => [
                'current_page' => $tickets->currentPage(),
                'last_page' => $tickets->lastPage(),
                'per_page' => $tickets->perPage(),
                'total' => $tickets->total(),
            ]
        ], 200);
    }

    /**
     * Create a new support ticket
     */
    public function createTicket(Request $request)
    {
        $customer = Auth::guard('sanctum')->user();
        
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'nullable|in:low,medium,high,urgent',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $ticket = SupportTicket::create([
            'customer_id' => $customer->id,
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority ?? 'medium',
            'status' => 'open',
        ]);

        $ticket->load(['customer:id,first_name,last_name,email,phone', 'assignedUser:id,name,email']);

        return response()->json([
            'success' => true,
            'message' => 'Ticket created successfully',
            'data' => $ticket
        ], 201);
    }

}
