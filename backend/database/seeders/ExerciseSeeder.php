<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exercise;

class ExerciseSeeder extends Seeder
{
    public function run()
    {
        $exercises = [
            [
                'name' => 'Koşu',
                'description' => 'Kardiyovasküler dayanıklılığı artıran temel egzersiz',
                'category' => 'cardio',
                'difficulty' => 'beginner',
                'duration_minutes' => 30,
                'calories_burned_per_minute' => 10,
                'muscle_groups' => ['bacaklar', 'kalça', 'core'],
                'equipment' => ['koşu ayakkabısı'],
                'instructions' => 'Düz bir tempoda koşun. İlk başta yavaş başlayıp kademeli olarak hızınızı artırın.',
                'is_popular' => true
            ],
            [
                'name' => 'Şınav',
                'description' => 'Üst vücut gücünü geliştiren klasik egzersiz',
                'category' => 'strength',
                'difficulty' => 'intermediate',
                'duration_minutes' => 15,
                'calories_burned_per_minute' => 8,
                'muscle_groups' => ['göğüs', 'omuz', 'triceps', 'core'],
                'equipment' => [],
                'instructions' => 'Plank pozisyonunda, ellerinizi omuz genişliğinde açın. Vücudunuzu düz tutarak yukarı aşağı hareket edin.',
                'is_popular' => true
            ],
            [
                'name' => 'Squat',
                'description' => 'Alt vücut kaslarını güçlendiren temel hareket',
                'category' => 'strength',
                'difficulty' => 'beginner',
                'duration_minutes' => 20,
                'calories_burned_per_minute' => 6,
                'muscle_groups' => ['quadriceps', 'glutes', 'hamstring'],
                'equipment' => [],
                'instructions' => 'Ayaklarınızı omuz genişliğinde açın. Kalçanızı geriye doğru iterek çömelin.',
                'is_popular' => true
            ],
            [
                'name' => 'Yoga',
                'description' => 'Esneklik ve zihinsel rahatlama için yoga pozları',
                'category' => 'flexibility',
                'difficulty' => 'beginner',
                'duration_minutes' => 45,
                'calories_burned_per_minute' => 3,
                'muscle_groups' => ['tüm vücut'],
                'equipment' => ['yoga matı'],
                'instructions' => 'Nefes kontrolü ile birlikte çeşitli yoga pozlarını uygulayın.',
                'is_popular' => true
            ],
            [
                'name' => 'Plank',
                'description' => 'Core kaslarını güçlendiren statik egzersiz',
                'category' => 'strength',
                'difficulty' => 'intermediate',
                'duration_minutes' => 10,
                'calories_burned_per_minute' => 5,
                'muscle_groups' => ['core', 'omuz', 'sırt'],
                'equipment' => [],
                'instructions' => 'Dirseklerinizin üzerinde durarak vücudunuzu düz bir çizgide tutun.',
                'is_popular' => false
            ],
            [
                'name' => 'Burpee',
                'description' => 'Tüm vücudu çalıştıran yoğun egzersiz',
                'category' => 'cardio',
                'difficulty' => 'advanced',
                'duration_minutes' => 15,
                'calories_burned_per_minute' => 12,
                'muscle_groups' => ['tüm vücut'],
                'equipment' => [],
                'instructions' => 'Çömelerek ellerinizi yere koyun, bacaklarınızı geriye atın, şınav çekin, ayaklarınızı çekerek zıplayın.',
                'is_popular' => false
            ]
        ];

        foreach ($exercises as $exercise) {
            Exercise::create($exercise);
        }
    }
}