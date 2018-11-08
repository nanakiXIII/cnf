<?php

use Illuminate\Database\Seeder;

class PostSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $input = array("Animes", "Scantrad", "Light-novel", "Visual-novel", 'all');
        $faker = \Faker\Factory::create();
        for ($i =1; $i <= 51; $i++){
            \App\post::create([
                'titre' => $faker->text(50),
                'contenu' => $faker->text(800),
                'staff' => $faker->text,
                'type' => $input[mt_rand(0, count($input) - 1)],
                'etat' => $faker->name,
                'slug' => $faker->slug,
                'image' => 'https://loremflickr.com/1280/720?random='.$i,
                'user_id' => 1,
                'publication' => true,
                'publish_at' => now()
            ]);
        }
    }
}
