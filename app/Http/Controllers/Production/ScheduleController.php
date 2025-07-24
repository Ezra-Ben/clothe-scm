<?php

namespace App\Http\Controllers\Production;

use App\Models\Schedule;
use App\Models\ProductionBatch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with('batches')->latest()->paginate(10);
        return view('production.schedules.index', compact('schedules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planned,active,completed,cancelled',
            'batch_ids' => 'nullable|string',
        ]);

        $schedule = Schedule::create([
            'description' => $validated['description'] ?? null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => $validated['status'],
        ]);

        // Attach batches
        if (!empty($validated['batch_ids'])) {
            $batchIds = array_filter(array_map('trim', explode(',', $validated['batch_ids'])));
            ProductionBatch::whereIn('id', $batchIds)->update(['schedule_id' => $schedule->id]);
        }

        return redirect()->route('schedules.index')->with('success', 'Schedule created.');
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'description' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planned,active,completed,cancelled',
            'batch_ids' => 'nullable|string',
        ]);

        $schedule->update([
            'description' => $validated['description'] ?? null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => $validated['status'],
        ]);

        // Detach all batches from this schedule first
        ProductionBatch::where('schedule_id', $schedule->id)->update(['schedule_id' => null]);

        // Then reassign selected ones
        if (!empty($validated['batch_ids'])) {
            $batchIds = array_filter(array_map('trim', explode(',', $validated['batch_ids'])));
            ProductionBatch::whereIn('id', $batchIds)->update(['schedule_id' => $schedule->id]);
        }

        return redirect()->route('schedules.index')->with('success', 'Schedule updated.');
    }

    public function destroy(Schedule $schedule)
    {
        // Optionally detach batches
        ProductionBatch::where('schedule_id', $schedule->id)->update(['schedule_id' => null]);

        $schedule->delete();

        return redirect()->route('schedules.index')->with('success', 'Schedule deleted.');
    }
}
