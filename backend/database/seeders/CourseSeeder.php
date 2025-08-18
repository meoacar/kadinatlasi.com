<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'title' => 'Kadın Sağlığı ve Beslenme',
                'description' => 'Kadınların yaşam dönemlerine göre beslenme ihtiyaçları, hormonlar ve sağlık üzerine kapsamlı bir eğitim.',
                'instructor_name' => 'Dr. Ayşe Kaya',
                'price' => 0,
                'duration' => 180,
                'level' => 'beginner',
                'category' => 'Sağlık',
                'image_url' => 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=400',
                'content' => 'Bu kursta kadın sağlığının temel prensiplerini öğreneceksiniz...',
                'enrollment_count' => 45,
                'rating' => 4.8
            ],
            [
                'title' => 'Gebelik Döneminde Yoga',
                'description' => 'Hamilelik sürecinde güvenli yoga pozları ve nefes teknikleri ile rahat bir gebelik geçirin.',
                'instructor_name' => 'Zeynep Demir',
                'price' => 0,
                'duration' => 120,
                'level' => 'beginner',
                'category' => 'Anne & Bebek',
                'image_url' => 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=400',
                'content' => 'Gebelik döneminde yapılabilecek güvenli yoga pozları...',
                'enrollment_count' => 32,
                'rating' => 4.9
            ],
            [
                'title' => 'Kişisel Gelişim ve Özgüven',
                'description' => 'İç sesinizi keşfedin, özgüveninizi artırın ve hayallerinize ulaşmak için gerekli adımları atın.',
                'instructor_name' => 'Psikolog Elif Yılmaz',
                'price' => 0,
                'duration' => 240,
                'level' => 'intermediate',
                'category' => 'Psikoloji',
                'image_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400',
                'content' => 'Özgüven geliştirme teknikleri ve kişisel gelişim stratejileri...',
                'enrollment_count' => 67,
                'rating' => 4.7
            ],
            [
                'title' => 'Doğal Cilt Bakımı',
                'description' => 'Evde hazırlayabileceğiniz doğal maskeler ve cilt bakım rutinleri ile sağlıklı bir cilde sahip olun.',
                'instructor_name' => 'Güzellik Uzmanı Selin Öz',
                'price' => 0,
                'duration' => 90,
                'level' => 'beginner',
                'category' => 'Güzellik',
                'image_url' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?w=400',
                'content' => 'Doğal cilt bakım ürünleri ve uygulama teknikleri...',
                'enrollment_count' => 89,
                'rating' => 4.6
            ],
            [
                'title' => 'Ev Pilates Antrenmanları',
                'description' => 'Evde yapabileceğiniz etkili pilates hareketleri ile vücut tonunuzu artırın ve esnekliğinizi geliştirin.',
                'instructor_name' => 'Fitness Antrenörü Melis Can',
                'price' => 0,
                'duration' => 150,
                'level' => 'intermediate',
                'category' => 'Fitness',
                'image_url' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400',
                'content' => 'Temel pilates hareketleri ve ev antrenman programları...',
                'enrollment_count' => 56,
                'rating' => 4.8
            ],
            [
                'title' => 'Sağlıklı Yemek Tarifleri',
                'description' => 'Besleyici ve lezzetli yemek tarifleri ile ailenizin sağlığını koruyun.',
                'instructor_name' => 'Şef Diyetisyen Burcu Ak',
                'price' => 0,
                'duration' => 200,
                'level' => 'beginner',
                'category' => 'Beslenme',
                'image_url' => 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=400',
                'content' => 'Sağlıklı beslenme prensipleri ve pratik tarifler...',
                'enrollment_count' => 78,
                'rating' => 4.5
            ]
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}