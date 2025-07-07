<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\QuickbooksPurchaseOrderDetail;

class QuickbooksPurchaseOrder extends Model
{
    protected $table = 'quickbooks_purchase_orders';

    public function details()
    {
        return $this->hasMany(QuickbooksPurchaseOrderDetail::class, 'purchase_order_id', 'id');
    }

}
