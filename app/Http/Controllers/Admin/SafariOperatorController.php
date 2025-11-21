<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Safari;
use App\Models\SafariOperator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class SafariOperatorController extends Controller
{
    public function operatorList(Request $request)
    {
        try {
            $operators = SafariOperator::query();

            if ($request->name) {
                $operators = $operators->where('name', 'like', '%' . trim($request->name) . '%');
            }

            if ($request->location) {
                $operators = $operators->where('location', 'like', '%' . trim($request->location) . '%');
            }

            if (isset($request->status)) {
                $operators = $operators->where('status', $request->status);
            }

            if ($request->fieldName && $request->shortBy) {
                $operators->orderBy($request->fieldName, $request->shortBy);
            }


            $operators = $operators->orderBy('id', 'desc')->get();
            return Inertia::render('Admin/operators/List', ['operators' => $operators]);
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function createOperator(Request $request)
    {
        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'name' => 'required|max:255',
                'location' => 'required|max:255',
                'no_of_employees' => 'required|max:255',
                'details' => 'required|max:500',
                'type' => 'required|max:255',
                'photo' => 'required|mimes:jpeg,png,jpg,gif|max:10240',
                'status' => 'required',
            ]);
            try {

                $operator = new SafariOperator();
                if ($request->hasFile('photo')) {
                    $file = $request->file('photo');
                    $path = 'operator_logo';
                    $final_image_url = ImageHelper::customSaveImage($file, $path);
                    $credentials['logo'] = $final_image_url;
                }
                $operator->fill($credentials)->save();

                session()->flash('success', 'Safari Operator Successfully Created.');
                return redirect()->route('admin.operators');
            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }

        return Inertia::render('Admin/operators/CreateEdit');
    }

    public function editOperator(Request $request, $id)
    {
        $operator = SafariOperator::find($id);
        $prev_picture = $operator->logo;
        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'name' => 'required|max:255',
                'location' => 'required|max:255',
                'no_of_employees' => 'required|max:255',
                'details' => 'required|max:500',
                'type' => 'required|max:255',
                'photo' => $request->hasFile('photo') ? 'mimes:jpeg,png,jpg,gif|max:10240' : '',
                'status' => 'required',
            ]);
            try {
                if ($request->hasFile('photo') && $request->file('photo')->getClientOriginalName() !== 'blob') {
                   
                    if (file_exists($prev_picture)) {
                        @unlink($prev_picture);
                    }

                    $file = $request->file('photo');
                    $path = 'operator_logo';
                    $final_image_url = ImageHelper::customSaveImage($file, $path);
                    $credentials['logo'] = $final_image_url;
                }
                $operator->fill($credentials)->save();

                session()->flash('success', 'Safari Operator Successfully Updated.');
                return redirect()->route('admin.operators');
            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }

        return Inertia::render('Admin/operators/CreateEdit', ['operator' => $operator]);
    }

    public function deleteOperator($id)
    {
        try {
            $operator = SafariOperator::where('id', $id)->first();
            $prev_picture = $operator->logo;
            if (file_exists($prev_picture)) {
                @unlink($prev_picture);
            }
            $operator->delete();

            session()->flash('success', 'Safari Operator successfully deleted');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function changeOperatorStatus(Request $request)
    {
        try {
            $operator = SafariOperator::find($request->id);
            $operator->status = !$operator->status;
            $operator->save();
            session()->flash('success', 'Safari Operator status successfully changed');
            return back();
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }
}
