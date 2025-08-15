<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends AdminController
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }

    /**
     * Kullanıcı listesi
     */
    public function index(Request $request)
    {
        try {
            $query = User::query();

            // Arama
            if ($request->filled('search')) {
                $query->search($request->search);
            }

            // Hızlı tarih filtresi
            if ($request->filled('quick_date')) {
                $query->quickDateFilter($request->quick_date);
            }

            // Gelişmiş filtreler
            $query->advancedFilter($request);

            $users = $query->paginate($request->get('per_page', 15))
                          ->withQueryString();
            
            $stats = [
                'total' => User::count(),
                'active' => User::where('is_active', true)->count(),
                'inactive' => User::where('is_active', false)->count(),
                'today' => User::whereDate('created_at', today())->count(),
            ];

            // Filtre seçenekleri
            $statusOptions = [
                'active' => 'Aktif',
                'inactive' => 'Pasif'
            ];

            return view('admin.users.index', compact('users', 'stats', 'statusOptions'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load users');
        }
    }

    /**
     * Kullanıcı oluşturma formu
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Yeni kullanıcı oluştur
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'birth_date' => 'nullable|date|before:today',
            'membership_type' => 'nullable|in:normal,basic,premium,vip',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Ad soyad gereklidir.',
            'email.required' => 'Email adresi gereklidir.',
            'email.email' => 'Geçerli bir email adresi giriniz.',
            'email.unique' => 'Bu email adresi zaten kullanılıyor.',
            'password.required' => 'Şifre gereklidir.',
            'password.min' => 'Şifre en az 8 karakter olmalıdır.',
            'password.confirmed' => 'Şifre onayı eşleşmiyor.',
            'birth_date.before' => 'Doğum tarihi bugünden önce olmalıdır.',
        ]);

        try {
            $userData = $request->only(['name', 'email', 'birth_date', 'membership_type']);
            $userData['password'] = Hash::make($request->password);
            $userData['is_active'] = $request->boolean('is_active', true);
            $userData['email_verified_at'] = now();

            $user = User::create($userData);

            // Profil oluştur (eğer UserProfile modeli varsa)
            if (class_exists(\App\Models\UserProfile::class)) {
                $user->profile()->create([
                    'bio' => $request->bio,
                    'interests' => $request->interests ? explode(',', $request->interests) : [],
                ]);
            }

            return $this->successResponse('Kullanıcı başarıyla oluşturuldu.')
                ->with('redirect', route('admin.users.show', $user));
        } catch (\Exception $e) {
            return $this->handleException($e, 'create user');
        }
    }

    /**
     * Kullanıcı detayları
     */
    public function show(User $user)
    {
        try {
            $user->load(['profile', 'blogPosts', 'forumTopics']);
            
            $stats = $this->userService->getUserStats($user);
            
            return view('admin.users.show', compact('user', 'stats'));
        } catch (\Exception $e) {
            return $this->handleException($e, 'load user details');
        }
    }

    /**
     * Kullanıcı düzenleme formu
     */
    public function edit(User $user)
    {
        $user->load('profile');
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Kullanıcı güncelle
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'birth_date' => 'nullable|date|before:today',
            'membership_type' => 'nullable|in:normal,basic,premium,vip',
            'membership_expires_at' => 'nullable|date|after:today',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Ad soyad gereklidir.',
            'email.required' => 'Email adresi gereklidir.',
            'email.email' => 'Geçerli bir email adresi giriniz.',
            'email.unique' => 'Bu email adresi zaten kullanılıyor.',
            'password.min' => 'Şifre en az 8 karakter olmalıdır.',
            'password.confirmed' => 'Şifre onayı eşleşmiyor.',
            'birth_date.before' => 'Doğum tarihi bugünden önce olmalıdır.',
            'membership_expires_at.after' => 'Üyelik bitiş tarihi gelecekte olmalıdır.',
        ]);

        try {
            $userData = $request->only(['name', 'email', 'birth_date', 'membership_type', 'membership_expires_at']);
            $userData['is_active'] = $request->boolean('is_active', true);

            // Şifre güncellenecekse
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user = $this->userService->updateUser($user, $userData);

            // Profil güncelle (eğer varsa)
            if ($user->profile && $request->has(['bio', 'interests'])) {
                $user->profile->update([
                    'bio' => $request->bio,
                    'interests' => $request->interests ? explode(',', $request->interests) : [],
                ]);
            }

            return $this->successResponse('Kullanıcı başarıyla güncellendi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'update user');
        }
    }

    /**
     * Kullanıcı sil (soft delete)
     */
    public function destroy(User $user)
    {
        try {
            // Admin kullanıcısını silmeyi engelle
            if ($this->isAdminUser($user)) {
                return $this->errorResponse('Admin kullanıcısı silinemez.');
            }

            // Mevcut kullanıcının kendisini silmesini engelle
            if ($user->id === auth()->id()) {
                return $this->errorResponse('Kendi hesabınızı silemezsiniz.');
            }

            $user->delete();

            return $this->successResponse('Kullanıcı başarıyla silindi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'delete user');
        }
    }

    /**
     * Kullanıcı durumunu değiştir (aktif/pasif)
     */
    public function toggleStatus(User $user)
    {
        try {
            // Admin kullanıcısını pasif yapmayı engelle
            if ($this->isAdminUser($user) && $user->is_active) {
                return $this->errorResponse('Admin kullanıcısı pasif yapılamaz.');
            }

            // Mevcut kullanıcının kendisini pasif yapmasını engelle
            if ($user->id === auth()->id() && $user->is_active) {
                return $this->errorResponse('Kendi hesabınızı pasif yapamazsınız.');
            }

            $user = $this->userService->toggleUserStatus($user);
            
            $status = $user->is_active ? 'aktif' : 'pasif';
            return $this->successResponse("Kullanıcı başarıyla {$status} yapıldı.");
        } catch (\Exception $e) {
            return $this->handleException($e, 'toggle user status');
        }
    }

    /**
     * Kullanıcı avatar'ını güncelle
     */
    public function updateAvatar(Request $request, User $user)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'avatar.required' => 'Avatar resmi gereklidir.',
            'avatar.image' => 'Dosya bir resim olmalıdır.',
            'avatar.mimes' => 'Sadece jpeg, png, jpg, gif formatları kabul edilir.',
            'avatar.max' => 'Dosya boyutu en fazla 2MB olabilir.',
        ]);

        try {
            if ($request->hasFile('avatar')) {
                // Eski avatar'ı sil
                if ($user->avatar) {
                    \Storage::disk('public')->delete($user->avatar);
                }

                // Yeni avatar'ı kaydet
                $path = $request->file('avatar')->store('avatars', 'public');
                $user->update(['avatar' => $path]);

                return $this->successResponse('Avatar başarıyla güncellendi.');
            }

            return $this->errorResponse('Avatar dosyası yüklenemedi.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'update avatar');
        }
    }

    /**
     * Kullanıcı şifresini sıfırla
     */
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'Yeni şifre gereklidir.',
            'password.min' => 'Şifre en az 8 karakter olmalıdır.',
            'password.confirmed' => 'Şifre onayı eşleşmiyor.',
        ]);

        try {
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            return $this->successResponse('Kullanıcı şifresi başarıyla sıfırlandı.');
        } catch (\Exception $e) {
            return $this->handleException($e, 'reset password');
        }
    }

    /**
     * Kullanıcıları toplu işlem
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'users' => 'required|array|min:1',
            'users.*' => 'exists:users,id',
        ]);

        try {
            $userIds = $request->users;
            $action = $request->action;
            
            // Admin kullanıcılarını ve mevcut kullanıcıyı filtrele
            $users = User::whereIn('id', $userIds)
                        ->where('id', '!=', auth()->id())
                        ->get();

            $adminEmails = ['admin@kadinatlasi.com', 'test@test.com', 'superadmin@kadinatlasi.com'];
            $users = $users->reject(function ($user) use ($adminEmails) {
                return in_array($user->email, $adminEmails);
            });

            if ($users->isEmpty()) {
                return $this->errorResponse('İşlem yapılacak geçerli kullanıcı bulunamadı.');
            }

            $count = 0;
            switch ($action) {
                case 'activate':
                    $count = $users->where('is_active', false)->count();
                    User::whereIn('id', $users->pluck('id'))->update(['is_active' => true]);
                    break;
                    
                case 'deactivate':
                    $count = $users->where('is_active', true)->count();
                    User::whereIn('id', $users->pluck('id'))->update(['is_active' => false]);
                    break;
                    
                case 'delete':
                    $count = $users->count();
                    User::whereIn('id', $users->pluck('id'))->delete();
                    break;
            }

            $actionText = match($action) {
                'activate' => 'aktif yapıldı',
                'deactivate' => 'pasif yapıldı',
                'delete' => 'silindi',
            };

            return $this->successResponse("{$count} kullanıcı başarıyla {$actionText}.");
        } catch (\Exception $e) {
            return $this->handleException($e, 'perform bulk action');
        }
    }

    /**
     * Kullanıcının admin olup olmadığını kontrol et
     */
    private function isAdminUser(User $user): bool
    {
        $adminEmails = ['admin@kadinatlasi.com', 'test@test.com', 'superadmin@kadinatlasi.com'];
        return in_array($user->email, $adminEmails) || 
               (method_exists($user, 'hasRole') && $user->hasRole('admin'));
    }
}