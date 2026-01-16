<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UploadController extends Controller
{
    public function index(Request $request)
    {
        $uploads = Upload::query();
        $perPage = 20;

        if ($request->perPage) {
            $perPage = $request->perPage == 0 ? $uploads->count() : $request->perPage;
        }

        $uploads = $uploads->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
        return Inertia::render('Admin/uploads/List', ['uploads' => $uploads]);
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'images' => 'required|array',
                'images.*' => 'required|mimes:jpeg,png,jpg,gif,bmp,webp|max:10240',
            ]);

            try {
                $uploadedFiles = [];
                
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $file) {
                        $path = 'uploads';
                        $fileName = ImageHelper::customSaveImage($file, $path);
                        
                        $upload = new Upload();
                        $upload->file_name = $fileName;
                        $upload->save();
                        
                        $uploadedFiles[] = $upload;
                    }
                }

                session()->flash('success', count($uploadedFiles) . ' image(s) uploaded successfully');
                return redirect()->route('admin.uploads.index');
            } catch (\Throwable $e) {
                Log::error("EXCEPTION :: upload images " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }

        return Inertia::render('Admin/uploads/CreateEdit');
    }

    public function destroy($id)
    {
        $upload = Upload::find($id);
        if ($upload) {
            if (!empty($upload->file_name) && file_exists(public_path($upload->file_name))) {
                @unlink(public_path($upload->file_name));
            }
            $upload->delete();
            session()->flash('success', 'Image deleted successfully');
            return redirect()->route('admin.uploads.index');
        }
        session()->flash('error', 'Image not found');
        return redirect()->route('admin.uploads.index');
    }
}
