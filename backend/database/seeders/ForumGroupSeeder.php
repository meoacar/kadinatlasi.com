<?php

namespace Database\Seeders;

use App\Models\ForumGroup;
use App\Models\User;
use Illuminate\Database\Seeder;

class ForumGroupSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@kadinatlasi.com')->first();
        
        if (!$admin) {
            return;
        }

        $groups = [
            [
                'name' => 'Anneler Kulübü',
                'description' => 'Annelerin deneyim paylaştığı, birbirlerine destek olduğu özel grup',
                'icon' => '👶',
                'color' => '#FF6B9D',
                'is_private' => false,
                'requires_approval' => true,
            ],
            [
                'name' => 'Kariyer Kadınları',
                'description' => 'İş hayatında başarılı olmak isteyen kadınlar için kariyer odaklı grup',
                'icon' => '💼',
                'color' => '#4ECDC4',
                'is_private' => false,
                'requires_approval' => false,
            ],
            [
                'name' => 'Sağlıklı Yaşam',
                'description' => 'Sağlıklı beslenme, spor ve yaşam tarzı hakkında bilgi paylaşımı',
                'icon' => '🏃‍♀️',
                'color' => '#45B7D1',
                'is_private' => false,
                'requires_approval' => false,
            ],
            [
                'name' => 'Güzellik & Bakım',
                'description' => 'Güzellik ipuçları, cilt bakımı ve makyaj teknikleri',
                'icon' => '💄',
                'color' => '#F39C12',
                'is_private' => false,
                'requires_approval' => false,
            ],
            [
                'name' => 'Hobiler & Sanat',
                'description' => 'El sanatları, resim, müzik ve diğer hobiler için yaratıcı alan',
                'icon' => '🎨',
                'color' => '#9B59B6',
                'is_private' => false,
                'requires_approval' => false,
            ]
        ];

        foreach ($groups as $groupData) {
            $group = ForumGroup::create([
                ...$groupData,
                'creator_id' => $admin->id,
                'member_count' => 1,
            ]);

            // Admin'i grup yöneticisi yap
            $group->members()->attach($admin->id, [
                'role' => 'admin',
                'is_approved' => true,
                'joined_at' => now()
            ]);
        }
    }
}