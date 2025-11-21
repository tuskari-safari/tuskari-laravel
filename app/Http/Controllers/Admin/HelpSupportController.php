<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HelpAndSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class HelpSupportController extends Controller
{
    public function index(Request $request)
    {
        $support = HelpAndSupport::query();

        if ($request->question) {
            $support = $support->where('question', 'like', '%' . $request->question . '%');
        }

        if ($request->tag) {
            $support = $support->where('tag', 'like', '%' . $request->tag . '%');
        }

        if (isset($request->active)) {
            $support = $support->where('status', $request->active);
        }

        if ($request->fieldName && $request->shortBy) {
            $support->orderBy($request->fieldName, $request->shortBy);
        }

        $perPage = $request->perPage ?? 20;

        $supports = $support->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
        return Inertia::render('Admin/help_support/List', ['supports' => $supports]);
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            // dd($request->all());
            $credentials = $request->validate(
                [
                    'tag' => 'required',
                    'question' => 'required|max:500',
                    'answer' => 'required|max:1000',
                    'status' => 'required',
                ]
            );
            try {
                $support = new HelpAndSupport();
                $support->fill($credentials);
                $support->save();
                session()->flash('success', 'Help & Support created successfully');
                return redirect()->route('admin.help-support.index');
            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }
        return Inertia::render('Admin/help_support/CreateEdit');
    }

    public function edit(Request $request, $id){
        $support = HelpAndSupport::find($id);
        if ($request->isMethod('post')) {
             $credentials = $request->validate([
                'tag' => 'required',
                'question' => 'required|max:500',
                'answer' => 'required|max:1000',
                'status' => 'required',
            ]);
            try {
                $support->fill($credentials);
                $support->save();
                session()->flash('success', 'Help & Support updated successfully');
                return redirect()->route('admin.help-support.index');
            } catch (\Exception $e) {
                Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
                abort(500);
            }
        }
        return Inertia::render('Admin/help_support/CreateEdit', compact('support'));
    }

    public function destroy($id){
          try {
            HelpAndSupport::where('id', $id)->delete();
            session()->flash('success', 'Help & Support deleted successfully');
            return back();
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

       public function changeStatus(Request $request)
    {
        try {
            $support = HelpAndSupport::find($request->id);
            $support->status = !$support->status;
            $support->save();
            session()->flash('success', 'Help & Support status successfully changed');
            return back();
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }
}
