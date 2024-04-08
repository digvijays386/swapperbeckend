<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data =[
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => '$2a$12$tSAELZnqYUw.RofVRrfWveawEHWwP5bUnsAx4ihn1gitkoCl5xUum'
        ];
        DB::table('users')->insert($data);
    }
}
