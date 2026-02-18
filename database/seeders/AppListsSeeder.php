<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppListsSeeder extends Seeder
{
    public function run()
    {
        DB::table('app_lists')->insert([
            [
                'app_slug' => 'pepi-portal',
                'app_name' => 'Pepi Portal',
                'logo' => 'assets/pepi-portal.webp',
            ],
            [
                'app_slug' => 'pepi-plus',
                'app_name' => 'Pepi Plus',
                'logo' => 'assets/pepi-plus-logo.webp',
            ],
        ]);
    }
}