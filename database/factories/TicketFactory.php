<?php

use App\Ticket;
use App\User;
use Faker\Generator as Faker;

$factory->define(Ticket::class, function (Faker $faker) {
    $status = rand(1,3);
    if (null !== Ticket::where('status', $status)->orderBy('id', 'desc')->first()) {
        $priority = Ticket::where('status', $status)->orderBy('id', 'desc')->first()->priority + 1;
    } else {
        $priority = 1;
    }
    return [
        'title' => $faker->sentence,
        'description' => $faker->sentence,
        'user_id' => User::all()->random()->user_id,
        'status' => $status,
        'priority' => $priority
    ];
});

