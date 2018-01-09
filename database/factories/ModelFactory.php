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

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {

    $number = $faker->numberBetween(1, 99);
    $gender = $faker->randomElement(['men', 'women']);
    gc_collect_cycles();

    return [
        'username'  => str_replace('.', '', $faker->unique()->userName),
        'email'     => $faker->unique()->email,
        'password'  => \Illuminate\Support\Facades\Hash::make('password'),
        'bio'       => $faker->sentence(10),
        'image'     => "https://randomuser.me/api/portraits/{$gender}/{$number}.jpg"
    ];
});

$factory->define(App\Models\Article::class, function (Faker\Generator $faker) {
    gc_collect_cycles();

    return [
        'title'         => $faker->sentence,
        'description'   => $faker->paragraph,
        'body'          => implode('<p>', $faker->paragraphs),
    ];
});

$factory->define(App\Models\Comment::class, function (Faker\Generator $faker) {
    return [
        'body'  => $faker->paragraph,
    ];
});

$factory->define(App\Models\Tag::class, function (Faker\Generator $faker) {
    return [
        'name'  => $faker->randomElement([
            'apples',
            'bananas',
            'cherries',
            'dates',
            'figs',
            'grapes',
            'kiwis',
            'limes',
            'melons',
            'oranges',
            'pears',
            'strawberries',
        ]),
    ];
});
