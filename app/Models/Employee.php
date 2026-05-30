<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Branch; // 🛠️ បានបន្ថែមការនាំចូល Model នេះដើម្បីដោះស្រាយ Error
use App\Models\Role;   // 🛠️ បានបន្ថែមការនាំចូល Model នេះដើម្បីដោះស្រាយ Error

class Employee extends Model
{
    protected $fillable = [
        'branch_id',
        'role_id',
        'employee_code',
        'fullname',
        'gender',
        'phone',
        'email',
        'address',
        'salary',
        'status'
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
