<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuickbooksPurchaseOrderDetail extends Model
{
    protected $table = 'quickbooks_purchase_order_details';

    public function order()
    {
        return $this->hasOne('App\Models\QuickbooksPurchaseOrder','id','purchase_order_id');
    }

    public function lego_use()
    {
        $this->use = Ct_product::selectRaw('IF(ISNULL(GROUP_CONCAT(DISTINCT `item_code` SEPARATOR "/")), "Retail Only", "Mixed Use") AS `use`')->where('ListID', $this->Item_ListID)->value('use');
        return $this->use;
        /*$item_code=Ct_product::selectRaw('GROUP_CONCAT(DISTINCT `item_code` SEPARATOR "/") AS `item_code`')->where('ListID', $this->Item_ListID)->value('item_code');
        $this->use = isset($item_code) ? 'Mixed Use' : 'Retail Only';
        return isset($item_code) ? 'Mixed Use' : 'Retail Only';*/
    }
}
