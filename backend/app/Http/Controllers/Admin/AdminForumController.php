<?php

namespace App\Http\Controllers\Admin;

use App\Models\ForumTopic;
use App\Models\ForumPost;
use App\Models\ForumGroup;
use App\Models\User;
use App\Services\ForumService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminForumController extends AdminController
{
    protected $forumService;

    public function __construct(ForumService $forumService)
    {
        parent::__construct();
        $this->forumService = $forumService;
    }

    /**
     * Forum ana sayfası - genel istatistikler
     */
    public function index(Request $request)
    {
        try {
            $stats = [
                'total_topics' => ForumTopic::count(),
                'total_posts' => ForumPost::count(),
                'total_groups' => ForumGroup::count(),
                'active_topics' => ForumTopic::where('is_active', true)->count(),
                'pending_posts' => ForumPost::where('is_approved', false)->count(),
                'today_topics' => ForumTopic::whereDate('created_at', today())->count(),
                'today_posts' => ForumPost::whereDate('created_at', today())->count(),
                'active_users' => User::whereHas('forumPosts', function($q) {
                    $q->where('created_at', '>=', now()->subDays(7));
                })->count(),
            ];

            // Son aktiviteler
            $recentTopics = ForumTopic::with(['user', 'group', 'posts'])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            $recentPosts = ForumPost::with(['user', 'topic'])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            // Moderasyon gereken içerikler
            $pendingPosts = ForumPost::with(['user', 'topic'])
                ->where('is_approved', false)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            return view('admin.forum.index', compact(
                'stats', 
                'recentTopics', 
                'recentPosts', 
                'pendingPosts'
            ));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load forum dashboard');
        }
    }

    /**
     * Forum konularını listeleme
     */
    public function topics(Request $request)
    {
        try {
            $filters = $this->getPaginationData($request);
            $filters['status'] = $request->get('status');
            $filters['group_id'] = $request->get('group_id');
            $filters['user_id'] = $request->get('user_id');
            $filters['date_from'] = $request->get('date_from');
            $filters['date_to'] = $request->get('date_to');

            $topics = $this->forumService->getTopicsWithFilters($filters);
            $groups = ForumGroup::orderBy('name')->get();

            $stats = [
                'total' => ForumTopic::count(),
                'active' => ForumTopic::where('is_active', true)->count(),
                'inactive' => ForumTopic::where('is_active', false)->count(),
                'pinned' => ForumTopic::where('is_pinned', true)->count(),
                'locked' => ForumTopic::where('is_locked', true)->count(),
                'today' => ForumTopic::whereDate('created_at', today())->count(),
            ];

            return view('admin.forum.topics', compact('topics', 'groups', 'stats', 'filters'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load forum topics');
        }
    }

    /**
     * Forum gönderilerini moderasyon
     */
    public function posts(Request $request)
    {
        try {
            $filters = $this->getPaginationData($request);
            $filters['status'] = $request->get('status');
            $filters['approval'] = $request->get('approval');
            $filters['topic_id'] = $request->get('topic_id');
            $filters['user_id'] = $request->get('user_id');
            $filters['date_from'] = $request->get('date_from');
            $filters['date_to'] = $request->get('date_to');

            $posts = $this->forumService->getPostsWithFilters($filters);

            $stats = [
                'total' => ForumPost::count(),
                'approved' => ForumPost::where('is_approved', true)->count(),
                'pending' => ForumPost::where('is_approved', false)->count(),
                'reported' => ForumPost::where('is_reported', true)->count(),
                'today' => ForumPost::whereDate('created_at', today())->count(),
            ];

            return view('admin.forum.posts', compact('posts', 'stats', 'filters'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load forum posts');
        }
    }

    /**
     * Forum gruplarını yönetme
     */
    public function groups(Request $request)
    {
        try {
            $search = $request->get('search');
            $sort = $request->get('sort', 'name');
            $direction = $request->get('direction', 'asc');

            $query = ForumGroup::withCount(['topics', 'posts']);

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            $allowedSortFields = ['name', 'topics_count', 'posts_count', 'created_at', 'is_active'];
            if (in_array($sort, $allowedSortFields)) {
                $query->orderBy($sort, $direction);
            }

            $groups = $query->paginate(20);

            $stats = [
                'total' => ForumGroup::count(),
                'active' => ForumGroup::where('is_active', true)->count(),
                'inactive' => ForumGroup::where('is_active', false)->count(),
                'with_topics' => ForumGroup::has('topics')->count(),
            ];

            return view('admin.forum.groups', compact('groups', 'stats', 'search', 'sort', 'direction'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load forum groups');
        }
    }

    /**
     * Yeni forum grubu oluştur
     */
    public function storeGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:forum_groups,name',
            'slug' => 'nullable|string|max:255|unique:forum_groups,slug',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ], [
            'name.required' => 'Grup adı gereklidir.',
            'name.unique' => 'Bu grup adı zaten kullanılıyor.',
            'slug.unique' => 'Bu slug zaten kullanılıyor.',
        ]);

        try {
            $groupData = $request->only([
                'name', 'slug', 'description', 'color', 'icon', 'is_active', 'sort_order'
            ]);

            if (empty($groupData['slug'])) {
                $groupData['slug'] = Str::slug($groupData['name']);
            }

            ForumGroup::create($groupData);

            return $this->successResponse('Forum grubu başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'create forum group');
        }
    }

    /**
     * Forum grubu güncelle
     */
    public function updateGroup(Request $request, ForumGroup $group)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('forum_groups')->ignore($group->id)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('forum_groups')->ignore($group->id)],
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            $groupData = $request->only([
                'name', 'slug', 'description', 'color', 'icon', 'is_active', 'sort_order'
            ]);

            if (empty($groupData['slug'])) {
                $groupData['slug'] = Str::slug($groupData['name']);
            }

            $group->update($groupData);

            return $this->successResponse('Forum grubu başarıyla güncellendi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'update forum group');
        }
    }

    /**
     * Forum grubu sil
     */
    public function destroyGroup(ForumGroup $group)
    {
        try {
            if ($group->topics()->count() > 0) {
                return $this->errorResponse('Bu gruba ait konular bulunduğu için silinemez.');
            }

            $group->delete();

            return $this->successResponse('Forum grubu başarıyla silindi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'delete forum group');
        }
    }

    /**
     * Konu durumunu değiştir (aktif/pasif)
     */
    public function toggleTopicStatus(ForumTopic $topic)
    {
        try {
            $topic->update(['is_active' => !$topic->is_active]);
            $status = $topic->is_active ? 'aktif' : 'pasif';

            return $this->successResponse("Konu başarıyla {$status} yapıldı.");
        } catch (\Exception $e) {
            return $this->handleException($e, 'toggle topic status');
        }
    }

    /**
     * Konuyu sabitle/sabitlemeden kaldır
     */
    public function toggleTopicPin(ForumTopic $topic)
    {
        try {
            $topic->update(['is_pinned' => !$topic->is_pinned]);
            $status = $topic->is_pinned ? 'sabitlendi' : 'sabitlemeden kaldırıldı';

            return $this->successResponse("Konu başarıyla {$status}.");
        } catch (\Exception $e) {
            return $this->handleException($e, 'toggle topic pin');
        }
    }

    /**
     * Konuyu kilitle/kilidi aç
     */
    public function toggleTopicLock(ForumTopic $topic)
    {
        try {
            $topic->update(['is_locked' => !$topic->is_locked]);
            $status = $topic->is_locked ? 'kilitlendi' : 'kilidi açıldı';

            return $this->successResponse("Konu başarıyla {$status}.");
        } catch (\Exception $e) {
            return $this->handleException($e, 'toggle topic lock');
        }
    }

    /**
     * Gönderiyi onayla
     */
    public function approvePost(ForumPost $post)
    {
        try {
            $post->update(['is_approved' => true]);

            return $this->successResponse('Gönderi başarıyla onaylandı.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'approve post');
        }
    }

    /**
     * Gönderiyi reddet
     */
    public function rejectPost(ForumPost $post)
    {
        try {
            $post->update(['is_approved' => false]);

            return $this->successResponse('Gönderi başarıyla reddedildi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'reject post');
        }
    }

    /**
     * Gönderiyi sil
     */
    public function deletePost(ForumPost $post)
    {
        try {
            $post->delete();

            return $this->successResponse('Gönderi başarıyla silindi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'delete post');
        }
    }

    /**
     * Konuları toplu işlem
     */
    public function bulkTopicAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,pin,unpin,lock,unlock,delete',
            'topics' => 'required|array|min:1',
            'topics.*' => 'exists:forum_topics,id',
        ]);

        try {
            $topicIds = $request->topics;
            $action = $request->action;
            $topics = ForumTopic::whereIn('id', $topicIds)->get();

            if ($topics->isEmpty()) {
                return $this->errorResponse('İşlem yapılacak konu bulunamadı.');
            }

            $count = 0;
            switch ($action) {
                case 'activate':
                    $count = $topics->where('is_active', false)->count();
                    ForumTopic::whereIn('id', $topicIds)->update(['is_active' => true]);
                    break;
                case 'deactivate':
                    $count = $topics->where('is_active', true)->count();
                    ForumTopic::whereIn('id', $topicIds)->update(['is_active' => false]);
                    break;
                case 'pin':
                    $count = $topics->where('is_pinned', false)->count();
                    ForumTopic::whereIn('id', $topicIds)->update(['is_pinned' => true]);
                    break;
                case 'unpin':
                    $count = $topics->where('is_pinned', true)->count();
                    ForumTopic::whereIn('id', $topicIds)->update(['is_pinned' => false]);
                    break;
                case 'lock':
                    $count = $topics->where('is_locked', false)->count();
                    ForumTopic::whereIn('id', $topicIds)->update(['is_locked' => true]);
                    break;
                case 'unlock':
                    $count = $topics->where('is_locked', true)->count();
                    ForumTopic::whereIn('id', $topicIds)->update(['is_locked' => false]);
                    break;
                case 'delete':
                    $count = $topics->count();
                    ForumTopic::whereIn('id', $topicIds)->delete();
                    break;
            }

            $actionText = match($action) {
                'activate' => 'aktif yapıldı',
                'deactivate' => 'pasif yapıldı',
                'pin' => 'sabitlendi',
                'unpin' => 'sabitlemeden kaldırıldı',
                'lock' => 'kilitlendi',
                'unlock' => 'kilidi açıldı',
                'delete' => 'silindi',
            };

            return $this->successResponse("{$count} konu başarıyla {$actionText}.");
        } catch (\Exception $e) {
            return $this->handleException($e, 'perform bulk topic action');
        }
    }

    /**
     * Gönderileri toplu işlem
     */
    public function bulkPostAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'posts' => 'required|array|min:1',
            'posts.*' => 'exists:forum_posts,id',
        ]);

        try {
            $postIds = $request->posts;
            $action = $request->action;
            $posts = ForumPost::whereIn('id', $postIds)->get();

            if ($posts->isEmpty()) {
                return $this->errorResponse('İşlem yapılacak gönderi bulunamadı.');
            }

            $count = 0;
            switch ($action) {
                case 'approve':
                    $count = $posts->where('is_approved', false)->count();
                    ForumPost::whereIn('id', $postIds)->update(['is_approved' => true]);
                    break;
                case 'reject':
                    $count = $posts->where('is_approved', true)->count();
                    ForumPost::whereIn('id', $postIds)->update(['is_approved' => false]);
                    break;
                case 'delete':
                    $count = $posts->count();
                    ForumPost::whereIn('id', $postIds)->delete();
                    break;
            }

            $actionText = match($action) {
                'approve' => 'onaylandı',
                'reject' => 'reddedildi',
                'delete' => 'silindi',
            };

            return $this->successResponse("{$count} gönderi başarıyla {$actionText}.");
        } catch (\Exception $e) {
            return $this->handleException($e, 'perform bulk post action');
        }
    }

    /**
     * Forum istatistikleri
     */
    public function stats()
    {
        try {
            $stats = $this->forumService->getForumStats();

            return view('admin.forum.stats', compact('stats'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load forum stats');
        }
    }

    /**
     * Forum verilerini dışa aktar
     */
    public function export(Request $request)
    {
        try {
            $type = $request->get('type', 'topics'); // topics, posts, groups
            $filters = $request->only(['status', 'group_id', 'date_from', 'date_to']);

            $data = $this->forumService->exportForumData($type, $filters);
            $filename = "forum_{$type}_" . now()->format('Y_m_d_H_i_s') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $callback = function() use ($data, $type) {
                $file = fopen('php://output', 'w');
                
                // CSV başlıkları
                if ($type === 'topics') {
                    fputcsv($file, [
                        'ID', 'Başlık', 'Grup', 'Kullanıcı', 'Gönderi Sayısı', 
                        'Durum', 'Sabitlenmiş', 'Kilitli', 'Oluşturma Tarihi'
                    ]);
                } elseif ($type === 'posts') {
                    fputcsv($file, [
                        'ID', 'Konu', 'Kullanıcı', 'İçerik', 'Onaylanmış', 
                        'Raporlanmış', 'Oluşturma Tarihi'
                    ]);
                } elseif ($type === 'groups') {
                    fputcsv($file, [
                        'ID', 'Grup Adı', 'Slug', 'Konu Sayısı', 'Gönderi Sayısı', 
                        'Durum', 'Sıra', 'Oluşturma Tarihi'
                    ]);
                }

                // Veri satırları
                foreach ($data as $row) {
                    fputcsv($file, $row);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return $this->handleException($e, 'export forum data');
        }
    }

    /**
     * Kullanıcı forum aktivitesi
     */
    public function userActivity(User $user)
    {
        try {
            $topics = ForumTopic::where('user_id', $user->id)
                ->with(['group', 'posts'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $posts = ForumPost::where('user_id', $user->id)
                ->with(['topic'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $stats = [
                'total_topics' => ForumTopic::where('user_id', $user->id)->count(),
                'total_posts' => ForumPost::where('user_id', $user->id)->count(),
                'approved_posts' => ForumPost::where('user_id', $user->id)->where('is_approved', true)->count(),
                'pending_posts' => ForumPost::where('user_id', $user->id)->where('is_approved', false)->count(),
            ];

            return view('admin.forum.user-activity', compact('user', 'topics', 'posts', 'stats'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load user forum activity');
        }
    }

    /**
     * Moderasyon raporu
     */
    public function moderationReport(Request $request)
    {
        try {
            $dateFrom = $request->get('date_from', now()->subDays(30)->format('Y-m-d'));
            $dateTo = $request->get('date_to', now()->format('Y-m-d'));

            $report = $this->forumService->getModerationReport($dateFrom, $dateTo);

            return view('admin.forum.moderation-report', compact('report', 'dateFrom', 'dateTo'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load moderation report');
        }
    }
}