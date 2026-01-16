<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\BottomBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class BannerController extends Controller
{
    public function bannerList(Request $request)
    {
        $banners = Banner::query();
        $perPage = 20;

        if ($request->title) {
            $banners = $banners->where('title', 'like', '%' . trim($request->title) . '%');
        }

        if ($request->page_name) {
            $banners = $banners->where('page_name', 'like', '%' . trim($request->page_name) . '%');
        }

        if ($request->perPage) {
            if ($request->perPage == 0) {
                $perPage = $banners->count();
            } else {
                $perPage = $request->perPage;
            }
        }

        $banners = $banners->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
        return Inertia::render('Admin/banner/List', ['banners' => $banners]);
    }

    public function createBanner(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'title' => 'required|max:100',
                'page_name' => 'required',
                'short_description' => 'required|max:500',
                'banner_image' => 'required|mimes:jpeg,png,jpg,gif,bmp,webp|max:10240',
            ]);

            try {

                $existingBanner = Banner::where('page_name', $request->page_name)->first();
                if ($existingBanner) {
                    return back()->withErrors(['page_name' => 'Only one banner is allowed for ' . $request->page_name])->withInput();
                }

                $banner = new Banner();
                $banner->fill($request->all());

                if ($request->hasFile('banner_image')) {
                    $file = $request->file('banner_image');
                    $path = 'banner_image';
                    $banner->thumbnail = ImageHelper::customSaveImage($file, $path);
                }

                $banner->save();
                session()->flash('success', 'Banner created successfully');
                return redirect()->route('admin.banner_list');
            } catch (\Throwable $e) {
                Log::error(" :: EXCEPTION :: create banner" . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }

        return Inertia::render('Admin/banner/CreateEdit');
    }

    public function edit($banner_id)
    {
        try {
            $banner = Banner::find($banner_id);
            if ($banner) {
                return Inertia::render('Admin/banner/CreateEdit', compact('banner'));
            }
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: edit error " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function updateBanner(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:100',
            'page_name' => 'required',
            'short_description' => 'required',
            'banner_image' => $request->hasFile('banner_image') ? 'required|mimes:jpeg,png,jpg,gif,bmp|max:10240' : 'nullable',
        ]);

        $banner = Banner::findOrFail($id);
        $existingBanner = Banner::where('page_name', $request->page_name)->where('id', '!=', $id)->first();
        if ($existingBanner) {
            return back()->withErrors(['page_name' => 'Only one banner is allowed for ' . $request->page_name])->withInput();
        }
        $banner->fill($request->all());

        if ($request->hasFile('banner_image')) {
            if (!empty($banner->thumbnail) && file_exists(public_path($banner->thumbnail))) {
                @unlink(public_path($banner->thumbnail));
            }
            $file = $request->file('banner_image');
            $path = 'banner_image';
            $banner->thumbnail = ImageHelper::customSaveImage($file, $path);
        }

        $banner->save();

        session()->flash('success', 'Banner updated successfully.');
        return redirect()->route('admin.banner_list');
    }

    public function deleteBanner($id)
    {
        $banner = Banner::find($id);
        if ($banner) {
            if (file_exists($banner->thumbnail)) {
                @unlink($banner->thumbnail);
            }
            $banner->delete();
            session()->flash('success', 'Banner deleted successfully');
            return redirect()->route('admin.banner_list');
        }
        session()->flash('error', 'Banner not found');
        return redirect()->route('admin.banner_list');
    }

    public function bottomBannerList(Request $request)
    {
        $banners = BottomBanner::query();
        $perPage = 20;

        if ($request->title) {
            $banners = $banners->where('title', 'like', '%' . trim($request->title) . '%');
        }

        if ($request->page_name) {
            $banners = $banners->where('page_name', 'like', '%' . trim($request->page_name) . '%');
        }

        if ($request->perPage) {
            if ($request->perPage == 0) {
                $perPage = $banners->count();
            } else {
                $perPage = $request->perPage;
            }
        }

        $banners = $banners->orderBy('id', 'asc')->paginate($perPage)->withQueryString();
        return Inertia::render('Admin/bottom_banner/List', ['banners' => $banners]);
    }

    public function createBottomBanner(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'title' => 'required|max:100',
                'page_name' => 'required',
                'subtitle' => 'required|max:500',
                'thumbnail' => 'required|mimes:jpeg,png,jpg,gif,bmp,webp|max:10240',
            ]);
            try {
                $banner = new BottomBanner;
                $existingBanner = BottomBanner::where('page_name', $request->page_name)->first();
                if ($existingBanner) {
                    return back()->withErrors(['page_name' => 'Only one bottom banner is allowed for ' . $request->page_name])->withInput();
                }
                $banner->fill($request->all());
                if ($request->hasFile('thumbnail')) {
                    $file = $request->file('thumbnail');
                    $path = 'bottom_banner_thumbnail';
                    $banner->thumbnail = ImageHelper::customSaveImage($file, $path);
                }
                $banner->save();
                session()->flash('success', 'Bottom Banner created successfully');
                return redirect()->route('admin.bottom-banner-list');
            } catch (\Throwable $e) {
                Log::error(" :: EXCEPTION :: create banner" . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }
        return Inertia::render('Admin/bottom_banner/CreateEdit');
    }
    public function editBottomBanner($banner_id)
    {
        try {
            $banner = BottomBanner::find($banner_id);
            if ($banner) {
                return Inertia::render('Admin/bottom_banner/CreateEdit', compact('banner'));
            }
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: edit error " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }
    public function updateBottomBanner(Request $request, $id)
    {

        $request->validate([
            'title' => 'required|max:100',
            'page_name' => 'required',
            'subtitle' => 'required',
            'thumbnail' => $request->hasFile('thumbnail') ? 'required|mimes:jpeg,png,jpg,gif,bmp|max:10240' : 'nullable',
        ]);

        $banner = BottomBanner::findOrFail($id);
        $existingBanner = BottomBanner::where('page_name', $request->page_name)->where('id', '!=', $id)->first();
        if ($existingBanner) {
            return back()->withErrors(['page_name' => 'Only one bottom banner is allowed for ' . $request->page_name])->withInput();
        }
        $banner->fill($request->all());

        if ($request->hasFile('thumbnail')) {
            if (!empty($banner->thumbnail) && file_exists(public_path($banner->thumbnail))) {
                @unlink(public_path($banner->thumbnail));
            }
            $file = $request->file('thumbnail');
            $path = 'bottom_banner_thumbnail';
            $banner->thumbnail = ImageHelper::customSaveImage($file, $path);
        }

        $banner->save();

        session()->flash('success', 'Bottom Banner updated successfully.');
        return redirect()->route('admin.bottom-banner-list');
    }
}
