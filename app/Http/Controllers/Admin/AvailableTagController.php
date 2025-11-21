<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AvailableTag;
use App\Models\Safari;
use App\Models\SafariAvailableTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class AvailableTagController extends Controller
{
       public function index(Request $request)
    {
        try {
            $tags = AvailableTag::query();

            if ($request->name) {
                $tags = $tags->where('name', 'like', '%' . trim($request->name) . '%');
            }

            if (isset($request->date)) {
                $tags = $tags->whereDate('created_at', $request->date);
            }

            if ($request->fieldName && $request->shortBy) {
                $tags->orderBy($request->fieldName, $request->shortBy);
            }
            $tags = $tags
                ->with('safaris:id,title')
                ->orderBy('id', 'desc')
                ->get();
            $safaris = Safari::pluck('title', 'id');
            return Inertia::render('Admin/available_tags/List', ['tags' => $tags, 'safaris' => $safaris]);
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'name' => 'required|max:255|unique:available_tags,name',
                'show_in_frontend' => 'boolean'
            ]);
            try {
                $collection = new AvailableTag();
                $collection->fill($credentials)->save();
                session()->flash('success', 'Available Tag Successfully Created.');
                return redirect()->route('admin.available-tags.index');
            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }
        return Inertia::render('Admin/available_tags/CreateEdit');
    }

    public function edit(Request $request, $id)
    {
        $tag = AvailableTag::findOrFail($id);
        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'name' => 'required|max:255|unique:available_tags,name,' . $tag->id,
                'show_in_frontend' => 'boolean'
            ]);
            try {
                $tag->fill($credentials)->save();
                session()->flash('success', 'Available Tag Successfully Updated.');
                return redirect()->route('admin.available-tags.index');
            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }
        return Inertia::render('Admin/available_tags/CreateEdit', ['tag' => $tag]);
    }

    public function destroy($id)
    {
        try {
            $tag = AvailableTag::where('id', $id)->first();
            $tag->delete();
            session()->flash('success', 'Available Tag successfully deleted');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function changeStatus(Request $request)
    {
        try {
            $tag = AvailableTag::find($request->id);
            $tag->show_in_frontend = !$tag->show_in_frontend;
            $tag->save();
            session()->flash('success', 'Available Tag status successfully changed');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function safariAvailabletags(Request $request)
    {
        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'tag_id' => 'required|exists:available_tags,id',
                'safari_ids' => 'required|array',
            ]);

            try {
                $tagId = $credentials['tag_id'];
                $newSafariIds = $credentials['safari_ids'];

                $existingSafariIds = SafariAvailableTag::where('tag_id', $tagId)
                    ->pluck('safari_id')
                    ->toArray();

                $toAdd = array_diff($newSafariIds, $existingSafariIds);
                
                foreach ($toAdd as $safariId) {
                    SafariAvailableTag::create([
                        'tag_id' => $tagId,
                        'safari_id' => $safariId,
                    ]);
                }

                $toRemove = array_diff($existingSafariIds, $newSafariIds);
                if (!empty($toRemove)) {
                    SafariAvailableTag::where('tag_id', $tagId)
                        ->whereIn('safari_id', $toRemove)
                        ->delete();
                }

                session()->flash('success', 'Safari available tag updated successfully.');
                return redirect()->route('admin.available-tags.index');
            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }
    }
}
