<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\KeyExperience;
use App\Models\SafariJourney;
use App\Models\SafariType;
use App\Models\SafariWildlifeSight;
use App\Models\WildLife;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class MasterController extends Controller
{
    public function index(Request $request)
    {
        try {
            $experience = KeyExperience::query();

            if ($request->title) {
                $experience = $experience->where('title', 'like', '%' . trim($request->title) . '%');
            }

            if (isset($request->date)) {
                $experience = $experience->whereDate('created_at', $request->date);
            }


            if ($request->fieldName && $request->shortBy) {
                $experience->orderBy($request->fieldName, $request->shortBy);
            }


            $experience = $experience->orderBy('id', 'desc')->get();
            return Inertia::render('Admin/key_experience/List', ['experience' => $experience]);
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'title' => 'required|max:255',
                'thumbnail' => 'required|mimes:jpeg,png,jpg,svg|max:10240',
                'subtitle' => 'nullable|max:500',
                'image' => 'nullable|mimes:jpeg,png,jpg,svg|max:10240',
            ]);
            try {

                $experience = new KeyExperience;
                if ($request->hasFile('thumbnail')) {
                    $file = $request->file('thumbnail');
                    $path = 'key_experience_thumbnail';
                    $final_image_url = ImageHelper::customSaveImage($file, $path);
                    $credentials['thumbnail'] = $final_image_url;
                }
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $path = 'key_experience_image';
                    $final_image_url = ImageHelper::customSaveImage($file, $path);
                    $credentials['image'] = $final_image_url;
                }
                $experience->fill($credentials)->save();

                session()->flash('success', 'Key Experience Successfully Created.');
                return redirect()->route('admin.key-experience.index');
            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }

        return Inertia::render('Admin/key_experience/CreateEdit');
    }

    public function edit(Request $request, $id)
    {
        $key_experience = KeyExperience::findOrFail($id);
        $prev_picture = $key_experience->thumbnail;
        $prev_picture2 = $key_experience->image;
        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'title' => 'required|max:255',
                'thumbnail' => $request->hasFile('photo') ? 'mimes:jpeg,png,jpg,gif,svg|max:10240' : '',
                'subtitle' => 'nullable|max:500',
                'image' => $request->hasFile('image') ? 'mimes:jpeg,png,jpg,svg|max:10240' : '',

            ]);
            try {
                if ($request->hasFile('thumbnail')) {
                    if (file_exists($prev_picture)) {
                        @unlink($prev_picture);
                    }
                    $thumbnail = ImageHelper::customSaveImage($request->file('thumbnail'), 'key_experience_thumbnail');
                }
                $credentials['thumbnail'] = $thumbnail ?? $key_experience->thumbnail;

                if ($request->hasFile('image')) {
                    if (file_exists($prev_picture2)) {
                        @unlink($prev_picture2);
                    }
                    $image = ImageHelper::customSaveImage($request->file('image'), 'key_experience_image');
                }
                $credentials['image'] = $image ?? $key_experience->image;

                $key_experience->fill($credentials)->save();

                session()->flash('success', 'Key Experience Successfully Updated.');
                return redirect()->route('admin.key-experience.index');
            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }

        return Inertia::render('Admin/key_experience/CreateEdit', ['key_experience' => $key_experience]);
    }

    public function destroy($id)
    {
        try {
            $experience = KeyExperience::where('id', $id)->first();
            $prev_picture = $experience->thumbnail;
            if (file_exists($prev_picture)) {
                @unlink($prev_picture);
            }
            $experience->delete();

            session()->flash('success', 'Key Experience successfully deleted');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function activityList(Request $request)
    {
        try {
            $activity = Activity::query();

            if ($request->title) {
                $activity = $activity->where('title', 'like', '%' . trim($request->title) . '%');
            }

            if (isset($request->date)) {
                $activity = $activity->whereDate('created_at', $request->date);
            }


            if ($request->fieldName && $request->shortBy) {
                $activity->orderBy($request->fieldName, $request->shortBy);
            }


            $activity = $activity->orderBy('id', 'desc')->get();
            return Inertia::render('Admin/activity/List', ['activity' => $activity]);
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function activityCreate(Request $request)
    {
        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'title' => 'required|max:255',
                'icon' => 'required|mimes:jpeg,png,jpg,svg|max:10240',

            ]);
            try {
                $activity = new Activity();
                if ($request->hasFile('icon')) {
                    $file = $request->file('icon');
                    $path = 'activity_icon';
                    $final_image_url = ImageHelper::customSaveImage($file, $path);
                    $credentials['icon'] = $final_image_url;
                }
                $activity->fill($credentials)->save();

                session()->flash('success', 'Activity Successfully Created.');
                return redirect()->route('admin.activity.index');
            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }

        return Inertia::render('Admin/activity/CreateEdit');
    }

    public function activityEdit(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);
        $prev_picture = $activity->icon;
        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'title' => 'required|max:255',
                'icon' => $request->hasFile('icon') ? 'mimes:jpeg,png,jpg,svg|max:10240' : '',
            ]);
            try {
                if ($request->hasFile('icon')) {
                    if (file_exists($prev_picture)) {
                        @unlink($prev_picture);
                    }
                    $icon = ImageHelper::customSaveImage($request->file('icon'), 'safari_icon');
                }
                $credentials['icon'] = $icon ?? $activity->icon;
                $activity->fill($credentials)->save();
                session()->flash('success', 'Activity Successfully Updated.');
                return redirect()->route('admin.activity.index');
            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }

        return Inertia::render('Admin/activity/CreateEdit', ['activity' => $activity]);
    }

    public function activityDestroy($id)
    {
        try {
            $activity = Activity::where('id', $id)->first();
            $activity->delete();

            session()->flash('success', 'Activity successfully deleted');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function types(Request $request)
    {
        try {
            $safariTypes = SafariType::query();

            if ($request->title) {
                $safariTypes = $safariTypes->where('title', 'like', '%' . trim($request->title) . '%');
            }

            if (isset($request->date)) {
                $safariTypes = $safariTypes->whereDate('created_at', $request->date);
            }


            if ($request->fieldName && $request->shortBy) {
                $safariTypes->orderBy($request->fieldName, $request->shortBy);
            }


            $safariTypes = $safariTypes->orderBy('id', 'desc')->get();
            return Inertia::render('Admin/Safari_type/List', ['safariTypes' => $safariTypes]);
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function typeCreate(Request $request)
    {
        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'title' => 'required|max:255',
                'thumbnail' => 'required|mimes:jpeg,png,jpg,svg|max:10240',
                'subtitle' => 'nullable|max:500',
                'button_text' => 'nullable|max:255',
            ]);
            try {

                $type = new SafariType();
                if ($request->hasFile('thumbnail')) {
                    $file = $request->file('thumbnail');
                    $path = 'safari_type_thumbnail';
                    $final_image_url = ImageHelper::customSaveImage($file, $path);
                    $credentials['thumbnail'] = $final_image_url;
                }

                $type->fill($credentials)->save();
                session()->flash('success', 'Safari Type Successfully Created.');
                return redirect()->route('admin.safari-type.index');
            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }

        return Inertia::render('Admin/Safari_type/CreateEdit');
    }

    public function typeEdit(Request $request, $id)
    {
        $type = SafariType::findOrFail($id);
        $prev_picture = $type->thumbnail;

        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'title' => 'required|max:255',
                'thumbnail' => $request->hasFile('photo') ? 'mimes:jpeg,png,jpg,gif,svg|max:10240' : '',
                'subtitle' => 'nullable|max:500',
                'button_text' => 'nullable|max:255',

            ]);
            try {
                if ($request->hasFile('thumbnail')) {
                    if (file_exists($prev_picture)) {
                        @unlink($prev_picture);
                    }
                    $thumbnail = ImageHelper::customSaveImage($request->file('thumbnail'), 'safari_type_thumbnail');
                }
                $credentials['thumbnail'] = $thumbnail ?? $type->thumbnail;
                $type->fill($credentials)->save();

                session()->flash('success', 'Safari Type Successfully Updated.');
                return redirect()->route('admin.safari-type.index');
            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }

        return Inertia::render('Admin/Safari_type/CreateEdit', ['type' => $type]);
    }

    public function typeDestroy($id)
    {
        try {
            $type = SafariType::where('id', $id)->first();
            $prev_picture = $type->thumbnail;
            if (file_exists($prev_picture)) {
                @unlink($prev_picture);
            }
            $type->delete();

            session()->flash('success', 'Safari Type successfully deleted');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function wildLifeList(Request $request)
    {
        try {
            $wildLives = WildLife::query();

            if (isset($request->name)) {
                $wildLives = $wildLives->where('name', 'like', '%' . $request->name . '%');
            }
            if (isset($request->date)) {
                $wildLives = $wildLives->whereDate('created_at', $request->date);
            }
            $perPage = $request->perPage ?? 20;

            $wildLives = $wildLives->orderBy('id', 'desc')->paginate($perPage)->withQueryString();

            return Inertia::render('Admin/wild_life/List', ['wildLives' => $wildLives]);
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function wildLifeCreate(Request $request)
    {
        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'name' => 'required|max:255',
                'thumbnail' => 'required|mimes:jpeg,png,jpg,svg|max:10240',
                'subtitle' => 'nullable|max:500',
            ]);
            try {
                $wildLife = new WildLife();
                if ($request->hasFile('thumbnail')) {
                    $file = $request->file('thumbnail');
                    $path = 'wild_life_thumbnail';
                    $final_image_url = ImageHelper::customSaveImage($file, $path);
                    $credentials['thumbnail'] = $final_image_url;
                }
                $wildLife->fill($credentials)->save();
                session()->flash('success', 'Wild Life Successfully Created.');
                return redirect()->route('admin.wild-lives');
            } catch (\Throwable $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }
        return Inertia::render('Admin/wild_life/CreateEdit');
    }

    public function wildLifeEdit(Request $request, $id)
    { {
            $wildlife = WildLife::findOrFail($id);

            $prev_picture = $wildlife->thumbnail;

            if ($request->isMethod('post')) {
                $credentials = $request->validate([
                    'name' => 'required|max:255',
                    'thumbnail' => $request->hasFile('thumbnail') ? 'mimes:jpeg,png,jpg,gif,svg|max:10240' : '',
                    'subtitle' => 'nullable|max:500',
                ]);
                try {
                    if ($request->hasFile('thumbnail')) {
                        if (file_exists($prev_picture)) {
                            @unlink($prev_picture);
                        }
                        $thumbnail = ImageHelper::customSaveImage($request->file('thumbnail'), 'wild_life_thumbnail');
                    }
                    $credentials['thumbnail'] = $thumbnail ?? $wildlife->thumbnail;
                    $wildlife->fill($credentials)->save();

                    session()->flash('success', 'Wild Life Successfully Updated.');
                    return redirect()->route('admin.wild-lives');
                } catch (\Exception $e) {
                    Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                    abort(500);
                }
            }

            return Inertia::render('Admin/wild_life/CreateEdit', ['wildlife' => $wildlife]);
        }
    }

    public function wildLifeDestroy($id)
    {
        try {
            $safarisWildlife = SafariJourney::whereJsonContains('wildlife_highlights', ['animal_id' => (string)$id])->get();
            if ($safarisWildlife->count() > 0) {
                return redirect()->back()->with('warning', 'This wild life can not be deleted as it is associated with some safaris.');
            }
            $wildlife = WildLife::where('id', $id)->first();
            $prev_picture = $wildlife->thumbnail;
            if (file_exists($prev_picture)) {
                @unlink($prev_picture);
            }
            $wildlife->delete();
            session()->flash('success', 'Wild Life successfully deleted');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }
}
