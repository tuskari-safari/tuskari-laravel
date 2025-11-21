<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CmsController extends Controller
{
    public function index(Request $request)
    {
        try {
            $pages = Cms::query();

            if ($request->title) {
                $pages = $pages->where('title', 'like', '%' . $request->title . '%');
            }

            if ($request->fieldName && $request->shortBy) {
                $pages->orderBy($request->fieldName, $request->shortBy);
            }

            $perPage = $request->perPage ?? 20;

            $pages = $pages->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
            return Inertia::render('Admin/cms/List', ['pages' => $pages]);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function edit($slug)
    {
        $cms = Cms::where('slug', $slug)->first();
        if (!$cms) {
            abort(404);
        }
        try {
            return Inertia::render('Admin/cms/CreateEdit', compact('cms', 'slug'));
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function update(Request $request, $slug)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'nullable',
        ]);

        try {
            $page = Cms::find($slug);
            $page->title = $request->title;
            $page->text_content = $request->content;
            $page->save();
            return redirect()->route('admin.cms.index')->with('success', $page->title . ' has been updated');
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }
}
