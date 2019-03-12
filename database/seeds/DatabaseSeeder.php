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
        // $this->call(UsersTableSeeder::class);
        factory(App\User::class, 1)->create()->each(function ($user) {
            $user->save();
        });

        factory(App\Service::class, 50)->create()->each(function ($service) {
            $service->save();
        });
    }
}
