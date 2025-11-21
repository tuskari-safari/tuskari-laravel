<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class WebsiteRatingController extends Controller
{
    public function index(Request $request)
    {
        try {
            $websiteRatings = WebsiteRating::with('user');

            if ($request->user_name) {
                $websiteRatings = $websiteRatings->whereHas('user', function($query) use($request){
                    $query->WhereRaw(
                        "concat(first_name,' ', last_name) like '%" . trim(addslashes($request->user_name)) . "%' "
                    );
                });
            }

            if (isset($request->rating)) {
                $websiteRatings = $websiteRatings->where('rating', $request->rating);
            }

            if ($request->fieldName && $request->shortBy) {
                $websiteRatings->orderBy($request->fieldName, $request->shortBy);
            }

            $perPage = $request->perPage ?? 20;

            $websiteRatings = $websiteRatings->orderBy('id', 'desc')->paginate($perPage)->withQueryString();

            return Inertia::render('Admin/WebsiteRating/List', ['websiteRatings' => $websiteRatings]);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function websiteRatingDelete($id)
    {
        try {
            $websiteRating = WebsiteRating::find($id);
            $websiteRating->delete();
            return back()->with('success', 'Website rating successfully deleted');
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', $e->getMessage());
        }
    }

}