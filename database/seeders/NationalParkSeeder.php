<?php

namespace Database\Seeders;

use App\Models\CountryGuide;
use App\Models\NationalParkAndReserves;
use Illuminate\Database\Seeder;

class NationalParkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parks = [
            [
                'country' => 'Kenya',
                'name' => 'Maasai Mara National Reserve',
                'type' => 'private_reserve',
                'short_description' => 'Kenya\'s premier wildlife destination and home to the Great Migration crossing.',
                'description' => 'The Maasai Mara is Kenya\'s most famous wildlife reserve, known for exceptional populations of lions, leopards, and cheetahs. Every year, over two million wildebeest and zebras cross from the Serengeti, creating one of nature\'s greatest spectacles.',
                'lat' => -1.4061,
                'long' => 35.0108,
                'is_most_popular' => true,
            ],
            [
                'country' => 'Tanzania',
                'name' => 'Serengeti National Park',
                'type' => 'national_park',
                'short_description' => 'Endless plains of Tanzania - birthplace of the Great Migration.',
                'description' => 'The Serengeti is Tanzania\'s oldest and most popular national park. Its name means "endless plains" in Maasai, and it\'s home to the largest terrestrial mammal migration in the world.',
                'lat' => -2.3333,
                'long' => 34.8333,
                'is_most_popular' => true,
            ],
            [
                'country' => 'South Africa',
                'name' => 'Kruger National Park',
                'type' => 'national_park',
                'short_description' => 'South Africa\'s flagship park - one of Africa\'s largest game reserves.',
                'description' => 'Kruger National Park is one of Africa\'s largest game reserves, covering nearly 2 million hectares. It offers incredible biodiversity with all of the Big Five and over 500 bird species.',
                'lat' => -23.9884,
                'long' => 31.5547,
                'is_most_popular' => true,
            ],
            [
                'country' => 'Botswana',
                'name' => 'Okavango Delta',
                'type' => 'private_reserve',
                'short_description' => 'Botswana\'s inland delta paradise - a UNESCO World Heritage Site.',
                'description' => 'The Okavango Delta is a unique inland delta where the Okavango River spreads across the Kalahari Desert, creating a lush wildlife paradise. Experience mokoro rides, walking safaris, and exceptional game viewing.',
                'lat' => -19.2871,
                'long' => 22.8378,
                'is_most_popular' => true,
            ],
            [
                'country' => 'Tanzania',
                'name' => 'Ngorongoro Crater',
                'type' => 'national_park',
                'short_description' => 'World\'s largest intact volcanic caldera - a natural Eden.',
                'description' => 'The Ngorongoro Crater is the world\'s largest intact volcanic caldera and a UNESCO World Heritage Site. This natural amphitheatre is home to around 25,000 large animals including the endangered black rhino.',
                'lat' => -3.1750,
                'long' => 35.5833,
                'is_most_popular' => true,
            ],
            [
                'country' => 'Kenya',
                'name' => 'Amboseli National Park',
                'type' => 'national_park',
                'short_description' => 'Best views of Mount Kilimanjaro with large elephant herds.',
                'description' => 'Amboseli offers stunning views of Mount Kilimanjaro and is famous for its large elephant herds. The park\'s open terrain makes it excellent for photography and game viewing.',
                'lat' => -2.6527,
                'long' => 37.2606,
                'is_most_popular' => false,
            ],
            [
                'country' => 'Namibia',
                'name' => 'Etosha National Park',
                'type' => 'national_park',
                'short_description' => 'Namibia\'s salt pan wilderness - excellent for self-drive safari.',
                'description' => 'Etosha National Park is centered around a vast salt pan visible from space. The park is excellent for self-drive safaris with floodlit waterholes for night viewing. Great populations of rhinos, elephants, and lions.',
                'lat' => -18.8556,
                'long' => 16.3278,
                'is_hidden_gem' => true,
            ],
            [
                'country' => 'Botswana',
                'name' => 'Chobe National Park',
                'type' => 'national_park',
                'short_description' => 'Largest elephant population in Africa - spectacular river safaris.',
                'description' => 'Chobe National Park is home to Africa\'s largest elephant population, estimated at over 100,000. The Chobe River frontage offers exceptional boat safaris and incredible wildlife concentrations.',
                'lat' => -18.7669,
                'long' => 24.9969,
                'is_most_popular' => false,
            ],
        ];

        foreach ($parks as $park) {
            $country = CountryGuide::where('name', $park['country'])->first();

            if (!$country) {
                continue;
            }

            NationalParkAndReserves::firstOrCreate(
                ['name' => $park['name']],
                [
                    'country_id' => $country->id,
                    'name' => $park['name'],
                    'type' => $park['type'],
                    'short_description' => $park['short_description'],
                    'title' => $park['name'],
                    'title_2' => 'Explore ' . $park['name'],
                    'subtitle' => 'Discover the wonders of ' . $park['name'],
                    'description' => $park['description'],
                    'location' => $park['country'] . ', Africa',
                    'lat' => $park['lat'],
                    'long' => $park['long'],
                    'banner_image' => null,
                    'middle_banner_image' => null,
                    'thumbnail' => null,
                    'category' => json_encode(['Safari', 'Wildlife', 'Nature']),
                    'wildlife_highlights_title' => 'Wildlife Highlights',
                    'wildlife_highlights_desc' => 'Discover the incredible wildlife of ' . $park['name'],
                    'best_visit_time' => json_encode([
                        ['month' => 'June - October', 'description' => 'Dry season - best for wildlife viewing'],
                        ['month' => 'November - May', 'description' => 'Green season - great for bird watching'],
                    ]),
                    'wild_lives_ids' => null,
                    'accomodation_ids' => null,
                    'traveler_tips' => json_encode([
                        ['tip' => 'Bring binoculars for better wildlife viewing'],
                        ['tip' => 'Pack layers as temperatures vary throughout the day'],
                        ['tip' => 'Book game drives in advance during peak season'],
                    ]),
                    'unique_experience' => json_encode([
                        ['title' => 'Sunrise Game Drive', 'description' => 'Experience the bush coming alive at dawn'],
                        ['title' => 'Sundowner', 'description' => 'Enjoy drinks as the sun sets over the plains'],
                    ]),
                    'impact' => 'Your visit supports conservation and local communities.',
                    'impact_image' => null,
                    'is_most_popular' => $park['is_most_popular'] ?? false,
                    'is_hidden_gem' => $park['is_hidden_gem'] ?? false,
                    'faqs' => json_encode([
                        ['question' => 'What animals can I see?', 'answer' => 'The park is home to the Big Five and hundreds of other species.'],
                        ['question' => 'How do I get there?', 'answer' => 'Accessible by road or charter flight from major cities.'],
                    ]),
                    'status' => true,
                    'is_hidden' => false,
                ]
            );
        }
    }
}
