<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserService
{
    /**
     * Filtrelere göre kullanıcıları getir
     */
    public function getUsersWithFilters(array $filters): LengthAwarePaginator
    {
        $query = User::with(['profile']);

        // Arama filtresi
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Durum filtresi
        if (!empty($filters['status'])) {
            $isActive = $filters['status'] === 'active';
            $query->where('is_active', $isActive);
        }

        // Üyelik filtresi
        if (!empty($filters['membership'])) {
            if ($filters['membership'] === 'normal') {
                $query->where(function ($q) {
                    $q->where('membership_type', 'normal')
                      ->orWhereNull('membership_type');
                });
            } else {
                $query->where('membership_type', $filters['membership']);
            }
        }

        // Tarih filtreleri
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Sıralama
        $sortField = $filters['sort'] ?? 'created_at';
        $sortDirection = $filters['direction'] ?? 'desc';
        
        $allowedSortFields = ['name', 'email', 'created_at', 'is_active', 'membership_type'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Sayfalama
        $perPage = min($filters['per_page'] ?? 15, 100); // Max 100 kayıt
        
        return $query->paginate($perPage);
    }

    /**
     * Kullanıcı güncelle
     */
    public function updateUser(User $user, array $data): User
    {
        DB::beginTransaction();
        
        try {
            $user->update($data);
            
            // Üyelik değişikliği logla
            if (isset($data['membership_type']) && $data['membership_type'] !== $user->getOriginal('membership_type')) {
                $this->logMembershipChange($user, $user->getOriginal('membership_type'), $data['membership_type']);
            }
            
            // Durum değişikliği logla
            if (isset($data['is_active']) && $data['is_active'] !== $user->getOriginal('is_active')) {
                $this->logStatusChange($user, $data['is_active']);
            }
            
            DB::commit();
            return $user->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Kullanıcı durumunu değiştir
     */
    public function toggleUserStatus(User $user): User
    {
        $newStatus = !$user->is_active;
        
        $user->update(['is_active' => $newStatus]);
        
        $this->logStatusChange($user, $newStatus);
        
        return $user;
    }

    /**
     * Kullanıcı istatistiklerini getir
     */
    public function getUserStats(User $user): array
    {
        $stats = [
            'basic_info' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_active' => $user->is_active,
                'membership_type' => $user->membership_type ?? 'normal',
                'membership_expires_at' => $user->membership_expires_at,
                'created_at' => $user->created_at,
                'last_login' => $this->getLastLogin($user),
            ],
            'activity' => [
                'blog_posts' => $this->getBlogPostsCount($user),
                'forum_topics' => $this->getForumTopicsCount($user),
                'forum_posts' => $this->getForumPostsCount($user),
                'comments' => $this->getCommentsCount($user),
            ],
            'engagement' => [
                'total_views' => $this->getTotalViews($user),
                'total_likes' => $this->getTotalLikes($user),
                'reputation_score' => $this->getReputationScore($user),
                'badges_count' => $this->getBadgesCount($user),
            ],
            'commerce' => [
                'orders_count' => $this->getOrdersCount($user),
                'total_spent' => $this->getTotalSpent($user),
                'last_order' => $this->getLastOrder($user),
            ]
        ];

        return $stats;
    }

    /**
     * Kullanıcı avatar'ını güncelle
     */
    public function updateAvatar(User $user, $avatarFile): string
    {
        // Eski avatar'ı sil
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Yeni avatar'ı kaydet
        $path = $avatarFile->store('avatars', 'public');
        
        $user->update(['avatar' => $path]);
        
        return $path;
    }

    /**
     * Kullanıcı verilerini dışa aktar
     */
    public function exportUsers(array $filters = []): array
    {
        $users = $this->getUsersWithFilters(array_merge($filters, ['per_page' => 10000]));
        
        return $users->map(function ($user) {
            return [
                'ID' => $user->id,
                'Ad Soyad' => $user->name,
                'Email' => $user->email,
                'Durum' => $user->is_active ? 'Aktif' : 'Pasif',
                'Üyelik Tipi' => $user->membership_type ?? 'Normal',
                'Üyelik Bitiş' => $user->membership_expires_at?->format('d.m.Y'),
                'Kayıt Tarihi' => $user->created_at->format('d.m.Y H:i'),
                'Son Giriş' => $this->getLastLogin($user)?->format('d.m.Y H:i'),
                'Blog Yazıları' => $this->getBlogPostsCount($user),
                'Forum Konuları' => $this->getForumTopicsCount($user),
                'Forum Gönderileri' => $this->getForumPostsCount($user),
            ];
        })->toArray();
    }

    /**
     * Kullanıcı aktivite geçmişi
     */
    public function getUserActivityHistory(User $user, int $limit = 50): array
    {
        $activities = [];

        // Blog yazıları
        if (class_exists(\App\Models\BlogPost::class)) {
            $blogPosts = $user->blogPosts()->latest()->take($limit/4)->get();
            foreach ($blogPosts as $post) {
                $activities[] = [
                    'type' => 'blog_post',
                    'title' => 'Blog yazısı oluşturdu',
                    'description' => $post->title,
                    'date' => $post->created_at,
                    'url' => route('admin.blog.show', $post),
                ];
            }
        }

        // Forum konuları
        if (class_exists(\App\Models\ForumTopic::class)) {
            $forumTopics = $user->forumTopics()->latest()->take($limit/4)->get();
            foreach ($forumTopics as $topic) {
                $activities[] = [
                    'type' => 'forum_topic',
                    'title' => 'Forum konusu oluşturdu',
                    'description' => $topic->title,
                    'date' => $topic->created_at,
                    'url' => route('admin.forum.topics.show', $topic),
                ];
            }
        }

        // Forum gönderileri
        if (class_exists(\App\Models\ForumPost::class)) {
            $forumPosts = $user->forumPosts()->latest()->take($limit/4)->get();
            foreach ($forumPosts as $post) {
                $activities[] = [
                    'type' => 'forum_post',
                    'title' => 'Forum gönderisi yaptı',
                    'description' => substr(strip_tags($post->content), 0, 100) . '...',
                    'date' => $post->created_at,
                    'url' => route('admin.forum.topics.show', $post->topic_id),
                ];
            }
        }

        // Siparişler
        if (class_exists(\App\Models\Order::class)) {
            $orders = $user->orders()->latest()->take($limit/4)->get();
            foreach ($orders as $order) {
                $activities[] = [
                    'type' => 'order',
                    'title' => 'Sipariş verdi',
                    'description' => number_format($order->total_amount, 2) . ' TL',
                    'date' => $order->created_at,
                    'url' => route('admin.orders.show', $order),
                ];
            }
        }

        // Tarihe göre sırala
        usort($activities, function ($a, $b) {
            return $b['date'] <=> $a['date'];
        });

        return array_slice($activities, 0, $limit);
    }

    /**
     * Kullanıcı önerilerini getir
     */
    public function getUserRecommendations(User $user): array
    {
        $recommendations = [];

        // Pasif kullanıcı uyarısı
        if (!$user->is_active) {
            $recommendations[] = [
                'type' => 'warning',
                'title' => 'Kullanıcı Pasif',
                'description' => 'Bu kullanıcı pasif durumda. Aktif yapmayı düşünün.',
                'action' => 'Aktif Yap',
                'url' => route('admin.users.toggle-status', $user),
            ];
        }

        // Email doğrulama uyarısı
        if (!$user->email_verified_at) {
            $recommendations[] = [
                'type' => 'info',
                'title' => 'Email Doğrulanmamış',
                'description' => 'Kullanıcı email adresini doğrulamamış.',
                'action' => 'Email Doğrula',
                'url' => '#',
            ];
        }

        // Üyelik yükseltme önerisi
        if (($user->membership_type ?? 'normal') === 'normal') {
            $activityScore = $this->calculateActivityScore($user);
            if ($activityScore > 50) {
                $recommendations[] = [
                    'type' => 'success',
                    'title' => 'Üyelik Yükseltme Önerisi',
                    'description' => 'Bu kullanıcı aktif. Premium üyelik önerebilirsiniz.',
                    'action' => 'Düzenle',
                    'url' => route('admin.users.edit', $user),
                ];
            }
        }

        return $recommendations;
    }

    // Private helper methods

    private function getLastLogin(User $user): ?\Carbon\Carbon
    {
        // Bu bilgi session tablosundan veya ayrı bir login_logs tablosundan alınabilir
        // Şimdilik null döndürüyoruz
        return null;
    }

    private function getBlogPostsCount(User $user): int
    {
        return $user->blogPosts()->count() ?? 0;
    }

    private function getForumTopicsCount(User $user): int
    {
        return $user->forumTopics()->count() ?? 0;
    }

    private function getForumPostsCount(User $user): int
    {
        return $user->forumPosts()->count() ?? 0;
    }

    private function getCommentsCount(User $user): int
    {
        // Blog yorumları sayısı
        return 0; // Implement when BlogComment model is available
    }

    private function getTotalViews(User $user): int
    {
        $views = 0;
        
        // Blog yazılarının toplam görüntülenmesi
        if ($user->blogPosts) {
            $views += $user->blogPosts->sum('views') ?? 0;
        }
        
        // Forum konularının toplam görüntülenmesi
        if ($user->forumTopics) {
            $views += $user->forumTopics->sum('views') ?? 0;
        }
        
        return $views;
    }

    private function getTotalLikes(User $user): int
    {
        // Like sistemi implement edildiğinde burası doldurulacak
        return 0;
    }

    private function getReputationScore(User $user): int
    {
        return $user->reputation_score ?? 0;
    }

    private function getBadgesCount(User $user): int
    {
        return $user->badges()->count() ?? 0;
    }

    private function getOrdersCount(User $user): int
    {
        try {
            return $user->orders()->count() ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getTotalSpent(User $user): float
    {
        try {
            return $user->orders()->where('status', 'completed')->sum('total_amount') ?? 0.0;
        } catch (\Exception $e) {
            return 0.0;
        }
    }

    private function getLastOrder(User $user): ?\Carbon\Carbon
    {
        try {
            $lastOrder = $user->orders()->latest()->first();
            return $lastOrder?->created_at;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function calculateActivityScore(User $user): int
    {
        $score = 0;
        
        // Blog yazıları (her yazı 10 puan)
        $score += $this->getBlogPostsCount($user) * 10;
        
        // Forum konuları (her konu 5 puan)
        $score += $this->getForumTopicsCount($user) * 5;
        
        // Forum gönderileri (her gönderi 2 puan)
        $score += $this->getForumPostsCount($user) * 2;
        
        // Siparişler (her sipariş 15 puan)
        $score += $this->getOrdersCount($user) * 15;
        
        return min($score, 100); // Max 100 puan
    }

    private function logMembershipChange(User $user, ?string $oldType, ?string $newType): void
    {
        \Log::info('User membership changed', [
            'user_id' => $user->id,
            'old_membership' => $oldType,
            'new_membership' => $newType,
            'changed_by' => auth()->id(),
            'timestamp' => now(),
        ]);
    }

    private function logStatusChange(User $user, bool $newStatus): void
    {
        \Log::info('User status changed', [
            'user_id' => $user->id,
            'new_status' => $newStatus ? 'active' : 'inactive',
            'changed_by' => auth()->id(),
            'timestamp' => now(),
        ]);
    }
}