<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        try {
            $blogs = Blog::query();

            if ($request->title) {
                $blogs = $blogs->where('title', 'LIKE', '%' . $request->title . '%');
            }

            if (isset($request->date)) {
                $blogs = $blogs->whereDate('created_at', $request->date);
            }

            if (isset($request->status)) {
                $blogs = $blogs->where('status', $request->status);
            }

            if ($request->fieldName && $request->shortBy) {
                $blogs->orderBy($request->fieldName, $request->shortBy);
            }

            $perPage = $request->perPage ?? 20;

            $blogs = $blogs->orderBy('id', 'desc')->paginate($perPage)->withQueryString();

            return Inertia::render('Admin/blog/List', ['blogs' => $blogs]);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'title' => 'required|max:255',
                'category_id' => 'required',
                'description' => 'required',
                'photo' => 'required|mimes:jpeg,png,jpg,gif|max:10240',
                'status' => 'required',
                'posted_by' => 'required|max:255',
                'tags' => 'required|max:500',
            ]);
            try {

                $blog = new Blog;
                if ($request->hasFile('photo')) {
                    $file = $request->file('photo');
                    $path = 'blog_thumbnail';
                    $final_image_url = ImageHelper::customSaveImage($file, $path);
                    $credentials['thumbnail'] = $final_image_url;
                }
                $blog->fill($credentials)->save();

                session()->flash('success', 'Blog Successfully Created.');
                return redirect()->route('admin.blog.index');
            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }
        $categories = Category::where('status', 1)->get();
        return Inertia::render('Admin/blog/CreateEdit', compact('categories'));
    }

    public function edit(Request $request, Blog $blog)
    {
        $prev_picture = $blog->thumbnail;
        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'title' => 'required|max:255',
                'description' => 'required',
                'category_id' => 'required',
                'photo' => $request->hasFile('photo') ? 'mimes:jpeg,png,jpg,gif|max:10240' : '',
                'status' => 'required',
                'posted_by' => 'required|max:255',
                'tags' => 'required|max:500',
            ]);
            try {
                if ($request->hasFile('photo') && $request->file('photo')->getClientOriginalName() !== 'blob') {
                    if (file_exists($prev_picture)) {
                        @unlink($prev_picture);
                    }

                    $file = $request->file('photo');
                    $path = 'blog_thumbnail';
                    $final_image_url = ImageHelper::customSaveImage($file, $path);
                    $credentials['thumbnail'] = $final_image_url;
                }
                $blog->fill($credentials)->save();

                session()->flash('success', 'Blog Successfully Updated.');
                return redirect()->route('admin.blog.index');
            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }
        $categories = Category::where('status', 1)->get();
        return Inertia::render('Admin/blog/CreateEdit', ['blog' => $blog, 'categories' => $categories]);
    }

    public function destroy($id)
    {
        try {
            $blog = Blog::where('id', $id)->first();
            $prev_picture = $blog->thumbnail;
            if (file_exists($prev_picture)) {
                @unlink($prev_picture);
            }
            $blog->delete();

            session()->flash('success', 'Blog successfully deleted');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function changeStatus(Request $request)
    {
        try {
            $blog = Blog::find($request->id);
            $blog->status = !$blog->status;
            $blog->save();
            session()->flash('success', 'Blog status successfully changed');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }
}
