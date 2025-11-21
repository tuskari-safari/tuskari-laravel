<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInfo;
use Illuminate\Http\Request;
use App\Models\ContactUs;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ContactUsController extends Controller
{

    public function index(Request $request)
    {
        try {
            $contacts = ContactUs::query();

            if ($request->name) {
                $contacts = $contacts->where('full_name', 'like', '%' . $request->name . '%');
            }

            if ($request->email) {
                $contacts = $contacts->where('email', 'like', '%' . $request->email . '%');
            }

            if (isset($request->status)) {
                $contacts = $contacts->where('status', $request->status);
            }

            if ($request->fieldName && $request->shortBy) {
                $contacts->orderBy($request->fieldName, $request->shortBy);
            }

            $perPage = $request->perPage ?? 20;

            $contacts = $contacts
                ->orderBy('id', 'desc')
                ->paginate($perPage)
                ->withQueryString();
            return Inertia::render('Admin/contact_us/List', ['contacts' => $contacts]);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function viewContact(ContactUs $contactus)
    {
        try {
            return Inertia::render('Admin/contact_us/View', compact('contactus'));
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function changeContactStatus(Request $request)
    {
        try {
            $contact = ContactUs::find($request->id);
            $contact->status = !$contact->status;
            $contact->save();
            session()->flash('success', 'Contact Us status successfully changed');
            return back();
        } catch (\Throwable $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function updateContactInfo(Request $request)
    {
        $contactInfo = ContactInfo::first();

        if ($request->isMethod('post')) {
            $request->validate([
                'traveller_enquiries' => 'nullable|max:255|email|regex:/(.+)@(.+)\.(.+)/i',
                'operator_partnerships' => 'nullable|max:255|email|regex:/(.+)@(.+)\.(.+)/i',
                'press_media' => 'nullable|max:255|email|regex:/(.+)@(.+)\.(.+)/i',
                'general' => 'nullable|max:255|email|regex:/(.+)@(.+)\.(.+)/i',
                'facebook_link' => 'nullable|url|max:255',
                'instagram_link' => 'nullable|url|max:255',
                'tiktok_link' => 'nullable|url|max:255',
                'youtube_link' => 'nullable|url|max:255',
            ]);

            if (!$contactInfo) {
                $contactInfo = new ContactInfo();
            }

            $contactInfo->traveller_enquiries = $request->traveller_enquiries;
            $contactInfo->operator_partnerships = $request->operator_partnerships;
            $contactInfo->press_media = $request->press_media;
            $contactInfo->general = $request->general;
            $contactInfo->facebook_link = $request->facebook_link;
            $contactInfo->instagram_link = $request->instagram_link;
            $contactInfo->tiktok_link = $request->tiktok_link;
            $contactInfo->youtube_link = $request->youtube_link;
            $contactInfo->save();

            session()->flash('success', 'Contact information updated successfully');
            return redirect()->route('admin.update.contact-info');
        }

        return Inertia::render('Admin/contact_info/createEdit', [
            'contactInfo' => $contactInfo
        ]);
    }
}
