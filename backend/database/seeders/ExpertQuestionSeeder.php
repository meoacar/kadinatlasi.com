<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpertQuestion;
use App\Models\User;
use App\Models\Category;

class ExpertQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        $users = User::all();
        
        if ($categories->isEmpty() || $users->isEmpty()) {
            return;
        }

        $questions = [
            [
                'title' => 'Gebelik döneminde hangi vitaminleri almalıyım?',
                'question' => 'Merhaba, 8 haftalık hamileyim. Doktorum folik asit önerdi ama başka hangi vitaminleri almam gerekiyor? Özellikle demir ve kalsiyum konusunda bilgi verebilir misiniz?',
                'category' => 'Sağlık',
                'status' => 'pending'
            ],
            [
                'title' => 'Doğum sonrası depresyon belirtileri nelerdir?',
                'question' => '3 ay önce doğum yaptım. Son zamanlarda kendimi çok yorgun ve mutsuz hissediyorum. Bu normal mi yoksa doğum sonrası depresyon mu? Ne yapmalıyım?',
                'category' => 'Psikoloji',
                'status' => 'answered',
                'answer' => 'Doğum sonrası depresyon oldukça yaygın bir durumdur. Belirtiler arasında sürekli yorgunluk, mutsuzluk, bebeğe karşı bağ kuramama, uyku problemleri sayılabilir. Mutlaka bir uzmana başvurmanızı öneririm. Bu durum tedavi edilebilir ve geçicidir.'
            ],
            [
                'title' => 'Kilo verme diyeti nasıl olmalı?',
                'question' => '10 kilo vermek istiyorum. Hangi diyet programını önerirsiniz? Spor yapmaya da başlamayı planlıyorum.',
                'category' => 'Beslenme',
                'status' => 'pending'
            ]
        ];

        foreach ($questions as $questionData) {
            $category = $categories->where('name', $questionData['category'])->first();
            if (!$category) {
                $category = $categories->first();
            }

            $question = ExpertQuestion::create([
                'user_id' => $users->random()->id,
                'category_id' => $category->id,
                'title' => $questionData['title'],
                'question' => $questionData['question'],
                'status' => $questionData['status'],
                'is_public' => rand(0, 1)
            ]);

            if ($questionData['status'] === 'answered') {
                $question->update([
                    'expert_id' => $users->random()->id,
                    'answer' => $questionData['answer'],
                    'answered_at' => now()
                ]);
            }
        }
    }
}