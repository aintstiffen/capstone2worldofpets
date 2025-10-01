<?php

namespace Database\Seeders;

use App\Models\Pet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PetHotspotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Golden Retriever example
        $goldenRetriever = Pet::where('name', 'Golden Retriever')->first();
        if ($goldenRetriever) {
            $goldenRetriever->hotspots = [
                [
                    'feature' => 'ears',
                    'position_x' => 50,
                    'position_y' => 15,
                    'width' => 64,
                    'height' => 40
                ],
                [
                    'feature' => 'eyes',
                    'position_x' => 50,
                    'position_y' => 30,
                    'width' => 64,
                    'height' => 32
                ],
                [
                    'feature' => 'tail',
                    'position_x' => 85,
                    'position_y' => 70,
                    'width' => 40,
                    'height' => 48
                ],
                [
                    'feature' => 'paws',
                    'position_x' => 25,
                    'position_y' => 85,
                    'width' => 32,
                    'height' => 32
                ]
            ];
            
            $goldenRetriever->fun_facts = [
                [
                    'feature' => 'ears',
                    'fact' => 'Their floppy ears help protect the ear canal from water and debris, making them excellent swimmers.'
                ],
                [
                    'feature' => 'eyes',
                    'fact' => 'Golden Retrievers have kind, intelligent eyes that reflect their friendly and gentle temperament.'
                ],
                [
                    'feature' => 'tail',
                    'fact' => 'The "golden" tail is often called their "rudder" as it helps them navigate while swimming.'
                ],
                [
                    'feature' => 'paws',
                    'fact' => 'Their webbed feet make Golden Retrievers excellent swimmers, perfect for retrieving waterfowl.'
                ]
            ];
            
            $goldenRetriever->save();
        }
        
        // German Shepherd example
        $germanShepherd = Pet::where('name', 'German Shepherd')->first();
        if ($germanShepherd) {
            $germanShepherd->hotspots = [
                [
                    'feature' => 'ears',
                    'position_x' => 50,
                    'position_y' => 12,
                    'width' => 70,
                    'height' => 40
                ],
                [
                    'feature' => 'eyes',
                    'position_x' => 50,
                    'position_y' => 28,
                    'width' => 60,
                    'height' => 30
                ],
                [
                    'feature' => 'tail',
                    'position_x' => 90,
                    'position_y' => 65,
                    'width' => 40,
                    'height' => 50
                ],
                [
                    'feature' => 'paws',
                    'position_x' => 25,
                    'position_y' => 90,
                    'width' => 35,
                    'height' => 35
                ],
                [
                    'feature' => 'coat',
                    'position_x' => 60,
                    'position_y' => 50,
                    'width' => 70,
                    'height' => 60
                ]
            ];
            
            $germanShepherd->fun_facts = [
                [
                    'feature' => 'ears',
                    'fact' => 'Their erect ears can rotate independently to precisely locate sounds from different directions.'
                ],
                [
                    'feature' => 'eyes',
                    'fact' => 'German Shepherds have almond-shaped eyes that provide wide peripheral vision to detect potential threats or movement.'
                ],
                [
                    'feature' => 'tail',
                    'fact' => 'Their bushy tail often hangs low when relaxed but forms a gentle curve when alert.'
                ],
                [
                    'feature' => 'paws',
                    'fact' => 'Their strong paw structure provides excellent traction and stability, crucial for police and military work.'
                ],
                [
                    'feature' => 'coat',
                    'fact' => 'Their double coat provides protection from rain and snow, with a dense undercoat for insulation.'
                ]
            ];
            
            $germanShepherd->save();
        }
        
        // Persian Cat example
        $persianCat = Pet::where('name', 'Persian Cat')->first();
        if ($persianCat) {
            $persianCat->hotspots = [
                [
                    'feature' => 'ears',
                    'position_x' => 25,
                    'position_y' => 20,
                    'width' => 30,
                    'height' => 30
                ],
                [
                    'feature' => 'ears',
                    'position_x' => 75,
                    'position_y' => 20,
                    'width' => 30,
                    'height' => 30
                ],
                [
                    'feature' => 'eyes',
                    'position_x' => 50,
                    'position_y' => 35,
                    'width' => 60,
                    'height' => 30
                ],
                [
                    'feature' => 'nose',
                    'position_x' => 50,
                    'position_y' => 45,
                    'width' => 25,
                    'height' => 25
                ],
                [
                    'feature' => 'coat',
                    'position_x' => 50,
                    'position_y' => 65,
                    'width' => 80,
                    'height' => 60
                ]
            ];
            
            $persianCat->fun_facts = [
                [
                    'feature' => 'ears',
                    'fact' => 'Their small ears are set far apart and tilted slightly forward, nestled in their luxurious fur.'
                ],
                [
                    'feature' => 'eyes',
                    'fact' => 'Persian cats have large, round eyes that give them a sweet, doll-like expression.'
                ],
                [
                    'feature' => 'nose',
                    'fact' => 'Their distinctive flat face (brachycephalic) and "squished" nose is the result of selective breeding.'
                ],
                [
                    'feature' => 'coat',
                    'fact' => 'Persian cats have one of the longest and densest coats of all cat breeds, requiring daily grooming.'
                ]
            ];
            
            $persianCat->save();
        }
    }
}
