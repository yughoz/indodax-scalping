<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Carbon\Carbon;

class ServiceMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

     public function run()
    {
        DB::table('service_menus')->insert([
            'title'  => "Beli Material",
            'image_path' => "storage/Images/services_menus/ic_renovasi.svg",
            'visible' => 1,
            'url'  => "material",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('service_menus')->insert([
            'title'  => "Layanan Kontruksi",
            'image_path' => "storage/Images/services_menus/ic_furnish.svg",
            'visible' => 1,
            'url'  => "construction",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
