<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProcessingActivityRequest;
use App\Http\Requests\UpdateProcessingActivityRequest;
use App\Models\ProcessingActivity;
use App\Support\Exports\RegisterExport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProcessingActivityController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', ProcessingActivity::class);

        $activities = ProcessingActivity::query()
            ->orderBy('name')
            ->paginate(20);

        return view('processing-activities.index', compact('activities'));
    }

    public function create(): View
    {
        $this->authorize('create', ProcessingActivity::class);

        return view('processing-activities.create');
    }

    public function store(StoreProcessingActivityRequest $request): RedirectResponse
    {
        $this->authorize('create', ProcessingActivity::class);

        $activity = DB::transaction(fn () => ProcessingActivity::create($request->validated()));

        return redirect()
            ->route('processing-activities.show', $activity)
            ->with('status', "Logged {$activity->ref_no}.");
    }

    public function show(ProcessingActivity $activity): View
    {
        $this->authorize('view', $activity);

        return view('processing-activities.show', compact('activity'));
    }

    public function edit(ProcessingActivity $activity): View
    {
        $this->authorize('update', $activity);

        return view('processing-activities.edit', compact('activity'));
    }

    public function update(UpdateProcessingActivityRequest $request, ProcessingActivity $activity): RedirectResponse
    {
        $this->authorize('update', $activity);

        $activity->update($request->validated());

        return redirect()->route('processing-activities.show', $activity)->with('status', 'Updated.');
    }

    public function destroy(ProcessingActivity $activity): RedirectResponse
    {
        $this->authorize('delete', $activity);

        $activity->delete();

        return redirect()->route('processing-activities.index')->with('status', 'Deleted.');
    }

    public function exportCsv()
    {
        $this->authorize('viewAny', ProcessingActivity::class);

        $headers = ['Reference', 'Activity', 'Legal Basis', 'Retention Period', 'Owner'];

        $rows = ProcessingActivity::query()->orderBy('name')->get()->map(fn ($activity) => [
            $activity->ref_no,
            $activity->name,
            $activity->legal_basis ?? '',
            $activity->retention_period ?? '',
            $activity->owner_name ?? '',
        ]);

        return RegisterExport::csv('processing-activities.csv', $headers, $rows);
    }

    public function exportPdf()
    {
        $this->authorize('viewAny', ProcessingActivity::class);

        $headers = ['Reference', 'Activity', 'Legal Basis', 'Retention Period', 'Owner'];

        $rows = ProcessingActivity::query()->orderBy('name')->get()->map(fn ($activity) => [
            $activity->ref_no,
            $activity->name,
            $activity->legal_basis ?? '',
            $activity->retention_period ?? '',
            $activity->owner_name ?? '',
        ]);

        return RegisterExport::pdf('Processing Activities Register (RoPA)', $headers, $rows, 'processing-activities.pdf');
    }
}
