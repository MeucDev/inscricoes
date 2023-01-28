<?php

use Illuminate\Database\Seeder;

class HistoricoPagamentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Please wait, updating the data...');        
        
        $data = [
            [
                'created_at'=>date('Y-m-d H:i:s'),
                'name'=>trans('Histórico Pagamentos'),
                'icon'=>'fa fa-money',
                'path'=>'historico_pagamentos',
                'table_name'=>'historico_pagamentos',
                'controller'=>'AdminHistoricoPagamentos',
                'is_protected'=>0,                                
                'is_active'=>0
            ]
        ];

        DB::table('cms_moduls')->insert($data);

        $this->command->info('Seeding complete');   
    }
}
