<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $categories = Category::query();

            if ($request->title) {
                $categories = $categories->where('title', 'like', '%' . trim($request->title) . '%');
            }

            if (isset($request->date)) {
                $categories = $categories->whereDate('created_at', $request->date);
            }

            if (isset($request->status)) {
                $categories = $categories->where('status', $request->status);
            }

            if ($request->fieldName && $request->shortBy) {
                $categories->orderBy($request->fieldName, $request->shortBy);
            }


            $categories = $categories->orderBy('order', 'asc')->get();
            return Inertia::render('Admin/category/List', ['categories' => $categories]);
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return Inertia::render('Admin/category/CreateEdit');
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:100|unique:categories',
            'status' => 'required',
        ]);
        try {
            Category::create($validatedData);
            session()->flash('success', 'Category created successfully');
            return redirect()->route('admin.category.index');
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        try {
            return Inertia::render('Admin/category/CreateEdit', compact('category'));
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'title' => ['required', 'max:100', Rule::unique('categories', 'title')->ignore($category->id)],
            'status' => 'required'
        ]);

        try {
            $category->update($validatedData);
            session()->flash('success', 'Category updated successfully');
            return redirect()->route('admin.category.index');
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $blog = Blog::where('category_id', $category->id)->first();
            if ($blog) {
                session()->flash('error', 'Category cannot be deleted because it is associated with a blog post.');
                return back();
            }
            $category->delete();
            session()->flash('success', 'Category deleted successfully.');
            return back();
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function changeStatus(Request $request)
    {
        try {
            $blog = Blog::where('category_id', $request->id)->first();
            if ($blog) {
                session()->flash('error', 'Category cannot be changed because it is associated with a blog post.');
                return back();
            }
            $category = Category::find($request->id);
            $category->status = !$category->status;
            $category->save();
            session()->flash('success', 'Category status successfully changed');
            return back();
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }


    public function reOrder(Request $request)
    {
        $categories = $request->input('categories');
        try {
            foreach ($categories as $key => $value) {
                Category::where('id', $value['id'])->update(['order' => $key]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['error' => 'Server Error'], 500);
        }
    }
}
