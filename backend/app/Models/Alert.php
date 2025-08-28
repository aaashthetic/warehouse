<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    protected $table = 'inventory_alerts';
    
    protected $primaryKey = 'alert_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'sku',
        'stock',
        'alert',
        'created_at'
    ];

    // Relationship to product
    public function product()
    {
        return $this->belongsTo(Product::class, 'sku', 'sku');
    }
}