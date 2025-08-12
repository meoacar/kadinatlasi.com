<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Giyim özellikleri
            $table->string('size')->nullable()->after('brand'); // XS, S, M, L, XL, XXL
            $table->string('color')->nullable()->after('size'); // Renk
            $table->string('material')->nullable()->after('color'); // Kumaş/Malzeme
            $table->string('pattern')->nullable()->after('material'); // Desen (çizgili, düz, çiçekli)
            $table->string('fit_type')->nullable()->after('pattern'); // Kesim (slim, regular, oversized)
            $table->string('sleeve_type')->nullable()->after('fit_type'); // Kol tipi (uzun, kısa, kolsuz)
            $table->string('neckline')->nullable()->after('sleeve_type'); // Yaka tipi (V yaka, bisiklet yaka)
            
            // Ayakkabı özellikleri
            $table->string('shoe_size')->nullable()->after('neckline'); // 36, 37, 38, 39, 40
            $table->string('heel_height')->nullable()->after('shoe_size'); // Topuk yüksekliği
            $table->string('shoe_type')->nullable()->after('heel_height'); // Ayakkabı tipi (spor, klasik, bot)
            
            // Aksesuar özellikleri
            $table->string('accessory_type')->nullable()->after('shoe_type'); // Çanta, takı, şapka
            $table->string('closure_type')->nullable()->after('accessory_type'); // Kapama tipi (fermuar, düğme)
            
            // Kozmetik özellikleri
            $table->string('skin_type')->nullable()->after('closure_type'); // Cilt tipi (yağlı, kuru, karma)
            $table->string('shade')->nullable()->after('skin_type'); // Ton/renk numarası
            $table->string('volume')->nullable()->after('shade'); // Hacim (ml, gr)
            $table->date('expiry_date')->nullable()->after('volume'); // Son kullanma tarihi
            $table->boolean('is_organic')->default(false)->after('expiry_date'); // Organik mi
            $table->boolean('is_vegan')->default(false)->after('is_organic'); // Vegan mi
            $table->boolean('is_cruelty_free')->default(false)->after('is_vegan'); // Hayvan deneyi yapılmamış
            
            // Genel özellikler
            $table->string('age_group')->nullable()->after('is_cruelty_free'); // Yaş grubu (bebek, çocuk, genç, yetişkin)
            $table->string('season')->nullable()->after('age_group'); // Mevsim (yaz, kış, sonbahar, ilkbahar)
            $table->string('occasion')->nullable()->after('season'); // Kullanım alanı (günlük, özel, spor)
            $table->json('care_instructions')->nullable()->after('occasion'); // Bakım talimatları
            $table->json('ingredients')->nullable()->after('care_instructions'); // İçerik/malzeme listesi
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'size', 'color', 'material', 'pattern', 'fit_type', 'sleeve_type', 'neckline',
                'shoe_size', 'heel_height', 'shoe_type',
                'accessory_type', 'closure_type',
                'skin_type', 'shade', 'volume', 'expiry_date', 'is_organic', 'is_vegan', 'is_cruelty_free',
                'age_group', 'season', 'occasion', 'care_instructions', 'ingredients'
            ]);
        });
    }
};