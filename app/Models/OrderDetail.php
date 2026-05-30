<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id',
        'menu_item_id',
        'qty',
        'unit_price',
        'subtotal'
    ];

    // សំខាន់បំផុត៖ ត្រូវប្រាកដថាឈ្មោះ function គឺ menuItem (អក្សរ I ធំ)
    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
