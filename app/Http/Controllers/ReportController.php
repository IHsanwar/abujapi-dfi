<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\Report;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request){
        try {
            $request->validate([
                'description' => 'required|string',
                'area' => 'required|string',
                'image' => 'required|image|max:2048',
                'location_code' => 'nullable|exists:locations,code',
            ]);

            $imageUrl = null;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = auth()->id() . '-' . $request->area . '-' . Str::random(4) . '.' . $image->getClientOriginalExtension();

                // Simpan ke folder storage/app/public/reports
                $path = $image->storeAs('reports', $imageName, 'public');

                // URL publik: /storage/reports/namafile.jpg
                $imageUrl = Storage::disk('public')->url($path);
            }
            // Jika ada kode lokasi, cari ID lokasi        
            $locationId = null;
            if ($request->filled('location_code')) {
                $location = \App\Models\Location::where('code', $request->location_code)->first();
                $locationId = $location ? $location->id : null;
            }

            $report = Report::create([
                'user_id' => auth()->id(),
                'description' => $request->description,
                'area' => $request->area,
                'reported_at' => now(),
                'image_url' => $imageUrl,
                'location_id' => $locationId,
            ]);

            return response()->json([
                'message' => 'Report created successfully.',
                'data' => $report->load('user', 'location')
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the report.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function updateReport(Request $request, $id)
{
    try {
        $report = Report::findOrFail($id);

        $validated = $request->validate([
            'description' => 'sometimes|required|string',
            'area' => 'sometimes|required|string',
            'reported_at' => 'sometimes|required|date',
            'image' => 'nullable|image|max:2048',
        ]);
        $report->fill($validated);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = auth()->id() . '-' . ($validated['area'] ?? $report->area) . '.' . Str::random(4) . '.' . $image->getClientOriginalExtension();
            $path = Storage::disk('filebase')->putFileAs('reports', $image, $imageName, 'public');
            $report->image_url = Storage::disk('filebase')->url($path);
        }

        $report->save();

        return response()->json([
            'message' => 'Report updated successfully.',
            'data' => $report
        ], 200);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['message' => 'Report not found.'], 404);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'message' => 'Validation failed.',
            'errors' => $e->errors()
        ], 422);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'An error occurred while updating the report.',
            'error' => $e->getMessage()
        ], 500);
    }
}


    public function deleteReport($id)
{
    try {
        $report = Report::findOrFail($id);
        $report->delete();

        return response()->json(['message' => 'Report deleted successfully.'], 200);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['message' => 'Report not found.'], 404);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'An error occurred while deleting the report.',
            'error' => $e->getMessage()
        ], 500);
    }
}

}