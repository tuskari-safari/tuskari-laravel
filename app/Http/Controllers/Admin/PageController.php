<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\CmsManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PageController extends Controller
{
    public function createEditPages(Request $request)
    {
        $pages = CmsManagement::get();

        if (!$request->isMethod('post')) {
            return Inertia::render('Admin/Pages/CreateEdit', compact('pages'));
        }

        $slug = $request->slug;
        $page = CmsManagement::where('slug', $slug)->firstOrFail();

        // try {
        switch ($slug) {
            case 'what-is-tuskari':
                $validatedData = $request->validate([
                    'title' => 'required|string|max:255',
                    'subtitle' => 'required|string|max:255',
                    'thumbnail' => 'nullable|mimes:jpeg,png,jpg,gif|max:10240',
                ]);

                if ($request->hasFile('thumbnail')) {
                    if (file_exists($page->thumbnail)) {
                        @unlink($page->thumbnail);
                    }
                    $thumbnail = ImageHelper::customSaveImage($request->file('thumbnail'), 'page_cms');
                }
                $validatedData['thumbnail'] = $thumbnail ?? $page->thumbnail;

                $page->update($validatedData);
                break;

            case 'how-it-works':
                $validatedData = $request->validate([
                    'title' => 'required|string|max:255',
                    'subtitle' => 'required|string|max:255',
                    'thumbnail' => 'nullable|mimes:jpeg,png,jpg,gif|max:10240',
                    'features' => 'array',
                    'features.*.title' => 'nullable|max:255',
                    'features.*.subtitle' => 'nullable|max:255',
                    'features.*.logo' => function ($attribute, $value, $fail) use ($request) {
                        if ($value instanceof \Illuminate\Http\UploadedFile && !in_array($value->extension(), ['jpeg', 'png', 'jpg', 'gif', 'svg', 'avif', 'webp'])) {
                            $fail('Invalid image format.');
                        }
                    },
                ], [
                    'features.*.title.required' => 'Feature title is required.',
                    'features.*.subtitle.required' => 'Feature sub title is required.',
                    'features.*.logo.required' => 'Feature logo is required.',
                ]);

                // Handle thumbnail
                if ($request->hasFile('thumbnail')) {
                    if (file_exists($page->thumbnail)) {
                        @unlink($page->thumbnail);
                    }
                    $thumbnail = ImageHelper::customSaveImage($request->file('thumbnail'), 'page_cms');
                }

                $validatedData['thumbnail'] = $thumbnail ?? $page->thumbnail;

                // Handle features
                $features = [];
                $existingFeatures = json_decode($page->features, true) ?? [];
                $baseUrl = config('app.url') . '/';

                if (isset($request->features)) {
                    foreach ($request->features as $index => $item) {
                        $imageUrl = null;
                        $prevLogoFillUrl = $existingFeatures[$index]['logo'] ?? null;
                        $prevLogoPath = $prevLogoFillUrl ? str_replace($baseUrl, '', $prevLogoFillUrl) : null;

                        if ($request->hasFile("features.$index.logo")) {
                            if (file_exists($prevLogoPath)) {
                                @unlink($prevLogoPath);
                            }
                            $imageUrl = ImageHelper::customSaveImage($request->file("features.$index.logo"), 'cms_features');
                        }

                        $features[] = [
                            'title' => $item['title'],
                            'subtitle' => $item['subtitle'],
                            'logo' => $imageUrl ? url($imageUrl) : $item['logo'],
                        ];
                    }
                }

                $validatedData['features'] = $features;
                $page->update($validatedData);
                break;

            case 'why-book-with-tuskari':
                $validatedData = $request->validate([
                    'title' => 'required|string|max:255',
                    'thumbnail' => 'nullable|mimes:jpeg,png,jpg,gif|max:10240',
                    'features' => 'array',
                    'features.*.title' => 'nullable|max:255',
                    'features.*.subtitle' => 'nullable|max:255',
                    'features.*.logo' => function ($attribute, $value, $fail) use ($request) {
                        if ($value instanceof \Illuminate\Http\UploadedFile && !in_array($value->extension(), ['jpeg', 'png', 'jpg', 'gif', 'svg', 'avif', 'webp'])) {
                            $fail('Invalid image format.');
                        }
                    },
                ], [
                    'features.*.title.required' => 'Feature title is required.',
                    'features.*.subtitle.required' => 'Feature sub title is required.',
                    'features.*.logo.required' => 'Feature logo is required.',
                ]);

                // Handle thumbnail
                if ($request->hasFile('thumbnail')) {
                    if (file_exists($page->thumbnail)) {
                        @unlink($page->thumbnail);
                    }
                    $thumbnail = ImageHelper::customSaveImage($request->file('thumbnail'), 'page_cms');
                }

                $validatedData['thumbnail'] = $thumbnail ?? $page->thumbnail;

                // Handle features
                $features = [];
                $existingFeatures = json_decode($page->features, true) ?? [];
                $baseUrl = config('app.url') . '/';

                if (isset($request->features)) {
                    foreach ($request->features as $index => $item) {
                        $imageUrl = null;
                        $prevLogoFillUrl = $existingFeatures[$index]['logo'] ?? null;
                        $prevLogoPath = $prevLogoFillUrl ? str_replace($baseUrl, '', $prevLogoFillUrl) : null;

                        if ($request->hasFile("features.$index.logo")) {
                            if (file_exists($prevLogoPath)) {
                                @unlink($prevLogoPath);
                            }
                            $imageUrl = ImageHelper::customSaveImage($request->file("features.$index.logo"), 'cms_features');
                        }
                        
                        $features[] = [
                            'title' => $item['title'],
                            'subtitle' => $item['subtitle'],
                            'logo' => $imageUrl ? url($imageUrl) : $item['logo'],
                        ];
                    }
                }

                $validatedData['features'] = $features;
                $page->update($validatedData);
                break;
            case 'trip-regions':
                $validatedData = $request->validate([
                    'title' => 'required|string|max:255',
                    'subtitle' => 'required|string|max:255',
                    'thumbnail' => 'nullable|mimes:jpeg,png,jpg,gif|max:10240',
                    'features' => 'array',
                    'features.*.title' => 'nullable|max:255',
                    'features.*.subtitle' => 'nullable|max:255',
                    'features.*.logo' => function ($attribute, $value, $fail) use ($request) {
                        if ($value instanceof \Illuminate\Http\UploadedFile && !in_array($value->extension(), ['jpeg', 'png', 'jpg', 'gif', 'svg', 'avif', 'webp'])) {
                            $fail('Invalid image format.');
                        }
                    },
                ], [
                    'features.*.title.required' => 'Feature title is required.',
                    'features.*.subtitle.required' => 'Feature sub title is required.',
                    'features.*.logo.required' => 'Feature logo is required.',
                ]);
                // Handle features
                $features = [];
                $existingFeatures = json_decode($page->features, true) ?? [];
                $baseUrl = config('app.url') . '/';

                if (isset($request->features)) {
                    foreach ($request->features as $index => $item) {
                        $imageUrl = null;
                        $prevLogoFillUrl = $existingFeatures[$index]['logo'] ?? null;
                        $prevLogoPath = $prevLogoFillUrl ? str_replace($baseUrl, '', $prevLogoFillUrl) : null;

                        if ($request->hasFile("features.$index.logo")) {
                            if (file_exists($prevLogoPath)) {
                                @unlink($prevLogoPath);
                            }
                            $imageUrl = ImageHelper::customSaveImage($request->file("features.$index.logo"), 'cms_features');
                        }

                        $features[] = [
                            'title' => $item['title'],
                            'subtitle' => $item['subtitle'],
                            'logo' => $imageUrl ? url($imageUrl) : ($item['logo'] ?? null),
                        ];
                    }
                }

                $validatedData['features'] = $features;
                $page->update($validatedData);
                break;
            case 'operator-highlights':
                $validatedData = $request->validate([
                    'title' => 'required|string|max:255',
                    'subtitle' => 'required|string|max:255',
                    'thumbnail' => 'nullable|mimes:jpeg,png,jpg,gif|max:10240',
                ]);

                if ($request->hasFile('thumbnail')) {
                    if (file_exists($page->thumbnail)) {
                        @unlink($page->thumbnail);
                    }
                    $thumbnail = ImageHelper::customSaveImage($request->file('thumbnail'), 'page_cms');
                }
                $validatedData['thumbnail'] = $thumbnail ?? $page->thumbnail;

                $page->update($validatedData);
                break;
            case 'why-travel-with-us':
                $validatedData = $request->validate([
                    'title' => 'required|string|max:255',
                    'text_content' => 'nullable|string|max:1000',
                    'thumbnail' => 'nullable|mimes:jpeg,png,jpg,gif|max:10240',
                    'features' => 'array',
                    'features.*.title' => 'nullable|max:255',
                    'features.*.subtitle' => 'nullable|max:255',
                    'features.*.logo' => function ($attribute, $value, $fail) use ($request) {
                        if ($value instanceof \Illuminate\Http\UploadedFile && !in_array($value->extension(), ['jpeg', 'png', 'jpg', 'gif', 'svg', 'avif', 'webp'])) {
                            $fail('Invalid image format.');
                        }
                    },
                ], [
                    'features.*.title.required' => 'Feature title is required.',
                    'features.*.subtitle.required' => 'Feature sub title is required.',
                    'features.*.logo.required' => 'Feature logo is required.',
                ]);

                // Handle thumbnail
                if ($request->hasFile('thumbnail')) {
                    if (file_exists($page->thumbnail)) {
                        @unlink($page->thumbnail);
                    }
                    $thumbnail = ImageHelper::customSaveImage($request->file('thumbnail'), 'page_cms');
                }
                $validatedData['thumbnail'] = $thumbnail ?? $page->thumbnail;

                // Handle features
                $features = [];
                $existingFeatures = json_decode($page->features, true) ?? [];
                $baseUrl = config('app.url') . '/';

                if (isset($request->features)) {
                    foreach ($request->features as $index => $item) {
                        $imageUrl = null;
                        $prevLogoFillUrl = $existingFeatures[$index]['logo'] ?? null;
                        $prevLogoPath = $prevLogoFillUrl ? str_replace($baseUrl, '', $prevLogoFillUrl) : null;

                        if ($request->hasFile("features.$index.logo")) {
                            if (file_exists($prevLogoPath)) {
                                @unlink($prevLogoPath);
                            }
                            $imageUrl = ImageHelper::customSaveImage($request->file("features.$index.logo"), 'cms_features');
                        }

                        $features[] = [
                            'title' => $item['title'],
                            'subtitle' => $item['subtitle'],
                            'logo' => $imageUrl ? url($imageUrl) : ($item['logo'] ?? null),
                        ];
                    }
                }

                $validatedData['features'] = $features;
                $page->update($validatedData);
                break;

            case 'why-tuskari-exists':
                $validatedData = $request->validate([
                    'title' => 'required|string|max:255',
                    'subtitle' => 'required|string|max:255',
                    'text_content' => 'nullable|string|max:1000',
                    'thumbnail' => 'nullable|mimes:jpeg,png,jpg,gif|max:10240',
                ]);

                if ($request->hasFile('thumbnail')) {
                    if (file_exists($page->thumbnail)) {
                        @unlink($page->thumbnail);
                    }
                    $thumbnail = ImageHelper::customSaveImage($request->file('thumbnail'), 'page_cms');
                }
                $validatedData['thumbnail'] = $thumbnail ?? $page->thumbnail;

                $page->update($validatedData);
                break;
            case 'footer':
                $validatedData = $request->validate([
                    'text_content' => 'nullable|string|max:1000',
                ]);

                $page->update($validatedData);
                break;

            case 'why-join-tuskari':
                $validatedData = $request->validate([
                    'title' => 'required|string|max:255',
                    'subtitle' => 'required|string|max:255',
                    'features' => 'array',
                    'features.*.title' => 'nullable|max:255',
                    'features.*.subtitle' => 'nullable|max:255',
                    'features.*.logo' => function ($attribute, $value, $fail) use ($request) {
                        if ($value instanceof \Illuminate\Http\UploadedFile && !in_array($value->extension(), ['jpeg', 'png', 'jpg', 'gif', 'svg', 'avif', 'webp'])) {
                            $fail('Invalid image format.');
                        }
                    },
                ], [
                    'features.*.title.required' => 'Feature title is required.',
                    'features.*.subtitle.required' => 'Feature sub title is required.',
                    'features.*.logo.required' => 'Feature logo is required.',
                ]);
                if ($request->hasFile('thumbnail')) {
                    if (file_exists($page->thumbnail)) {
                        @unlink($page->thumbnail);
                    }
                    $thumbnail = ImageHelper::customSaveImage($request->file('thumbnail'), 'page_cms');
                }
                $validatedData['thumbnail'] = $thumbnail ?? $page->thumbnail;

                // Handle features
                $features = [];
                $existingFeatures = json_decode($page->features, true) ?? [];
                $baseUrl = config('app.url') . '/';

                if (isset($request->features)) {
                    foreach ($request->features as $index => $item) {
                        $imageUrl = null;
                        $prevLogoFillUrl = $existingFeatures[$index]['logo'] ?? null;
                        $prevLogoPath = $prevLogoFillUrl ? str_replace($baseUrl, '', $prevLogoFillUrl) : null;

                        if ($request->hasFile("features.$index.logo")) {
                            if (file_exists($prevLogoPath)) {
                                @unlink($prevLogoPath);
                            }
                            $imageUrl = ImageHelper::customSaveImage($request->file("features.$index.logo"), 'cms_features');
                        }

                        $features[] = [
                            'title' => $item['title'],
                            'subtitle' => $item['subtitle'],
                            'logo' => $imageUrl ? url($imageUrl) : ($item['logo'] ?? null),
                        ];
                    }
                }

                $validatedData['features'] = $features;
                $page->update($validatedData);
                break;
            case 'what-you-can-list':
                $validatedData = $request->validate([
                    'title' => 'required|string|max:255',
                    'subtitle' => 'required|string|max:255',
                    'thumbnail' => 'nullable|mimes:jpeg,png,jpg,gif,svg,avif,webp|max:10240',
                    'text_content' => 'nullable|string|max:1000',
                ]);

                if ($request->hasFile('thumbnail')) {
                    if (file_exists($page->thumbnail)) {
                        @unlink($page->thumbnail);
                    }
                    $thumbnail = ImageHelper::customSaveImage($request->file('thumbnail'), 'page_cms');
                }
                $validatedData['thumbnail'] = $thumbnail ?? $page->thumbnail;

                $page->update($validatedData);
                break;
            case 'built-for-you':
                $validatedData = $request->validate([
                    'title' => 'required|string|max:255',
                    'subtitle' => 'required|string|max:255',
                    'thumbnail' => 'nullable|mimes:jpeg,png,jpg,gif,svg,avif,webp|max:10240',
                    'text_content' => 'nullable|string|max:1000',
                ]);
                if ($request->hasFile('thumbnail')) {
                    if (file_exists($page->thumbnail)) {
                        @unlink($page->thumbnail);
                    }
                    $thumbnail = ImageHelper::customSaveImage($request->file('thumbnail'), 'page_cms');
                }
                $validatedData['thumbnail'] = $thumbnail ?? $page->thumbnail;

                $page->update($validatedData);
                break;
            case 'more-of-the-money-goes':
                $validatedData = $request->validate([
                    'title' => 'required|string|max:255',
                    'subtitle' => 'required|string|max:255',
                    'thumbnail' => 'nullable|mimes:jpeg,png,jpg,gif,svg,avif,webp|max:10240',
                ]);
                if ($request->hasFile('thumbnail')) {
                    if (file_exists($page->thumbnail)) {
                        @unlink($page->thumbnail);
                    }
                    $thumbnail = ImageHelper::customSaveImage($request->file('thumbnail'), 'page_cms');
                }
                $validatedData['thumbnail'] = $thumbnail ?? $page->thumbnail;

                $page->update($validatedData);
                break;
            case 'where-safari-born':
                $validatedData = $request->validate([
                    'title' => 'required|string|max:255',
                    'subtitle' => 'required|string|max:500',
                    'thumbnail' => 'nullable|mimes:jpeg,png,jpg,gif,svg,avif,webp|max:10240',
                ]);
                if ($request->hasFile('thumbnail')) {
                    if (file_exists($page->thumbnail)) {
                        @unlink($page->thumbnail);
                    }
                    $thumbnail = ImageHelper::customSaveImage($request->file('thumbnail'), 'page_cms');
                }
                $validatedData['thumbnail'] = $thumbnail ?? $page->thumbnail;

                $page->update($validatedData);
                break;
            case 'mara-region':
                $validatedData = $request->validate([
                    'title' => 'required|string|max:255',
                    'subtitle' => 'required|string|max:500',
                    'thumbnail' => 'nullable|mimes:jpeg,png,jpg,gif,svg,avif,webp|max:10240',
                ]);
                if ($request->hasFile('thumbnail')) {
                    if (file_exists($page->thumbnail)) {
                        @unlink($page->thumbnail);
                    }
                    $thumbnail = ImageHelper::customSaveImage($request->file('thumbnail'), 'page_cms');
                }
                $validatedData['thumbnail'] = $thumbnail ?? $page->thumbnail;

                $page->update($validatedData);
                break;
            case 'laikipia-and-northern-kenya':
                $validatedData = $request->validate([
                    'title' => 'required|string|max:255',
                    'subtitle' => 'required|string|max:500',
                    'thumbnail' => 'nullable|mimes:jpeg,png,jpg,gif,svg,avif,webp|max:10240',
                ]);
                if ($request->hasFile('thumbnail')) {
                    if (file_exists($page->thumbnail)) {
                        @unlink($page->thumbnail);
                    }
                    $thumbnail = ImageHelper::customSaveImage($request->file('thumbnail'), 'page_cms');
                }
                $validatedData['thumbnail'] = $thumbnail ?? $page->thumbnail;

                $page->update($validatedData);
                break;
            case 'kenyan-coast':
                $validatedData = $request->validate([
                    'title' => 'required|string|max:255',
                    'subtitle' => 'required|string|max:500',
                    'thumbnail' => 'nullable|mimes:jpeg,png,jpg,gif,svg,avif,webp|max:10240',
                ]);
                if ($request->hasFile('thumbnail')) {
                    if (file_exists($page->thumbnail)) {
                        @unlink($page->thumbnail);
                    }
                    $thumbnail = ImageHelper::customSaveImage($request->file('thumbnail'), 'page_cms');
                }
                $validatedData['thumbnail'] = $thumbnail ?? $page->thumbnail;

                $page->update($validatedData);
                break;
            default:
                session()->flash('error', 'Work in progress ...');
                return back();
        }

        session()->flash('success', 'CMS Updated successfully.');
        return redirect()->route('admin.create-edit-pages');
        // } catch (\Throwable $th) {
        //     Log::error("CMS Update Error ({$slug}): " . $th->getMessage(), [
        //         'trace' => $th->getTraceAsString(),
        //         'request' => $request->all()
        //     ]);
        //     abort(500, 'An error occurred while updating the CMS.');
        // }
    }
}
