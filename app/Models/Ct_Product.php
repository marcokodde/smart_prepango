<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ct_Product extends Model
{
    use HasFactory;

    protected $table = 'ct_products';

    protected $fillable = [
        'item_code',
        'item',
        'brand',
        'upc',
        'item_sku',
        'ean',
        'irc',
        'ListID',
        'product_type_id',
        'minimum',
        'e911',
        'platform',
        'product_category_id',
        'lead_time_vendor_whs',
        'target_whs_inv_days',
        'safety_whs_inv_days',
        'safety_stock',
        'target_inventory',
        'product_status_id',
        'consignment_cost',
    ];

    //relationships

    public function priceList(): HasMany
    {
        return $this->hasMany(PriceList::class);
    }

    function sales()
    {
        return $this->hasMany(SaleDetail::class);
    }

    function purchases()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'model');
    }

    public function latestImage()
    {
        //recent image
        return $this->morphOne(Image::class, 'model')->latestOfMany();
    }

    //accessors
    public function getPhotoAttribute()
    {
        if (count($this->images)) {
            return  "storage/products/" . $this->images->last()->file;
        } else {
            return 'storage/noimage.jpg';
        }
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    //scope
    public function scopeSearch($query, $term)
    {
        return $query->with(['category', 'supplier', 'priceList'])
            ->where('name', 'like', '%' . $term . '%')
            ->orWhere('description', 'like', '%' . $term . '%')
            ->orWhere('sku', 'like', '%' . $term . '%')
            ->orWhereHas('category', function ($query) use ($term) {
                $query->where('name', 'like', '%' . $term . '%');
            });
    }


    //appends


}
