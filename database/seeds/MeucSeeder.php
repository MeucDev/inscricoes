<?php

use Illuminate\Database\Seeder;

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
            'name'=>trans('Inscrições'),
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

