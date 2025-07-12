<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function storeOrUpdate(Request $request)
{
    try {
        $user = Auth::user();

        $validated = $request->validate([
            'nik' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|max:2048', // ✅ image bukan string
            'phone_number' => 'nullable|string|max:20',
            'status' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'gender' => 'nullable|string|in:Laki-laki,Perempuan,Lainnya',
            'age' => 'nullable|integer|min:0',
            'height' => 'nullable|integer|min:0',
            'weight' => 'nullable|integer|min:0',
            'education' => 'nullable|string|max:100',
            'bank_account' => 'nullable|string|max:100',
            'employee_status' => 'nullable|string',
            'position' => 'nullable|string',
            'work_duration' => 'nullable|string',
            'placement_location' => 'nullable|string',
            'portfolio_link' => 'nullable|url',
            'work_experience' => 'nullable|string',
            'skills' => 'nullable|string',
            'grade' => 'nullable|string|max:10',
        ]);

        if ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo');
            $filename = 'profile_' . $user->id . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('profiles', $filename, 'public');

            $validated['profile_photo_url'] = Storage::disk('public')->url($path);
        }


        $profile = UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            $validated + ['user_id' => $user->id]
        );

        return response()->json([
            'message' => 'Profil berhasil disimpan',
            'data' => $profile
        ], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'message' => 'Validasi gagal.',
            'errors' => $e->errors(),
        ], 422);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Terjadi kesalahan saat menyimpan profil.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    public function show()
{
    $user = Auth::user();
    $profile = $user->profile;

    if (!$profile) {
        return response()->json(['message' => 'Profil belum dibuat.'], 404);
    }

    return response()->json([
        'data' => [
            'name' => $user->name,
            'email' => $user->email,
            'profile' => $profile
        ]
    ]);
}

}
