<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Recipe; // នាំចូល Model រូបមន្ត បើមានការប្រើប្រាស់ទៅថ្ងៃមុខ

class Ingredient extends Model
{
    // 🛠️ សំខាន់បំផុត៖ បន្ថែមជួរឈរទាំងអស់ចូលក្នុង $fillable ដើម្បីបាត់ Error
    protected $fillable = [
        'ingredient_code',
        'name',
        'unit',
        'current_stock',
        'minimum_stock'
    ];

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class, 'ingredient_id');
    }
}
