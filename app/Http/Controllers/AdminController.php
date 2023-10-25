<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bodega;
use App\Models\Despachador;
use App\Models\Pedido;

class AdminController extends Controller{

    /*
    //CRUD BODEGA
    */

    public function crearBodega(Request $request){
        try{
            $bodega = Bodega::create(
                [
                    'id_bodega' => $request->input('id_bodega'),
                    'region' => $request->input('region'),
                    'ciudad' => $request->input('ciudad'),
                    'direccion' => $request->input('direccion'),
                ]
            );
            return response()->json(['mensaje' => true]);
        }catch(\Exception $e){
            return response()->json(['mensaje' => false], 400);
        }

    }

    public function actualizarBodega(Request $request){

        $bodega = Bodega::where('id_bodega', '=', $request->input('id_bodega'))->first();

        if (!$bodega) {
            return response()->json(['mensaje' => false], 404);
        }
        $bodega->region = $request->input('region');
        $bodega->ciudad = $request->input('ciudad');
        $bodega->direccion = $request->input('direccion');
        $bodega->save();

        return response()->json(['mensaje' => true]);
    }

    public function eliminarBodega(Request $request){

        $id_bodega = $request->query('id_bodega');
    
        if (!Bodega::where('id_bodega', $id_bodega)->delete()) {
            return response()->json(['mensaje' => false], 404);
        }else{
            return response()->json(['mensaje' => true]);
        }
    
    }

    public function mostrarbodega(Request $request){

        $bodega = Bodega::where('id_bodega', '=', $request->input('id_bodega'))->first();

            $Bodega = [
                'id_bodega' => $bodega->id_bodega,
                'region' => $bodega->region,
                'ciudad' => $bodega->ciudad,
                'direccion' => $bodega->direccion,
            ];

        return response()->json($Bodega);
    }

    public function mostrarBodegas(Request $request){

        $bodegas = Bodega::all();
        $Bodegas = [];

        foreach ($bodegas as $bodega) {
            $datosBodega = [
                'id_bodega' => $bodega->id_bodega,
                'region' => $bodega->region,
                'ciudad' => $bodega->ciudad,
                'direccion' => $bodega->direccion,
            ];

            $Bodegas[] = $datosBodega;
        }
        return response()->json($Bodegas);
    }

    /*
    //CRUD DESPACHADOR
    */

    public function crearDespachador(Request $request){
        try{
            $despachador = Despachador::create(
                [
                    'empresa' => $request->input('empresa'),
                    'url' => $request->input('url'),
                ]
            );
            return response()->json(['mensaje' => true]);
        }catch(\Exception $e){
            return response()->json(['mensaje' => false], 400);
        }
    }

    public function actualizarDespachador(Request $request){

        $despachador = Despachador::where('id_despachador', '=', $request->input('id_despachador'))->first();

        if (!$despachador) {
            return response()->json(['mensaje' => false], 404);
        }
        $despachador->empresa = $request->input('empresa');
        $despachador->url = $request->input('url');
        $despachador->save();

        return response()->json(['mensaje' => true]);
    }

    public function eliminarDespachador(Request $request){

        $id_despachador = $request->query('id_despachador');
    
        if (!Despachador::where('id_despachador', $id_despachador)->delete()) {
            return response()->json(['mensaje' => false], 404);
        }else{
            return response()->json(['mensaje' => true]);
        }
    
    }

    public function mostrarDespachador(Request $request){

        $despachador = Despachador::where('id_despachador', '=', $request->input('id_despachador'))->first();

            $Despachador = [
                'id_despachador' => $despachador->id_despachador,
                'empresa' => $despachador->empresa,
                'url' => $despachador->url,
            ];

        return response()->json($Despachador);
    }

    public function mostrarDespachadores(Request $request){

        $despachadores = Despachador::all();
        $Despachadores = [];

        foreach ($despachadores as $despachador) {
            $datosDespachador = [
                'id_despachador' => $despachador->id_despachador,
                'empresa' => $despachador->empresa,
                'url' => $despachador->url,
            ];

            $Despachadores[] = $datosDespachador;
        }
        return response()->json($Despachadores);
    }


    /*
    //CRUD PEDIDO
    */

    public function crearPedido(Request $request){
        try{
            $pedido = Pedido::create(
                [
                    'id_despachador' => $request->input('id_despachador'),
                    'id_compra' => $request->input('id_compra'),
                    'email' => $request->input('email'),
                    'precio_pedido' => $request->input('precio_pedido'),
                    'estado' => $request->input('estado'),
                    'contenido_pedido' => $request->input('contenido_pedido'),
                ]
            );
            return response()->json(['mensaje' => true]);
        }catch(\Exception $e){
            return response()->json(['mensaje' => false], 400);
        }
    }

    public function actualizarPedido(Request $request){

        $pedido = Pedido::where('id_pedido', '=', $request->input('id_pedido'))->first();

        if (!$pedido) {
            return response()->json(['mensaje' => false], 404);
        }
        $pedido->id_despachador = $request->input('id_despachador');
        $pedido->id_compra = $request->input('id_compra');
        $pedido->email = $request->input('email');
        $pedido->precio_pedido = $request->input('precio_pedido');
        $pedido->estado = $request->input('estado');
        $pedido->contenido_pedido = $request->input('contenido_pedido');
        $pedido->save();

        return response()->json(['mensaje' => true]);
    }

    public function eliminarPedido(Request $request){

        $id_pedido = $request->query('id_pedido');
    
        if (!Pedido::where('id_pedido', $id_pedido)->delete()) {
            return response()->json(['mensaje' => false], 404);
        }else{
            return response()->json(['mensaje' => true]);
        }
    
    }

    public function mostrarPedido(Request $request){

        $pedido = Pedido::where('id_pedido', '=', $request->input('id_pedido'))->first();

            $Pedido = [
                'id_pedido' => $pedido->id_pedido,
                'id_despachador' => $pedido->id_despachador,
                'id_compra' => $pedido->id_compra,
                'email' => $pedido->email,
                'precio_pedido' => $pedido->precio_pedido,
                'estado' => $pedido->estado,
                'contenido_pedido' => $pedido->contenido_pedido,
            ];

        return response()->json($Pedido);
    }

    public function mostrarPedidos(Request $request){

        $pedidos = Pedido::all();
        $Pedidos = [];

        foreach ($pedidos as $pedido) {
            $datosPedido = [
                'id_pedido' => $pedido->id_pedido,
                'id_despachador' => $pedido->id_despachador,
                'id_compra' => $pedido->id_compra,
                'email' => $pedido->email,
                'precio_pedido' => $pedido->precio_pedido,
                'estado' => $pedido->estado,
                'contenido_pedido' => $pedido->contenido_pedido,
            ];

            $Pedidos[] = $datosPedido;
        }
        return response()->json($Pedidos);
    }

}


