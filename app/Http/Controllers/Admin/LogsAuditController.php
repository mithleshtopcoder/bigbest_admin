<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\LoginHistory;
use App\Models\FailedLoginHistory;
use Illuminate\Http\Request;

class LogsAuditController extends Controller
{
    public function userLogs()
    {
        return view('logs-audit.user-logs.index');
    }

    public function getUserLogs(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        $module = $request->input('module');
        $action = $request->input('action');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = ActivityLog::with('user');

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('module', 'like', '%' . $search . '%')
                  ->orWhere('action', 'like', '%' . $search . '%')
                  ->orWhere('details', 'like', '%' . $search . '%')
                  ->orWhere('ip_address', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }

        if (!empty($module)) {
            $query->where('module', $module);
        }

        if (!empty($action)) {
            $query->where('action', $action);
        }

        if (!empty($dateFrom)) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $totalRecords = ActivityLog::count();
        $filteredRecords = $query->count();

        $logs = $query->orderBy('created_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $logs->map(function ($log) {
            $actionBadges = [
                'created' => '<span class="badge bg-success">Created</span>',
                'updated' => '<span class="badge bg-primary">Updated</span>',
                'deleted' => '<span class="badge bg-danger">Deleted</span>',
                'viewed' => '<span class="badge bg-info">Viewed</span>',
            ];

            return [
                'id' => $log->id,
                'user' => $log->user ? $log->user->name : 'System',
                'action' => $actionBadges[$log->action] ?? '<span class="badge bg-secondary">' . ucfirst($log->action) . '</span>',
                'module' => $log->module,
                'details' => $log->details ?? 'N/A',
                'ip_address' => $log->ip_address ?? 'N/A',
                'date_time' => $log->created_at->format('d M Y H:i:s'),
            ];
        });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->values()->toArray()
        ]);
    }

    public function loginHistory()
    {
        return view('logs-audit.login-history.index');
    }

    public function getLoginHistory(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        $status = $request->input('status');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = LoginHistory::with('user');

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', '%' . $search . '%')
                  ->orWhere('ip_address', 'like', '%' . $search . '%')
                  ->orWhere('device', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($dateFrom)) {
            $query->whereDate('login_at', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->whereDate('login_at', '<=', $dateTo);
        }

        $totalRecords = LoginHistory::count();
        $filteredRecords = $query->count();

        $history = $query->orderBy('login_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $history->map(function ($login) {
            return [
                'id' => $login->id,
                'user' => $login->user ? $login->user->name : 'N/A',
                'email' => $login->email,
                'ip_address' => $login->ip_address ?? 'N/A',
                'device' => $login->device ?? 'N/A',
                'status' => $login->status === 'success' 
                    ? '<span class="badge bg-success">Success</span>' 
                    : '<span class="badge bg-danger">Failed</span>',
                'login_time' => $login->login_at->format('d M Y H:i:s'),
            ];
        });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->values()->toArray()
        ]);
    }

    public function failedLoginHistory()
    {
        return view('logs-audit.failed-login-history.index');
    }

    public function getFailedLoginHistory(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        $reason = $request->input('reason');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = FailedLoginHistory::query();

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', '%' . $search . '%')
                  ->orWhere('ip_address', 'like', '%' . $search . '%')
                  ->orWhere('device', 'like', '%' . $search . '%')
                  ->orWhere('reason', 'like', '%' . $search . '%');
            });
        }

        if (!empty($reason)) {
            $query->where('reason', $reason);
        }

        if (!empty($dateFrom)) {
            $query->whereDate('attempted_at', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->whereDate('attempted_at', '<=', $dateTo);
        }

        $totalRecords = FailedLoginHistory::count();
        $filteredRecords = $query->count();

        $failedLogins = $query->orderBy('attempted_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $failedLogins->map(function ($failed) {
            $reasonBadges = [
                'Invalid Password' => '<span class="badge bg-danger">Invalid Password</span>',
                'Invalid Credentials' => '<span class="badge bg-danger">Invalid Credentials</span>',
                'Account Locked' => '<span class="badge bg-warning">Account Locked</span>',
                'User Not Found' => '<span class="badge bg-info">User Not Found</span>',
            ];

            return [
                'id' => $failed->id,
                'email' => $failed->email,
                'ip_address' => $failed->ip_address ?? 'N/A',
                'device' => $failed->device ?? 'N/A',
                'reason' => $reasonBadges[$failed->reason] ?? '<span class="badge bg-secondary">' . ($failed->reason ?? 'Unknown') . '</span>',
                'attempt_time' => $failed->attempted_at->format('d M Y H:i:s'),
            ];
        });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->values()->toArray()
        ]);
    }
}

