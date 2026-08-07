<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use App\Models\Concerns\GeneratesRefNumbers;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use BelongsToCompany, GeneratesRefNumbers;

    protected $fillable = [
        'company_id', 'name', 'category', 'dpa_on_file',
        'risk_level', 'last_reviewed_at', 'notes',
    ];

    protected $casts = [
        'dpa_on_file' => 'boolean',
        'last_reviewed_at' => 'date',
    ];

    protected function refPrefix(): string
    {
        return 'SUP';
    }

    protected function refSequenceColumn(): string
    {
        return 'supplier_sequence';
    }

    /**
     * No deadline math here, unlike the other four modules — a supplier
     * either has its paperwork in order or it doesn't, right now.
     */
    public function needsAttention(): bool
    {
        return ! $this->dpa_on_file || $this->risk_level === 'high';
    }
}
