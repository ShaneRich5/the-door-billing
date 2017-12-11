<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'Shane',
            'last_name' => 'Richards',
            'phone' => '8726369',
            'email' => 'shane.richards121@gmail.com',
            'password' => 'password'
        ]);
    }
}
