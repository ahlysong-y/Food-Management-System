<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Branch; // 🛠️ នាំចូល Model សាខា

class DiningTable extends Model
{
    // 🛠️ ត្រូវប្រាកដថាមានជួរឈរទាំងនេះនៅក្នុង fillable
    protected $fillable = ['branch_id', 'table_no', 'capacity', 'status'];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
