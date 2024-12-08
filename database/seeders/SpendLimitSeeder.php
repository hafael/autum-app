<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class SpendLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $limits = [
            [
                'code' => 'DEMO_TIER',
                'price' => 0,
                'requests' => 20,
                'objects' => 5,
                'teams' => 1,
                'team_members' => 5,
                'trial_period_days' => 0,
            ],
            [
                'code' => 'TIER_1',
                'price' => 1000,
                'requests' => 1000,
                'objects' => 100,
                'teams' => 3,
                'team_members' => 5,
                'trial_period_days' => 15,
            ],
        ];

        DB::table('spend_limits')->insert($limits);
    }
}
