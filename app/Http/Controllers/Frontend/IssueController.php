<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\ReportIssue;
use App\Models\SomethingDidNotWork;
use App\Models\WebsiteRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IssueController extends Controller
{
    public function somethingDidNotWork(Request $request)
    {
        $request->validate([
            'issue' => 'required|max:1000',
            'pageUrl' => 'required |url',
            'deviceInfo' => 'required|max:255',
            'screenshot' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $somethingDidntWork = new SomethingDidNotWork();
            $somethingDidntWork->user_id = Auth::id();
            $somethingDidntWork->issue_description = $request->issue;
            $somethingDidntWork->page_url = $request->pageUrl;
            $somethingDidntWork->device_info = $request->deviceInfo;
            if ($request->hasFile('screenshot')) {
                $file = $request->file('screenshot');
                $path = 'somethingDidNotWork';
                $final_image_url = ImageHelper::customSaveImage($file, $path);
                $somethingDidntWork->screenshot = $final_image_url;
            }
            $somethingDidntWork->save();
            session()->flash('success', 'We received your report. We will look into it. Thank you for your patience.');
            return redirect()->route('frontend.help-and-support');
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }
    public function submitRatingForWebsite(Request $request, $ratingId = null)
    {
        $request->validate([
            'rating' => 'required|max:5',
            'comment' => 'required|max:1000',
        ]);

        try {

            $rating = $ratingId ? WebsiteRating::find($ratingId) : new WebsiteRating();
            $rating->user_id = Auth::id();
            $rating->rating = $request->rating;
            $rating->comment = $request->comment;
            $rating->save();
            session()->flash('success', 'Thank you for your rating.');
            return redirect()->route('frontend.help-and-support');
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function reportIssue(Request $request)
    {
        $request->validate([
            'description' => 'required|max:1000',
            'issueType' => 'required|max:1000',
        ]);

        try {
            $reportIssue = new ReportIssue();
            $reportIssue->user_id = Auth::id();
            $reportIssue->safari_id = $request->safari_id ?? null;
            $reportIssue->description = $request->description;
            $reportIssue->issue_type = $request->issueType;
            $reportIssue->save();
            session()->flash('success', 'We received your report. We will look into it. Thank you for your patience.');
            return redirect()->back();
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }
}
