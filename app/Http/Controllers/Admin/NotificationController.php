<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }
    
    public function index()
    {
        $notifications = Notification::latest()->paginate(15);
        return view('admin.masterdata.notifications', compact('notifications'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:promo,event,announcement',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);
        
        Notification::create($validated);
        
        return back()->with('success', 'Notifikasi berhasil ditambahkan');
    }
    
    public function update(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:promo,event,announcement',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);
        
        $notification->update($validated);
        
        return back()->with('success', 'Notifikasi berhasil diperbarui');
    }
    
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();
        
        return back()->with('success', 'Notifikasi berhasil dihapus');
    }
}
