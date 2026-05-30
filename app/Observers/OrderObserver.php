<?php

namespace App\Observers;

use App\Models\Order;
// 🛠️ បានលុបបន្ទាត់ use App\Models\Ingredient; ចេញដើម្បីបាត់ការព្រមាន (Warning)

class OrderObserver
{
    public function updated(Order $order): void
    {
        // ពិនិត្យមើលថាតើ Status ត្រូវបានប្តូរទៅជា 'Completed' មែនឬអត់
        if ($order->status === 'Completed' && $order->isDirty('status')) {

            // ដើររកមើលមុខម្ហូបទាំងអស់ដែលមាននៅក្នុង Order នេះ
            foreach ($order->orderDetails as $detail) {
                $menuItem = $detail->menuItem;

                if ($menuItem) {
                    // ដើររកមើលរូបមន្ត (Recipes) នៃមុខម្ហូបនោះថាប្រើគ្រឿងផ្សំអ្វីខ្លះ
                    foreach ($menuItem->recipes as $recipe) {
                        $ingredient = $recipe->ingredient;

                        if ($ingredient) {
                            // គណនាចំនួនដែលត្រូវដក៖ ចំនួនចានដែលភ្ញៀវកុម្មង់ x ចំនួនគ្រឿងផ្សំក្នុងមួយចាន
                            $totalDeduct = $detail->qty * $recipe->quantity_required;

                            // ធ្វើបច្ចុប្បន្នភាពដកចេញពីស្តុកបច្ចុប្បន្ន
                            $ingredient->decrement('current_stock', $totalDeduct);
                        }
                    }
                }
            }
        }
    }
}
