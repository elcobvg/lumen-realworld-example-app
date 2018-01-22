<?php

use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Article::class, 25)->create()->each(function ($article) {

            $faker = Faker\Factory::create();
            gc_collect_cycles();

            $authors = App\Models\User::all();
            $author = $authors[$faker->numberBetween(0, sizeof($authors) - 1)];
            $article->author()->associate($author);

            $num_com = $faker->numberBetween(1, 5);
            for ($i = 0; $i < $num_com; $i++) {
                $comment = factory(App\Models\Comment::class)->make();
                $comment->author()->associate($authors[$faker->numberBetween(0, sizeof($authors) - 1)]);
                $article->comments()->save($comment);
            }

            $num_tags = $faker->numberBetween(0, 4);
            for ($j = 0; $j < $num_tags; $j++) {
                $tag = factory(App\Models\Tag::class)->make();
                $article->tags()->save($tag);
            }

            $users = App\Models\User::where('id', '<>', $author->id)->get();
            $num_favs = $faker->numberBetween(0, 10);
            for ($k = 0; $k < $num_favs; $k++) {
                $article->favoritedBy()->attach($users[$faker->numberBetween(0, sizeof($users) - 1)]);
            }

            $article->save();
            $this->command->info('Article created');
        });
    }
}
