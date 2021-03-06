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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('123456'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Role::class, function ($faker) {
    return [
        'name' => 'medico',
    ];
});

$factory->define(App\Patient::class, function ($faker) {
    return [
        'user_id'=> 1,
        'first_name'=> $faker->firstName,
        'last_name'=>$faker->lastName,
        'birth_date'=> $faker->date(),
        'gender'=> $faker->randomElement(array ('m','f')),
        'phone'=> $faker->phoneNumber,
        'phone2'=> $faker->phoneNumber,
        'email'=>$faker->email,
        'address'=>$faker->address,
        'province'=>$faker->state,
        'city' => $faker->city,
    ];
});

$factory->define(App\Speciality::class, function ($faker) {
    return [
        'name' => 'Medico General',
    ];
});

$factory->define(App\Configuration::class, function ($faker) {
    return [
        'amount_attended' => 1,
        'amount_expedient' => 10,
    ];
});

$factory->define(App\Plan::class, function ($faker) {
    return [
        'title' => 'Plan $10',
        'cost' => 10,
        'quantity' => 1
    ];
});



