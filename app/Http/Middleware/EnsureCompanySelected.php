<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanySelected
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        if (! session('current_company_id')) {
            $company = $user->companies()->first();

            if (! $company) {
                return redirect()
                    ->route('companies.create')
                    ->with('status', 'Create or join a company to continue.');
            }

            session(['current_company_id' => $company->id]);
        }

        return $next($request);
    }
}