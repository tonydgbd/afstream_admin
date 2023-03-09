<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CurrencySeeder::class,
            LanguageSeeder::class,
            SettingSeeder::class,
            AllStates::class,
            AllCountries::class,
            AllCities::class
        ]);
    }
}