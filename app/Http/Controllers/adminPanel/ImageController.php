<?php

namespace App\Http\Controllers\adminPanel;

use App\Models\Image;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ImageController extends Controller
{
    public function uploadImage(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_type' => 'required|in:xray,background,contract,profile_picture,proof_of_payment',
        ]);

        $path = $request->file('image')->store('images', 'public');

        Image::create([
            'patient_id' => $request->input('patient_id'),
            'image_type' => $request->input('image_type'),
            'image_path' => $path,
        ]);

        $imageTypes = [
            'xray' => 'Patient x-ray uploaded',
            'background' => 'Patient background uploaded',
            'contract' => 'Patient contract uploaded',
        ];
        
        $imageType = $request->input('image_type');
        
        if (array_key_exists($imageType, $imageTypes)) {
            AuditLog::create([
                'action' => 'Upload',
                'model_type' => $imageTypes[$imageType],
                'model_id' => $request->input('patient_id'),
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'changes' => json_encode($request->all()), // Log the request data
            ]);
        }

        return redirect()->back()->with('success', 'Image uploaded successfully!');
    }
}
