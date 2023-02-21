<?php

use Illuminate\Database\Seeder;

class EquipesSeeder extends Seeder
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
                'name'=>trans('Equipes'),
                'icon'=>'fa fa-users',
                'path'=>'equipes',
                'table_name'=>'equipes',
                'controller'=>'AdminEquipesController',
                'is_protected'=>0,                                
                'is_active'=>0
            ]
        ];

        DB::table('cms_moduls')->insert($data);

        $this->command->info('Seeding complete');   
    }
}
