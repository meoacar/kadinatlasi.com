<?php

namespace Database\Seeders;

use App\Models\Partnership;
use Illuminate\Database\Seeder;

class PartnershipSeeder extends Seeder
{
    public function run(): void
    {
        $partnerships = [
            [
                'company_name' => 'Avon Türkiye',
                'contact_person' => 'Ayşe Demir',
                'email' => 'ayse.demir@avon.com.tr',
                'phone' => '+90 212 555 0101',
                'partnership_type' => 'brand',
                'description' => 'Kozmetik ürünleri ve güzellik içerikleri için stratejik işbirliği',
                'budget' => 50000.00,
                'start_date' => '2024-01-15',
                'end_date' => '2024-12-31',
                'status' => 'active',
                'commission_rate' => 15.00,
                'total_revenue' => 12500.00
            ],
            [
                'company_name' => 'Oriflame',
                'contact_person' => 'Mehmet Kaya',
                'email' => 'mehmet.kaya@oriflame.com',
                'phone' => '+90 216 555 0202',
                'partnership_type' => 'affiliate',
                'description' => 'Doğal kozmetik ürünleri affiliate programı',
                'budget' => 30000.00,
                'start_date' => '2024-02-01',
                'end_date' => '2024-11-30',
                'status' => 'active',
                'commission_rate' => 12.00,
                'total_revenue' => 8750.00
            ],
            [
                'company_name' => 'Farmasi',
                'contact_person' => 'Zeynep Özkan',
                'email' => 'zeynep.ozkan@farmasi.com.tr',
                'phone' => '+90 212 555 0303',
                'partnership_type' => 'sponsor',
                'description' => 'Cilt bakımı ve makyaj ürünleri sponsorluğu',
                'budget' => 25000.00,
                'start_date' => '2024-03-01',
                'end_date' => '2024-09-30',
                'status' => 'active',
                'commission_rate' => 10.00,
                'total_revenue' => 5200.00
            ],
            [
                'company_name' => 'Herbalife Nutrition',
                'contact_person' => 'Can Yılmaz',
                'email' => 'can.yilmaz@herbalife.com',
                'phone' => '+90 212 555 0404',
                'partnership_type' => 'brand',
                'description' => 'Beslenme ve fitness ürünleri işbirliği',
                'budget' => 40000.00,
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
                'status' => 'active',
                'commission_rate' => 18.00,
                'total_revenue' => 15600.00
            ],
            [
                'company_name' => 'Defacto',
                'contact_person' => 'Selin Arslan',
                'email' => 'selin.arslan@defacto.com.tr',
                'phone' => '+90 212 555 0505',
                'partnership_type' => 'influencer',
                'description' => 'Kadın giyim koleksiyonu tanıtım işbirliği',
                'budget' => 35000.00,
                'start_date' => '2024-04-01',
                'end_date' => '2024-10-31',
                'status' => 'pending',
                'commission_rate' => 8.00,
                'total_revenue' => 0.00
            ],
            [
                'company_name' => 'LC Waikiki',
                'contact_person' => 'Emre Çelik',
                'email' => 'emre.celik@lcwaikiki.com',
                'phone' => '+90 212 555 0606',
                'partnership_type' => 'affiliate',
                'description' => 'Anne-bebek ürünleri affiliate programı',
                'budget' => 20000.00,
                'start_date' => '2024-05-01',
                'end_date' => '2024-12-31',
                'status' => 'active',
                'commission_rate' => 6.00,
                'total_revenue' => 3200.00
            ],
            [
                'company_name' => 'Gratis',
                'contact_person' => 'Burcu Şahin',
                'email' => 'burcu.sahin@gratis.com',
                'phone' => '+90 216 555 0707',
                'partnership_type' => 'brand',
                'description' => 'Parfüm ve kozmetik ürünleri stratejik ortaklığı',
                'budget' => 45000.00,
                'start_date' => '2024-02-15',
                'end_date' => '2024-12-31',
                'status' => 'active',
                'commission_rate' => 14.00,
                'total_revenue' => 18900.00
            ],
            [
                'company_name' => 'Koton',
                'contact_person' => 'Deniz Aktaş',
                'email' => 'deniz.aktas@koton.com',
                'phone' => '+90 212 555 0808',
                'partnership_type' => 'sponsor',
                'description' => 'Kadın modasında trend belirleme sponsorluğu',
                'budget' => 28000.00,
                'start_date' => '2024-06-01',
                'end_date' => '2024-11-30',
                'status' => 'completed',
                'commission_rate' => 9.00,
                'total_revenue' => 7800.00
            ]
        ];

        foreach ($partnerships as $partnership) {
            Partnership::create($partnership);
        }
    }
}