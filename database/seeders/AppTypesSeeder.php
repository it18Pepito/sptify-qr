<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppTypesSeeder extends Seeder
{
    public function run()
    {
        DB::table('app_types')->insert([
            // Pepi Portal
            [
                'app_list_id' => 1,
                'store_type' => 'play_store',
                'url' => 'https://play.google.com/store/apps/details?id=com.pepito.app',
            ],
            [
                'app_list_id' => 1,
                'store_type' => 'app_store',
                'url' => 'https://apps.apple.com/id/app/pepi-portal/id6757992868',
            ],

            // Pepi Plus
            [
                'app_list_id' => 2,
                'store_type' => 'play_store',
                'url' => 'https://play.google.com/store/apps/details?id=id.loyal.pepito&pcampaignid=web_share',
            ],
            [
                'app_list_id' => 2,
                'store_type' => 'app_store',
                'url' => 'https://apps.apple.com/id/app/pepi-plus/id6752885751',
            ],
        ]);
    }
}
