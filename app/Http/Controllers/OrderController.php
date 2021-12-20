<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\log;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Arr;
use Illuminate\Support\Facades\DB;

use App\Models\Product;
use App\Models\Pedido;
use App\Models\PedidoProduct;



class OrderController extends Controller
{
    public function create(Request $request)
    {
                
        try { 
            DB::beginTransaction();
              
        $request->validate([
            "email" => 'required|email'

        ]);


        // Crear una pedido
        $pedido = new Pedido();
        $pedido->email = $request->email;
        $pedido->saveOrFail();

        $idPedido = $pedido->id;
 
 
        $products = $request->input('products');
        if(count($products) <=0 ){
            return response()->json(['message' => 'Ingresar toda la información del producto']);
        }
 
        for($i=0;$i<count($products);$i++){
        
        $actualizarProducto = Product::find($products[$i]["id"]);
            
        $producto= new PedidoProduct();
        $producto->pedido_id=$idPedido;
        $producto->product_id=$products[$i]["id"];
        $producto->price=$actualizarProducto->price;
        $producto->quantity=$products[$i]["quantity"];
                
        if ($producto->quantity > $actualizarProducto->inventory OR $producto->quantity <=0) {
            
            return response()->json(['message' => 'Verificar la cantidad ingresada']);
        }
        $producto->save();

        $actualizarProducto->inventory -= $producto->quantity;
        $actualizarProducto->save();
        
    } 
       
        DB::commit();

        return response()->json(['message' => 'Datos ingresados correctamente']);

        }

        catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Verificar la información ingresada']);
        }
          return response()->json(['message' => 'Correcto']);

    
    }


    public function index() {
        $pedidos = Pedido::with(['products'])->get();
        return response()->json($pedidos, 200);
    }


    public function getById($email) {
        $pedidos = Pedido::with(['products'])
                            ->where('email', $email)    
                            ->first();

        if (empty($pedidos)) {
            return response()->json(['message' => 'Not Found'], 404);
        }      

        return response()->json($pedidos, 200);
    }  

}
