<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\Trainer;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }
    
    public function index()
    {
        $schedules = ClassSchedule::with('trainer')->latest()->paginate(15);
        return view('admin.schedules.index', compact('schedules'));
    }
    
    public function create()
    {
        $trainers = Trainer::where('is_active', true)->get();
        return view('admin.schedules.create', compact('trainers'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'start_time' => 'required',
            'end_time' => 'required',
            'trainer_id' => 'required|exists:trainers,id',
            'max_quota' => 'required|integer|min:1',
            'type' => 'required',
            'description' => 'nullable|string',
        ]);
        
        ClassSchedule::create($validated);
        
        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal kelas berhasil ditambahkan');
    }
    
    public function edit($id)
    {
        $schedule = ClassSchedule::findOrFail($id);
        $trainers = Trainer::where('is_active', true)->get();
        return view('admin.schedules.edit', compact('schedule', 'trainers'));
    }
    
    public function update(Request $request, $id)
    {
        $schedule = ClassSchedule::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'start_time' => 'required',
            'end_time' => 'required',
            'trainer_id' => 'required|exists:trainers,id',
            'max_quota' => 'required|integer|min:1',
            'type' => 'required',
            'description' => 'nullable|string',
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

