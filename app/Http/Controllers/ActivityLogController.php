<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $action = $request->query('action');
        $userId = $request->query('user_id');

        $logs = ActivityLog::with('user')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', "%{$search}%")
                        ->orWhere('ip_address', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($uq) use ($search) {
                            $uq->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->when($action, function ($query, $action) {
                $query->where('action', $action);
            })
            ->when($userId, function ($query, $userId) {
                $query->where('user_id', $userId);
            })
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $actions = ActivityLog::select('action')->distinct()->pluck('action');
        $users = User::orderBy('name')->get(['id', 'name', 'role']);

        return view('pages.logs.index', compact('logs', 'actions', 'users', 'search', 'action', 'userId'));
    }
}
