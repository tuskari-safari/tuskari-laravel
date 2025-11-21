<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Safari;
use App\Models\SafariCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CollectionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $collections = Collection::query();

            if ($request->name) {
                $collections = $collections->where('name', 'like', '%' . trim($request->name) . '%');
            }

            if (isset($request->date)) {
                $collections = $collections->whereDate('created_at', $request->date);
            }

            if ($request->fieldName && $request->shortBy) {
                $collections->orderBy($request->fieldName, $request->shortBy);
            }

            $collections = $collections
                ->with('safaris:id,title')
                ->orderBy('id', 'desc')
                ->get();
            $safaris = Safari::pluck('title', 'id');
            return Inertia::render('Admin/collection/List', ['collections' => $collections, 'safaris' => $safaris]);
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'name' => 'required|max:255|unique:collections,name',
                'show_in_frontend' => 'boolean'
            ]);
            try {
                $collection = new Collection();
                $collection->fill($credentials)->save();
                session()->flash('success', 'Collection Successfully Created.');
                return redirect()->route('admin.collection.index');
            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }
        return Inertia::render('Admin/collection/CreateEdit');
    }

    public function edit(Request $request, $id)
    {
        $collection = Collection::findOrFail($id);
        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'name' => 'required|max:255|unique:collections,name,' . $collection->id,
                'show_in_frontend' => 'boolean'
            ]);
            try {
                $collection->fill($credentials)->save();
                session()->flash('success', 'Collection Successfully Updated.');
                return redirect()->route('admin.collection.index');
            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }
        return Inertia::render('Admin/collection/CreateEdit', ['collection' => $collection]);
    }

    public function destroy($id)
    {
        try {
            $collection = Collection::where('id', $id)->first();
            $collection->delete();
            session()->flash('success', 'Collection successfully deleted');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function changeStatus(Request $request)
    {
        try {
            $collection = Collection::find($request->id);
            $collection->show_in_frontend = !$collection->show_in_frontend;
            $collection->save();
            session()->flash('success', 'Collection status successfully changed');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function safariCollection(Request $request)
    {
        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'collection_id' => 'required|exists:collections,id',
                'safari_ids' => 'required|array',
            ]);

            try {
                $collectionId = $credentials['collection_id'];
                $newSafariIds = $credentials['safari_ids'];

                $existingSafariIds = SafariCollection::where('collection_id', $collectionId)
                    ->pluck('safari_id')
                    ->toArray();

                $toAdd = array_diff($newSafariIds, $existingSafariIds);
                foreach ($toAdd as $safariId) {
                    SafariCollection::create([
                        'collection_id' => $collectionId,
                        'safari_id' => $safariId,
                    ]);
                }

                $toRemove = array_diff($existingSafariIds, $newSafariIds);
                if (!empty($toRemove)) {
                    SafariCollection::where('collection_id', $collectionId)
                        ->whereIn('safari_id', $toRemove)
                        ->delete();
                }

                session()->flash('success', 'Safari collection updated successfully.');
                return redirect()->route('admin.collection.index');
            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }
    }
}
