<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product')->insert([
            'name'          => "Semen Gresik",
            'image_path_1'  => "storage/Images/product/2_ProductMaterial_img_gresik40.jpg",
            'show'          => 1,
            'description'       => '<p class="text">
                            <p class="text">
                                - Kuat tekan awal lebih tinggi<br>
                                - Lebih cepat kering<br>
                                - Tahan retak<br>
                                - Lebih mudah digunakan<br>
                                - Hasil lebih halus<br>
                                - Menggunakan bahan baku terpilih<br>
                                - Produk ramah lingkungan<br>
                                -Produk Nasional<br>
                                -Kualitas Premium dan Merek Terkenal<br>
                                <br></p>
                            <p class="text-dark font-weight-bold mb-2">Rincian Produk</p>
                            <p class="text">
                                - Tipe : PCC/Portland Composite Cement - SNI : 7064 - 2014<br>
                                - Berat Bersih : 40kg<br>
                                <br></p>
                            <p class="text-dark font-weight-bold mb-2">Aplikasi Produk</p>
                            <p class="text">
                                - Konstruksi Umum : Pekerjaan Beton, Pasangan Bata, Plesteran, Acian, Selokan, & Pagar dinding<br>
                                - Bangunan khusus : Beton Pracetak, Beton Pratekan, Panel beton, Bata beton/ paving block<br>
                                <br></p>
                            <p class="text-dark font-weight-bold mb-2">Cara Aplikasi</p>
                            <p class="text">
                                - Adukan Beton = 1 : 2 : 3 ( semen : pasir : batu / kerikil)<br>- Adukan Mortar = 1 : 7 ( semen : pasir )</p>
                        </p>',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('product')->insert([
            'name'  => "Dynamix Serba Guna",
            'image_path_1' => "storage/Images/product/2_ProductMaterial_img_gresik40.jpg",
            'show' => 1,
            'description'  => '<p class="text">
            <p class="text">
                 Semen Dynamix Serba Guna adalah produk inovatif dari SIG dengan Micro Filler Particle, butiran mineral mikro yang halus, mampu mengisi rongga dengan sempurna, memberikan kekuatan dari dalam, sehingga hasil akhir kuat dan permukaan halus.<br>
                <br>
                 Kekuatan yang melindungi dari dalam tersebut membuat bangunan menjadi lebih tahan lama dan minim renovasi, yang pada akhirnya akan menghemat biaya. Selain itu, butiran mikro yang sangat halus ini membuat adukan semen jadi lebih pulen dan mudah dikerjakan, hasilnya pemakaian menjadi lebih hemat, kerja tukang bangunan jadi lebih mudah dan cepat selesai.<br>
                <br></p>
            <p class="text-dark font-weight-bold mb-2">Keunggulan Produk</p>
            <p class="text">
                - Mudah dikerjakan<br>
                 - Cepat kering<br>
                 - Hasil permukaan lebih halus<br>
                 - Memberikan kuat tekan tinggi sehingga hasil aplikasi lebih tahan lama<br>
                 - Butiran Micro Filler Particle memiliki kemampuan mengisi rongga mikro yang mengikat kuat sehingga hasil aplikasi semen lebih rapat dan minim retak rambut<br>
                 - Lebih pulen<br>
                <br></p>
            <p class="text-dark font-weight-bold mb-2">Rincian Produk</p>
            <p class="text">
                - Tipe : PCC/Portland Composite Cement<br>
                 - SNI : 7064 - 2014<br>
                 - Berat Bersih : 50kg<br>
                <br></p>
            <p class="text-dark font-weight-bold mb-2">Aplikasi Produk</p>
            <p class="text">
                - Konstruksi Umum : Pekerjaan Beton, Pasangan Bata, Plesteran, Acian, Selokan, & Pagar dinding<br>
                 - Bangunan khusus : Beton Pracetak, Beton Pratekan, Panel beton, Bata beton/ paving block<br>
                <br></p>
            <p class="text-dark font-weight-bold mb-2">Cara Aplikasi</p>
            <p class="text">
                - Adukan Beton = 1 : 2 : 3 ( semen : pasir : batu / kerikil)<br>- Adukan Mortar = 1 : 7 ( semen : pasir )</p>
        </p>',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
