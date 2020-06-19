<?php

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
        //
        $users = [
            [
                'name' => 'テスト太郎',
                'email' => 'test@test.com',
                'icon' => 'https://aitoke.s3-ap-northeast-1.amazonaws.com/icon/default/default_icon.png',
                'password' => 'testpassword'
            ]
        ];
        DB::table('users')->insert($users);
    }
}
