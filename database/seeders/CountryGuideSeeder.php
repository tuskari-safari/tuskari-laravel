<?php

namespace Database\Seeders;

use App\Models\CountryGuide;
use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CountryGuideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eastAfrica = Region::where('name', 'East Africa')->first();
        $southernAfrica = Region::where('name', 'Southern Africa')->first();

        $countries = [
            [
                'region' => $eastAfrica,
                'name' => 'Kenya',
                'subtitle' => 'Home of the Maasai Mara and the Great Migration. Experience classic safari adventures.',
                'content_details' => [
                    'overview' => 'Kenya is the birthplace of safari tourism and remains one of Africa\'s premier wildlife destinations. The country offers diverse landscapes from the iconic Maasai Mara to the snow-capped Mount Kenya.',
                    'climate' => 'Kenya has a tropical climate with two rainy seasons. The long rains occur from March to May, and short rains from October to December.',
                    'best_time' => 'July to October for the Great Migration. Year-round for general game viewing.',
                ],
                'faqs' => [
                    ['question' => 'What is the best time to visit Kenya?', 'answer' => 'July to October is ideal for witnessing the Great Migration in the Maasai Mara.'],
                    ['question' => 'Do I need a visa for Kenya?', 'answer' => 'Most visitors need a visa. E-visas are available for many nationalities.'],
                    ['question' => 'What vaccinations are required?', 'answer' => 'Yellow fever vaccination is required. Malaria prophylaxis is recommended.'],
                ],
            ],
            [
                'region' => $eastAfrica,
                'name' => 'Tanzania',
                'subtitle' => 'Experience the Serengeti and Ngorongoro Crater. Home to Mount Kilimanjaro.',
                'content_details' => [
                    'overview' => 'Tanzania offers some of Africa\'s most iconic safari destinations including the Serengeti, Ngorongoro Crater, and the spice island of Zanzibar.',
                    'climate' => 'Tanzania has a tropical climate. The main dry season is from June to October, with short rains in November and long rains from March to May.',
                    'best_time' => 'June to October for dry season game viewing. December to February for calving season in the Serengeti.',
                ],
                'faqs' => [
                    ['question' => 'What is the best time to visit Tanzania?', 'answer' => 'June to October offers the best game viewing during the dry season.'],
                    ['question' => 'Can I combine safari with Zanzibar?', 'answer' => 'Yes! A safari-beach combination is very popular and easily arranged.'],
                    ['question' => 'Is Tanzania safe for tourists?', 'answer' => 'Tanzania is generally safe for tourists, especially in safari areas and Zanzibar.'],
                ],
            ],
            [
                'region' => $southernAfrica,
                'name' => 'South Africa',
                'subtitle' => 'Diverse wildlife from Kruger to the Cape. Malaria-free options available.',
                'content_details' => [
                    'overview' => 'South Africa offers incredible diversity - from the world-famous Kruger National Park to the malaria-free Eastern Cape reserves, the Cape Winelands, and vibrant cities.',
                    'climate' => 'South Africa has varied climates. The highveld has warm summers and cool winters. Coastal areas are milder year-round.',
                    'best_time' => 'May to September (winter) is best for game viewing as vegetation is sparse and animals gather at water sources.',
                ],
                'faqs' => [
                    ['question' => 'Are there malaria-free safari options?', 'answer' => 'Yes, the Eastern Cape reserves and parts of KwaZulu-Natal are malaria-free.'],
                    ['question' => 'Can I self-drive in Kruger?', 'answer' => 'Yes, Kruger National Park is perfect for self-drive safaris with excellent roads.'],
                    ['question' => 'What makes South Africa unique for safari?', 'answer' => 'South Africa offers the Big Five, diverse landscapes, excellent infrastructure, and no jet lag from Europe.'],
                ],
            ],
            [
                'region' => $southernAfrica,
                'name' => 'Botswana',
                'subtitle' => 'Pristine wilderness of the Okavango Delta. Exclusive, low-impact safari experiences.',
                'content_details' => [
                    'overview' => 'Botswana is known for its pristine wilderness and high-value, low-impact tourism. The Okavango Delta, a UNESCO World Heritage site, offers unique water-based safari experiences.',
                    'climate' => 'Botswana has a semi-arid climate with hot summers (November to March) and mild winters (May to August). The Delta floods from June to August.',
                    'best_time' => 'May to October for general game viewing. June to August for the flooded Delta experience.',
                ],
                'faqs' => [
                    ['question' => 'Why is Botswana more expensive?', 'answer' => 'Botswana focuses on low-volume, high-value tourism to protect its pristine wilderness.'],
                    ['question' => 'What is the Okavango Delta?', 'answer' => 'A unique inland delta where the Okavango River meets the Kalahari Desert, creating a wildlife paradise.'],
                    ['question' => 'Can I see the Big Five in Botswana?', 'answer' => 'Yes, all Big Five can be seen, with excellent elephant and predator sightings.'],
                ],
            ],
            [
                'region' => $southernAfrica,
                'name' => 'Namibia',
                'subtitle' => 'Desert-adapted wildlife and stunning landscapes. Unique safari experiences.',
                'content_details' => [
                    'overview' => 'Namibia offers dramatic landscapes from the Namib Desert to Etosha National Park. See desert-adapted elephants and lions in stark, beautiful terrain.',
                    'climate' => 'Namibia is mostly arid with hot days and cool nights. The short rainy season is from December to April.',
                    'best_time' => 'May to October for game viewing in Etosha. Year-round for landscapes and desert experiences.',
                ],
                'faqs' => [
                    ['question' => 'Is Namibia safe for self-driving?', 'answer' => 'Yes, Namibia is excellent for self-drive safaris with good roads and clear signage.'],
                    ['question' => 'What makes Namibia unique?', 'answer' => 'Dramatic desert landscapes, excellent photography opportunities, and unique desert-adapted wildlife.'],
                    ['question' => 'Can I see the Big Five in Namibia?', 'answer' => 'Etosha National Park has four of the Big Five (no buffalo), plus excellent rhino sightings.'],
                ],
            ],
            [
                'region' => $eastAfrica,
                'name' => 'Uganda',
                'subtitle' => 'Mountain gorilla trekking in misty forests. The Pearl of Africa.',
                'content_details' => [
                    'overview' => 'Uganda is the Pearl of Africa, offering mountain gorilla trekking, chimpanzee tracking, tree-climbing lions, and the source of the Nile.',
                    'climate' => 'Uganda has a tropical climate with two dry seasons: December to February and June to September.',
                    'best_time' => 'June to August and December to February for gorilla trekking during dry seasons.',
                ],
                'faqs' => [
                    ['question' => 'How do I book a gorilla permit?', 'answer' => 'Permits should be booked months in advance through Uganda Wildlife Authority or a tour operator.'],
                    ['question' => 'Is gorilla trekking difficult?', 'answer' => 'It can be strenuous with steep terrain. A moderate fitness level is recommended.'],
                    ['question' => 'Are gorillas dangerous?', 'answer' => 'Habituated gorillas are generally safe. Strict guidelines ensure both human and gorilla safety.'],
                ],
            ],
            [
                'region' => $eastAfrica,
                'name' => 'Rwanda',
                'subtitle' => 'The land of a thousand hills and gorillas. Remarkable conservation success story.',
                'content_details' => [
                    'overview' => 'Rwanda has transformed into a premier gorilla trekking destination. The country offers gorilla and golden monkey experiences in Volcanoes National Park.',
                    'climate' => 'Rwanda has a temperate tropical highland climate. Dry seasons are June to September and December to February.',
                    'best_time' => 'June to September for the main dry season. December to February for short dry season.',
                ],
                'faqs' => [
                    ['question' => 'How much is a gorilla permit in Rwanda?', 'answer' => 'Rwanda gorilla permits cost $1,500 per person, making it the most premium destination.'],
                    ['question' => 'Why choose Rwanda over Uganda?', 'answer' => 'Shorter trek times, better infrastructure, and supports remarkable conservation efforts.'],
                    ['question' => 'Is Rwanda safe to visit?', 'answer' => 'Rwanda is one of the safest countries in Africa with very low crime rates.'],
                ],
            ],
        ];

        foreach ($countries as $country) {
            if (!$country['region']) {
                continue;
            }

            CountryGuide::firstOrCreate(
                ['slug' => Str::slug($country['name'])],
                [
                    'region_id' => $country['region']->id,
                    'name' => $country['name'],
                    'slug' => Str::slug($country['name']),
                    'subtitle' => $country['subtitle'],
                    'banner_image' => null,
                    'thumbnail' => null,
                    'content_details' => json_encode($country['content_details']),
                    'unique_experience_title' => 'Unique Experiences in ' . $country['name'],
                    'unique_experience_desc' => 'Discover what makes ' . $country['name'] . ' special.',
                    'unique_experience' => json_encode([]),
                    'middle_sec_title' => 'Discover ' . $country['name'],
                    'middle_sec_subtitle' => 'Your African adventure awaits',
                    'bottom_banner' => json_encode([
                        'title' => 'Ready to explore ' . $country['name'] . '?',
                        'subtitle' => 'Start planning your safari today',
                    ]),
                    'faqs' => json_encode($country['faqs']),
                    'status' => true,
                ]
            );
        }
    }
}
