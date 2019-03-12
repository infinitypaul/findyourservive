<?php

use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => 'infinitypaul',
        'email' => 'infinitypaul@live.com',
        'email_verified_at' => now(),
        'is_admin' => true,
        'password' => '$2y$10$o8hLsQKyFpktF5ThX7QLY.kddEVbMj6WegFTnT0FDQKn8GgkvRN3q', // password
        'remember_token' => Str::random(10),
    ];
});
