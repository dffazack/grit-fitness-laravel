<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Menampilkan daftar notifikasi.
     */
    public function index()
    {
        $notifications = Notification::orderBy('is_active', 'desc')
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        // INI ADALAH PERBAIKANNYA:
        // Kita panggil view 'admin.notifications.index'
        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Menyimpan notifikasi baru.
     */
    public function store(Request $request)
    {
        // Tambahkan ini untuk error handling modal
        $request->merge(['form_type' => 'add']);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:'.implode(',', \App\Models\Notification::TYPES),
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'sometimes|boolean',
        ]);

        // Set 'is_active' ke true jika dicentang, false jika tidak
        $validated['is_active'] = $request->has('is_active');

        Notification::create($validated);

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notifikasi baru berhasil ditambahkan.');
    }

    /**
     * Memperbarui notifikasi.
     */
    public function update(Request $request, Notification $notification)
    {
        // Tambahkan ini untuk error handling modal
        $request->merge([
            'form_type' => 'edit',
            'notification_id' => $notification->id,
        ]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:'.implode(',', \App\Models\Notification::TYPES),
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'sometimes|boolean',
        ]);

        // Set 'is_active' ke true jika dicentang, false jika tidak
        $validated['is_active'] = $request->has('is_active');

        $notification->update($validated);

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notifikasi berhasil diperbarui.');
    }

    /**
     * Menghapus notifikasi.
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notifikasi berhasil dihapus.');
    }

    /**
     * Toggle status aktif/non-aktif.
     * Ini untuk tombol "Non-aktifkan"
     */
    public function toggleStatus(Notification $notification)
    {
        $notification->update(['is_active' => ! $notification->is_active]);
        $message = $notification->is_active ? 'Notifikasi diaktifkan.' : 'Notifikasi dinon-aktifkan.';

        return back()->with('success', $message);
    }
}
