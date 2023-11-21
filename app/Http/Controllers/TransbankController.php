<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use Transbank\Webpay\WebpayPlus;
use Transbank\Webpay\WebpayPlus\Transaction;

use App\Models\Compra;
use App\Models\Carrito;
use App\Models\Usuario;

class TransbankController extends Controller{

    public function __construct() {
        if(app()->environment('production')){
            WebpayPlus::configureForProduction(
                env('WEBPAY_PLUS_CC'),
                env('WEBPAY_PLUS_API_KEY')
            );
        }else{
            WebpayPlus::configureForTesting();
        }
    }

    public function iniciarDeposito(Request $request){
        $usuario = Auth::user();
        $compra = new Compra();
        $compra->email_titular = $usuario->email;
        $compra->total = $request->input('total'); 
        $compra->id_sesion = '123456'; 
        $compra->fecha_inicio = date("Y-m-d H:i:s"); 
        $compra->save();
        $url = self::start_transaction($compra);
        return response()->json(["url"=>$url]);
    }

    public function iniciarCompra(Request $request){
        $usuario = Auth::user();
        $compra = new Compra();
        $compra->email_titular = $usuario->email;
        $compra->total = $request->input('total'); 
        $compra->id_sesion = '123456'; 
        $compra->id_carrito = $request->input('id_carrito'); 
        $compra->fecha_inicio = date("Y-m-d H:i:s"); 
        $compra->save();
        $url = self::start_transaction($compra);
        return response()->json(["url"=>$url]);
    }

    public function start_transaction($compra){
        $transaccion= (new Transaction)->create(
            $compra->id_compra,
            $compra->id_sesion,
            $compra->total,
            route('confirmarPago')
        );
        $url = $transaccion->getUrl().'?token_ws='.$transaccion->getToken();
        return $url;
    }
    public function confirmarPago(Request $request){

        try{
            $confirmacion = (new Transaction)->commit($request->get('token_ws'));

            $compra= Compra::where('id_compra',$confirmacion->buyOrder)->first();

            if($confirmacion->isApproved()){
                $compra->status=1;
                $compra->fecha_confirmacion = date("Y-m-d H:i:s"); 
                $compra->update();
                if(!is_null($compra->id_carrito)){
                    $carrito= Carrito::where('id_carrito',$compra->id_carrito)->first();
                    $carrito->en_uso = 0;
                    $carrito->update();
                    $new = Carrito::firstOrCreate(['en_uso' => 1],['email_carro' => $carrito->email_carro]);
                }else if(is_null($compra->id_carrito)){
                    $usuario=Usuario::where('email',$compra->email_titular)->first();
                    $usuario->cartera = $usuario->cartera + $compra->total;
                    $usuario->update();
                }
                return redirect('http://localhost:4200/principal');
            }else{
                return response()->json(["mensaje"=>"pago cancelado {$compra->id_compra}"]);
            }
        }catch(\Exception $e){
            return response()->json(['mensaje' => "tiempo de espera agotado"]);
        }

    }


    public function mostrarCompra(Request $request){

        $compra = Compra::where('id_compra', '=', $request->input('id_compra'))->first();

            $compra = [
                'id_compra' => $compra->id_compra,
                'email_titular' => $compra->email_titular,
                'total' => $compra->total,
                'fecha_inicio' => $compra->fecha_inicio,
                'fecha_confirmacion' => $compra->fecha_confirmacion,
                'status' => $compra->status,
            ];

        return response()->json($compra);
    }

    public function mostrarCompras(Request $request){

        $compras = Compra::all();
        $Compras = [];

        foreach ($compras as $compra) {
            $datoscompra = [
                'id_compra' => $compra->id_compra,
                'email_titular' => $compra->email_titular,
                'total' => $compra->total,
                'fecha_compra' => $compra->fecha_compra,
                'contenido_compra' => $compra->contenido_compra,
                'status' => $compra->status,
            ];

            $Compras[] = $datosCompra;
        }
        return response()->json($Compras);
    }
}
