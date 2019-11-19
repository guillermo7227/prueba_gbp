<?php

use Illuminate\Database\Seeder;
use App\Bodega;
use App\Producto;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        factory(Bodega::class)->create(['nombre' => 'Centro']);
        factory(Bodega::class)->create(['nombre' => 'Oriente']);
        factory(Bodega::class)->create(['nombre' => 'Occidente']);
        factory(Bodega::class)->create(['nombre' => 'Sur']);

        factory(Producto::class, 25)->create();
    }
}
