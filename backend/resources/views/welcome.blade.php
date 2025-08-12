<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KadınAtlası API Backend</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #E57399 0%, #F5A9BC 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        h1 {
            color: #E57399;
            font-size: 2.5rem;
            margin-bottom: 20px;
            text-align: center;
        }
        .subtitle {
            text-align: center;
            color: #666;
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        .links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .link-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        .link-card:hover {
            border-color: #E57399;
            transform: translateY(-2px);
        }
        .link-card a {
            color: #E57399;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .link-card p {
            color: #666;
            margin: 10px 0 0 0;
            font-size: 0.9rem;
        }
        .info {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .info h3 {
            color: #1976d2;
            margin-top: 0;
        }
        .status {
            display: inline-block;
            padding: 5px 15px;
            background: #4caf50;
            color: white;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🌸 KadınAtlası</h1>
        <p class="subtitle">API Backend <span class="status">Aktif</span></p>
        
        <div class="info">
            <h3>📋 Sistem Bilgileri</h3>
            <p><strong>Backend:</strong> Laravel 10.x (Port: 8000)</p>
            <p><strong>Frontend:</strong> Vue.js 3 + TypeScript (Port: 5173)</p>
            <p><strong>Database:</strong> MySQL</p>
            <p><strong>Admin Panel:</strong> Laravel Filament</p>
        </div>

        <div class="links">
            <div class="link-card">
                <a href="http://localhost:5173" target="_blank">🏠 Ana Sayfa</a>
                <p>Vue.js Frontend Uygulaması</p>
            </div>
            
            <div class="link-card">
                <a href="/admin">⚙️ Admin Panel</a>
                <p>Laravel Filament Yönetim Paneli</p>
            </div>
            
            <div class="link-card">
                <a href="http://localhost:5173/blog" target="_blank">📝 Blog</a>
                <p>Blog yazıları ve içerikler</p>
            </div>
            
            <div class="link-card">
                <a href="http://localhost:5173/forum" target="_blank">💬 Forum</a>
                <p>Topluluk tartışmaları</p>
            </div>
            
            <div class="link-card">
                <a href="http://localhost:5173/hesaplama" target="_blank">🧮 Hesaplayıcılar</a>
                <p>VKİ, Regl, Gebelik hesaplayıcıları</p>
            </div>
            
            <div class="link-card">
                <a href="/api" target="_blank">🔌 API</a>
                <p>RESTful API Endpoints</p>
            </div>
        </div>

        <div class="info">
            <h3>🚀 Hızlı Başlangıç</h3>
            <p><strong>Frontend'i görüntülemek için:</strong> <a href="http://localhost:5173" target="_blank">http://localhost:5173</a></p>
            <p><strong>Admin paneline erişim:</strong> <a href="/admin">admin@kadinatlasi.com / password</a></p>
        </div>
    </div>
</body>
</html>