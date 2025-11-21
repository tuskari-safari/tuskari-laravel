<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SomethingDidNotWork;
use App\Models\ReportIssue;
use App\Mail\IssueResolvedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class IssueController extends Controller
{
    public function somethingDidNotWorkIndex(Request $request)
    {
        try {
            $issues = SomethingDidNotWork::with('user');
            if ($request->user_name) {
                $issues = $issues->whereHas('user', function ($query) use ($request) {
                    $query->WhereRaw(
                        "concat(first_name,' ', last_name) like '%" . trim(addslashes($request->user_name)) . "%' "
                    );
                });
            }

            if (isset($request->resolve)) {
                $issues = $issues->where('resolve', $request->resolve);
            }

            if ($request->fieldName && $request->shortBy) {
                $issues->orderBy($request->fieldName, $request->shortBy);
            }

            $perPage = $request->perPage ?? 20;

            $issues = $issues->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
            return Inertia::render('Admin/something_didnt_work/List', ['issues' => $issues]);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function somethingDidNotWorkView(SomethingDidNotWork $issue)
    {
        try {
            $issue->load('user');
            return Inertia::render('Admin/something_didnt_work/View', compact('issue'));
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function changeSomethingDidNotWorkStatus(Request $request)
    {
        try {
            $issue = SomethingDidNotWork::with('user')->find($request->id);
            $oldStatus = $issue->resolve;
            $issue->resolve = !$issue->resolve;
            $issue->save();

            // Send email when issue is resolved (changed from pending to resolved)
            if ($oldStatus == 0 && $issue->resolve == 1 && $issue->user && $issue->user->email) {
                Mail::to($issue->user->email)->send(new IssueResolvedMail($issue));
            }

            session()->flash('success', 'Issue status successfully changed');
            return back();
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function reportIssueIndex(Request $request)
    {
        try {
            $reports = ReportIssue::with(['user', 'safari']);

            if ($request->user_name) {
                $reports = $reports->whereHas('user', function ($query) use ($request) {
                    $query->WhereRaw(
                        "concat(first_name,' ', last_name) like '%" . trim(addslashes($request->user_name)) . "%' "
                    );
                });
            }

            if ($request->issue_type) {
                $reports = $reports->where('issue_type', 'like', '%' . $request->issue_type . '%');
            }

            if (isset($request->resolve)) {
                $reports = $reports->where('resolve', $request->resolve);
            }

            if ($request->fieldName && $request->shortBy) {
                $reports->orderBy($request->fieldName, $request->shortBy);
            }

            $perPage = $request->perPage ?? 20;

            $reports = $reports->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
            return Inertia::render('Admin/report_issue/List', ['reports' => $reports]);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function reportIssueView(ReportIssue $report)
    {
        try {
            $report->load(['user', 'safari']);
            return Inertia::render('Admin/report_issue/View', compact('report'));
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function changeReportIssueStatus(Request $request)
    {
        try {
            $report = ReportIssue::with('user')->find($request->id);
            $oldStatus = $report->resolve;
            $report->resolve = !$report->resolve;
            $report->save();

            // Send email when issue is resolved (changed from pending to resolved)
            if ($oldStatus == 0 && $report->resolve == 1 && $report->user && $report->user->email) {
                Mail::to($report->user->email)->send(new IssueResolvedMail($report));
            }

            session()->flash('success', 'Report status successfully changed');
            return back();
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }
}
