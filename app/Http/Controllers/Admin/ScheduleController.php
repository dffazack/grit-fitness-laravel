<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\Trainer; // <-- [PERBAIKAN] Pastikan baris ini ada
use Illuminate\Http\Request;

class ScheduleController extends Controller
{

    
    public function index()
    {
        // [PERBAIKAN] Ambil juga data trainers untuk mengisi form modal
        $trainers = Trainer::where('is_active', true)->get();
        
        $schedules = ClassSchedule::with('trainer')
                        ->withCount('bookings') // Menghitung jumlah booking
                        ->orderBy('day', 'asc') // Urutkan berdasarkan hari
                        ->orderBy('start_time', 'asc') // Lalu urutkan berdasarkan jam
                        ->paginate(15);
        
        // [PERBAIKAN] Kirimkan $schedules DAN $trainers ke view
        return view('admin.schedules.index', compact('schedules', 'trainers'));
    }
    
    public function create()
    {
        // Method ini tidak lagi digunakan oleh modal, tapi tidak masalah
        $trainers = Trainer::where('is_active', true)->get();
        return view('admin.schedules.create', compact('trainers'));
    }
    
    public function store(Request $request)
    {
        // [PERBAIKAN] Validasi yang lebih baik menggunakan konstanta dari Model
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'day' => 'required|in:'.implode(',', \App\Models\ClassSchedule::DAYS),
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'trainer_id' => 'required|exists:trainers,id',
            'max_quota' => 'required|integer|min:1',
            'type' => 'required|in:'.implode(',', \App\Models\ClassSchedule::CLASS_TYPES),
            'description' => 'nullable|string',
        ]);
        
        // [PERBAIKAN] Tambahkan ini untuk error handling di modal
        $request->merge(['form_type' => 'add']);

        ClassSchedule::create($validated);
        
        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal kelas berhasil ditambahkan');
    }
    
    public function edit($id)
    {
        // Method ini tidak lagi digunakan oleh modal, tapi tidak masalah
        $schedule = ClassSchedule::findOrFail($id);
        $trainers = Trainer::where('is_active', true)->get();
        return view('admin.schedules.edit', compact('schedule', 'trainers'));
    }
    
    public function update(Request $request, $id)
    {
        $schedule = ClassSchedule::findOrFail($id);
        
        // [PERBAIKAN] Validasi yang lebih baik menggunakan konstanta dari Model
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'day' => 'required|in:'.implode(',', \App\Models\ClassSchedule::DAYS),
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'trainer_id' => 'required|exists:trainers,id',
            'max_quota' => 'required|integer|min:1',
            'type' => 'required|in:'.implode(',', \App\Models\ClassSchedule::CLASS_TYPES),
            'description' => 'nullable|string',
        ]);
        
        // [PERBAIKAN] Tambahkan ini untuk error handling di modal
        $request->merge([
            'form_type' => 'edit',
            'schedule_id' => $id
        ]);

        $schedule->update($validated);
        
        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal kelas berhasil diperbarui');
    }
    
    public function destroy($id)
    {
        $schedule = ClassSchedule::findOrFail($id);
        $schedule->delete();
        
        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal kelas berhasil dihapus');
    }
}

