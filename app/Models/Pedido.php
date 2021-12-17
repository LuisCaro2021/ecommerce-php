<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    public $timestamps = false;
    protected $table = 'pedidos';

    protected $fillable = [
        'email',
        
    ];

    public function products() {
        return $this->hasMany(PedidoProduct::class);
    }

    
}
