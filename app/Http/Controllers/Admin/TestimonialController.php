<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        try {
            $testimonials = Testimonial::query();

            if ($request->title) {
                $testimonials = $testimonials->where('title', 'LIKE', '%' . $request->title . '%');
            }

            if ($request->full_name) {
                $testimonials = $testimonials->where('full_name', 'LIKE', '%' . $request->full_name . '%');
            }

            if (isset($request->status)) {
                $testimonials = $testimonials->where('status', $request->status);
            }

            if ($request->fieldName && $request->shortBy) {
                $testimonials->orderBy($request->fieldName, $request->shortBy);
            }

            $perPage = $request->perPage ?? 20;

            $testimonials = $testimonials->orderBy('id', 'desc')->paginate($perPage)->withQueryString();

            return Inertia::render('Admin/Testimonial/List', ['testimonials' => $testimonials]);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }


    public function testimonialCreate(Request $request)
    {
        if (request()->isMethod('post')) {
            $validateData = request()->validate([
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'full_name' => 'nullable|string|max:255',
                'rating' => 'nullable|string|max:10',
                'user_image' => 'nullable|mimes:jpeg,png,jpg,gif|max:10240',
                'status' => 'required|boolean'
            ]);

            $testimonial = new Testimonial();
            $testimonial->title = $request->title;
            $testimonial->description = $request->description;
            $testimonial->full_name = $request->full_name;
            $testimonial->rating = $request->rating;
            $testimonial->status = $request->status;

            if ($request->hasFile('user_image')) {
                $file = $request->file('user_image');
                $path = 'testimonial_images';
                $final_image_url = ImageHelper::customSaveImage($file, $path);
                $testimonial->user_image = $final_image_url;
            }

            $testimonial->save();
            return redirect()->route('admin.testimonial.list')->with('success', 'Testimonial has been added successfully.');
        }
        return Inertia::render('Admin/Testimonial/CreateEdit');
    }

    public function testimonialEdit(Request $request, $id)
    {
        $testimonial = Testimonial::find($id);
        $prev_picture = $testimonial->user_image;
        
        if (request()->isMethod('post')) {
            $validateData = request()->validate([
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'full_name' => 'nullable|string|max:255',
                'rating' => 'nullable|string|max:10',
                'user_image' => 'nullable|mimes:jpeg,png,jpg,gif|max:10240',
                'status' => 'required|boolean'
            ]);
            
            $testimonial->title = $request->title;
            $testimonial->description = $request->description;
            $testimonial->full_name = $request->full_name;
            $testimonial->rating = $request->rating;
            $testimonial->status = $request->status;
            
            if ($request->hasFile('user_image')) {
                if (file_exists($prev_picture)) {
                    @unlink($prev_picture);
                }
                $file = $request->file('user_image');
                $path = 'testimonial_images';
                $final_image_url = ImageHelper::customSaveImage($file, $path);
                $testimonial->user_image = $final_image_url;
            }
            
            $testimonial->save();
            return redirect()->route('admin.testimonial.list')->with('success', 'Testimonial has been updated successfully.');
        }

        return Inertia::render('Admin/Testimonial/CreateEdit', compact('testimonial'));
    }

    public function testimonialStatusChange(Request $request)
    {

        try {

            $testimonial = Testimonial::find($request->id);
            $testimonial->status = !$testimonial->status;
            $testimonial->save();
            session()->flash('success', 'Testimonial status successfully changed');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function testimonialDelete($id)
    {
        try {
            $testimonial = Testimonial::find($id);
            $testimonial->delete();
            return back()->with('success', 'Testimonial successfully deleted');
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', $e->getMessage());
        }
    }

    public function viewTestimonial($testimonial_id){
        try {
            $testimonial = Testimonial::find($testimonial_id);
            return Inertia::render('Admin/Testimonial/Details', compact('testimonial'));
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }
}
