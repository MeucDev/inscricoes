<?php

use Illuminate\Database\Seeder;
use App\Evento;
use App\Inscricao;


class MeucSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Please wait updating the data...');        
        
        $this->call('Meuc_modulsSeeder');                         
        
        $this->command->info('Updating the data completed !');

        $this->call('Meuc_eventoSeeder');                         
    }
}


class Meuc_modulsSeeder extends Seeder {

    public function run()
    {        

        /* 
            1 = Public
            2 = Setting        
        */

        $data = [
        [            
            'created_at'=>date('Y-m-d H:i:s'),
            'name'=>trans('Pessoas'),
            'icon'=>'fa fa-users',
            'path'=>'pessoas',
            'table_name'=>'pessoas',
            'controller'=>'AdminPessoasController',
            'is_protected'=>0,                                
            'is_active'=>1
        ],
        [
            'created_at'=>date('Y-m-d H:i:s'),
            'name'=>trans('Eventos'),
            'icon'=>'fa fa-flag',
            'path'=>'eventos',
            'table_name'=>'eventos',
            'controller'=>'AdminEventosController',
            'is_protected'=>0,                                
            'is_active'=>1
        ],
        [
            'created_at'=>date('Y-m-d H:i:s'),
            'name'=>trans('Valores'),
            'icon'=>'fa fa-dollar',
            'path'=>'valores',
            'table_name'=>'valores',
            'controller'=>'AdminValoresController',
            'is_protected'=>0,                                
            'is_active'=>1
        ],
        [
            'created_at'=>date('Y-m-d H:i:s'),
            'name'=>trans('Incrições'),
            'icon'=>'fa fa-list',
            'path'=>'inscricoes',
            'table_name'=>'inscricoes',
            'controller'=>'AdminInscricoesController',
            'is_protected'=>0,                                
            'is_active'=>1
        ],        
        ];

        foreach($data as $k=>$d) {
            if(DB::table('cms_moduls')->where('name',$d['name'])->count()) {
                unset($data[$k]);
            }
        }

        DB::table('cms_moduls')->insert($data);
    }
}

class Meuc_eventoSeeder extends Seeder {
    
        public function run()
        {      
            $evento2016 = App\Evento::where("nome", "Congresso de famílias 2016")->first();
            if (!$evento2016)
                $evento2016 = new App\Evento;

            $evento2016->nome = "Congresso de famílias 2016";
            $date = new DateTime();
            $date->setDate(2016, 1, 1);
            $evento2016->data_inicio = $date;
            $evento2016->data_fim = $date;
            $evento2016->save();
    
            $evento2017 = App\Evento::where("nome", "Congresso de famílias 2017")->first();
            if (!$evento2017)
                $evento2017 = new App\Evento;
            $evento2017->nome = "Congresso de famílias 2017";
            $date = new DateTime();
            $date->setDate(2017, 1, 1);
            $evento2017->data_inicio = $date;
            $evento2017->data_fim = $date;
            $evento2017->save();


            $incricoes = App\Inscricao::where('ano', 2016)->get();
            foreach ($incricoes as $incricao){
                $incricao->evento_id = $evento2016->id;
                $incricao->save();
            }

            $incricoes = App\Inscricao::where('ano', 2017)->get();
            foreach ($incricoes as $incricao){
                $incricao->evento_id = $evento2017->id;
                $incricao->save();
            }
        }
    }
    