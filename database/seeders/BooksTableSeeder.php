<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Books;
use Faker\Factory as Faker;
class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('en_US');
        for ($i = 0; $i < 100; $i++) {
            Books::create([
                //title in english language
                'title' => $faker->sentence($nbWords = 3, $variableNbWords = true),
                'author' => $faker->name,
                'isbn' => $faker->isbn13,
                'cover' => $faker->imageUrl($width = 640, $height = 480),
                'description' => $faker->paragraph($nbSentences = 2, $variableNbSentences = true, $asText = true),
                'published' => $faker->date($format = 'Y-m-d', $max = 'now'),
            ]);
        }
    }

}
