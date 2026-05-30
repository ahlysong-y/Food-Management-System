<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Ingredient; // 🛠️ បានបន្ថែមការនាំចូល Model នេះដើម្បីដោះស្រាយ Error
use App\Models\MenuItem;   // 🛠️ នាំចូលបន្ថែមដើម្បីភាពច្បាស់លាស់របស់ប្រព័ន្ធ

class Recipe extends Model
{
    protected $fillable = ['menu_item_id', 'ingredient_id', 'quantity_required'];

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class, 'ingredient_id');
    }
}
