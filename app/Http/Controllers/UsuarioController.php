<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\Usuario;
use App\Models\Administrador;
use App\Models\Carrito;
use App\Models\Contendido;
use App\Mail\AceptoSubastaMailable;
use App\Mail\RechazoSubastaMailable;



class UsuarioController extends Controller{


    /*
    //CRUD USUARIO
    */

    private function compararObjetos($a, $b) {
        $atributo = 'puja';
    
        if ($a->$atributo == $b->$atributo) {
            return 0;
        }
        return ($a->$atributo < $b->$atributo) ? 1 : -1;
    }

    private function agrupar($array){
        $atributo = 'id_subasta';
        $grupos = [];
        foreach ($array as $objeto) {
            $numero = $objeto->$atributo;
            if (!isset($grupos[$numero])) {
                $grupos[$numero] = [];
            }
            $grupos[$numero][] = $objeto;
        }
        return $grupos;
    }

    public function pruebaCorreo(){
        $id_subasta;
        $resultados = DB::table('subasta as S')
        ->select('S.id_subasta', 'SD.puja', 'U.email')
        ->join('subastador as SD', 'S.id_subasta', '=', 'SD.id_subasta')
        ->join('usuario as U', 'SD.email', '=', 'U.email')
        ->where('S.fecha_cierre', '<=', DB::raw('CURRENT_TIMESTAMP'))
        ->where('abierto', true) 
        ->get();
        $groups=[];
        $resultados = $this->agrupar($resultados);
        foreach($resultados as $res){
            usort($res,[$this, 'compararObjetos']);
            $groups[]=$res;
        }
        foreach($groups as $result){
            $acreditado = false;
            foreach($result as $resultado){
                $usuario = DB::table('usuario as U')->select('U.*')->where('email','=', $resultado->email)->first();
                $id_subasta = $resultado->id_subasta;
                if($usuario->cartera>=$resultado->puja && !$acreditado){
                    $usuario->cartera =$usuario->cartera - $resultado->puja;
                    $acreditado=true;
                    Mail::to($resultado->email)->send(new AceptoSubastaMailable($usuario,$resultado));
                }else{
                    Mail::to($resultado->email)->send(new RechazoSubastaMailable($usuario,$resultado));
                }
            }
            DB::table('subasta')
            ->where('id_subasta', $id_subasta)
            ->update(['abierto' => false]);
        }
        return response()->json(true);
    }

    public function login(Request $request){
        if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json(["mensaje" => false], 401);
        }
        $usuario = Usuario::where('email',$request->input('email'))->firstOrFail();

        $token = $usuario->createToken('auth_token')->plainTextToken;
    
        return response()->json(["mensaje" => true, "token" => $token, "usuario" => $usuario]);
        
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return response()->json(["mensaje" => true]);
    }

    
    public function crearUsuario(Request $request){

        $email = $request->input('email');

        $usuario = Usuario::firstOrCreate(
            ['email' => $email],
            [
                'nombre' => $request->input('nombre'),
                'apellido' => $request->input('apellido'),
                'sexo' => $request->input('sexo'),
                'fecha' => date('Y-m-d H:i:s'),
                'password' => bcrypt($request->input('password')),
                'telefono' => $request->input('telefono'),
                'ciudad' => $request->input('ciudad'),
                'direccion' => $request->input('direccion'),
                'tipo_usuario' => $request->input('tipo_usuario'),
            ]
        );

        if (!$usuario->wasRecentlyCreated) {
            return response()->json(["mensaje" => false], 400);
        }

        $carrito = Carrito::create([
            'email_carro' => $email
        ]);

        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json(["mensaje" => true, "token" => $token, "tipo_token" => "Bearer"]);
    }

    public function actualizarUsuario(Request $request){

        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json(["mensaje" => false], 404);
        }
        $usuario->nombre = $request->input('nombre');
        $usuario->apellido = $request->input('apellido');
        $usuario->telefono = $request->input('telefono');
        $usuario->fecha = $request->input('fecha');
        $usuario->ciudad = $request->input('ciudad');
        $usuario->direccion = $request->input('direccion');
        $usuario->save();

        return response()->json(["mensaje" => true]);
    }

    public function eliminarUsuario(Request $request){

        $email = $request->query('email');
    
        if (!Usuario::where('email', $email)->delete()) {
            return response()->json(["mensaje" => false], 404);
        }else{
            return response()->json(["mensaje" => true]);
        }
    
    }

    public function verCartera(Request $request){
        $usuario = Auth::user();
        return response()->json(["cartera" => $usuario->cartera]);
    }

    public function mostrarUsuario($email, Request $request){
        $usuario = new Usuario;
        $Usuario = $usuario->mostrarUsuario($email);
        return response()->json($Usuario);
    }

    public function mostrarPerfil(Request $request){
        $usuario = Auth::user();
        $Usuario = $usuario->mostrarUsuario($usuario->email);
        return response()->json($Usuario);
    }

    public function mostrarUsuarios(Request $request){
        $usuarios = Usuario::all();
        $Usuarios = [];
        foreach ($usuarios as $usuario) {
            $Usuarios[] = $usuario->mostrarUsuario($usuario->email);
        }
        return response()->json($Usuarios);
    }



    /*
    //CRUD ADMINISTRADOR
    */




    public function crearAdministrador(Request $request){

        $email = $request->input('email');

        $administrador = Administrador::firstOrCreate(
            ['email' => $email],
            [
                'nombre' => $request->input('nombre'),
                'apellido' => $request->input('apellido'),
                'sexo' => $request->input('sexo'),
                'fecha' => date('Y-m-d H:i:s'),
                'password' => bcrypt($request->input('password')),
                'telefono' => $request->input('telefono'),
                'ciudad' => $request->input('ciudad'),
                'direccion' => $request->input('direccion'),
                'api_token' => Str::random(80),
                'tipo_administrador' => $request->input('tipo_administrador'),
                'cargo' => $request->input('cargo'),
                'fecha_de_inicio' => $request->input('fecha_de_inicio'),
                'fecha_de_termino' => $request->input('fecha_de_termino'),
                'salario' => $request->input('salario'),
            ]
        );

        if (!$administrador->wasRecentlyCreated) {
            return response()->json(["mensaje" => false], 400);
        }

        return response()->json(["mensaje" => true]);
    }

    public function actualizarAdministrador(Request $request){

        $administrador = Administrador::where('email', '=', $request->input('email'))->first();

        if (!$administrador) {
            return response()->json(["mensaje" => false], 404);
        }
        $administrador->nombre = $request->input('nombre');
        $administrador->apellido = $request->input('apellido');
        $administrador->sexo = $request->input('sexo');
        $administrador->telefono = $request->input('telefono');
        $administrador->ciudad = $request->input('ciudad');
        $administrador->direccion = $request->input('direccion');
        $administrador->tipo_administrador = $request->input('tipo_administrador');
        $administrador->cargo = $request->input('cargo');
        $administrador->fecha_de_inicio = $request->input('fecha_de_inicio');
        $administrador->fecha_de_termino = $request->input('fecha_de_termino');
        $administrador->salario = $request->input('salario');
        $administrador->save();

        return response()->json(["mensaje" => true]);
    }

    public function eliminarAdministrador(Request $request){

        $email = $request->query('email');
    
        if (!Administrador::where('email', $email)->delete()) {
            return response()->json(["mensaje" => false], 404);
        }else{
            return response()->json(["mensaje" => true]);
        }
    
    }

    public function mostrarAdministrador($email, Request $request){

        $administrador = new Administrador;
        $Administrador = $administrador->mostrarAdministrador($email);
        return response()->json($Administrador);
    }

    public function mostrarAdministradores(Request $request){

        $administradores = Administrador::all();
        $Administradores = [];

        foreach ($administradores as $administrador) {
            $Administradores[] = $administrador->mostrarAdministrador($administrador->email);
        }
        return response()->json($Administradores);
    }

}
