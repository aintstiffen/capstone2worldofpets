<?php

/**
 * Populate Pet Care Guide data for all pets
 * Sets avoid_title to "Pet Care Guide" and avoid_description with breed-specific care information
 * Run with: php populate_pet_care_guide.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Pet;

echo "🐾 Populating Pet Care Guide Data...\n\n";

// Pet care guide data for each breed
$petCareData = [
    // DOGS
    'Akita' => 'Akitas require regular grooming, especially during shedding season. Avoid overfeeding as they can become overweight. They need consistent training and early socialization. Never leave them unsupervised with small animals or unfamiliar dogs. Avoid hot weather exercise as they are prone to heat stress. Regular vet checkups are essential for hip dysplasia screening.',
    
    'Beagle' => 'Beagles are prone to obesity, so avoid overfeeding and maintain regular exercise. Keep them on a leash during walks as they tend to follow scents. Secure your yard as they are excellent escape artists. Avoid leaving them alone for long periods as they can develop separation anxiety. Regular ear cleaning is essential to prevent infections.',
    
    'Border Collie' => 'Border Collies need extensive mental and physical stimulation daily. Avoid leaving them in small spaces or without activities as they can become destructive. They require consistent training and boundaries. Not suitable for sedentary owners. Avoid repetitive movements that can lead to joint issues. Regular eye examinations are important.',
    
    'Boxer' => 'Boxers are sensitive to extreme temperatures, especially heat. Avoid strenuous exercise in hot weather. They are prone to bloat, so feed smaller meals throughout the day. Regular cardiac checkups are essential. Avoid rough play that could injure their short muzzle. They need consistent training to manage their exuberant energy.',
    
    'Bulldog' => 'Bulldogs are extremely heat-sensitive and should never be exercised in hot weather. Keep facial wrinkles clean and dry to prevent infections. Avoid overfeeding as obesity worsens breathing problems. Swimming requires supervision as most cannot swim well. Regular vet visits for breathing and joint issues are crucial. Avoid stairs when possible.',
    
    'Chihuahua' => 'Chihuahuas are fragile and should be handled carefully. Avoid cold weather exposure and use sweaters when needed. Dental care is critical as they are prone to tooth problems. Keep them away from larger dogs to prevent injuries. Avoid overfeeding as obesity is common. Regular vet checkups for heart and knee issues are important.',
    
    'Dachshund' => 'Dachshunds must avoid jumping from heights or using stairs excessively to prevent back injuries. Maintain healthy weight to reduce spinal stress. Use ramps for furniture access. Avoid rough play with larger dogs. Regular back and joint examinations are essential. Keep them warm in cold weather as they have low body fat.',
    
    'Dalmatian' => 'Dalmatians require extensive daily exercise to prevent behavioral issues. They are prone to deafness, so have hearing tested. Avoid high-purine foods to prevent urinary stones. Regular skin checks for allergies are important. They need consistent training and socialization. Not suitable for first-time dog owners.',
    
    'German Shepherd' => 'German Shepherds need daily mental and physical exercise. Avoid overfeeding as they can develop hip dysplasia. Regular grooming during shedding season is essential. They require consistent training and early socialization. Avoid leaving them alone for extended periods. Regular vet checkups for joint and digestive issues are important.',
    
    'Golden Retriever' => 'Golden Retrievers are prone to obesity, so monitor food intake carefully. Regular exercise is essential but avoid overexertion in puppies to protect developing joints. Brush regularly to manage shedding. They are prone to cancer, so regular vet checkups are crucial. Avoid hot weather exercise as they can overheat easily.',
    
    'Great Dane' => 'Great Danes are prone to bloat, so feed multiple small meals and avoid exercise right after eating. Provide orthopedic bedding to support joints. Avoid stairs when young to prevent joint damage. Regular cardiac screenings are essential. They have shorter lifespans, so maximize quality care. Watch for signs of bone cancer.',
    
    'Labrador Retriever' => 'Labradors are prone to obesity, so strict portion control is essential. Provide daily exercise to maintain healthy weight. They can develop hip and elbow dysplasia, so avoid excessive jumping when young. Regular ear cleaning after swimming prevents infections. Monitor for signs of arthritis as they age.',
    
    'Pomeranian' => 'Pomeranians require daily brushing to prevent matting. Dental care is critical as they are prone to tooth loss. Avoid rough handling due to their small size. They can develop tracheal collapse, so use harnesses instead of collars. Regular vet checkups for heart and knee issues are important. Keep them warm in cold weather.',
    
    'Poodle' => 'Poodles require professional grooming every 6-8 weeks. Regular ear cleaning is essential to prevent infections. They are prone to eye issues, so schedule regular examinations. Maintain dental hygiene to prevent tooth problems. Avoid overfeeding as obesity can worsen joint issues. Provide daily mental stimulation to prevent boredom.',
    
    'Pug' => 'Pugs are extremely heat-sensitive and should never be exercised in hot weather. Keep facial wrinkles clean and dry daily. Monitor breathing and avoid strenuous exercise. They are prone to obesity, so strict diet control is necessary. Regular eye examinations are crucial. Avoid exposure to respiratory irritants like smoke.',
    
    'Rottweiler' => 'Rottweilers need consistent training and early socialization. Regular exercise is essential but avoid overexertion in puppies. They are prone to hip dysplasia and should maintain healthy weight. Provide mental stimulation to prevent destructive behavior. Regular vet checkups for joint and cardiac issues are important.',
    
    'Shih Tzu' => 'Shih Tzus require daily facial cleaning to prevent tear staining and eye infections. Professional grooming every 6-8 weeks is essential. They are heat-sensitive, so avoid hot weather exposure. Dental care is critical as they are prone to tooth problems. Regular eye examinations are important. Use harnesses to prevent tracheal issues.',
    
    'Siberian Husky' => 'Huskies require extensive daily exercise and mental stimulation. They are escape artists, so ensure secure fencing. Regular brushing during shedding season is essential. Avoid hot weather exercise as they are prone to heat stress. They need consistent training but can be stubborn. Not suitable for first-time owners.',
    
    'Yorkshire Terrier' => 'Yorkshire Terriers are fragile and should be handled carefully. Daily brushing prevents matting of their long coat. Dental care is critical as they are prone to tooth problems. They are sensitive to cold, so provide warmth. Avoid rough play with larger dogs. Regular vet checkups for liver and knee issues are important.',
    
    // CATS
    'Abyssinian' => 'Abyssinians need extensive daily play and mental stimulation. Avoid leaving them alone for long periods as they crave companionship. Provide vertical spaces for climbing. Regular dental care is essential. They are prone to kidney disease, so ensure proper hydration. Schedule regular vet checkups for eye and joint issues.',
    
    'American Shorthair' => 'American Shorthairs are prone to obesity, so monitor food intake carefully. Regular play sessions help maintain healthy weight. Brush weekly to reduce shedding. They are generally healthy but watch for heart disease as they age. Regular dental cleanings prevent tooth problems. Provide scratching posts to protect furniture.',
    
    'Bengal' => 'Bengals require extensive daily play and mental stimulation or they can become destructive. Provide tall cat trees and interactive toys. They love water, so secure toilets and aquariums. Regular vet checkups for heart issues are important. Avoid declawing as it can cause behavioral problems. They need large, secure living spaces.',
    
    'Birman' => 'Birmans require regular grooming despite being semi-longhaired. Brush 2-3 times weekly to prevent matting. They are prone to kidney disease, so ensure proper hydration and regular vet checkups. Maintain dental hygiene to prevent gum disease. They are social and can become depressed if left alone too long.',
    
    'British Shorthair' => 'British Shorthairs are prone to obesity, so monitor food portions carefully. Provide regular play sessions despite their calm nature. They can develop heart disease, so regular cardiac screenings are important. Brush weekly during shedding season. Avoid overfeeding treats. Regular dental care prevents tooth problems.',
    
    'Burmese' => 'Burmese cats need daily interactive play and companionship. Avoid leaving them alone for extended periods. They are prone to diabetes, so maintain healthy weight and monitor water intake. Regular dental care is essential. Provide window perches and vertical spaces. Schedule regular vet checkups for heart and eye issues.',
    
    'Exotic Shorthair' => 'Exotic Shorthairs require daily facial cleaning to prevent tear staining. Their flat faces make them prone to breathing issues, so avoid stressful situations. They are heat-sensitive and should be kept in climate-controlled environments. Regular eye examinations are crucial. Monitor for signs of kidney disease. Provide easy-to-reach food and water bowls.',
    
    'Himalayan' => 'Himalayans require daily brushing to prevent severe matting. Clean their faces daily to prevent tear staining and eye infections. They are heat-sensitive and prone to breathing issues due to flat faces. Regular eye examinations are essential. Avoid stressful situations. Professional grooming every 6-8 weeks is recommended.',
    
    'Maine Coon' => 'Maine Coons require regular brushing 2-3 times weekly to prevent matting. They are prone to heart disease (HCM), so regular cardiac screenings are essential. Monitor for signs of hip dysplasia as they age. Ensure proper dental care. Provide large litter boxes and tall cat trees. Regular vet checkups for kidney issues are important.',
    
    'Persian' => 'Persians require daily brushing to prevent severe matting and hairballs. Clean their faces daily to prevent tear staining. They are prone to kidney disease (PKD), so regular vet screenings are essential. Their flat faces cause breathing issues, so avoid heat and stress. Professional grooming every 6-8 weeks is necessary.',
    
    'Ragdoll' => 'Ragdolls require regular brushing 2-3 times weekly despite having low-matting coats. They are prone to heart disease (HCM), so regular cardiac screenings are essential. Avoid letting them go outdoors as they are too trusting. Monitor for urinary issues. They need companionship and can become lonely if left alone too long.',
    
    'Russian Blue' => 'Russian Blues are generally healthy but can be prone to obesity if overfed. Provide regular play sessions to maintain healthy weight. They are shy and need gradual socialization. Avoid sudden changes in routine or environment. Maintain a clean litter box as they are fastidious. Regular dental care prevents tooth problems.',
    
    'Scottish Fold' => 'Scottish Folds are prone to severe joint and cartilage problems due to their folded ears. Regular vet checkups for arthritis are essential. Avoid breeding folded-ear cats together. Monitor ears for infections and clean regularly. They can develop heart disease, so cardiac screenings are important. Provide soft bedding for joint comfort.',
    
    'Siamese' => 'Siamese cats are extremely vocal and social, requiring daily interaction. Avoid leaving them alone for long periods as they can become depressed. They are prone to dental issues, so regular cleanings are essential. Monitor for respiratory infections. Provide mental stimulation with puzzle toys. Regular vet checkups for heart and eye issues are important.',
    
    'Sphynx' => 'Sphynx cats require weekly baths to remove oil buildup on their skin. They are sensitive to temperature extremes and need warm environments. Clean ears regularly as they produce more wax. Protect from sun exposure to prevent burns. They are prone to heart disease, so regular cardiac screenings are essential. Monitor for skin infections.',
    
    // Additional breeds with different naming
    'Persian Cat' => 'Persian Cats require daily brushing to prevent severe matting and hairballs. Clean their faces daily to prevent tear staining. They are prone to kidney disease (PKD), so regular vet screenings are essential. Their flat faces cause breathing issues, so avoid heat and stress. Professional grooming every 6-8 weeks is necessary.',
    
    'chihuahua' => 'Chihuahuas are fragile and should be handled carefully. Avoid cold weather exposure and use sweaters when needed. Dental care is critical as they are prone to tooth problems. Keep them away from larger dogs to prevent injuries. Avoid overfeeding as obesity is common. Regular vet checkups for heart and knee issues are important.',
    
    'dachshund' => 'Dachshunds must avoid jumping from heights or using stairs excessively to prevent back injuries. Maintain healthy weight to reduce spinal stress. Use ramps for furniture access. Avoid rough play with larger dogs. Regular back and joint examinations are essential. Keep them warm in cold weather as they have low body fat.',
    
    'French Bulldog' => 'French Bulldogs are extremely heat-sensitive and should never be exercised in hot weather. Keep facial wrinkles clean and dry to prevent infections. They are prone to breathing issues due to their flat faces. Avoid overfeeding as obesity worsens respiratory problems. Regular vet visits for spine and joint issues are crucial.',
    
    'Boston Terrier' => 'Boston Terriers are sensitive to extreme temperatures due to their short muzzles. Keep facial wrinkles clean daily. They are prone to eye injuries, so avoid rough play. Regular eye examinations are essential. Monitor for breathing difficulties. Dental care is important to prevent tooth problems. Avoid overfeeding to maintain healthy weight.',
    
    'Japanese spitz' => 'Japanese Spitz require regular brushing 2-3 times weekly to maintain their white coat. They are generally healthy but can develop patellar luxation, so monitor for limping. Dental care is important to prevent tooth problems. Provide daily exercise and mental stimulation. They can become vocal if bored, so keep them engaged.',
    
    'Dobermann' => 'Dobermanns require extensive daily exercise and mental stimulation. They are prone to heart disease (DCM), so regular cardiac screenings are essential. Monitor for signs of bloat and feed smaller meals throughout the day. They need consistent training and early socialization. Regular vet checkups for hip dysplasia and thyroid issues are important.',
    
    'Bullmastiff' => 'Bullmastiffs are prone to bloat, so feed multiple small meals and avoid exercise right after eating. They are heat-sensitive and should not be exercised in hot weather. Regular vet checkups for hip dysplasia and heart issues are essential. Keep facial wrinkles clean. Monitor for signs of cancer as they age.',
    
    'Alaskan Malamute' => 'Alaskan Malamutes require extensive daily exercise and mental stimulation. They are escape artists, so ensure secure fencing. Regular brushing during shedding season is essential. Avoid hot weather exercise as they are prone to heat stress. They need consistent training but can be stubborn. Monitor for hip dysplasia and eye issues.',
    
    'Chow Chow' => 'Chow Chows require daily brushing to prevent matting, especially during shedding season. They need early socialization and consistent training. Regular eye examinations are essential as they are prone to entropion. Monitor for hip dysplasia and elbow issues. They are heat-sensitive, so avoid hot weather exercise. Not suitable for first-time owners.',
    
    'Puspin' => 'Puspins (Philippine domestic cats) are generally hardy but require regular veterinary care. Provide balanced nutrition and maintain healthy weight. Regular deworming and vaccinations are essential. Keep them indoors or in safe outdoor enclosures. Dental care prevents tooth problems. Monitor for signs of illness and provide immediate care when needed.',
    
    'Aspin' => 'Aspins (Philippine domestic dogs) are generally hardy but require regular veterinary care. Provide balanced nutrition and maintain healthy weight. Regular deworming, vaccinations, and tick prevention are essential. Dental care prevents tooth problems. Provide daily exercise and mental stimulation. Monitor for signs of illness and provide immediate care when needed.',
];

// Get all pets
$allPets = Pet::all();

echo "📊 Found " . $allPets->count() . " pets in database\n\n";

$updatedCount = 0;
$skippedCount = 0;

foreach ($allPets as $pet) {
    if (isset($petCareData[$pet->name])) {
        $pet->avoid_title = 'Pet Care Guide';
        $pet->avoid_description = $petCareData[$pet->name];
        $pet->save();
        
        echo "✅ Updated: {$pet->name} ({$pet->category})\n";
        $updatedCount++;
    } else {
        echo "⚠️  Skipped: {$pet->name} (no care data defined)\n";
        $skippedCount++;
    }
}

echo "\n";
echo "═══════════════════════════════════════════════\n";
echo "📊 SUMMARY\n";
echo "═══════════════════════════════════════════════\n";
echo "✅ Updated: {$updatedCount} pets\n";
echo "⚠️  Skipped: {$skippedCount} pets\n";
echo "📋 Total processed: {$allPets->count()} pets\n";
echo "═══════════════════════════════════════════════\n";
echo "\n✨ Pet Care Guide population complete!\n";
