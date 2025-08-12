<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KadınAtlası - Kadınlar İçin Dijital Platform</title>
    <meta name="description" content="Kadınların günlük hayatını kolaylaştıran, bilgi alabileceği ve topluluk oluşturabileceği dijital platform">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <style>
        /* Laravel backend sadece API olarak kullanılıyor */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .api-info {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .api-info h1 {
            color: #E57399;
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .api-info p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .api-info a {
            color: #E57399;
            text-decoration: none;
        }
        .api-info a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="api-info">
        <h1>KadınAtlası API Backend</h1>
        <p>Bu Laravel uygulaması sadece API backend olarak çalışmaktadır.</p>
        <p><strong>Frontend Uygulaması:</strong> <a href="http://localhost:5173" target="_blank">http://localhost:5173</a></p>
        <p><strong>Admin Panel:</strong> <a href="/admin">/admin</a></p>
        <p><strong>API Dokümantasyonu:</strong> <a href="/api">/api</a></p>
        
        <h2>Hızlı Linkler</h2>
        <ul>
            <li><a href="http://localhost:5173">Ana Sayfa (Frontend)</a></li>
            <li><a href="http://localhost:5173/blog">Blog</a></li>
            <li><a href="http://localhost:5173/forum">Forum</a></li>
            <li><a href="http://localhost:5173/hesaplama">Hesaplama Araçları</a></li>
            <li><a href="/admin">Admin Panel</a></li>
        </ul>
        
        <h2>Geliştirme Bilgileri</h2>
        <p><strong>Backend:</strong> Laravel 10.x (Port: 8000)</p>
        <p><strong>Frontend:</strong> Vue.js 3 + TypeScript (Port: 5173)</p>
        <p><strong>Database:</strong> MySQL</p>
        <p><strong>Admin:</strong> Laravel Filament</p>
    </div>
</body>
</html>