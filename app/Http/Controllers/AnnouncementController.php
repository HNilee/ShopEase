<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Get active announcements for global popup
     */
    public function getGlobalAnnouncements(Request $request)
    {
        $announcements = Announcement::where('active', true)
            ->where('type', 'global_popup')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Mark as viewed for this session
        $viewedIds = session('viewed_announcements', []);
        
        $filtered = $announcements->filter(function ($announcement) use ($viewedIds) {
            return !in_array($announcement->id, $viewedIds);
        });

        return response()->json([
            'announcements' => $filtered->map(function ($announcement) {
                return [
                    'id' => $announcement->id,
                    'title' => $announcement->title,
                    'message' => $announcement->body,
                    'type' => $announcement->type,
                    'priority' => $announcement->priority,
                    'created_at' => $announcement->created_at->toIso8601String(),
                ];
            })
        ]);
    }

    /**
     * Mark announcement as viewed
     */
    public function markAsViewed(Request $request, $id)
    {
        $viewedIds = session('viewed_announcements', []);
        $viewedIds[] = $id;
        session(['viewed_announcements' => array_unique($viewedIds)]);

        return response()->json(['success' => true]);
    }

    /**
     * Store new announcement (Admin only)
     */
    public function store(Request $request)
    {
        $this->authorize('create', Announcement::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
            'type' => 'required|in:global_popup,banner,email',
            'priority' => 'required|in:low,medium,high,critical',
            'active' => 'boolean',
            'expires_at' => 'nullable|date',
        ]);

        $announcement = Announcement::create($validated);

        return redirect()->route('admin.announcement.create')
            ->with('success', 'Announcement created successfully');
    }
}