<?php
namespace App\Http\Controllers;

use App\Models\AttendanceToken;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class AttendanceController extends Controller
{
    // Generate QR Token (expires in 5 mins)
    public function generate()
    {
        $token = Str::uuid()->toString();
        $expiresAt = Carbon::now()->addMinutes(5);

        AttendanceToken::create([
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);

        return response()->json([
            'token' => $token,
            'expires_at' => $expiresAt->toDateTimeString(),
        ]);
    }

    // Process Attendance via scanned QR token
    
public function submit(Request $request)
{
    try {
        $request->validate([
            'token' => 'required|string'
        ]);

        $attendanceToken = AttendanceToken::where('token', $request->token)->first();

        if (!$attendanceToken || $attendanceToken->isExpired()) {
            return response()->json(['message' => 'Token tidak valid atau telah kadaluarsa.'], 400);
        }

        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User tidak terautentikasi.'], 401);
        }

        $attendance = Attendance::create([
            'user_id'     => $user->id,
            'attended_at' => now(),
            'kehadiran'      => 'Hadir',
        ]);
        if (!$attendance) {
            return response()->json(['message' => 'Gagal mencatat absensi.'], 500);
        }

        $attendanceToken->delete();

        return response()->json([
            'message' => 'Absensi berhasil.',
            'data' => [
                'user_id' => $user->id,
                'name'    => $user->name,
                'attended_at' => $attendance->attended_at,
                'kehadiran'  => $attendance->kehadiran,
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Gagal memproses absensi: ' . $e->getMessage());
        return response()->json(['message' => 'Terjadi kesalahan saat memproses absensi.'], 500);
    }
}

}
