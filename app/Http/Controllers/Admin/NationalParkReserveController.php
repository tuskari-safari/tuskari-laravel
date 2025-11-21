<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\AccomodationToStay;
use App\Models\CountryGuide;
use App\Models\NationalParkAndReserves;
use App\Models\Safari;
use App\Models\WildLife;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class NationalParkReserveController extends Controller
{
    public function index(Request $request)
    {
        try {
            $parksReserves = NationalParkAndReserves::query();

            if ($request->name) {
                $parksReserves = $parksReserves->where('name', 'LIKE', '%' . $request->name . '%');
            }

            if ($request->type) {
                $parksReserves = $parksReserves->where('type', $request->type);
            }

            // if ($request->title) {
            //     $parksReserves = $parksReserves->where('title', 'LIKE', '%' . $request->title . '%');
            // }

            if (isset($request->status)) {
                $parksReserves = $parksReserves->where('status', $request->status);
            }

            if (isset($request->is_hidden)) {
                $parksReserves = $parksReserves->where('is_hidden', $request->is_hidden);
            }

            if ($request->fieldName && $request->shortBy) {
                $parksReserves->orderBy($request->fieldName, $request->shortBy);
            }

            $perPage = $request->perPage ?? 20;

            $parksReserves = $parksReserves->orderBy('id', 'desc')->paginate($perPage)->withQueryString();

            return Inertia::render('Admin/national_parks/List', ['parksReserves' => $parksReserves]);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function createPark(Request $request)
    {
        if ($request->isMethod('post')) {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'title_2' => 'nullable|string|max:255',
                'name' => 'required|string|max:255',
                'subtitle' => 'required|string|max:1000',
                'subtitle_2' => 'nullable|string|max:1000',
                'type' => 'required|string|in:national_park,private_reserve',
                'description' => 'required|string',
                'location' => 'required|string|max:255',
                'latitude' => 'nullable',
                'longitude' => 'nullable',
                'short_description' => 'required|string',
                'status' => 'required',
                'impact' => 'required|string',
                // 'accomodation_ids' => 'required',
                'is_most_popular' => 'nullable',
                'is_hidden_gem' => 'nullable',
                'wildlife_highlights_title' => 'nullable|max:255',
                'wildlife_highlights_desc' => 'nullable|max:1000',

                // 'wild_lives_ids' => 'required',
                'country_id' => 'required|exists:country_guides,id',

                // Image uploads
                'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
                'impact_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
                // 'middle_banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',

                // Arrays
                'category' => 'array',
                'category.*.title' => 'nullable|string|max:255',
                'category.*.subtitle' => 'nullable|string|max:1000',
                'category.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',

                'best_visit_time' => 'array',
                'best_visit_time.*.title' => 'nullable|string|max:255',
                'best_visit_time.*.subtitle' => 'nullable|string|max:1000',
                'best_visit_time.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',

                // 'traveler_tips' => 'array',
                // 'traveler_tips.*.title' => 'nullable|string|max:255',
                // 'traveler_tips.*.subtitle' => 'nullable|string|max:255',
                // 'traveler_tips.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',

                'unique_experience' => 'array',
                'unique_experience.*.title' => 'nullable|string|max:255',
                'unique_experience.*.subtitle' => 'nullable|string|max:1000',
                'unique_experience.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',

                'faqs' => 'array',
                'faqs.*.question' => 'nullable|max:500',
                'faqs.*.answer' => 'nullable|max:5000',
            ], [
                // Custom Messages for Category
                'category.*.title.max' => 'The title for category must not exceed 255 characters.',
                'category.*.subtitle.max' => 'The subtitle for category must not exceed 1000 characters.',
                'category.*.image.image' => 'The image for category must be a valid image format.',
                'category.*.image.mimes' => 'The image for category must be a file of type: jpeg, png, jpg, gif.',
                'category.*.image.max' => 'The image for category may not be greater than 2MB.',

                // Custom Messages for Best Visit Time
                'best_visit_time.*.title.max' => 'The title for Best Visit Time must not exceed 255 characters.',
                'best_visit_time.*.subtitle.max' => 'The subtitle for Best Visit Time must not exceed 1000 characters.',
                'best_visit_time.*.image.image' => 'The image for Best Visit Time must be a valid image format.',
                'best_visit_time.*.image.mimes' => 'The image for Best Visit Time must be a file of type: jpeg, png, jpg, gif.',
                'best_visit_time.*.image.max' => 'The image for Best Visit Time may not be greater than 2MB.',

                // Custom Messages for Traveler Tips
                // 'traveler_tips.*.title.max' => 'The title for Traveler Tips must not exceed 255 characters.',
                // 'traveler_tips.*.subtitle.max' => 'The subtitle for Traveler Tips must not exceed 255 characters.',
                // 'traveler_tips.*.image.image' => 'The image for Traveler Tips must be a valid image format.',
                // 'traveler_tips.*.image.mimes' => 'The image for Traveler Tips must be a file of type: jpeg, png, jpg, gif.',
                // 'traveler_tips.*.image.max' => 'The image for Traveler Tips may not be greater than 2MB.',

                // Custom Messages for Unique Experience
                'unique_experience.*.title.max' => 'The title for Unique Experience must not exceed 255 characters.',
                'unique_experience.*.subtitle.max' => 'The subtitle for Unique Experience must not exceed 1000 characters.',
                'unique_experience.*.image.image' => 'The image for Unique Experience must be a valid image format.',
                'unique_experience.*.image.mimes' => 'The image for Unique Experience must be a file of type: jpeg, png, jpg, gif.',
                'unique_experience.*.image.max' => 'The image for Unique Experience may not be greater than 2MB.',
            ]);
            $park = new NationalParkAndReserves($validatedData);

            if ($request->hasFile('impact_photo')) {

                $file = $request->file('impact_photo');
                $path = 'impact_images';
                $final_image_url = ImageHelper::customSaveImage($file, $path);
                $park->impact_image = $final_image_url;
            }
            if ($request->hasFile('banner')) {
                $file = $request->file('banner');
                $path = 'park_banner_images';
                $final_image_url = ImageHelper::customSaveImage($file, $path);
                $park->banner_image = $final_image_url;
            }
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $path = 'park_thumbnail_images';
                $final_image_url = ImageHelper::customSaveImage($file, $path);
                $park->thumbnail = $final_image_url;
            }
            // if ($request->hasFile('middle_banner_image')) {
            //     $file = $request->file('middle_banner_image');
            //     $path = 'park_middle_banner_image';
            //     $final_image_url = ImageHelper::customSaveImage($file, $path);
            //     $park->middle_banner_image = $final_image_url;
            // }

            $details = [];

            // Handle Category
            if (isset($request->category)) {
                foreach ($request->category as $index => $item) {
                    $imageUrl = null;

                    if ($request->hasFile("category.$index.image")) {
                        $imageUrl = ImageHelper::customSaveImage(
                            $request->file("category.$index.image"),
                            'parks/category'
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

                $park->category = !empty($details) ? json_encode($details) : null;
            }

            $details = [];

            // Handle Best Visit Time
            if (isset($request->best_visit_time)) {
                foreach ($request->best_visit_time as $index => $item) {
                    $imageUrl = null;

                    if ($request->hasFile("best_visit_time.$index.image")) {
                        $imageUrl = ImageHelper::customSaveImage(
                            $request->file("best_visit_time.$index.image"),
                            'parks/best_visit_time'
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

                $park->best_visit_time = !empty($details) ? json_encode($details) : null;
            }

            // $details = [];

            // // Handle Traveler Tips
            // if (isset($request->traveler_tips)) {
            //     foreach ($request->traveler_tips as $index => $item) {
            //         $imageUrl = null;

            //         if ($request->hasFile("traveler_tips.$index.image")) {
            //             $imageUrl = ImageHelper::customSaveImage(
            //                 $request->file("traveler_tips.$index.image"),
            //                 'parks/traveler_tips'
            //             );
            //         }
            //         if (empty($item['title']) && empty($item['subtitle']) && empty($imageUrl)) {
            //             continue;
            //         } 
            //         $details[] = [
            //             'title' => $item['title'],
            //             'subtitle' => $item['subtitle'],
            //             'image' => $imageUrl ? url($imageUrl) : null,
            //         ];
            //     }

            //     $park->traveler_tips = !empty($details) ? json_encode($details) : null;
            // }

            $details = [];
            if (isset($request->unique_experience)) {
                foreach ($request->unique_experience as $index => $item) {
                    $imageUrl = null;

                    if ($request->hasFile("unique_experience.$index.image")) {
                        $imageUrl = ImageHelper::customSaveImage(
                            $request->file("unique_experience.$index.image"),
                            'parks/unique_experience'
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

                $park->unique_experience = !empty($details) ? json_encode($details) : null;
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
                $park->faqs = !empty($details) ? json_encode($details) : null;
            }

            $park->accomodation_ids = json_encode($request->accomodation_ids);

            $park->wild_lives_ids = json_encode($request->wild_lives_ids);
            $park->lat = $request->latitude;
            $park->long = $request->longitude;
            $park->location = $request->location;
            $park->save();

            session()->flash('success', 'Park Successfully Created.');
            return redirect()->route('admin.national-park-reserves.index');
        }
        $accomodations = AccomodationToStay::where('status', 1)->get(['id', 'title']);
        $accomodations->each(function ($item) {
            $item->value = $item->id;
            $item->label = $item->title;
            return $item;
        })->pluck('value', 'label');

        $wildlife = WildLife::all(['id', 'name']);
        $wildlife->each(function ($item) {
            $item->value = $item->id;
            $item->label = $item->name;
            return $item;
        })->pluck('value', 'label');

        $safari = Safari::where('status', 1)->where('is_approved', 1)->get(['id', 'title']);
        $safari->each(function ($item) {
            $item->value = $item->id;
            $item->label = $item->title;
            return $item;
        })->pluck('value', 'label');


        $countries = CountryGuide::where('status', 1)->get(['id', 'name']);

        return Inertia::render('Admin/national_parks/CreateEdit', ['accomodations' => $accomodations, 'wildlife' => $wildlife, 'safari' => $safari, 'countries' => $countries]);
    }

    public function editPark(Request $request, $id)
    {
        $park = NationalParkAndReserves::find($id);
        $prev_picture1 = $park->banner_image;
        $prev_picture2 = $park->thumbnail;
        $prev_picture3 = $park->impact_image;
        // $prev_picture4 = $park->middle_banner_image;
        if ($request->isMethod('post')) {

            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'title_2' => 'nullable|string|max:255',
                'name' => 'required|string|max:255',
                'subtitle' => 'required|string|max:1000',
                'subtitle_2' => 'nullable|string|max:1000',
                'type' => 'required|string|in:national_park,private_reserve',
                'description' => 'required|string',
                'location' => 'required|string|max:255',
                'latitude' => 'nullable',
                'longitude' => 'nullable',
                // 'location' => 'required|string|max:255',
                'short_description' => 'required|string',
                'status' => 'required',
                'impact' => 'required|string',
                // 'accomodation_ids' => 'required',
                // 'wild_lives_ids' => 'required',
                'is_most_popular' => 'nullable',
                'is_hidden_gem' => 'nullable',
                'country_id' => 'required|exists:country_guides,id',
                'wildlife_highlights_title' => 'nullable|max:255',
                'wildlife_highlights_desc' => 'nullable|max:1000',

                // Image uploads
                'banner' => $request->hasFile('banner') ? 'mimes:jpeg,png,jpg,gif|max:10240' : '',
                'photo' => $request->hasFile('photo') ? 'mimes:jpeg,png,jpg,gif|max:10240' : '',
                'impact_photo' => $request->hasFile('impact_photo') ? 'mimes:jpeg,png,jpg,gif|max:10240' : '',
                // 'middle_banner_image' => $request->hasFile('middle_banner_image') ? 'mimes:jpeg,png,jpg,gif|max:10240' : '',

                // Arrays
                'category' => 'array',
                'category.*.title' => 'nullable|string|max:255',
                'category.*.subtitle' => 'nullable|string|max:1000',
                'category.*.image' => function ($attribute, $value, $fail) use ($request) {
                    if ($value instanceof \Illuminate\Http\UploadedFile && !in_array($value->extension(), ['jpeg', 'png', 'jpg', 'gif', 'svg', 'avif', 'webp'])) {
                        $fail('Invalid image format.');
                    }
                },

                'best_visit_time' => 'array',
                'best_visit_time.*.title' => 'nullable|string|max:255',
                'best_visit_time.*.subtitle' => 'nullable|string|max:1000',
                'best_visit_time.*.image' =>  function ($attribute, $value, $fail) use ($request) {
                    if ($value instanceof \Illuminate\Http\UploadedFile && !in_array($value->extension(), ['jpeg', 'png', 'jpg', 'gif', 'svg', 'avif', 'webp'])) {
                        $fail('Invalid image format.');
                    }
                },

                // 'traveler_tips' => 'array',
                // 'traveler_tips.*.title' => 'nullable|string|max:255',
                // 'traveler_tips.*.subtitle' => 'nullable|string|max:255',
                // 'traveler_tips.*.image' =>  function ($attribute, $value, $fail) use ($request) {

                //     if ($value instanceof \Illuminate\Http\UploadedFile && !in_array($value->extension(), ['jpeg', 'png', 'jpg', 'gif', 'svg', 'avif', 'webp'])) {
                //         $fail('Invalid image format.');
                //     }
                // },

                'unique_experience' => 'array',
                'unique_experience.*.title' => 'nullable|string|max:255',
                'unique_experience.*.subtitle' => 'nullable|string|max:1000',
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
                'category.*.title.max' => 'The title for category must not exceed 255 characters.',
                'category.*.subtitle.max' => 'The subtitle for category must not exceed 1000 characters.',
                'category.*.image.image' => 'The image for category must be a valid image format.',
                'category.*.image.mimes' => 'The image for category must be a file of type: jpeg, png, jpg, gif.',
                'category.*.image.max' => 'The image for category may not be greater than 20MB.',

                // Custom Messages for Best Visit Time
                'best_visit_time.*.title.max' => 'The title for Best Visit Time must not exceed 255 characters.',
                'best_visit_time.*.subtitle.max' => 'The subtitle for Best Visit Time must not exceed 1000 characters.',
                'best_visit_time.*.image.image' => 'The image for Best Visit Time must be a valid image format.',
                'best_visit_time.*.image.mimes' => 'The image for Best Visit Time must be a file of type: jpeg, png, jpg, gif.',
                'best_visit_time.*.image.max' => 'The image for Best Visit Time may not be greater than 20MB.',

                // Custom Messages for Traveler Tips
                // 'traveler_tips.*.title.max' => 'The title for Traveler Tips must not exceed 255 characters.',
                // 'traveler_tips.*.subtitle.max' => 'The subtitle for Traveler Tips must not exceed 255 characters.',
                // 'traveler_tips.*.image.image' => 'The image for Traveler Tips must be a valid image format.',
                // 'traveler_tips.*.image.mimes' => 'The image for Traveler Tips must be a file of type: jpeg, png, jpg, gif.',
                // 'traveler_tips.*.image.max' => 'The image for Traveler Tips may not be greater than 20MB.',

                // Custom Messages for Unique Experience
                'unique_experience.*.title.max' => 'The title for Unique Experience must not exceed 255 characters.',
                'unique_experience.*.subtitle.max' => 'The subtitle for Unique Experience must not exceed 1000 characters.',
                'unique_experience.*.image.image' => 'The image for Unique Experience must be a valid image format.',
                'unique_experience.*.image.mimes' => 'The image for Unique Experience must be a file of type: jpeg, png, jpg, gif.',
                'unique_experience.*.image.max' => 'The image for Unique Experience may not be greater than 20MB.',
            ]);


            if ($request->hasFile('banner')) {

                if (file_exists($prev_picture1)) {
                    @unlink($prev_picture1);
                }
                $file = $request->file('banner');
                $path = 'park_banner_images';
                $final_image_url = ImageHelper::customSaveImage($file, $path);
                $park->banner_image = $final_image_url;
            }

            if ($request->hasFile('impact_photo')) {
                if (file_exists($prev_picture3)) {
                    @unlink($prev_picture3);
                }
                $file = $request->file('impact_photo');
                $path = 'impact_images';
                $final_image_url = ImageHelper::customSaveImage($file, $path);
                $park->impact_image = $final_image_url;
            }

            if ($request->hasFile('photo')) {
                if (file_exists($prev_picture2)) {
                    @unlink($prev_picture2);
                }
                $file = $request->file('photo');
                $path = 'park_thumbnail_images';
                $final_image_url = ImageHelper::customSaveImage($file, $path);
                $park->thumbnail = $final_image_url;
            }

            // if ($request->hasFile('middle_banner_image')) {
            //     if (file_exists($prev_picture4)) {
            //         @unlink($prev_picture4);
            //     }
            //     $file = $request->file('middle_banner_image');
            //     $path = 'park_middle_banner_image';
            //     $final_image_url = ImageHelper::customSaveImage($file, $path);
            //     $park->middle_banner_image = $final_image_url;
            // }

            $details = [];

            // Handle Category


            if (isset($request->category)) {
                foreach ($request->category as $index => $item) {
                    $imageUrl = null;
                    $categoryDetails = json_decode($park->category, true) ?? [];
                    $fullUrl = $categoryDetails[$index]['image'] ?? null;

                    $baseUrl = env('APP_URL') . '/';
                    $prev_picture = str_replace($baseUrl, '', $fullUrl);

                    if ($request->hasFile("category.$index.image")) {
                        if (file_exists($prev_picture)) {
                            @unlink($prev_picture);
                        }

                        $imageUrl = ImageHelper::customSaveImage(
                            $request->file("category.$index.image"),
                            'parks/category'
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


                $park->category = !empty($details) ? json_encode($details) : null;
            }
            $details = [];

            // Handle Best Visit Time
            if (isset($request->best_visit_time)) {
                foreach ($request->best_visit_time as $index => $item) {
                    $imageUrl = null;

                    $bestVisitTime = json_decode($park->best_visit_time, true) ?? [];
                    $fullUrl = $bestVisitTime[$index]['image'] ?? null;

                    $baseUrl = env('APP_URL') . '/';
                    $prev_picture = str_replace($baseUrl, '', $fullUrl);

                    if ($request->hasFile("best_visit_time.$index.image")) {
                        $imageUrl = ImageHelper::customSaveImage(
                            $request->file("best_visit_time.$index.image"),
                            'parks/best_visit_time'
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

                $park->best_visit_time = !empty($details) ? json_encode($details) : null;
            }

            // $details = [];

            // // Handle Traveler Tips
            // if (isset($request->traveler_tips)) {
            //     foreach ($request->traveler_tips as $index => $item) {
            //         $imageUrl = null;
            //         $travelerTips = json_decode($park->traveler_tips, true) ?? [];
            //         $fullUrl = $travelerTips[$index]['image'] ?? null;

            //         $baseUrl = env('APP_URL') . '/';
            //         $prev_picture = str_replace($baseUrl, '', $fullUrl);

            //         if ($request->hasFile("traveler_tips.$index.image")) {
            //             $imageUrl = ImageHelper::customSaveImage(
            //                 $request->file("traveler_tips.$index.image"),
            //                 'parks/traveler_tips'
            //             );
            //         }
            //         if (empty($item['title']) && empty($item['subtitle']) && empty($imageUrl)) {
            //             continue;
            //         } 
            //         $details[] = [
            //             'title' => $item['title'],
            //             'subtitle' => $item['subtitle'],
            //             'image' => $imageUrl ? url($imageUrl) : $fullUrl,
            //         ];
            //     }

            //     $park->traveler_tips = !empty($details) ? json_encode($details) : null;
            // }

            $details = [];


            if (isset($request->unique_experience)) {
                foreach ($request->unique_experience as $index => $item) {
                    $imageUrl = null;

                    $uniqueExperience = json_decode($park->unique_experience, true) ?? [];
                    $fullUrl = $uniqueExperience[$index]['image'] ?? null;

                    $baseUrl = env('APP_URL') . '/';
                    $prev_picture = str_replace($baseUrl, '', $fullUrl);

                    if ($request->hasFile("unique_experience.$index.image")) {
                        $imageUrl = ImageHelper::customSaveImage(
                            $request->file("unique_experience.$index.image"),
                            'parks/unique_experience'
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

                $park->unique_experience = !empty($details) ? json_encode($details) : null;
            }

            $details = [];
            if (isset($request->faqs)) {
                foreach ($request->faqs as $index => $item) {
                    $uniqueExperience = json_decode($park->faqs, true) ?? [];
                    if (empty($item['question']) && empty($item['answer'])) {
                        continue;
                    }
                    $details[] = [
                        'question' => $item['question'],
                        'answer' => $item['answer'],
                    ];
                }
                $park->faqs = !empty($details) ? json_encode($details) : null;
            }

            $park->name = $request->name;
            $park->title = $request->title;
            $park->title_2 = $request->title_2;
            $park->subtitle = $request->subtitle;
            $park->subtitle_2 = $request->subtitle_2;
            $park->type = $request->type;
            $park->description = $request->description;
            $park->location = $request->location;
            $park->lat = $request->latitude;
            $park->long = $request->longitude;
            $park->short_description = $request->short_description;
            $park->status = $request->status;
            $park->impact = $request->impact;
            $park->accomodation_ids = json_encode($request->accomodation_ids);
            $park->is_most_popular = $request->is_most_popular;
            $park->is_hidden_gem = $request->is_hidden_gem;
            $park->wild_lives_ids = json_encode($request->wild_lives_ids);
            $park->country_id = $request->country_id;
            $park->wildlife_highlights_title = $request->wildlife_highlights_title;
            $park->wildlife_highlights_desc = $request->wildlife_highlights_desc;
            $park->save();
            
            session()->flash('success', 'Park Successfully Updated.');
            return redirect()->route('admin.national-park-reserves.index');
        }
        $accomodations = AccomodationToStay::where('status', 1)->get(['id', 'title']);
        $accomodations->each(function ($item) {
            $item->value = $item->id;
            $item->label = $item->title;
            return $item;
        })->pluck('value', 'label');

        $wildlife = WildLife::all(['id', 'name']);
        $wildlife->each(function ($item) {
            $item->value = $item->id;
            $item->label = $item->name;
            return $item;
        })->pluck('value', 'label');

        $safari = Safari::where('status', 1)->where('is_approved', 1)->get(['id', 'title']);
        $safari->each(function ($item) {
            $item->value = $item->id;
            $item->label = $item->title;
            return $item;
        })->pluck('value', 'label');

        $selected_accomodations = json_decode($park->accomodation_ids);

        $selected_wildlife = json_decode($park->wild_lives_ids);
        $countries = CountryGuide::where('status', 1)->get(['id', 'name']);

        return Inertia::render('Admin/national_parks/CreateEdit', ['park' => $park, 'selected_accomodations' => $selected_accomodations, 'selected_wildlife' => $selected_wildlife, 'accomodations' => $accomodations, 'wildlife' => $wildlife, 'safari' => $safari, 'countries' => $countries]);
    }

    public function destroyPark($id)
    {
        $park = NationalParkAndReserves::where('id', $id)->first();
        $park->delete();
        session()->flash('success', 'Park Successfully Deleted.');
        return redirect()->route('admin.national-park-reserves.index');
    }

    public function changeParkStatus(Request $request)
    {
        $park = NationalParkAndReserves::find($request->id);
        $park->status = !$park->status;
        $park->save();
        session()->flash('success', 'Park Status Successfully Updated.');
        return redirect()->route('admin.national-park-reserves.index');
    }

    public function changeHiddenStatus(Request $request)
    {
        $park = NationalParkAndReserves::find($request->id);
        $park->is_hidden = !$park->is_hidden;
        $park->save();
        session()->flash('success', 'Park Hidden Status Successfully Updated.');
        return redirect()->route('admin.national-park-reserves.index');
    }

    public function detailsPark($id)
    {
        $nationalPark = NationalParkAndReserves::find($id);
        return Inertia::render('Admin/national_parks/Details', ['nationalPark' => $nationalPark]);
    }
}
