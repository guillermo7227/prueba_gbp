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
        factory(Bodega::class, 5)->create();
        factory(Producto::class, 30)->create();
    }
}
