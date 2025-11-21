<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;

class RegionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $regions = Region::query();

            if ($request->name) {
                $regions = $regions->where('name', 'like', '%' . trim($request->name) . '%');
            }



            if ($request->fieldName && $request->shortBy) {
                $regions->orderBy($request->fieldName, $request->shortBy);
            }

            $regions = $regions->orderBy('id', 'desc')->get();
            return Inertia::render('Admin/region/List', ['regions' => $regions]);
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'name' => 'required|max:255',
                'description' => 'nullable',
                'image' => 'required|mimes:jpeg,png,jpg,svg|max:10240',
                'status' => 'required|in:0,1',
            ]);
            try {
                $region = new Region();
                
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $path = 'region_images';
                    $final_image_url = ImageHelper::customSaveImage($file, $path);
                    $credentials['image'] = $final_image_url;
                }

                $credentials['slug'] = Str::slug($credentials['name']);

                $region->fill($credentials)->save();

                session()->flash('success', 'Region Successfully Created.');
                return redirect()->route('admin.region.index');
            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }

        return Inertia::render('Admin/region/CreateEdit');
    }

    public function edit(Request $request, $id)
    {
        $region = Region::findOrFail($id);
        $prev_picture = $region->image;

        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'name' => 'required|max:255',
                'description' => 'nullable',
                'image' => $request->hasFile('image') ? 'mimes:jpeg,png,jpg,svg|max:10240' : '',
                'status' => 'required|in:0,1',
            ]);
            try {
                if ($request->hasFile('image')) {
                    if (file_exists($prev_picture)) {
                        @unlink($prev_picture);
                    }
                    $image = ImageHelper::customSaveImage($request->file('image'), 'region_images');
                }
                
                $credentials['image'] = $image ?? $region->image;
                $credentials['slug'] = Str::slug($credentials['name']);

                $region->fill($credentials)->save();

                session()->flash('success', 'Region Successfully Updated.');
                return redirect()->route('admin.region.index');
            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }

        return Inertia::render('Admin/region/CreateEdit', ['region' => $region]);
    }

    public function destroy($id)
    {
        try {
            $region = Region::where('id', $id)->first();
            $prev_picture = $region->image;
            if (file_exists($prev_picture)) {
                @unlink($prev_picture);
            }
            $region->delete();

            session()->flash('success', 'Region successfully deleted');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function changeStatus(Request $request)
    {
        try {
            $region = Region::findOrFail($request->id);
            $region->status = $region->status == 1 ? 0 : 1;
            $region->save();

            session()->flash('success', 'Status updated successfully');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            session()->flash('error', 'Failed to update status');
            return back();
        }
    }
}