<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ForumCategory;
use Illuminate\Http\Request;

class ForumTopicController extends Controller
{
    public function getCategories()
    {
        $categories = [
            [
                'id' => 1,
                'name' => 'Anneler Kulübü',
                'slug' => 'anneler-kulubu',
                'description' => 'Hamilelik, doğum, bebek bakımı ve çocuk gelişimi konularında deneyim paylaşımı',
                'icon' => '🤱',
                'color' => '#e57399',
                'topics_count' => 3245,
                'members_count' => 18420,
                'recent_members' => ['AK', 'MZ', 'SY']
            ],
            [
                'id' => 2,
                'name' => 'Kariyer & İş Hayatı',
                'slug' => 'kariyer-is-hayati',
                'description' => 'İş arama, kariyer gelişimi, girişimcilik ve iş-yaşam dengesi',
                'icon' => '💼',
                'color' => '#3b82f6',
                'topics_count' => 1892,
                'members_count' => 12650,
                'recent_members' => ['EL', 'BT', 'NK']
            ],
            [
                'id' => 3,
                'name' => 'Sağlık & Wellness',
                'slug' => 'saglik-wellness',
                'description' => 'Kadın sağlığı, beslenme, egzersiz ve mental sağlık',
                'icon' => '🏥',
                'color' => '#10b981',
                'topics_count' => 2156,
                'members_count' => 15780,
                'recent_members' => ['DK', 'FG', 'HL']
            ],
            [
                'id' => 4,
                'name' => 'Güzellik & Bakım',
                'slug' => 'guzellik-bakim',
                'description' => 'Cilt bakımı, makyaj, saç bakımı ve güzellik ipuçları',
                'icon' => '💄',
                'color' => '#f59e0b',
                'topics_count' => 1567,
                'members_count' => 9340,
                'recent_members' => ['ZA', 'PL', 'QW']
            ],
            [
                'id' => 5,
                'name' => 'İlişkiler & Evlilik',
                'slug' => 'iliskiler-evlilik',
                'description' => 'Romantik ilişkiler, evlilik, aile hayatı ve sosyal ilişkiler',
                'icon' => '💕',
                'color' => '#ec4899',
                'topics_count' => 2890,
                'members_count' => 16720,
                'recent_members' => ['RT', 'UI', 'OP']
            ],
            [
                'id' => 6,
                'name' => 'Hobi & Yaşam',
                'slug' => 'hobi-yasam',
                'description' => 'El sanatları, yemek, dekorasyon ve yaşam tarzı',
                'icon' => '🎨',
                'color' => '#8b5cf6',
                'topics_count' => 1234,
                'members_count' => 8560,
                'recent_members' => ['AS', 'DF', 'GH']
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function getStats()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'totalTopics' => 12847,
                'todayTopics' => 23,
                'activeMembers' => 52341,
                'onlineNow' => 1247,
                'totalMessages' => 186592,
                'todayMessages' => 342,
                'expertAnswers' => 8934
            ]
        ]);
    }
    public function index(Request $request)
    {
        $topics = [
            [
                'id' => 1,
                'title' => 'Bebeğim 6 aylık oldu, ek gıdaya nasıl başlamalıyım?',
                'content' => 'Merhaba anneler, bebeğim 6 aylık oldu ve doktor ek gıdaya başlamamızı söyledi. Hangi yiyeceklerle başlamalıyım? Deneyimlerinizi paylaşır mısınız?',
                'excerpt' => 'Merhaba anneler, bebeğim 6 aylık oldu ve doktor ek gıdaya başlamamızı söyledi. Hangi yiyeceklerle başlamalıyım?',
                'author' => [
                    'id' => 1,
                    'name' => 'Ayşe M.',
                    'avatar' => 'AM',
                    'is_expert' => false
                ],
                'category' => [
                    'id' => 1,
                    'name' => 'Anneler Kulübü',
                    'slug' => 'anneler-kulubu'
                ],
                'replies_count' => 23,
                'views_count' => 1247,
                'likes_count' => 18,
                'is_pinned' => false,
                'is_locked' => false,
                'is_hot' => true,
                'has_expert_reply' => true,
                'last_reply' => [
                    'author' => 'Dr. Elif K.',
                    'created_at' => '2024-01-15T14:20:00Z'
                ],
                'created_at' => '2024-01-15T10:30:00Z',
                'updated_at' => '2024-01-15T14:20:00Z'
            ],
            [
                'id' => 2,
                'title' => 'İş görüşmesinde hamile olduğumu söylemeli miyim?',
                'content' => '3 aylık hamileyim ve yeni bir işe başvurdum. İş görüşmesinde hamile olduğumu belirtmeli miyim? Hukuki durumu bilen var mı?',
                'excerpt' => '3 aylık hamileyim ve yeni bir işe başvurdum. İş görüşmesinde hamile olduğumu belirtmeli miyim?',
                'author' => [
                    'id' => 2,
                    'name' => 'Zeynep K.',
                    'avatar' => 'ZK',
                    'is_expert' => false
                ],
                'category' => [
                    'id' => 2,
                    'name' => 'Kariyer & İş Hayatı',
                    'slug' => 'kariyer-is-hayati'
                ],
                'replies_count' => 31,
                'views_count' => 2156,
                'likes_count' => 42,
                'is_pinned' => true,
                'is_locked' => false,
                'is_hot' => true,
                'has_expert_reply' => true,
                'last_reply' => [
                    'author' => 'Av. Murat B.',
                    'created_at' => '2024-01-15T13:45:00Z'
                ],
                'created_at' => '2024-01-15T09:15:00Z',
                'updated_at' => '2024-01-15T13:45:00Z'
            ],
            [
                'id' => 3,
                'title' => 'Cilt bakım rutinimi ne değiştirmeliyim?',
                'content' => 'Yaşım 35, karma cilt tipim var. Son zamanlarda cildimde değişiklikler fark ettim. Hangi ürünleri kullanmalıyım?',
                'excerpt' => 'Yaşım 35, karma cilt tipim var. Son zamanlarda cildimde değişiklikler fark ettim.',
                'author' => [
                    'id' => 3,
                    'name' => 'Selin A.',
                    'avatar' => 'SA',
                    'is_expert' => false
                ],
                'category' => [
                    'id' => 4,
                    'name' => 'Güzellik & Bakım',
                    'slug' => 'guzellik-bakim'
                ],
                'replies_count' => 15,
                'views_count' => 892,
                'likes_count' => 12,
                'is_pinned' => false,
                'is_locked' => false,
                'is_hot' => false,
                'has_expert_reply' => false,
                'last_reply' => [
                    'author' => 'Fatma Y.',
                    'created_at' => '2024-01-15T12:30:00Z'
                ],
                'created_at' => '2024-01-15T08:45:00Z',
                'updated_at' => '2024-01-15T12:30:00Z'
            ],
            [
                'id' => 4,
                'title' => 'Doğum sonrası kilo verme deneyimleriniz',
                'content' => 'Doğum yaptım, 15 kilo aldım. Nasıl sağlıklı şekilde kilo verebilirim? Emzirirken diyet yapılır mı?',
                'excerpt' => 'Doğum yaptım, 15 kilo aldım. Nasıl sağlıklı şekilde kilo verebilirim?',
                'author' => [
                    'id' => 4,
                    'name' => 'Merve T.',
                    'avatar' => 'MT',
                    'is_expert' => false
                ],
                'category' => [
                    'id' => 3,
                    'name' => 'Sağlık & Wellness',
                    'slug' => 'saglik-wellness'
                ],
                'replies_count' => 28,
                'views_count' => 1834,
                'likes_count' => 35,
                'is_pinned' => false,
                'is_locked' => false,
                'is_hot' => true,
                'has_expert_reply' => true,
                'last_reply' => [
                    'author' => 'Dyt. Ayşe B.',
                    'created_at' => '2024-01-15T16:10:00Z'
                ],
                'created_at' => '2024-01-14T14:20:00Z',
                'updated_at' => '2024-01-15T16:10:00Z'
            ],
            [
                'id' => 5,
                'title' => 'Evlilik terapisi deneyimi olan var mı?',
                'content' => 'Eşimle iletişim problemleri yaşıyoruz. Evlilik terapisi almayı düşünüyoruz. Deneyimi olan var mı?',
                'excerpt' => 'Eşimle iletişim problemleri yaşıyoruz. Evlilik terapisi almayı düşünüyoruz.',
                'author' => [
                    'id' => 5,
                    'name' => 'Elif K.',
                    'avatar' => 'EK',
                    'is_expert' => false
                ],
                'category' => [
                    'id' => 5,
                    'name' => 'İlişkiler & Evlilik',
                    'slug' => 'iliskiler-evlilik'
                ],
                'replies_count' => 19,
                'views_count' => 967,
                'likes_count' => 24,
                'is_pinned' => false,
                'is_locked' => false,
                'is_hot' => false,
                'has_expert_reply' => true,
                'last_reply' => [
                    'author' => 'Psik. Deniz A.',
                    'created_at' => '2024-01-15T11:45:00Z'
                ],
                'created_at' => '2024-01-13T09:30:00Z',
                'updated_at' => '2024-01-15T11:45:00Z'
            ]
        ];

        // Filter by category if provided
        if ($request->has('category')) {
            $topics = array_filter($topics, function($topic) use ($request) {
                return $topic['category']['slug'] === $request->category;
            });
        }

        // Sort by pinned first, then by updated_at desc
        usort($topics, function($a, $b) {
            if ($a['is_pinned'] && !$b['is_pinned']) return -1;
            if (!$a['is_pinned'] && $b['is_pinned']) return 1;
            return strtotime($b['updated_at']) - strtotime($a['updated_at']);
        });

        return response()->json([
            'success' => true,
            'data' => array_values($topics),
            'meta' => [
                'total' => count($topics),
                'current_page' => 1,
                'per_page' => 20
            ]
        ]);
    }

    public function show($id)
    {
        $topics = [
            1 => [
                'id' => 1,
                'title' => 'Bebeğim 6 aylık oldu, ek gıdaya nasıl başlamalıyım?',
                'content' => 'Merhaba anneler, bebeğim 6 aylık oldu ve doktor ek gıdaya başlamamızı söyledi. Hangi yiyeceklerle başlamalıyım? Deneyimlerinizi paylaşır mısınız?',
                'author' => [
                    'id' => 1,
                    'name' => 'Ayşe M.',
                    'avatar' => 'AM',
                    'is_expert' => false
                ],
                'category' => [
                    'id' => 1,
                    'name' => 'Anneler Kulübü',
                    'slug' => 'anneler-kulubu'
                ],
                'replies_count' => 23,
                'views_count' => 1248,
                'likes_count' => 18,
                'is_pinned' => false,
                'is_locked' => false,
                'created_at' => '2024-01-15T10:30:00Z',
                'updated_at' => '2024-01-15T14:20:00Z'
            ]
        ];

        $topic = $topics[$id] ?? null;

        if (!$topic) {
            return response()->json([
                'success' => false,
                'message' => 'Konu bulunamadı'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $topic
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|integer',
        ]);

        $topic = [
            'id' => rand(1000, 9999),
            'title' => $request->title,
            'content' => $request->content,
            'author' => [
                'id' => auth()->id() ?? 1,
                'name' => auth()->user()->name ?? 'Anonim Kullanıcı',
                'avatar' => strtoupper(substr(auth()->user()->name ?? 'AK', 0, 2)),
                'is_expert' => false
            ],
            'category' => [
                'id' => $request->category_id,
                'name' => 'Genel',
                'slug' => 'genel'
            ],
            'replies_count' => 0,
            'views_count' => 0,
            'likes_count' => 0,
            'is_pinned' => false,
            'is_locked' => false,
            'created_at' => now()->toISOString(),
            'updated_at' => now()->toISOString()
        ];

        return response()->json([
            'success' => true,
            'message' => 'Konu başarıyla oluşturuldu',
            'data' => $topic
        ], 201);
    }
}