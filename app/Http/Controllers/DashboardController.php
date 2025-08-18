<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Attendance;
use App\Models\Report;
use App\Models\Location;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Welcome to the Dashboard'
        ]);}

   public function show()
{
        $users = User::whereHas('profile')->with('profile')->get();

        $result = $users->map(function ($user) {
            return [
                'id'=> $user->id,
                'nik' => $user->profile->nik,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->profile->phone_number,
                'role' => $user->role,
            ];
        });

        return response()->json([
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
                'profile_photo_url' => $user->profile->profile_photo_url ?? null,
            ]
        ]);
    }

    public function updateUserProfile(Request $request, $id)
{
    try {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $profile = $user->profile;
        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'role'     => 'nullable|string|in:admin,user',
            'name'     => 'nullable|string|max:255',
            'email'    => 'nullable|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        // Hash password jika ada
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // Update user
        $user->update([
            'role'     => $data['role']     ?? $user->role,
            'name'     => $data['name']     ?? $user->name,
            'email'    => $data['email']    ?? $user->email,
            'password' => $data['password'] ?? $user->password,
        ]);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user'    => $user->load('profile')
        ], 200);

    } catch (QueryException $e) {
        // Error database (misalnya constraint email unik)
        return response()->json([
            'message' => 'Database error',
            'error'   => $e->getMessage(),
        ], 500);

    } catch (\Exception $e) {
        // Error umum
        return response()->json([
            'message' => 'Something went wrong',
            'error'   => $e->getMessage(),
        ], 500);
    }
}


    public function showAttendance()
    {
        $attendances = Attendance::with('user.profile')->get();


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
    public function showReportsById($id){
        $reports = Report::with('user')->find($id);
        if (!$reports) {
            return response()->json(['message' => 'Reports not found'], 404);
        }

        return response()->json([
            'message' => 'Reports Details',
            'data' => $reports
        ]); 
    }



    public function getDashboardStats()
    {
        $userId = auth()->id();

        $totalReports = Report::where('user_id', $userId)->count();

        $attendanceToday = Attendance::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->exists();

        return response()->json([
            'total_reports' => $totalReports,
            'attendance_status' => $attendanceToday ? 'Sudah absen' : 'Belum absen',
        ]);
    }


}
