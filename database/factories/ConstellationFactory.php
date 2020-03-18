<?php
use App\Models\Constellation;
use Faker\Generator as Faker;

$factory->define(Constellation::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});
