<?php

use App\User;
use App\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('123123')
        ]);
        User::create([
            'name' => 'User',
            'email' => 'user@test.com',
            'password' => Hash::make('123123')
        ]);
        Category::create([
            'tag' => 'Electronica'
        ]);
        Category::create([
            'tag' => 'Cars'
        ]);
        Category::create([
            'tag' => 'Other'
        ]);
        Category::create([
            'tag' => 'smmth'
        ]);
        Category::create([
            'tag' => 'another'
        ]);
    }
}
