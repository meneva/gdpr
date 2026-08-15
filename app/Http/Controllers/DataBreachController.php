<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDataBreachRequest;
use App\Http\Requests\UpdateDataBreachRequest;
use App\Models\DataBreach;
use App\Support\Exports\RegisterExport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DataBreachController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', DataBreach::class);

        $breaches = DataBreach::query()
            ->latest('discovered_at')
            ->paginate(20);

        return view('breaches.index', compact('breaches'));
    }

    public function create(): View
    {
        $this->authorize('create', DataBreach::class);

        return view('breaches.create');
    }

    public function store(StoreDataBreachRequest $request): RedirectResponse
    {
        $this->authorize('create', DataBreach::class);

        $breach = DB::transaction(fn () => DataBreach::create($request->validated()));

        return redirect()
            ->route('breaches.show', $breach)
            ->with('status', "Logged {$breach->ref_no}.");
    }

    public function show(DataBreach $breach): View
    {
        $this->authorize('view', $breach);

        return view('breaches.show', compact('breach'));
    }

    public function edit(DataBreach $breach): View
    {
        $this->authorize('update', $breach);

        return view('breaches.edit', compact('breach'));
    }

    public function update(UpdateDataBreachRequest $request, DataBreach $breach): RedirectResponse
    {
        $this->authorize('update', $breach);

        $data = $request->validated();

        // Auto-stamp resolved_at when the status transitions to resolved,
        // if the form didn't already provide one.
        if ($data['status'] === 'resolved' && empty($data['resolved_at']) && ! $breach->resolved_at) {
            $data['resolved_at'] = now();
        }

        $breach->update($data);

        return redirect()->route('breaches.show', $breach)->with('status', 'Updated.');
    }

    public function destroy(DataBreach $breach): RedirectResponse
    {
        $this->authorize('delete', $breach);

        $breach->delete();

        return redirect()->route('breaches.index')->with('status', 'Deleted.');
    }

    public function exportCsv()
    {
        $this->authorize('viewAny', DataBreach::class);

        $headers = ['Reference', 'Incident', 'Severity', 'Discovered', 'ICO Deadline', 'Status'];

        $rows = DataBreach::query()->orderBy('discovered_at')->get()->map(fn ($breach) => [
            $breach->ref_no,
            $breach->title,
            ucfirst($breach->severity),
            $breach->discovered_at->format('Y-m-d H:i'),
            $breach->notify_deadline_at->format('Y-m-d H:i'),
            ucfirst($breach->status),
        ]);

        return RegisterExport::csv('data-breaches.csv', $headers, $rows);
    }

    public function exportPdf()
    {
        $this->authorize('viewAny', DataBreach::class);

        $headers = ['Reference', 'Incident', 'Severity', 'Discovered', 'ICO Deadline', 'Status'];

        $rows = DataBreach::query()->orderBy('discovered_at')->get()->map(fn ($breach) => [
            $breach->ref_no,
            $breach->title,
            ucfirst($breach->severity),
            $breach->discovered_at->format('d M Y H:i'),
            $breach->notify_deadline_at->format('d M Y H:i'),
            ucfirst($breach->status),
        ]);

        return RegisterExport::pdf('Breaches & Incidents Register', $headers, $rows, 'data-breaches.pdf');
    }
}
