<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['title'] ?? 'Rapor' }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #4F46E5;
            font-size: 24px;
            margin: 0 0 10px 0;
        }
        .header .subtitle {
            color: #666;
            font-size: 14px;
        }
        .info-section {
            margin-bottom: 20px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4F46E5;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ $data['title'] ?? 'Veri Raporu' }}</h1>
        <div class="subtitle">KadınAtlası Admin Panel</div>
    </div>

    <!-- Report Info -->
    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Rapor Türü:</span>
            <span>{{ ucfirst($type) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Oluşturulma Tarihi:</span>
            <span>{{ $generated_at->format('d.m.Y H:i:s') }}</span>
        </div>
        @if(isset($filters['date_from']) && $filters['date_from'])
        <div class="info-row">
            <span class="info-label">Başlangıç Tarihi:</span>
            <span>{{ \Carbon\Carbon::parse($filters['date_from'])->format('d.m.Y') }}</span>
        </div>
        @endif
        @if(isset($filters['date_to']) && $filters['date_to'])
        <div class="info-row">
            <span class="info-label">Bitiş Tarihi:</span>
            <span>{{ \Carbon\Carbon::parse($filters['date_to'])->format('d.m.Y') }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Toplam Kayıt:</span>
            <span>{{ $data['items']->count() }}</span>
        </div>
    </div>

    <!-- Data Table -->
    @if($data['items']->count() > 0)
    <table>
        @if($type === 'users')
        <thead>
            <tr>
                <th>ID</th>
                <th>Ad Soyad</th>
                <th>E-posta</th>
                <th>Üyelik Türü</th>
                <th>Durum</th>
                <th>Kayıt Tarihi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['items'] as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->membership_type ?? 'Standart' }}</td>
                <td>{{ $user->is_active ? 'Aktif' : 'Pasif' }}</td>
                <td>{{ $user->created_at->format('d.m.Y') }}</td>
            </tr>
            @endforeach
        </tbody>
        @elseif($type === 'blog')
        <thead>
            <tr>
                <th>ID</th>
                <th>Başlık</th>
                <th>Yazar</th>
                <th>Kategori</th>
                <th>Durum</th>
                <th>Görüntülenme</th>
                <th>Oluşturulma</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['items'] as $post)
            <tr>
                <td>{{ $post->id }}</td>
                <td>{{ Str::limit($post->title, 30) }}</td>
                <td>{{ $post->author->name ?? 'Bilinmiyor' }}</td>
                <td>{{ $post->category->name ?? 'Kategorisiz' }}</td>
                <td>{{ $post->status === 'published' ? 'Yayında' : 'Taslak' }}</td>
                <td>{{ $post->views_count ?? 0 }}</td>
                <td>{{ $post->created_at->format('d.m.Y') }}</td>
            </tr>
            @endforeach
        </tbody>
        @elseif($type === 'products')
        <thead>
            <tr>
                <th>ID</th>
                <th>Ürün Adı</th>
                <th>Kategori</th>
                <th>Fiyat</th>
                <th>Durum</th>
                <th>Oluşturulma</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['items'] as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ Str::limit($product->name, 25) }}</td>
                <td>{{ $product->category->name ?? 'Kategorisiz' }}</td>
                <td>{{ $product->price ? number_format($product->price, 2) . ' ₺' : '-' }}</td>
                <td>{{ $product->is_active ? 'Aktif' : 'Pasif' }}</td>
                <td>{{ $product->created_at->format('d.m.Y') }}</td>
            </tr>
            @endforeach
        </tbody>
        @elseif($type === 'forum')
        <thead>
            <tr>
                <th>ID</th>
                <th>Başlık</th>
                <th>Yazar</th>
                <th>Grup</th>
                <th>Yanıt</th>
                <th>Görüntülenme</th>
                <th>Oluşturulma</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['items'] as $topic)
            <tr>
                <td>{{ $topic->id }}</td>
                <td>{{ Str::limit($topic->title, 25) }}</td>
                <td>{{ $topic->author->name ?? 'Bilinmiyor' }}</td>
                <td>{{ $topic->group->name ?? 'Genel' }}</td>
                <td>{{ $topic->replies_count ?? 0 }}</td>
                <td>{{ $topic->views_count ?? 0 }}</td>
                <td>{{ $topic->created_at->format('d.m.Y') }}</td>
            </tr>
            @endforeach
        </tbody>
        @endif
    </table>
    @else
    <div style="text-align: center; padding: 40px; color: #666;">
        <p>Bu kriterlere uygun veri bulunamadı.</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Bu rapor KadınAtlası Admin Panel tarafından otomatik olarak oluşturulmuştur.</p>
        <p>Oluşturulma Zamanı: {{ $generated_at->format('d.m.Y H:i:s') }}</p>
    </div>
</body>
</html>