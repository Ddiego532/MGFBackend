<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Mail\CorreoMailable;
use Illuminate\Support\Facades\Mail;

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
    public function handle()
    {

        $resultados = DB::table('subasta as S')
            ->select('S.id_subasta as subasta', 'S.precio_inicial as precio', 'U.email as email')
            ->join('subastador as SD', 'S.id_subasta', '=', 'SD.id_subasta')
            ->join('usuario as U', 'SD.email', '=', 'U.email')
            ->where('S.fecha_cierre', '<=', DB::raw('CURRENT_TIMESTAMP'))
            ->where('abierto', true) // Agrega la condición basada en un valor booleano aquí
            ->get();
        foreach($resultados as $resultado){
            Mail::to($resultado->email)->send(new CorreoMailable);
        }
        DB::table('subasta')
        ->whereIn('id_subasta', $resultados->pluck('subasta')->all())
        ->update(['abierto' => false]);
    }
}
