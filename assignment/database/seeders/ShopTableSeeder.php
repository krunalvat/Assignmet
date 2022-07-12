<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ShopTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('shops')->truncate();
        Schema::enableForeignKeyConstraints();
        Shop::factory()->count(500)->create();
     
    }
}
