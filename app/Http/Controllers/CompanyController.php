<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function create(): View
    {
        $this->authorize('create', Company::class);

        return view('companies.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Company::class);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
        ]);

        $data['slug'] = $this->uniqueSlug($data['name']);
        $user = $request->user();

        $company = Company::create([
            'owner_id' => $user->id,
            'name' => $data['name'],
            'industry' => $data['industry'] ?? null,
            'slug' => $data['slug'],
        ]);

        $company->users()->attach($user->id, [
            'role' => 'owner',
            'joined_at' => now(),
        ]);

        $user->update(['current_company_id' => $company->id]);
        session(['current_company_id' => $company->id]);

        return redirect()->route('dashboard')->with('status', "Welcome to {$company->name}.");
    }

    protected function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $suffix = 1;

        while (Company::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
