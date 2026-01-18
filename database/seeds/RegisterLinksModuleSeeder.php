<?php

use Illuminate\Database\Seeder;

class RegisterLinksModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Links de Inscrição',
                'icon' => 'fa fa-link',
                'path' => 'links_inscricao',
                'table_name' => 'link_inscricoes',
                'controller' => 'AdminLinksInscricaoController',
                'is_protected' => 0,
                'is_active' => 1
            ]
        ];

        foreach($data as $k=>$d) {
            if(DB::table('cms_moduls')->where('path', $d['path'])->count()) {
                $this->command->info('Módulo "Links de Inscrição" já está registrado!');
                unset($data[$k]);
            }
        }

        if(count($data) > 0) {
            DB::table('cms_moduls')->insert($data);
            $this->command->info('Módulo "Links de Inscrição" registrado com sucesso!');
        }
    }
}