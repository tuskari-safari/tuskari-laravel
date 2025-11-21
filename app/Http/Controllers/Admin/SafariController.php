<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Safari;
use App\Models\SafariBooking;
use App\Models\SafariImage;
use App\Models\SafariJourney;
use App\Models\SafariReview;
use App\Models\SafariThingsToKnow;
use App\Models\SafariWildlifeSight;
use App\Models\WildLife;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class SafariController extends Controller
{
    public function index(Request $request)
    {
        try {
            $safaris = Safari::query();
            $safaris = $safaris->with(['user', 'key_experiences', 'accomodation', 'safariOperator'])
                ->addSelect(['total_price' => DB::table('seasonal_pricings')
                ->select('adult_price')
                ->whereColumn('seasonal_pricings.safari_id', 'safaris.id')
                ->where('seasonal_pricings.season', 'LOW')
                ->orderBy('seasonal_pricings.id', 'asc') // or date if you want first chronologically
                ->limit(1)]);


            if ($request->provider) {
                $safaris = $safaris->whereHas('user', function ($query) use ($request) {
                    $query->WhereRaw(
                        "concat(first_name,' ', last_name) like '%" . trim(addslashes($request->provider)) . "%' "
                    );
                });
            }

            if ($request->title) {
                $safaris = $safaris->where('title', 'LIKE', '%' . $request->title . '%');
            }

            if (isset($request->location)) {
                $safaris = $safaris->where('location', 'LIKE', '%' . $request->location . '%');
            }
            if (isset($request->price)) {
                $safaris = $safaris->whereRaw('(high_season_adult_price + high_season_child_price) LIKE ?', ['%' . $request->price . '%']);
            }

            // if (isset($request->duration)) {
            //     $safaris = $safaris->where('duration', 'LIKE', '%' . $request->duration . '%');
            // }

            if (isset($request->status)) {
                $safaris = $safaris->where('status', $request->status);
            }
            if (isset($request->approval)) {
                $safaris = $safaris->where('is_approved', $request->approval);
            }

            if (isset($request->featured)) {
                $safaris = $safaris->where('is_featured', $request->featured);
            }

            if ($request->fieldName && $request->shortBy) {
                $safaris->orderBy($request->fieldName, $request->shortBy);
            }

            $perPage = $request->perPage ?? 20;

            $safaris = $safaris->with('user')->orderBy('id', 'desc')->paginate($perPage)->withQueryString();

            return Inertia::render('Admin/safari/List', ['safaris' => $safaris]);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function safariApproval(Request $request)
    {
        $safari = Safari::find($request->id);
        $request->validate([
            'status' => 'required',
        ]);
        if ($safari->is_draft) {
            return redirect()->route('admin.safari.index')->with('error', 'Safari is in draft mode, please publish the safari first.');
        }
        try {
            $safari->is_approved = $request->status;
            $safari->save();
            session()->flash('success', 'Safari approval status successfully changed');
            return redirect()->route('admin.safari.index');
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function viewSafari($slug)
    {
        try {
            $safari = Safari::with(['user', 'gallery', 'accomodation', 'safariOperator', 'journeys.journey_images', 'wild_lives.animal', 'things_to_know', 'country.region', 'create_safari_type.type', 'activity', 'dateRange'])->where('slug', $slug)->first();
            $safariImages = SafariImage::where('safari_id', $safari->id)->get();

            $animalSighting = SafariWildlifeSight::where('safari_id', $safari->id)->first();
            $safariReviews = SafariReview::where('safari_id', $safari->id)
                ->with(['user'])
                ->latest()
                ->paginate(12);

            foreach ($safari->journeys as $journey) {
                if (!empty($journey->wildlife_highlights)) {
                    $highlights = collect(json_decode($journey->wildlife_highlights, true))
                        ->map(function ($highlight) {
                            $animal = WildLife::find($highlight['animal_id']);
                            return [
                                'animal_id'   => $highlight['animal_id'],
                                'animal_name' => $animal ? $animal->name : null,
                                'description' => $highlight['description'] ?? null,
                                'image'       => $animal ? $animal->full_thumbnail_url : null
                            ];
                        });
                    $journey->wildlife_highlights = $highlights;
                }
            }
            return Inertia::render('Admin/safari/Details', ['safari' => $safari, 'safariImages' => $safariImages, 'animalSighting' => $animalSighting, 'safariReviews' => $safariReviews]);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }


    public function changeSafariStatus(Request $request)
    {
        try {
            $Safari = Safari::find($request->id);
            $Safari->status = !$Safari->status;
            $Safari->save();
            session()->flash('success', 'Safari status successfully changed');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }


    public function changeFeaturedStatus(Request $request)
    {
        try {
            $safari = Safari::find($request->id);
            $safari->is_featured = !$safari->is_featured;
            $safari->save();
            session()->flash('success', 'Safari featured status successfully changed');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function createSafariReview(Request $request)
    {
        $credentials = $request->validate([
            'safari_id' => 'required |exists:safaris,id',
            'rating' => 'required |max:5',
            'details' => 'required',
            'username' => 'required |max:255',
            'heading' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240'
        ]);

        try {
            $safari = Safari::find($request->safari_id);
            if (!$safari) {
                session()->flash('error', 'Safari not found');
                return back();
            }
            $safariReview = new SafariReview();
            $safariReview->safari_id = $request->safari_id;
            $safariReview->rating = $request->rating;
            $safariReview->details = $request->details;
            $safariReview->username = $request->username;
            $safariReview->heading = $request->heading;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = 'reviews_username';
                $final_image_url = ImageHelper::customSaveImage($file, $path);
                $safariReview->user_image = $final_image_url;
            }
            $safariReview->save();
            session()->flash('success', 'Safari review successfully created');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }
}
