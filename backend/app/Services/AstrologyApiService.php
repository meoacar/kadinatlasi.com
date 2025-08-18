<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Horoscope;
use Carbon\Carbon;

class AstrologyApiService
{
    public function fetchHoroscope($zodiacSign, $type = 'daily', $category = 'general')
    {
        try {
            // Gerçek API yerine gelişmiş fallback kullan
            return $this->generateAdvancedHoroscope($zodiacSign, $type, $category);
        } catch (\Exception $e) {
            return $this->generateAdvancedHoroscope($zodiacSign, $type, $category);
        }
    }

    public function fetchAllSigns($type = 'daily', $category = 'general')
    {
        $signs = array_keys(Horoscope::getZodiacSigns());
        $results = [];

        foreach ($signs as $sign) {
            $horoscope = $this->fetchHoroscope($sign, $type, $category);
            if ($horoscope) {
                $results[] = $horoscope;
            }
        }

        return $results;
    }

    private function generateAdvancedHoroscope($zodiacSign, $type, $category)
    {
        $messages = [
            'daily' => [
                'general' => [
                    'Bugün enerjiniz yüksek olacak. Yeni fırsatları değerlendirin.',
                    'Sabırlı olmanız gereken bir gün. Acele etmeyin.',
                    'Yaratıcılığınızı ön plana çıkarın. Sanatsal aktiviteler faydalı.',
                    'Sosyal çevrenizle güzel vakit geçireceğiniz bir gün.',
                    'İç huzurunuzu bulmanın zamanı. Kendinize zaman ayırın.'
                ],
                'love' => [
                    'Aşk hayatınızda güzel gelişmeler olabilir.',
                    'Partnerinizle iletişiminizi güçlendirin.',
                    'Yeni tanışıklıklar hayatınıza renk katabilir.',
                    'Duygularınızı açık bir şekilde ifade edin.'
                ],
                'career' => [
                    'İş hayatınızda yeni fırsatlar doğabilir.',
                    'Projelerinizde başarılı olacağınız bir dönem.',
                    'Mesleki gelişiminiz için adımlar atın.',
                    'Ekip çalışmasında öne çıkacaksınız.'
                ],
                'health' => [
                    'Sağlığınıza dikkat etmeniz gereken bir dönem.',
                    'Düzenli egzersiz yapmanın faydalarını göreceksiniz.',
                    'Beslenme alışkanlıklarınızı gözden geçirin.',
                    'Stres seviyenizi kontrol altında tutun.'
                ]
            ],
            'weekly' => [
                'general' => [
                    'Bu hafta size yeni kapılar açılacak. Hazır olun.',
                    'Sabır ve kararlılık bu hafta anahtarınız olacak.',
                    'Yaratıcı projeleriniz için mükemmel bir hafta.',
                    'Sosyal aktiviteleriniz artacak, yeni dostluklar kuracaksınız.'
                ],
                'love' => [
                    'Bu hafta aşk hayatınızda heyecan verici gelişmeler yaşanabilir.',
                    'İlişkinizde yeni bir sayfa açılabilir.',
                    'Romantik sürprizler sizi bekliyor olabilir.'
                ],
                'career' => [
                    'Kariyer hedeflerinize ulaşmak için önemli adımlar atacaksınız.',
                    'İş yerinde tanınırlığınız artacak.',
                    'Yeni projeler kapınızı çalabilir.'
                ],
                'health' => [
                    'Bu hafta sağlığınıza özel dikkat gösterin.',
                    'Yeni bir egzersiz rutini başlamak için ideal zaman.',
                    'Beslenme düzeninizi gözden geçirin.'
                ]
            ],
            'monthly' => [
                'general' => [
                    'Bu ay kişisel gelişiminiz için harika fırsatlar sunuyor.',
                    'Hedeflerinize ulaşmak için sabırlı ve kararlı olun.',
                    'Yaratıcılığınızı keşfedeceğiniz bereketli bir ay.',
                    'İlişkilerinizde derinlik kazanacağınız bir dönem.'
                ],
                'love' => [
                    'Bu ay aşk hayatınızda köklü değişimler yaşanabilir.',
                    'Uzun vadeli ilişki planları yapabileceğiniz bir dönem.',
                    'Geçmiş ilişkilerden çıkarılacak dersler olabilir.'
                ],
                'career' => [
                    'Kariyer yolculuğunuzda önemli dönüm noktaları sizi bekliyor.',
                    'Uzun vadeli hedeflerinizi netleştirmenin zamanı.',
                    'Mesleki ağınızı genişletecek fırsatlar doğacak.'
                ],
                'health' => [
                    'Bu ay sağlık konularında bilinçli kararlar alacaksınız.',
                    'Yaşam tarzınızda kalıcı değişiklikler yapmanın zamanı.',
                    'Zihinsel sağlığınıza da önem vermeyi unutmayın.'
                ]
            ]
        ];

        $categoryMessages = $messages[$type][$category] ?? $messages['daily']['general'];
        $content = $categoryMessages[array_rand($categoryMessages)];
        $date = $this->getDateForType($type);

        return Horoscope::updateOrCreate([
            'zodiac_sign' => $zodiacSign,
            'date' => $date,
            'type' => $type,
            'category' => $category,
        ], [
            'content' => $content,
            'source' => 'advanced'
        ]);
    }

    private function getDateForType($type)
    {
        switch ($type) {
            case 'weekly':
                return Carbon::now()->startOfWeek();
            case 'monthly':
                return Carbon::now()->startOfMonth();
            default:
                return Carbon::today();
        }
    }
}