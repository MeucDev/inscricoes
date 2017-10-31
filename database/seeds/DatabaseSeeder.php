<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CBSeeder::class);
        $this->call(MeucSeeder::class);
        $this->call(AjustandoInscricoes::class);
    }
}
