<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'mobile' => '7777777777',
            'password' => '$2y$10$i9HyIv.IHVmoSpAiKSZdyuvR9s1UxcAcVwWUEBCcWQ4I4euVDHG0e',
            'role' => 1,
            'status' => 1,
            'created_at' => date('Y-m-d h:i:s'),
            'updated_at' => date('Y-m-d h:i:s')
        ]);
    }
}
