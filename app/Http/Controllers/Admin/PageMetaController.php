<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PageMetaController extends Controller
{
    public function index(Request $request)
    {
        $pageMeta = PageMeta::query();

        if ($request->page_name) {
            $pageMeta = $pageMeta->where('page_name', 'like', '%' . $request->page_name . '%');
        }

        if ($request->meta_title) {
            $pageMeta = $pageMeta->where('meta_title', 'like', '%' . $request->meta_title . '%');
        }

        if (isset($request->index_follow)) {
            $pageMeta = $pageMeta->where('index_follow', $request->index_follow);
        }

        if ($request->fieldName && $request->shortBy) {
            $pageMeta->orderBy($request->fieldName, $request->shortBy);
        }

        $perPage = $request->perPage ?? 20;

        $pageMetas = $pageMeta->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
        return Inertia::render('Admin/page_meta/List', ['pageMetas' => $pageMetas]);
    }

    public function create()
    {
        try {
            return Inertia::render('Admin/page_meta/CreateEdit');
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'page_name' => 'required|unique:page_metas,page_name',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable|max:500',
            'og_title' => 'nullable|max:255',
            'og_type' => 'nullable|max:255',
            'og_image' => 'nullable|max:255',
            'og_image_width' => 'nullable|integer',
            'og_image_height' => 'nullable|integer',
            'og_url' => 'nullable|max:255|url',
            'og_description' => 'nullable|max:500',
            'canonical_url' => 'nullable|max:255',
            'index_follow' => 'required|boolean',
            'schema_details' => 'nullable'
        ]);

        $pageMeta = new PageMeta;
        $pageMeta->fill($request->all());
        $pageMeta->save();
        session()->flash('success', 'Page Meta created successfully');
        return redirect()->route('admin.page-meta.index');
    }

    public function edit($id)
    {
        try {
            $pageMeta = PageMeta::find($id);
            return Inertia::render('Admin/page_meta/CreateEdit', compact('pageMeta'));
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'page_name' => 'required|unique:page_metas,page_name,' . $id,
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable|max:500',
            'og_title' => 'nullable|max:255',
            'og_type' => 'nullable|max:255',
            'og_image' => 'nullable|max:255',
            'og_image_width' => 'nullable|integer',
            'og_image_height' => 'nullable|integer',
            'og_url' => 'nullable|max:255|url',
            'og_description' => 'nullable|max:500',
            'canonical_url' => 'nullable|max:255|url',
            'index_follow' => 'required|boolean',
            'schema_details' => 'nullable'
        ]);

        try {
            $pageMeta = PageMeta::find($id);
            $pageMeta->fill($request->all());
            $pageMeta->save();
            session()->flash('success', 'Page Meta updated successfully');
            return redirect()->route('admin.page-meta.index');
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function destroy($id)
    {
        try {
            PageMeta::where('id', $id)->delete();
            session()->flash('success', 'Page Meta deleted successfully');
            return back();
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }
}