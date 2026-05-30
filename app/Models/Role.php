<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Employee;

class Role extends Model
{
    // 🛠️ សំខាន់បំផុត៖ បន្ថែម name និង description ចូលក្នុង $fillable
    protected $fillable = ['name', 'description'];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'role_id');
    }
}
