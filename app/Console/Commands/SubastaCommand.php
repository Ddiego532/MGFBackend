<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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

    public function compararObjetos($a, $b) {
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
            $numero = $objeto->getNumero();
            if (!isset($grupos[$numero])) {
                $grupos[$numero] = [];
            }
            $grupos[$numero][] = $objeto;
        }
        return $grupos;
    }
    
    public function handle(){
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
    }
}
