<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use App\Models\Concerns\GeneratesRefNumbers;
use Illuminate\Database\Eloquent\Model;

class ProcessingActivity extends Model
{
    use BelongsToCompany, GeneratesRefNumbers;

    protected $fillable = [
        'company_id', 'name', 'purpose', 'legal_basis', 'data_categories',
        'retention_period', 'third_parties_involved', 'owner_name',
    ];

    protected function refPrefix(): string
    {
        return 'ROPA';
    }

    protected function refSequenceColumn(): string
    {
        return 'ropa_sequence';
    }
}
