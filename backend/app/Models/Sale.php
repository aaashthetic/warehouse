<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';
    
    protected $primaryKey = 'sale_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'sku',
        'month',
        'sales',
        'predicted_demand'
    ];

    // Relationship to product
    public function product()
    {
        return $this->belongsTo(Product::class, 'sku', 'sku');
    }
}