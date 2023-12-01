<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Carrito;
use App\Models\Contenido;
use App\Models\Compra;

use App\Mail\AceptoSubastaMailable;
use App\Mail\RechazoSubastaMailable;

class SubastaCommand extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subasta:view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'verifica la fecha de cierre de una subasta';

    /**
     * Execute the console command.
     *
     * @return int
     */

    public function crearCompra($usuario,$total,$id_articulo){
        $carrito = Carrito::create([
            'email_carro' => $usuario->email
        ]);
        $cont = Contenido::firstOrCreate([
            'id_carrito' => $carrito->id_carrito,
            'id_articulo' => $id_articulo,

        ],[]);
        $carrito->items = 1;
        $carrito->en_uso = 0;
        $carrito->update();
        $compra = new Compra();
        $compra->email_titular = $usuario->email;
        $compra->total = $total; 
        $compra->id_sesion = '123456'; 
        $compra->id_carrito = $carrito->id_carrito; 
        $compra->fecha_inicio = date("Y-m-d H:i:s"); 
        $compra->fecha_confirmacion = date("Y-m-d H:i:s"); 
        $compra->status = 1; 
        $compra->save();

        
        DB::table('articulo')
        ->where('id_articulo', $id_articulo)
        ->update(['en_subasta' => 2]);
    }

    public function handle(){
        $subastas = DB::table('subasta as S')
                ->select('S.id_subasta','S.id_articulo')
                ->where('abierto',true)
                ->where('fecha_cierre','<',date('Y-m-d H:i:s'))
                ->orderByDesc('S.id_subasta')->get();
        foreach($subastas as $subasta){
            $acreditado = false;
            $subastadores = DB::table('subastador as t1')
            ->join(DB::raw('(SELECT email, MAX(puja) AS max_puja FROM subastador WHERE id_subasta = '.$subasta->id_subasta.' GROUP BY email) as t2'), function ($join) {
                $join->on('t1.email', '=', 't2.email')->on('t1.puja', '=', 't2.max_puja');
            })
            ->select('t1.*')
            ->orderByDesc('t1.puja')
            ->get();
            foreach($subastadores as $subastador){
                $usuario = DB::table('usuario as U')->select('U.*')->where('email','=', $subastador->email)->first();
                $id_subasta = $subastador->id_subasta;
                if($usuario->cartera>=$subastador->puja && !$acreditado){
                    DB::table('usuario')
                    ->where('email', $subastador->email)
                    ->update(['cartera' => $usuario->cartera - $subastador->puja]);
                    $acreditado=true;
                    Mail::to($subastador->email)->send(new AceptoSubastaMailable($usuario,$subastador));
                    $this->crearCompra($usuario,$subastador->puja,$subasta->id_articulo);
                }else{
                    Mail::to($subastador->email)->send(new RechazoSubastaMailable($usuario,$subastador));
                }
            }
            DB::table('subasta')
            ->where('id_subasta', $id_subasta)
            ->update(['abierto' => false]);
        }
        return response()->json(true);
            
    }
}
