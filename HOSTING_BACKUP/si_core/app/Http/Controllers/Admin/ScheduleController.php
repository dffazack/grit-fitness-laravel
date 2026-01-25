<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassList;
use App\Models\ClassSchedule;
use App\Models\Trainer;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $trainers = Trainer::where('is_active', true)->get();
        $classLists = ClassList::all();

        $schedules = ClassSchedule::with('trainer', 'classList')
            ->withCount('bookings')
            ->orderBy('day', 'asc')
            ->orderBy('start_time', 'asc')
            ->paginate(15);

        // Kirim sinyal untuk membuka modal jika ada (dari create/edit redirect)
        $modal = $request->session()->get('modal');

        return view('admin.schedules.index', compact('schedules', 'trainers', 'classLists', 'modal'));
    }

    public function create(Request $request)
    {
        $trainers = Trainer::where('is_active', true)->get();
        $classLists = ClassList::all();
        return view('admin.schedules.create', compact('trainers', 'classLists'));
    }

    public function store(Request $request)
    {
        // Validasi field umum
        $validated = $request->validate([
            'day' => 'required|in:' . implode(',', ClassSchedule::DAYS),
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'trainer_id' => 'required|exists:trainers,id',
            'max_quota' => 'required|integer|min:1',
            'type' => 'required|in:' . implode(',', ClassSchedule::CLASS_TYPES),
            'description' => 'nullable|string',
        ]);

        // Validasi kondisional untuk class_list_id dan custom_class_name
        $classListIdInput = $request->input('class_list_id');
        if ($classListIdInput === 'other') {
            $customClassValidated = $request->validate(['custom_class_name' => 'required|string|max:255']);
            $validated['class_list_id'] = null;
            $validated['custom_class_name'] = $customClassValidated['custom_class_name'];
        } else {
            $classListValidated = $request->validate(['class_list_id' => 'required|exists:class_lists,id']);
            $validated['class_list_id'] = $classListValidated['class_list_id'];
            $validated['custom_class_name'] = null;
        }

        ClassSchedule::create($validated);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal kelas berhasil ditambahkan');
    }

    public function edit(Request $request, $id)
    {
        // Redirect ke index dengan sinyal untuk buka modal edit
        return redirect()->route('admin.schedules.index')
            ->with('modal', 'edit')
            ->with('edit_schedule_id', $id);
    }

    public function update(Request $request, $id)
    {
        $schedule = ClassSchedule::findOrFail($id);

        // Validasi field umum
        $validated = $request->validate([
            'day' => 'required|in:' . implode(',', ClassSchedule::DAYS),
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'trainer_id' => 'required|exists:trainers,id',
            'max_quota' => 'required|integer|min:1',
            'type' => 'required|in:' . implode(',', ClassSchedule::CLASS_TYPES),
            'description' => 'nullable|string',
        ]);

        // Validasi kondisional untuk class_list_id dan custom_class_name
        $classListIdInput = $request->input('class_list_id');
        if ($classListIdInput === 'other') {
            $customClassValidated = $request->validate(['custom_class_name' => 'required|string|max:255']);
            $validated['class_list_id'] = null;
            $validated['custom_class_name'] = $customClassValidated['custom_class_name'];
        } else {
            $classListValidated = $request->validate(['class_list_id' => 'required|exists:class_lists,id']);
            $validated['class_list_id'] = $classListValidated['class_list_id'];
            $validated['custom_class_name'] = null;
        }

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

    public function show($id)
    {
        $schedule = ClassSchedule::with(['bookings.user', 'trainer', 'classList'])->findOrFail($id);
        return view('admin.schedules.show', compact('schedule'));
    }

    public function toggleAttendance($bookingId)
    {
        $booking = \App\Models\Booking::findOrFail($bookingId);
        $booking->is_present = !$booking->is_present;
        $booking->save();

        return back();
    }
}