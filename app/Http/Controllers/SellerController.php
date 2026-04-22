<?php

namespace App\Http\Controllers;

use App\Models\SellerApplication;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    public function showApplicationForm()
    {
        if (Auth::user()->sellerApplication && Auth::user()->sellerApplication->status === 'pending') {
            return redirect()->route('seller.application.status')->with('info', 'Your application is being reviewed.');
        }

        return view('seller.application');
    }

    public function submitApplication(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'age' => 'required|integer|min:18|max:100',
            'email' => 'required|email|max:255',
            'ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'purpose' => 'required|string|min:50',
            'security_confidence' => 'required|string|min:50',
            'agree_to_sop' => 'required|accepted',
            'agree_to_terms' => 'required|accepted',
        ]);

        // Handle KTP upload
        $ktpPath = null;
        if ($request->hasFile('ktp')) {
            $file = $request->file('ktp');
            $filename = time() . '_' . $file->getClientOriginalName();
            $ktpPath = $file->storeAs('ktp', $filename, 'public');
        }

        $application = SellerApplication::create([
            'user_id' => Auth::id(),
            'full_name' => $validated['full_name'],
            'age' => $validated['age'],
            'email' => $validated['email'],
            'ktp_path' => $ktpPath,
            'purpose' => $validated['purpose'],
            'security_confidence' => $validated['security_confidence'],
            'agree_to_sop' => true,
            'agree_to_terms' => true,
            'status' => 'pending',
        ]);

        // Notify admin and owner
        $this->notifyAdmins('New Seller Application', 'New seller application from ' . Auth::user()->name);

        return redirect()->route('seller.application.status')->with('success', 'Application submitted successfully! We will review it soon.');
    }

    public function showApplicationStatus()
    {
        $application = Auth::user()->sellerApplication;
        
        if (!$application) {
            return redirect()->route('seller.application.form');
        }

        return view('seller.application-status', compact('application'));
    }

    public function showTerms()
    {
        return view('seller.terms');
    }

    private function notifyAdmins($title, $message)
    {
        $admins = \App\Models\User::whereIn('role', ['admin', 'owner'])->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'seller_application',
                'title' => $title,
                'message' => $message,
                'data' => ['applicant_id' => Auth::id()],
            ]);
        }
    }
}