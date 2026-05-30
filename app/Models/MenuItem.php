<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Category;
use App\Models\Recipe;

class MenuItem extends Model
{
    // 🛠️ ត្រូវប្រាកដថាមានជួរឈរទាំងនេះនៅក្នុង fillable (ជាពិសេស category_id, item_code, name, selling_price)
    protected $fillable = [
        'category_id',
        'item_code',
        'name',
        'description',
        'selling_price',
        'image',
        'status'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class, 'menu_item_id');
    }
}
