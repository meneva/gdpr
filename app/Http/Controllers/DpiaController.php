<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDpiaRequest;
use App\Http\Requests\UpdateDpiaRequest;
use App\Models\Dpia;
use App\Support\Exports\RegisterExport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DpiaController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Dpia::class);

        $dpias = Dpia::query()
            ->latest('due_at')
            ->paginate(20);

        return view('dpias.index', compact('dpias'));
    }

    public function create(): View
    {
        $this->authorize('create', Dpia::class);

        return view('dpias.create');
    }

    public function store(StoreDpiaRequest $request): RedirectResponse
    {
        $this->authorize('create', Dpia::class);

        $dpia = DB::transaction(fn () => Dpia::create($request->validated()));

        return redirect()
            ->route('dpias.show', $dpia)
            ->with('status', "Started {$dpia->ref_no}.");
    }

    public function show(Dpia $dpia): View
    {
        $this->authorize('view', $dpia);

        return view('dpias.show', compact('dpia'));
    }

    public function edit(Dpia $dpia): View
    {
        $this->authorize('update', $dpia);

        return view('dpias.edit', compact('dpia'));
    }

    public function update(UpdateDpiaRequest $request, Dpia $dpia): RedirectResponse
    {
        $data = $request->validated();
        $movingToDecision = in_array($data['status'], ['approved', 'rejected'], true)
            && $data['status'] !== $dpia->status;

        // Approving or rejecting is a higher bar than ordinary editing —
        // an editor can update fields and move a DPIA into review, but
        // only owner/admin can actually sign off on it either way.
        $this->authorize($movingToDecision ? 'approve' : 'update', $dpia);

        if ($data['status'] === 'approved' && empty($dpia->approved_at)) {
            $data['approved_at'] = now();
        }

        $dpia->update($data);

        return redirect()->route('dpias.show', $dpia)->with('status', 'Updated.');
    }

    public function destroy(Dpia $dpia): RedirectResponse
    {
        $this->authorize('delete', $dpia);

        $dpia->delete();

        return redirect()->route('dpias.index')->with('status', 'Deleted.');
    }

    public function exportCsv()
    {
        $this->authorize('viewAny', Dpia::class);

        $headers = ['Reference', 'Project', 'Owner', 'Risk', 'Review Due', 'Status'];

        $rows = Dpia::query()->orderBy('due_at')->get()->map(fn ($dpia) => [
            $dpia->ref_no,
            $dpia->project_name,
            $dpia->owner_name ?? '',
            ucfirst($dpia->risk_level),
            $dpia->due_at->format('Y-m-d'),
            ucfirst(str_replace('_', ' ', $dpia->status)),
        ]);

        return RegisterExport::csv('dpias.csv', $headers, $rows);
    }

    public function exportPdf()
    {
        $this->authorize('viewAny', Dpia::class);

        $headers = ['Reference', 'Project', 'Owner', 'Risk', 'Review Due', 'Status'];

        $rows = Dpia::query()->orderBy('due_at')->get()->map(fn ($dpia) => [
            $dpia->ref_no,
            $dpia->project_name,
            $dpia->owner_name ?? '',
            ucfirst($dpia->risk_level),
            $dpia->due_at->format('d M Y'),
            ucfirst(str_replace('_', ' ', $dpia->status)),
        ]);

        return RegisterExport::pdf('DPIA Register', $headers, $rows, 'dpias.pdf');
    }
}
