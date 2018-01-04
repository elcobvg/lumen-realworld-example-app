<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\User::class, 10)->create();
        $this->command->info('Users created');

        $users = App\Models\User::all();
        foreach ($users as $user) {
            $faker = Faker\Factory::create();
            
            $others = App\Models\User::where('id', '<>', $user->id)->get();

            $num = $faker->numberBetween(1, 5);
            for ($i = 0; $i < $num; $i++) {
                $user->follow($others[$faker->numberBetween(0, sizeof($others) - 1)]);
            }
        }
        $this->command->info('Followers added');
    }
}
