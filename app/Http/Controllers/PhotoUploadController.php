<?php

namespace App\Http\Controllers;

use App\Models\ProblemItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
            'problem_item_id' => 'required' // Allow 'temp' for temporary uploads
        ]);

        try {
            // Upload photo
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $filename = time() . '_' . str_replace(' ', '_', $photo->getClientOriginalName());
                
                // Store in public/uploads/problem-photos directory
                $path = $photo->storeAs('problem-photos', $filename, 'public');
                $photoPath = 'uploads/' . $path;
                $photoUrl = asset($photoPath);
                
                // Only add to problem item if it's not a temporary upload
                if ($request->problem_item_id !== 'temp') {
                    $problemItem = ProblemItem::findOrFail($request->problem_item_id);
                    $problemItem->addPhoto($photoPath);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Photo uploaded successfully',
                    'photo_url' => $photoUrl,
                    'photo_path' => $photoPath,
                    'all_photos' => $request->problem_item_id !== 'temp' ? $problemItem->photos : []
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No photo file found'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading photo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        $request->validate([
            'photo_path' => 'required|string',
            'problem_item_id' => 'required|exists:problem_items,id'
        ]);

        try {
            $problemItem = ProblemItem::findOrFail($request->problem_item_id);

            // Remove photo from database
            $problemItem->removePhoto($request->photo_path);

            // Delete file from storage
            $filePath = str_replace('uploads/', '', $request->photo_path);
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return response()->json([
                'success' => true,
                'message' => 'Photo deleted successfully',
                'remaining_photos' => $problemItem->photos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting photo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function list($problemItemId)
    {
        try {
            $problemItem = ProblemItem::with('problem')->findOrFail($problemItemId);

            $photoUrls = [];
            if ($problemItem->hasPhotos()) {
                foreach ($problemItem->photos as $photoPath) {
                    $photoUrls[] = [
                        'url' => asset($photoPath),
                        'path' => $photoPath
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'photos' => $photoUrls,
                'count' => count($photoUrls)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching photos: ' . $e->getMessage()
            ], 500);
        }
    }
}
