<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Attendance;
use App\Models\Report;


class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Welcome to the Dashboard'
        ]);}

    public function show()
    {
        
        $users = User::with('profile')->get();

        $result = $users->map(function ($user) {
            return [
                'nik' => $user->profile->nik ?? null,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->profile->phone_number ?? null,
                'role' => $user->role,
            ];
        });

        return response()->json([
            'message' => 'Dashboard Details',
            'data' => $result
        ]);
}

    public function showUserProfile($id)
    {
        $user = User::with('profile')->find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'message' => 'User Profile Details',
            'data' => [
                'nik' => $user->profile->nik ?? null,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->profile->phone_number ?? null,
                'role' => $user->role,
            ]
        ]);
    }
    public function showAttendance()
    {
        $attendances = Attendance::with('user')->get();

        return response()->json([
            'message' => 'Attendance Details',
            'data' => $attendances
        ]);
    }
    public function showAttendanceByUser($id)
    {
        $user = User::with('attendance')->find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'message' => 'User Attendance Details',
            'data' => $user->attendance
        ]);
    }
    public function showReports()
    {
        $reports = Report::with('user')->get();

        return response()->json([
            'message' => 'Reports Details',
            'data' => $reports
        ]);
    }
}
