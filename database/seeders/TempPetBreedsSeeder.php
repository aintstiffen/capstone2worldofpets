<?php

namespace Database\Seeders;

use App\Models\Pet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TempPetBreedsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cat breeds - Let's start with these since there are fewer
        $catBreeds = [
            [
                'name' => 'Philippine Shorthair (Puspin)',
                'size' => 'Small to Medium',
                'temperament' => 'Adaptable, Resilient, Intelligent',
                'lifespan' => '12-15',
                'energy' => 'Medium',
                'friendliness' => 4,
                'trainability' => 3,
                'exerciseNeeds' => 3,
                'grooming' => 1,
                'colors' => ['Various', 'Tabby', 'Black', 'White', 'Calico'],
                'description' => 'The Philippine Shorthair, affectionately known as "Puspin," is the native cat of the Philippines. These mixed-breed cats are highly adaptable, resilient, and have naturally evolved to thrive in the local climate. They make affectionate companions and efficient hunters.',
                'category' => 'cat',
            ],
            [
                'name' => 'Persian Cat',
                'size' => 'Medium',
                'temperament' => 'Sweet, Gentle, Quiet',
                'lifespan' => '12-16',
                'energy' => 'Low',
                'friendliness' => 4,
                'trainability' => 2,
                'exerciseNeeds' => 1,
                'grooming' => 5,
                'colors' => ['White', 'Blue', 'Black', 'Red', 'Cream', 'Tabby'],
                'description' => 'Persian cats are known for their long, luxurious coats, round faces, and sweet expressions. One of the oldest cat breeds, they have a calm, gentle temperament and prefer quiet environments. Their magnificent coat requires daily grooming to prevent mats and tangles.',
                'category' => 'cat',
            ],
            [
                'name' => 'Siamese',
                'size' => 'Medium',
                'temperament' => 'Talkative, Social, Intelligent',
                'lifespan' => '12-15',
                'energy' => 'High',
                'friendliness' => 5,
                'trainability' => 5,
                'exerciseNeeds' => 4,
                'grooming' => 1,
                'colors' => ['Seal Point', 'Blue Point', 'Chocolate Point', 'Lilac Point'],
                'description' => 'Siamese cats are distinctive for their sleek bodies, color points, and striking blue eyes. Known for their intelligence and vocal nature, they form strong bonds with their humans and are often described as "dog-like" in their loyalty and sociability.',
                'category' => 'cat',
            ],
            [
                'name' => 'Himalayan',
                'size' => 'Medium to Large',
                'temperament' => 'Gentle, Calm, Playful',
                'lifespan' => '9-15',
                'energy' => 'Medium',
                'friendliness' => 4,
                'trainability' => 3,
                'exerciseNeeds' => 2,
                'grooming' => 5,
                'colors' => ['Seal Point', 'Blue Point', 'Chocolate Point', 'Flame Point'],
                'description' => 'Himalayans are a cross between Persian and Siamese cats, combining the Persian\'s long coat and sweet temperament with the Siamese\'s colorpoint pattern. They are gentle, affectionate companions who enjoy playtime but also appreciate quiet lounging. Their luxurious coat requires regular grooming.',
                'category' => 'cat',
            ],
            [
                'name' => 'Russian Blue',
                'size' => 'Medium',
                'temperament' => 'Reserved, Intelligent, Gentle',
                'lifespan' => '15-20',
                'energy' => 'Medium',
                'friendliness' => 3,
                'trainability' => 4,
                'exerciseNeeds' => 2,
                'grooming' => 2,
                'colors' => ['Blue'],
                'description' => 'Russian Blues are elegant cats with a plush, silvery-blue coat and emerald green eyes. They are known for their intelligence, gentle temperament, and somewhat reserved nature with strangers. Once bonded, they are deeply loyal to their families and often get along well with other pets.',
                'category' => 'cat',
            ],
            [
                'name' => 'American Shorthair',
                'size' => 'Medium to Large',
                'temperament' => 'Easy-going, Independent, Affectionate',
                'lifespan' => '15-20',
                'energy' => 'Medium',
                'friendliness' => 4,
                'trainability' => 3,
                'exerciseNeeds' => 3,
                'grooming' => 2,
                'colors' => ['Silver Tabby', 'Brown Tabby', 'Black', 'White', 'Bi-color'],
                'description' => 'American Shorthairs are sturdy, adaptable cats descended from European cats brought to America to control rodents. They are known for their easy-going nature, hunting prowess, and adaptability. With moderate exercise needs and minimal grooming requirements, they make excellent family pets.',
                'category' => 'cat',
            ],
            [
                'name' => 'Exotic Shorthair',
                'size' => 'Medium',
                'temperament' => 'Gentle, Calm, Playful',
                'lifespan' => '12-15',
                'energy' => 'Low to Medium',
                'friendliness' => 4,
                'trainability' => 3,
                'exerciseNeeds' => 2,
                'grooming' => 3,
                'colors' => ['White', 'Black', 'Blue', 'Red', 'Cream', 'Tabby', 'Bi-color'],
                'description' => 'Often called "the lazy man\'s Persian," Exotic Shorthairs have the Persian\'s sweet face and personality but with a short, plush coat that requires less grooming. They are gentle, calm companions who enjoy playing but are also content to lounge around the house.',
                'category' => 'cat',
            ],
            [
                'name' => 'Bengal',
                'size' => 'Medium to Large',
                'temperament' => 'Active, Intelligent, Playful',
                'lifespan' => '12-16',
                'energy' => 'Very High',
                'friendliness' => 4,
                'trainability' => 5,
                'exerciseNeeds' => 5,
                'grooming' => 1,
                'colors' => ['Brown Spotted', 'Snow Spotted', 'Silver Spotted', 'Marble Pattern'],
                'description' => 'Bengal cats are known for their wild appearance with distinctive spotted or marbled coat patterns. These active, athletic cats have a playful, energetic nature and high intelligence. They often enjoy water, can learn tricks, and need substantial environmental enrichment to stay happy and healthy.',
                'category' => 'cat',
            ],
            [
                'name' => 'British Shorthair',
                'size' => 'Medium to Large',
                'temperament' => 'Easygoing, Reserved, Loyal',
                'lifespan' => '12-17',
                'energy' => 'Low to Medium',
                'friendliness' => 3,
                'trainability' => 3,
                'exerciseNeeds' => 2,
                'grooming' => 2,
                'colors' => ['Blue', 'Black', 'White', 'Red', 'Cream', 'Tabby'],
                'description' => 'British Shorthairs are known for their dense, plush coat and round face with characteristic "Cheshire cat" smile. They have a calm, undemanding nature and quiet voice. While not overly demonstrative, they are loyal companions who prefer to be near their humans without demanding constant attention.',
                'category' => 'cat',
            ],
            [
                'name' => 'American Curl',
                'size' => 'Small to Medium',
                'temperament' => 'Curious, People-oriented, Playful',
                'lifespan' => '12-16',
                'energy' => 'Medium',
                'friendliness' => 5,
                'trainability' => 4,
                'exerciseNeeds' => 3,
                'grooming' => 2,
                'colors' => ['Various', 'Tabby', 'Solid', 'Bi-color', 'Pointed'],
                'description' => 'American Curls are distinguished by their unique ears that curl backward away from the face. These cats remain playful throughout their lives and are known for their people-oriented, affectionate nature. They adapt well to various living situations and get along well with children and other pets.',
                'category' => 'cat',
            ],
        ];

        // Insert cat breeds
        foreach ($catBreeds as $breed) {
            Pet::firstOrCreate(
                ['name' => $breed['name']],
                array_merge(
                    $breed,
                    ['slug' => Str::slug($breed['name'])]
                )
            );
        }
        
        // Flush the cat data first - just to be safe
        Pet::whereIn('name', array_column($catBreeds, 'name'))->get();
    }
}
