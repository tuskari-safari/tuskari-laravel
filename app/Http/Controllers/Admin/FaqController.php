<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $faq = Faq::query();

        if ($request->question) {
            $faq = $faq->where('question', 'like', '%' . $request->question . '%');
        }

        if ($request->answer) {
            $faq = $faq->where('answer', 'like', '%' . $request->answer . '%');
        }

        if (isset($request->active)) {
            $faq = $faq->where('active', $request->active);
        }

        if ($request->fieldName && $request->shortBy) {
            $faq->orderBy($request->fieldName, $request->shortBy);
        }

        $perPage = $request->perPage ?? 20;

        $faqs = $faq->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
        return Inertia::render('Admin/faq/List', ['faqs' => $faqs]);
    }

    public function create()
    {
        try {
            return Inertia::render('Admin/faq/CreateEdit');
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'question' => 'required|max:500',
                'answer' => 'required|max:500',
                'active' => 'required',
            ],
            [
                'active.required'    => 'The status field is required',
            ]
        );

        $faq = new Faq;
        $faq->fill($request->all());
        $faq->save();
        session()->flash('success', 'Faq created successfully');
        return redirect()->route('admin.faq.index');
    }

    public function edit($id)
    {
        try {
            $faq = Faq::find($id);
            return Inertia::render('Admin/faq/CreateEdit', compact('faq'));
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|max:500',
            'answer' => 'required|max:500',
            'active' => 'required',
        ],            [
            'active.required'    => 'The status field is required',
        ]);

        try {
            $faq = Faq::find($id);
            $faq->fill($request->all());
            $faq->save();
            session()->flash('success', 'Faq updated successfully');
            return redirect()->route('admin.faq.index');
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function destroy($id)
    {
        try {
            Faq::where('id', $id)->delete();
            session()->flash('success', 'Faq deleted successfully');
            return back();
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function changeFaqStatus(Request $request)
    {
        try {
            $faq = Faq::find($request->id);
            $faq->active = !$faq->active;
            $faq->save();
            session()->flash('success', 'Faq status successfully changed');
            return back();
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }
}
