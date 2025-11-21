<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OperatorReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class OperatorReviewController extends Controller
{
    public function index(Request $request)
    {
        try {
            $reviews = OperatorReview::with('operator');

            if ($request->operator_id) {
                $reviews = $reviews->where('operator_id', $request->operator_id);
            }

            if ($request->source) {
                $reviews = $reviews->where('source', 'LIKE', '%' . $request->source . '%');
            }

            if (isset($request->status)) {
                $reviews = $reviews->where('status', $request->status);
            }

            if ($request->fieldName && $request->shortBy) {
                $reviews->orderBy($request->fieldName, $request->shortBy);
            }

            $perPage = $request->perPage ?? 20;
            $reviews = $reviews->orderBy('id', 'desc')->paginate($perPage)->withQueryString();

            $operators = User::role('SAFARI_OPERATOR')->get(['id', 'business_name', 'first_name', 'last_name']);

            return Inertia::render('Admin/OperatorReview/List', ['reviews' => $reviews, 'operators' => $operators]);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function create(Request $request)
    {
        if (request()->isMethod('post')) {
            $validateData = request()->validate([
                'operator_id' => 'required|exists:users,id',
                'review_text' => 'required|string',
                'source' => 'required|string|max:255',
                'status' => 'required|boolean',
                'rating' => 'required|integer|min:1|max:5'
            ]);

            $review = new OperatorReview();
            $review->operator_id = $request->operator_id;
            $review->review_text = $request->review_text;
            $review->source = $request->source;
            $review->status = $request->status;
            $review->rating = $request->rating;
            $review->save();

            return redirect()->route('admin.operator-review.list')->with('success', 'Review has been added successfully.');
        }
        
        $operators = User::role('SAFARI_OPERATOR')->where('active', 1)->latest()->get(['id', 'business_name','first_name','last_name']);
        return Inertia::render('Admin/OperatorReview/CreateEdit', ['operators' => $operators]);
    }

    public function edit(Request $request, $id)
    {
        $review = OperatorReview::find($id);
        
        if (request()->isMethod('post')) {
            $validateData = request()->validate([
                'operator_id' => 'required|exists:users,id',
                'review_text' => 'required|string',
                'source' => 'required|string|max:255',
                'status' => 'required|boolean',
                'rating' => 'required|integer|min:1|max:5'
            ]);
            
            $review->operator_id = $request->operator_id;
            $review->review_text = $request->review_text;
            $review->source = $request->source;
            $review->status = $request->status;
            $review->rating = $request->rating;
            $review->save();
            
            return redirect()->route('admin.operator-review.list')->with('success', 'Review has been updated successfully.');
        }

        $operators = User::role('SAFARI_OPERATOR')->where('active', 1)->latest()->get(['id', 'business_name', 'first_name', 'last_name']);
        return Inertia::render('Admin/OperatorReview/CreateEdit', compact('review', 'operators'));
    }

    public function changeStatus(Request $request)
    {
        try {
            $review = OperatorReview::find($request->id);
            $review->status = !$review->status;
            $review->save();
            session()->flash('success', 'Review status successfully changed');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function delete($id)
    {
        try {
            $review = OperatorReview::find($id);
            $review->delete();
            return back()->with('success', 'Review successfully deleted');
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', $e->getMessage());
        }
    }
}