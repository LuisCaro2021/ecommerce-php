<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoProduct extends Model
{
    protected $table = 'pedidos_products';

    protected $fillable = [
        'order_id',
        'product_id',
        'price',
        'quantity',
    ];

    public function order() {
        return $this->belongsTo(Pedido::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
