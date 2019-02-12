<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use App\Models\User;

$factory->define(\App\Models\Role::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->randomElement([
            'user', 'admin'
        ]),
    ];
});

$factory->define(User::class, function (Faker\Generator $faker) {
    return [
        'email' => $faker->email,
        'password' => app('hash')->make('123456'),
        'role_id' => 1
    ];
});

$factory->define(\App\Models\Permission::class, function (Faker\Generator $faker) {
    return [
        'slug' => $faker->randomElement([
            'view-task', 'edit-task', 'delete-task'
        ])
    ];
});
