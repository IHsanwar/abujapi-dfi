<?php

namespace App\Http\Controllers;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function createLocation(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:locations,code',
        ]);
        $location = Location::create([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
        ]);
        return response()->json(['message' => 'Location created successfully', 'location' => $location], 201);
    }

    public function deleteLocation($id) {
        $location = Location::find($id);
        if (!$location) {
            return response()->json(['message' => 'Location not found'], 404);
        }

        $location->delete();
        return response()->json(['message' => 'Location deleted successfully'], 200);
    }
    public function updateLocation(Request $request, $id) {
        $location = Location::find($id);
        if (!$location) {
            return response()->json(['message' => 'Location not found'], 404);
        }
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|required|string|max:10|unique:locations,code,' . $location->id,
        ]);
        $location->name = $request->input('name', $location->name);
        $location->code = $request->input('code', $location->code);
        $location->save();
        return response()->json(['message' => 'Location updated successfully', 'location' => $location], 200);

    }
}
