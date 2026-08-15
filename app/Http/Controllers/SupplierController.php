<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;
use App\Support\Exports\RegisterExport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Supplier::class);

        $suppliers = Supplier::query()
            ->orderBy('name')
            ->paginate(20);

        return view('suppliers.index', compact('suppliers'));
    }

    public function create(): View
    {
        $this->authorize('create', Supplier::class);

        return view('suppliers.create');
    }

    public function store(StoreSupplierRequest $request): RedirectResponse
    {
        $this->authorize('create', Supplier::class);

        $supplier = DB::transaction(fn () => Supplier::create($request->validated()));

        return redirect()
            ->route('suppliers.show', $supplier)
            ->with('status', "Registered {$supplier->ref_no}.");
    }

    public function show(Supplier $supplier): View
    {
        $this->authorize('view', $supplier);

        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier): View
    {
        $this->authorize('update', $supplier);

        return view('suppliers.edit', compact('supplier'));
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        $this->authorize('update', $supplier);

        $supplier->update($request->validated());

        return redirect()->route('suppliers.show', $supplier)->with('status', 'Updated.');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $this->authorize('delete', $supplier);

        $supplier->delete();

        return redirect()->route('suppliers.index')->with('status', 'Deleted.');
    }

    public function exportCsv()
    {
        $this->authorize('viewAny', Supplier::class);

        $headers = ['Reference', 'Supplier', 'Category', 'DPA on file', 'Risk', 'Last reviewed'];

        $rows = Supplier::query()->orderBy('name')->get()->map(fn ($supplier) => [
            $supplier->ref_no,
            $supplier->name,
            $supplier->category ?? '',
            $supplier->dpa_on_file ? 'Yes' : 'No',
            ucfirst($supplier->risk_level),
            $supplier->last_reviewed_at?->format('Y-m-d') ?? '',
        ]);

        return RegisterExport::csv('suppliers.csv', $headers, $rows);
    }

    public function exportPdf()
    {
        $this->authorize('viewAny', Supplier::class);

        $headers = ['Reference', 'Supplier', 'Category', 'DPA on file', 'Risk', 'Last reviewed'];

        $rows = Supplier::query()->orderBy('name')->get()->map(fn ($supplier) => [
            $supplier->ref_no,
            $supplier->name,
            $supplier->category ?? '',
            $supplier->dpa_on_file ? 'Yes' : 'No',
            ucfirst($supplier->risk_level),
            $supplier->last_reviewed_at?->format('d M Y') ?? '',
        ]);

        return RegisterExport::pdf('Suppliers & Processors Register', $headers, $rows, 'suppliers.pdf');
    }
}
