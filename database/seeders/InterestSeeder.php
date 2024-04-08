<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterestSeeder extends Seeder
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
               'name' =>  'Other',
               'icon' => '1633450539.jpg'
            ],
            [
                'name' =>  'Sports',
                'icon' => '1633450539.jpg'
            ],
            [
                'name' =>  'Sleeping',
                'icon' => '1633450539.jpg'
             ]

        ];
        DB::table('intrests')->insert($data);
    }
}
