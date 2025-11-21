<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\CountryGuide;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Str;

class CountryGuideController extends Controller
{
    public function index(Request $request)
    {
        try {
            $countries = CountryGuide::query();

            if ($request->name) {
                $countries = $countries->where('name', 'LIKE', '%' . $request->name . '%');
            }

            if ($request->region) {
                $countries = $countries->where('region', $request->region);
            }

            if (isset($request->status)) {
                $countries = $countries->where('status', $request->status);
            }

            if ($request->fieldName && $request->shortBy) {
                $countries->orderBy($request->fieldName, $request->shortBy);
            }

            $perPage = $request->perPage ?? 20;

            $countries = $countries->with('region')->orderBy('id', 'desc')->paginate($perPage)->withQueryString();

            return Inertia::render('Admin/country_guide/List', ['countries' => $countries]);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $validatedData = $request->validate([

                'name' => 'required|string|max:255|unique:country_guides,name',
                'subtitle' => 'required|string|max:800',
                'region' => 'required|exists:regions,id',
                'status' => 'required',
                // Image uploads
                'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
                'unique_experience_title' => 'nullable|string|max:255',
                'unique_experience_desc' => 'nullable|string|max:1000',
                'middle_sec_title' => 'nullable|string|max:255',
                'middle_sec_subtitle' => 'nullable|string',

                // Arrays
                'content_details' => 'array',
                'content_details.*.title' => 'nullable|string|max:255',
                'content_details.*.subtitle' => 'nullable|string|max:800',
                'content_details.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
     

                'bottom_banner' => 'array',
                'bottom_banner.*.title' => 'nullable|string|max:255',
                'bottom_banner.*.subtitle' => 'nullable|string|max:255',
                'bottom_banner.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',

                'unique_experience' => 'array',
                'unique_experience.*.title' => 'nullable|string|max:255',
                'unique_experience.*.subtitle' => 'nullable|string|max:255',
                'unique_experience.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',

                'faqs' => 'array',
                'faqs.*.question' => 'nullable|max:500',
                'faqs.*.answer' => 'nullable|max:5000',
            ], [
                // Custom Messages for Category
                'content_details.*.title.max' => 'The title for content_details must not exceed 255 characters.',
                'content_details.*.subtitle.max' => 'The subtitle for content_details must not exceed 255 characters.',
                'content_details.*.image.image' => 'The image for content_details must be a valid image format.',
                'content_details.*.image.mimes' => 'The image for content_details must be a file of type: jpeg, png, jpg, gif.',
                'content_details.*.image.max' => 'The image for content_details may not be greater than 20MB.',

                // Custom Messages for Traveler Tips
                'bottom_banner.*.title.max' => 'The title for Traveler Tips must not exceed 255 characters.',
                'bottom_banner.*.subtitle.max' => 'The subtitle for Traveler Tips must not exceed 255 characters.',
                'bottom_banner.*.image.image' => 'The image for Traveler Tips must be a valid image format.',
                'bottom_banner.*.image.mimes' => 'The image for Traveler Tips must be a file of type: jpeg, png, jpg, gif.',
                'bottom_banner.*.image.max' => 'The image for Traveler Tips may not be greater than 20MB.',

                // Custom Messages for Unique Experience
                'unique_experience.*.title.max' => 'The title for Unique Experience must not exceed 255 characters.',
                'unique_experience.*.subtitle.max' => 'The subtitle for Unique Experience must not exceed 255 characters.',
                'unique_experience.*.image.image' => 'The image for Unique Experience must be a valid image format.',
                'unique_experience.*.image.mimes' => 'The image for Unique Experience must be a file of type: jpeg, png, jpg, gif.',
                'unique_experience.*.image.max' => 'The image for Unique Experience may not be greater than 20MB.',
            ]);
            $country = new CountryGuide($validatedData);

            if ($request->hasFile('banner_image')) {
                $file = $request->file('banner_image');
                $path = 'country_banner_images';
                $final_image_url = ImageHelper::customSaveImage($file, $path);
                $country->banner_image = $final_image_url;
            }
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $path = 'country_thumbnail_images';
                $final_image_url = ImageHelper::customSaveImage($file, $path);
                $country->thumbnail = $final_image_url;
            }


            $details = [];
            if (isset($request->content_details)) {
                foreach ($request->content_details as $index => $item) {
                    $imageUrl = null;

                    if ($request->hasFile("content_details.$index.image")) {
                        $imageUrl = ImageHelper::customSaveImage(
                            $request->file("content_details.$index.image"),
                            'countries/content'
                        );
                    }
                    if (empty($item['title']) && empty($item['subtitle']) && empty($imageUrl)) {
                        continue;
                    }

                    $details[] = [
                        'title' => $item['title'],
                        'subtitle' => $item['subtitle'],
                        'image' => $imageUrl ? url($imageUrl) : null,
                        // 'align' => $item['align'],
                    ];
                }

                $country->content_details = !empty($details) ? json_encode($details) : null;
            }

            $details = [];
            if (isset($request->bottom_banner)) {
                foreach ($request->bottom_banner as $index => $item) {
                    $imageUrl = null;

                    if ($request->hasFile("bottom_banner.$index.image")) {
                        $imageUrl = ImageHelper::customSaveImage(
                            $request->file("bottom_banner.$index.image"),
                            'countries/bottom_banner'
                        );
                    }
                    if (empty($item['title']) && empty($item['subtitle']) && empty($imageUrl)) {
                        continue;
                    }
                    $details[] = [
                        'title' => $item['title'],
                        'subtitle' => $item['subtitle'],
                        'image' => $imageUrl ? url($imageUrl) : null,
                    ];
                }

                $country->bottom_banner = !empty($details) ? json_encode($details) : null;
            }

            $details = [];
            if (isset($request->unique_experience)) {
                foreach ($request->unique_experience as $index => $item) {
                    $imageUrl = null;

                    if ($request->hasFile("unique_experience.$index.image")) {
                        $imageUrl = ImageHelper::customSaveImage(
                            $request->file("unique_experience.$index.image"),
                            'countries/unique_experience'
                        );
                    }
                    if (empty($item['title']) && empty($item['subtitle']) && empty($imageUrl)) {
                        continue;
                    }
                    $details[] = [
                        'title' => $item['title'],
                        'subtitle' => $item['subtitle'],
                        'image' => $imageUrl ? url($imageUrl) : null,
                    ];
                }

                $country->unique_experience = !empty($details) ? json_encode($details) : null;
            }

            $details = [];
            if (isset($request->faqs)) {
                foreach ($request->faqs as $index => $item) {
                    if (empty($item['question']) && empty($item['answer'])) {
                        continue;
                    }
                    $details[] = [
                        'question' => $item['question'],
                        'answer' => $item['answer'],

                    ];
                }
                $country->faqs = !empty($details) ? json_encode($details) : null;
            }

            $country->slug = Str::slug($country->name);
            $country->save();
            session()->flash('success', 'Park Successfully Created.');
            return redirect()->route('admin.country-guides');
        }
        $regions = Region::where('status', 1)->get();
        return Inertia::render('Admin/country_guide/CreateEdit', ['regions' => $regions]);
    }

    public function edit(Request $request, $id)
    {
        $country = CountryGuide::find($id);
        $prev_picture1 = $country->banner_image;
        $prev_picture2 = $country->thumbnail;
      

        if ($request->isMethod('post')) {

            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:country_guides,name,' . $id,
                'subtitle' => 'required|string|max:800',
                'region' => 'required|exists:regions,id',
                'status' => 'required',
                'unique_experience_title' => 'nullable|string|max:255',
                'unique_experience_desc' => 'nullable|string|max:1000',
                'middle_sec_title' => 'nullable|string|max:255',
                'middle_sec_subtitle' => 'nullable|string|max:1000',
                // Image uploads
                'banner_image' => $request->hasFile('banner_image') ? 'mimes:jpeg,png,jpg,gif|max:20480' : '',
                'thumbnail' => $request->hasFile('thumbnail') ? 'mimes:jpeg,png,jpg,gif|max:20480' : '',
                // Arrays
                'content_details' => 'array',
                'content_details.*.title' => 'nullable|string|max:255',
                'content_details.*.subtitle' => 'nullable|string|max:800',
               
                'content_details.*.image' => function ($attribute, $value, $fail) use ($request) {
                    if ($value instanceof \Illuminate\Http\UploadedFile && !in_array($value->extension(), ['jpeg', 'png', 'jpg', 'gif', 'svg', 'avif', 'webp'])) {
                        $fail('Invalid image format.');
                    }
                },
                'bottom_banner' => 'array',
                'bottom_banner.*.title' => 'nullable|string|max:255',
                'bottom_banner.*.subtitle' => 'nullable|string|max:255',
                'bottom_banner.*.image' =>  function ($attribute, $value, $fail) use ($request) {
                    if ($value instanceof \Illuminate\Http\UploadedFile && !in_array($value->extension(), ['jpeg', 'png', 'jpg', 'gif', 'svg', 'avif', 'webp'])) {
                        $fail('Invalid image format.');
                    }
                },
                'unique_experience' => 'array',
                'unique_experience.*.title' => 'nullable|string|max:255',
                'unique_experience.*.subtitle' => 'nullable|string|max:255',
                'unique_experience.*.image' =>  function ($attribute, $value, $fail) use ($request) {
                    if ($value instanceof \Illuminate\Http\UploadedFile && !in_array($value->extension(), ['jpeg', 'png', 'jpg', 'gif', 'svg', 'avif', 'webp'])) {
                        $fail('Invalid image format.');
                    }
                },

                'faqs' => 'array',
                'faqs.*.question' => 'nullable|max:500',
                'faqs.*.answer' => 'nullable|max:5000',
            ], [
                // Custom Messages for Category
                'content_details.*.title.max' => 'The title for content_details must not exceed 255 characters.',
                'content_details.*.subtitle.max' => 'The subtitle for content_details must not exceed 255 characters.',
                'content_details.*.image.image' => 'The image for content_details must be a valid image format.',
                'content_details.*.image.mimes' => 'The image for content_details must be a file of type: jpeg, png, jpg, gif.',
                'content_details.*.image.max' => 'The image for content_details may not be greater than 2MB.',

                // Custom Messages for Traveler Tips
                'bottom_banner.*.title.max' => 'The title for Traveler Tips must not exceed 255 characters.',
                'bottom_banner.*.subtitle.max' => 'The subtitle for Traveler Tips must not exceed 255 characters.',
                'bottom_banner.*.image.image' => 'The image for Traveler Tips must be a valid image format.',
                'bottom_banner.*.image.mimes' => 'The image for Traveler Tips must be a file of type: jpeg, png, jpg, gif.',
                'bottom_banner.*.image.max' => 'The image for Traveler Tips may not be greater than 2MB.',

                // Custom Messages for Unique Experience
                'unique_experience.*.title.max' => 'The title for Unique Experience must not exceed 255 characters.',
                'unique_experience.*.subtitle.max' => 'The subtitle for Unique Experience must not exceed 255 characters.',
                'unique_experience.*.image.image' => 'The image for Unique Experience must be a valid image format.',
                'unique_experience.*.image.mimes' => 'The image for Unique Experience must be a file of type: jpeg, png, jpg, gif.',
                'unique_experience.*.image.max' => 'The image for Unique Experience may not be greater than 2MB.',
            ]);


            if ($request->hasFile('banner_image')) {

                if (file_exists($prev_picture1)) {
                    @unlink($prev_picture1);
                }
                $file = $request->file('banner_image');
                $path = 'country_banner_images';
                $final_image_url = ImageHelper::customSaveImage($file, $path);
                $country->banner_image = $final_image_url;
            }

            if ($request->hasFile('thumbnail')) {
                if (file_exists($prev_picture2)) {
                    @unlink($prev_picture2);
                }
                $file = $request->file('thumbnail');
                $path = 'country_thumbnail_images';
                $final_image_url = ImageHelper::customSaveImage($file, $path);
                $country->thumbnail = $final_image_url;
            }

            $details = [];
            if (isset($request->content_details)) {
                foreach ($request->content_details as $index => $item) {
                    $imageUrl = null;
                    $contentDetails = json_decode($country->content_details, true) ?? [];
                    $fullUrl = $contentDetails[$index]['image'] ?? null;

                    $baseUrl = env('APP_URL') . '/';
                    $prev_picture = str_replace($baseUrl, '', $fullUrl);

                    if ($request->hasFile("content_details.$index.image")) {
                        if (file_exists($prev_picture)) {
                            @unlink($prev_picture);
                        }

                        $imageUrl = ImageHelper::customSaveImage(
                            $request->file("content_details.$index.image"),
                            'countries/content_details'
                        );
                    }
                    if (empty($item['title']) && empty($item['subtitle']) && empty($imageUrl)) {
                        continue;
                    }
                    $details[] = [
                        'title' => $item['title'],
                        'subtitle' => $item['subtitle'],
                        'image' => $imageUrl ? url($imageUrl) : $fullUrl,
                        // 'align' => $item['align'] ?? null,
                    ];
                }
                $country->content_details = !empty($details) ? json_encode($details) : null;
            }
        
            $details = [];
            if (isset($request->bottom_banner)) {
                foreach ($request->bottom_banner as $index => $item) {
                    $imageUrl = null;
                    $bottomBanner = json_decode($country->bottom_banner, true) ?? [];
                    $fullUrl = $bottomBanner[$index]['image'] ?? null;

                    $baseUrl = env('APP_URL') . '/';
                    $prev_picture = str_replace($baseUrl, '', $fullUrl);

                    if ($request->hasFile("bottom_banner.$index.image")) {
                        $imageUrl = ImageHelper::customSaveImage(
                            $request->file("bottom_banner.$index.image"),
                            'countries/bottom_banner'
                        );
                    }
                    if (empty($item['title']) && empty($item['subtitle']) && empty($imageUrl)) {
                        continue;
                    }
                    $details[] = [
                        'title' => $item['title'],
                        'subtitle' => $item['subtitle'],
                        'image' => $imageUrl ? url($imageUrl) : $fullUrl,
                    ];
                }

                $country->bottom_banner = !empty($details) ? json_encode($details) : null;
            }

            $details = [];
            if (isset($request->unique_experience)) {
                foreach ($request->unique_experience as $index => $item) {
                    $imageUrl = null;

                    $uniqueExperience = json_decode($country->unique_experience, true) ?? [];
                    $fullUrl = $uniqueExperience[$index]['image'] ?? null;

                    $baseUrl = env('APP_URL') . '/';
                    $prev_picture = str_replace($baseUrl, '', $fullUrl);

                    if ($request->hasFile("unique_experience.$index.image")) {
                        $imageUrl = ImageHelper::customSaveImage(
                            $request->file("unique_experience.$index.image"),
                            'countrys/unique_experience'
                        );
                    }
                    if (empty($item['title']) && empty($item['subtitle']) && empty($imageUrl)) {
                        continue;
                    }
                    $details[] = [
                        'title' => $item['title'],
                        'subtitle' => $item['subtitle'],
                        'image' => $imageUrl ? url($imageUrl) : $fullUrl,
                    ];
                }

                $country->unique_experience = !empty($details) ? json_encode($details) : null;
            }

            $details = [];
            if (isset($request->faqs)) {
                foreach ($request->faqs as $index => $item) {
                    $uniqueExperience = json_decode($country->faqs, true) ?? [];
                    if (empty($item['question']) && empty($item['answer'])) {
                        continue;
                    }
                    $details[] = [
                        'question' => $item['question'],
                        'answer' => $item['answer'], 
                    ];
                }
                $country->faqs = !empty($details) ? json_encode($details) : null;
            }

            $country->name = $request->name;
            $country->subtitle = $request->subtitle;
            $country->unique_experience_title = $request->unique_experience_title;
            $country->unique_experience_desc = $request->unique_experience_desc;
            $country->middle_sec_title = $request->middle_sec_title;
            $country->middle_sec_subtitle = $request->middle_sec_subtitle;
            $country->region = $request->region;
            $country->status = $request->status;
            $country->save();
            session()->flash('success', 'Country guide Successfully Updated.');
            return redirect()->route('admin.country-guides');
        }
        $regions = Region::where('status', 1)->get();
        return Inertia::render('Admin/country_guide/CreateEdit', ['country' => $country, 'regions' => $regions]);
    }


    public function destroy($id)
    {
        $park = CountryGuide::where('id', $id)->first();
        $park->delete();
        session()->flash('success', 'Country Guide Successfully Deleted.');
        return redirect()->route('admin.country-guides');
    }

    public function changeStatus(Request $request)
    {
        $park = CountryGuide::find($request->id);
        $park->status = !$park->status;
        $park->save();
        session()->flash('success', 'Country Guide Successfully Updated.');
        return redirect()->route('admin.country-guides');
    }
}
