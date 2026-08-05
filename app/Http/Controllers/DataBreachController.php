<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDataBreachRequest;
use App\Http\Requests\UpdateDataBreachRequest;
use App\Models\DataBreach;
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
}
