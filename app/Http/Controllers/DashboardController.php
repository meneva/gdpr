<?php

namespace App\Http\Controllers;

use App\Models\DataBreach;
use App\Models\Dpia;
use App\Models\ProcessingActivity;
use App\Models\SubjectAccessRequest;
use App\Models\Supplier;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        // Each query below is automatically scoped to the current company
        // via the BelongsToCompany global scope — no company_id filtering
        // needed here.
        $openSars = SubjectAccessRequest::where('status', '!=', 'completed')->get();
        $overdueSars = $openSars->filter->isOverdue()->count();

        $openBreaches = DataBreach::where('status', '!=', 'resolved')->get();
        $urgentBreaches = $openBreaches->filter(fn ($b) => $b->hoursRemaining() <= 48)->count();

        $pendingDpias = Dpia::whereNotIn('status', ['approved', 'rejected'])->count();

        $supplierGaps = Supplier::where('dpa_on_file', false)
            ->orWhere('risk_level', 'high')
            ->count();

        $ropaCount = ProcessingActivity::count();

        return view('dashboard', [
            'openSarsCount' => $openSars->count(),
            'overdueSars' => $overdueSars,
            'openBreachesCount' => $openBreaches->count(),
            'urgentBreaches' => $urgentBreaches,
            'pendingDpias' => $pendingDpias,
            'supplierGaps' => $supplierGaps,
            'ropaCount' => $ropaCount,
            'score' => $this->complianceScore($overdueSars, $urgentBreaches, $pendingDpias, $supplierGaps),
        ]);
    }

    /**
     * A simple weighted score, purely to give the dashboard something to
     * anchor on at a glance. Not a legal compliance rating — just a prompt
     * to act, same spirit as the original standalone demo.
     */
    protected function complianceScore(int $overdueSars, int $urgentBreaches, int $pendingDpias, int $supplierGaps): int
    {
        $score = 100;
        $score -= $overdueSars * 10;
        $score -= $urgentBreaches * 8;
        $score -= max(0, $pendingDpias - 2) * 4;
        $score -= $supplierGaps * 3;

        return max(20, min(100, $score));
    }
}
