<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\AccomodationToStay;
use App\Models\AccomodationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class AccomodationController extends Controller
{
    public function index(Request $request)
    {
        try {
            $accomodations = AccomodationToStay::query();

            if ($request->title) {
                $accomodations = $accomodations->where('title', 'LIKE', '%' . $request->title . '%');
            }


            if (isset($request->status)) {
                $accomodations = $accomodations->where('status', $request->status);
            }

            if ($request->fieldName && $request->shortBy) {
                $accomodations->orderBy($request->fieldName, $request->shortBy);
            }

            $perPage = $request->perPage ?? 20;

            $accomodations = $accomodations->orderBy('id', 'desc')->paginate($perPage)->withQueryString();

            return Inertia::render('Admin/accomodation/List', ['accomodations' => $accomodations]);
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
                'types' => 'required',
                'description' => 'required|max:500',
                'photo' => 'required|mimes:jpeg,png,jpg,gif|max:10240',
                'status' => 'required',
            ]);
            try {

                $accomodation = new AccomodationToStay;
                $credentials['types'] = json_encode($credentials['types']);
                if ($request->hasFile('photo')) {
                    $file = $request->file('photo');
                    $path = 'accomodation_images';
                    $final_image_url = ImageHelper::customSaveImage($file, $path);
                    $credentials['image'] = $final_image_url;
                }
                $accomodation->fill($credentials)->save();

                session()->flash('success', 'Blog Successfully Created.');
                return redirect()->route('admin.accomodations.index');
            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }

        $types = AccomodationType::where('status', 1)->get(['id', 'name']);
        $types->each(function ($item) {
            $item->value = $item->id;
            $item->label = $item->name;
            return $item;
        })->pluck('value', 'label');
        return Inertia::render('Admin/accomodation/CreateEdit', compact('types'));
    }

    public function edit(Request $request)
    {
        $accomodation = AccomodationToStay::find($request->id);
        $prev_picture = $accomodation->thumbnail;
        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'title' => 'required|max:255',
                'types' => 'required',
                'description' => 'required|max:500',
                'photo' => $request->hasFile('photo') ? 'mimes:jpeg,png,jpg,gif|max:10240' : '',
                'status' => 'required',
            ]);
            try {
                if ($request->hasFile('photo') && $request->file('photo')->getClientOriginalName() !== 'blob') {
                    if (file_exists($prev_picture)) {
                        @unlink($prev_picture);
                    }

                    $file = $request->file('photo');
                    $path = 'accomodation_images';
                    $final_image_url = ImageHelper::customSaveImage($file, $path);
                    $credentials['image'] = $final_image_url;
                }
                $credentials['types'] = json_encode($credentials['types']);
                $accomodation->fill($credentials)->save();

                session()->flash('success', 'Accomodation Successfully Updated.');
                return redirect()->route('admin.accomodations.index');
            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }
        $selected_Type = json_decode($accomodation->types);
        $types = AccomodationType::where('status', 1)->get(['id', 'name']);
        $types->each(function ($item) {
            $item->value = $item->id;
            $item->label = $item->name;
            return $item;
        })->pluck('value', 'label');
        return Inertia::render('Admin/accomodation/CreateEdit', ['accomodation' => $accomodation, 'types' => $types, 'selected_Type' => $selected_Type]);
    }

    public function destroy($id)
    {
        try {
            $accomodation = AccomodationToStay::where('id', $id)->first();
            $prev_picture = $accomodation->image;
            if (file_exists($prev_picture)) {
                @unlink($prev_picture);
            }
            $accomodation->delete();

            session()->flash('success', 'Accomodation successfully deleted');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function changeStatus(Request $request)
    {
        try {
            $accomodation = AccomodationToStay::find($request->id);
            $accomodation->status = !$accomodation->status;
            $accomodation->save();
            session()->flash('success', 'Accomodation status successfully changed');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }
}
