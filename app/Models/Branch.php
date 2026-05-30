<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Employee;
use App\Models\DiningTable;

class Branch extends Model
{
    // 🛠️ ត្រូវប្រាកដថាមានជួរឈរទាំងនេះនៅក្នុង fillable (ជាពិសេស code និង name)
    protected $fillable = ['code', 'name', 'phone', 'email', 'address', 'status'];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'branch_id');
    }

    public function diningTables(): HasMany
    {
        return $this->hasMany(DiningTable::class, 'branch_id');
    }
}
