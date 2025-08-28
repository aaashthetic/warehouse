<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $primaryKey = 'sku';
    public $incrementing = false;
    protected $keyType = 'string';
    
    public $timestamps = false;

    protected $fillable = [
        'sku',
        'product_name'
    ];

    // Relationships
    public function sales()
    {
        return $this->hasMany(Sale::class, 'sku', 'sku');
    }

    public function alerts()
    {
        return $this->hasMany(InventoryAlert::class, 'sku', 'sku');
    }

    public function logs()
    {
        return $this->hasMany(RfidLog::class, 'sku', 'sku');
    }
}
