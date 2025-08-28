<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $table = 'rfid_logs';
    protected $primaryKey = 'log_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'sku',
        'location',
        'last_scanned',
        'status'
    ];

    // Relationship to product
    public function product()
    {
        return $this->belongsTo(Product::class, 'sku', 'sku');
    }
}