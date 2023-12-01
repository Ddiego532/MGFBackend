<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Events\SubastaUpdated;
use App\Models\Articulo;
use App\Models\Carrito;
use App\Models\Compra;
use App\Models\Contenido;
use App\Models\Subasta;
use App\Models\Subastador;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TiendaController extends Controller{

    /*
    //CRUD ARTICULO
    */

    public function crearArticulo(Request $request){
        $usuario = Auth::user();
        try {
            $imagen = null;
            if ($request->hasFile('file')) {
                $imagen = $request->file('file');
                $nombreImagen = uniqid() . '.' . $imagen->getClientOriginalExtension();
                $imagen->storeAs('public/images', $nombreImagen);
                $articulo = Articulo::create([
                    'email' => $usuario->email,
                    'precio' => $request->input('precio'),
                    'tipo_articulo' => $request->input('tipo_articulo'),
                    'marca' => $request->input('marca'),
                    'descripcion' => $request->input('descripcion'),
                    'nombre_articulo' => $request->input('nombre_articulo'),
                    'cantidad' => $request->input('cantidad'),
                    'imagen_path' => 'images/' . $nombreImagen, 
                ]);
                return response()->json(['mensaje' => true]);
            }else{
                return response()->json(['mensaje' => false], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['mensaje' => $e], 400);
        }
    }
    
    


    public function actualizarArticulo(Request $request){

        $articulo = Articulo::where('id_articulo', '=', $request->input('id_articulo'))->first();

        if (!$articulo) {
            return response()->json(['mensaje' => false], 404);
        }
        $articulo->email = $request->input('email');
        $articulo->marca = $request->input('marca');
        $articulo->precio = $request->input('precio');
        $articulo->tipo_articulo = $request->input('tipo_articulo');
        $articulo->descripcion = $request->input('descripcion');
        $articulo->nombre_articulo = $request->input('nombre_articulo');
        $articulo->save();

        return response()->json(['mensaje' => true]);
    }

    public function eliminarArticulo(Request $request){

        $id_articulo = $request->query('id_articulo');
    
        if (!Articulo::where('id_articulo', $id_articulo)->delete()) {
            return response()->json(['mensaje' => false], 404);
        }else{
            return response()->json(['mensaje' => true]);
        }
    
    }

    public function mostrarArticulo($id_articulo ,Request $request){

        $articulo = Articulo::where('id_articulo', '=', $id_articulo)->first();

            $Articulo = [
                'id_articulo' => $articulo->id_articulo,
                'id_subasta' => $articulo->id_subasta,
                'email' => $articulo->email,
                'marca' => $articulo->marca,
                'precio' => $articulo->precio,
                'tipo_articulo' => $articulo->tipo_articulo,
                'descripcion' => $articulo->descripcion,
                'nombre_articulo' => $articulo->nombre_articulo,
                'cantidad' => $articulo->cantidad,
                'en_subasta' => $articulo->en_subasta,
                'imagen_path' => $articulo->imagen_path
            ];

        return response()->json($Articulo);
    }

    public function mostrarArticulos(Request $request){

        $articulos = Articulo::all();
        $Articulos = [];

        foreach ($articulos as $articulo) {
            $datosarticulo = [
                'id_articulo' => $articulo->id_articulo,
                'id_subasta' => $articulo->id_subasta,
                'email' => $articulo->email,
                'precio' => $articulo->precio,
                'tipo_articulo' => $articulo->tipo_articulo,
                'descripcion' => $articulo->descripcion,
                'nombre_articulo' => $articulo->nombre_articulo,
                'cantidad' => $articulo->cantidad,
                'en_subasta' => $articulo->en_subasta,
                'imagen_path' => $articulo->imagen_path
            ];

            $Articulos[] = $datosarticulo;
        }
        return response()->json($Articulos);
    }

    /*
    //CRUD CARRITO
    */

    public function mostrarCarrito( Request $request){
        $usuario = Auth::user();
        $carrito = Carrito::where('email_carro', '=', $usuario->email)->where('en_uso', '=', 1)->first();
        $Carrito = $carrito->mostrarCarrito($carrito->id_carrito);
        return response()->json($Carrito);
    }

    public function agregarCarrito($id_articulo,Request $request){
        try{
            $usuario = Auth::user();
            $carrito = Carrito::where('email_carro', '=', $usuario->email)->where('en_uso', '=', 1)->first();
            $cont = Contenido::firstOrCreate([
                    'id_carrito' => $carrito->id_carrito,
                    'id_articulo' => $id_articulo
                ],[]
            );
            if ($cont->wasRecentlyCreated) {
                $carrito->items = $carrito->items + 1;
                $carrito->update();
            }
            return response()->json(['mensaje' => true]);
        }catch(\Exception $e){
            return response()->json(['mensaje' => false], 400);
        }
    }

    public function removerCarrito($id_articulo,Request $request){
        $usuario = Auth::user();
        $carrito = Carrito::where('email_carro', '=', $usuario->email)->where('en_uso', '=', 1)->first();
        if (!Contenido::where('id_carrito', $carrito->id_carrito)->where('id_articulo', $id_articulo)->delete()) {
            return response()->json(['mensaje' => false], 404);
        }else{
            $carrito->items = $carrito->items - 1;
            $carrito->update();
            return response()->json(['mensaje' => true]);
        }
    }

    /*
    //CRUD SUBASTA
    */

    public function subasta(){
        $js=[];
        $resultados = DB::table('subasta as S')
            ->select('S.id_subasta as subasta', 'S.precio_inicial as precio', 'U.email as email')
            ->join('subastador as SD', 'S.id_subasta', '=', 'SD.id_subasta')
            ->join('usuario as U', 'SD.email', '=', 'U.email')
            ->where('S.fecha_cierre', '>=', DB::raw('CURRENT_TIMESTAMP'))
            ->get();
        foreach($resultados as $resultado){
            $mensaje = 'se ha enviado un mensaje a '.$resultado->email;
            $res = [
                'mensaje' => $mensaje
            ];
            $js[] = $res;
        }

            
        return response()->json($js);
    }


    public function crearSubasta($id_articulo, Request $request){
        try{
            $articulo = Articulo::where('id_articulo', '=', $id_articulo)->first();
            $precio = ($articulo->precio/2)+($articulo->precio*0.15);
            $subasta = Subasta::firstOrCreate(
                ['id_articulo' => $id_articulo],
                [
                    'precio_venta' => $precio,
                    'precio_inicial' => $precio,
                    'fecha_apertura' => date('Y-m-d H:i:s'),
                    'fecha_cierre' => date('Y-m-d H:i:s', strtotime('+1 day')),
                ]
            );
            $articulo->precio = $precio;
            $articulo->id_subasta = $subasta->id_subasta;
            $articulo->en_subasta = 1;
            $articulo->save();

            return response()->json(['mensaje' => true, 'id_subasta' => $subasta->id_subasta]);
        }catch(\Exception $e){
            return response()->json(['mensaje' => false], 400);
        }

    }

    public function actualizarSubasta(Request $request){

        $subasta = Subasta::where('id_subasta', '=', $request->input('id_subasta'))->first();

        if (!$subasta) {
            return response()->json(['mensaje' => false], 404);
        }
        $subasta->id_articulo = $request->input('id_articulo');
        $subasta->puja = $request->input('puja');
        $subasta->precio_inicial = $request->input('precio_inicial');
        $subasta->cantidad = $request->input('cantidad');
        $subasta->save();

        return response()->json(['mensaje' => true]);
    }

    public function eliminarSubasta(Request $request){

        $id_subasta = $request->query('id_subasta');
    
        if (!Subasta::where('id_subasta', $id_subasta)->delete()) {
            return response()->json(['mensaje' => false], 404);
        }else{
            return response()->json(['mensaje' => true]);
        }
    
    }

    public function mostrarDueno($dueno, Request $request){
        $usuario = Auth::user();
        if($usuario->email==$dueno){
            return response()->json(['dueno' => 1]);
        }else{
            return response()->json(['dueno' => 0]);
        }
    }

    public function mostrarSubasta($id_subasta, Request $request){
        $subasta = Subasta::where('id_subasta', '=', $id_subasta)->first();
        if($subasta){
            $Subasta = $subasta->mostrarSubasta($subasta->id_subasta);
            return response()->json($Subasta);

        }else{
            return response()->json(['mensaje' => false],404);
        }
    }

    public function mostrarSubastas(Request $request){

        $subastas = DB::table('subasta AS S')
        ->join('articulo AS A', 'S.id_subasta', '=', 'A.id_subasta')
        ->select('S.*', 'A.*')
        ->orderBy('A.en_subasta', 'asc')
        ->get();

        return response()->json($subastas);
    }

    public function cerrarSubasta($id_subasta ,Request $request){
        $subasta = Subasta::where('id_subasta', '=', $id_subasta)->first();
        $subasta->fecha_cierre = date('Y-m-d H:i:s');
        $subasta->save();
        $Subasta = $subasta->mostrarSubasta($subasta->id_subasta);
        return response()->json($Subasta);
    }

    public function agregarSubastador(Request $request){
        $usuario = Auth::user();
        $id_subasta = $request->input('id_subasta');
        $puja = $request->input('puja');
        $subasta = Subasta::where('id_subasta', '=', $id_subasta)->first();

        try{
            if($subasta->precio_venta < $puja){
                $subastador = Subastador::create(
                    [
                        'id_subasta' => $id_subasta,
                        'email' => $usuario->email,
                        'puja' => $puja,
                        'fecha_puja' => date('Y-m-d H:i:s')
                    ]
                );
                if($subastador){
                    $subasta->precio_venta = $puja;
                    $subasta->save();
                    return response()->json(['mensaje' => true]);
                }else{
                    return response()->json(['mensaje' => 'puja no actualizada'],404);
                }
            }else{
                return response()->json(['mensaje' => 'puja no correspondiente'],404);
            }
        }catch(\Exception $e){
            return response()->json(['mensaje' => $e], 400);
        }
    }

    public function mostrarCompras(Request $request){
        //try{
            $usuario = Auth::user();
            $c = Compra::where('email_titular','=',$usuario->email)->first();
            $Compras = $c->mostrarCompra($usuario->email);
            return response()->json($Compras);
        //}catch(\Exception $e){
        //    return response()->json(['mensaje' => $e], 400);
        //}
    }
}
