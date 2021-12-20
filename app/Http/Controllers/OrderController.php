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


        // Crear un pedido
        $pedido = new Pedido();
        $pedido->email = $request->email;
        $pedido->saveOrFail();

        $idPedido = $pedido->id;
 
 
        $products = $request->input('products');
        if(count($products) <=0 ){
            return response()->json(['message' => 'Ingresar toda la información del producto']);
        }
 
        //Ingreso de los datos a la tabla PedidosProducts
        for($i=0;$i<count($products);$i++){
        $actualizarProducto = Product::find($products[$i]["id"]);
            
        $registro = new PedidoProduct();
        $registro->pedido_id=$idPedido;
        $registro->product_id=$products[$i]["id"];
        $registro->price=$actualizarProducto->price;
        $registro->quantity=$products[$i]["quantity"];
                
        if ($registro->quantity > $actualizarProducto->inventory OR $registro->quantity <=0) {
            
            return response()->json(['message' => 'Verificar la cantidad ingresada']);
        }
        $registro->save();
        //Para la actualización del inventario
        $actualizarProducto->inventory -= $registro->quantity;
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
