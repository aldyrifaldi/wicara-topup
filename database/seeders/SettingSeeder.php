<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use App\Models\Banner;
use App\Models\Faq;
use App\Models\PaymentSetting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        // App Settings
        AppSetting::create([
            'key' => 'app_name',
            'value' => 'Wicara Topup',
            'type' => 'text',
            'group' => 'general',
        ]);

        AppSetting::create([
            'key' => 'app_description',
            'value' => 'Platform top-up produk digital terpercaya',
            'type' => 'text',
            'group' => 'general',
        ]);

        AppSetting::create([
            'key' => 'whatsapp_number',
            'value' => '6281234567890',
            'type' => 'text',
            'group' => 'contact',
        ]);

        AppSetting::create([
            'key' => 'commission_percent',
            'value' => '5',
            'type' => 'number',
            'group' => 'referral',
        ]);

        // Payment Settings
        PaymentSetting::create([
            'method' => 'midtrans',
            'is_active' => true,
            'config' => json_encode([
                'is_production' => false,
                'enable_recurring' => true,
            ]),
        ]);

        PaymentSetting::create([
            'method' => 'balance',
            'is_active' => true,
            'config' => json_encode([
                'min_balance' => 10000,
            ]),
        ]);

        // Banners
        Banner::create([
            'title' => 'Welcome to Wicara Topup',
            'image' => 'banners/welcome.jpg',
            'link' => '/products',
            'position' => 'home',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Banner::create([
            'title' => 'Special Promotion',
            'image' => 'banners/promo.jpg',
            'link' => '/products?featured=true',
            'position' => 'home',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // FAQs
        $faqs = [
            [
                'question' => 'Bagaimana cara top up?',
                'answer' => 'Pilih produk yang ingin Anda beli, masukkan data yang diperlukan, pilih metode pembayaran, dan selesaikan pembayaran. Produk akan dikirim secara otomatis.',
                'category' => 'general',
            ],
            [
                'question' => 'Metode pembayaran apa yang tersedia?',
                'answer' => 'Kami menerima pembayaran melalui Midtrans (transfer bank, e-wallet), QRIS, dan saldo akun.',
                'category' => 'payment',
            ],
            [
                'question' => 'Berapa lama proses pengiriman?',
                'answer' => 'Untuk produk digital, proses pengiriman biasanya 1-5 menit setelah pembayaran berhasil.',
                'category' => 'general',
            ],
            [
                'question' => 'Apakah ada program referral?',
                'answer' => 'Ya! Kami memiliki program referral yang memberikan komisi untuk setiap teman yang Anda ajak.',
                'category' => 'referral',
            ],
        ];

        foreach ($faqs as $index => $faq) {
            Faq::create([
                'question' => $faq['question'],
                'answer' => $faq['answer'],
                'category' => $faq['category'],
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }
    }
}