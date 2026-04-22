<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function settings()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        return view('account.settings');
    }

    public function updatePicture(Request $request)
    {
        $request->validate([
            'profile_pic' => 'required|image|max:2048', // 2MB Max
        ]);

        $user = auth()->user();

        if ($request->hasFile('profile_pic')) {
            // Delete old picture if exists
            if ($user->profile_pic && file_exists(public_path($user->profile_pic))) {
                @unlink(public_path($user->profile_pic));
            }

            $file = $request->file('profile_pic');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/profile_pics'), $filename);
            
            $user->profile_pic = 'uploads/profile_pics/' . $filename;
            $user->save();
        }

        return back()->with('success', 'Profile picture updated successfully!');
    }
}
