<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingCourse extends Model
{
    use BelongsToCompany;

    protected $fillable = ['company_id', 'name', 'description'];

    public function completions(): HasMany
    {
        return $this->hasMany(TrainingCompletion::class);
    }

    public function completionPercent(): int
    {
        $total = $this->completions()->count();

        if ($total === 0) {
            return 0;
        }

        $done = $this->completions()->whereNotNull('completed_at')->count();

        return (int) round(100 * $done / $total);
    }
}
