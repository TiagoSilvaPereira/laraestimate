<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Estimate;
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

$factory->define(Estimate::class, function (Faker $faker) {
    return [
        'name' => Str::title($faker->sentence(3)),
        'expiration_date' => $faker->date(),
        'allows_to_select_items' => $faker->boolean(),
    ];
});
