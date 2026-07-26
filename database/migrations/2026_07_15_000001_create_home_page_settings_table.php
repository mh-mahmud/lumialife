<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_page_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('banner_section_status')->default(true);
            $table->string('banner_one_image')->nullable();
            $table->string('banner_one_url')->nullable();
            $table->string('banner_two_image')->nullable();
            $table->string('banner_two_url')->nullable();
            $table->boolean('about_section_status')->default(true);
            $table->string('about_title')->default('Fabrilife');
            $table->string('about_subtitle')->nullable();
            $table->text('about_description')->nullable();
            $table->string('about_image')->nullable();
            $table->string('about_url')->nullable();
            $table->boolean('promo_section_status')->default(true);
            $table->string('promo_left_media')->nullable();
            $table->string('promo_left_url')->nullable();
            $table->string('promo_right_media')->nullable();
            $table->string('promo_right_url')->nullable();
            $table->boolean('bulk_section_status')->default(true);
            $table->string('bulk_title')->nullable();
            $table->text('bulk_description')->nullable();
            $table->string('bulk_image')->nullable();
            $table->string('bulk_url')->nullable();
            $table->boolean('partners_section_status')->default(true);
            $table->string('partners_title')->nullable();
            $table->string('partners_subtitle')->nullable();
            $table->text('partners_description')->nullable();
            $table->string('featured_partner_logo')->nullable();
            $table->json('partner_logos')->nullable();
            $table->timestamps();
        });

        DB::table('home_page_settings')->insert([
            'banner_one_image' => 'feb/images/homepage/6a2202639a9ce-official-adition-jersey-1.jpg',
            'banner_one_url' => '/shop-new',
            'banner_two_image' => 'feb/images/homepage/6a2202b1d7863-fan-adition-jersey-1.jpg',
            'banner_two_url' => '/shop-new',
            'about_title' => 'Fabrilife',
            'about_subtitle' => 'Because comfort and confidence go hand in hand.',
            'about_description' => 'We focus on carefully selecting the best clothing that is comfortable, looks great, and makes you confident. Apart from the fabric, design and fit, we go through strict quality control parameters to give you what you truly deserve. The power of a good outfit is how it can influence your perception of yourself.',
            'about_image' => 'feb/image-gallery/638b1d9333f59.png',
            'about_url' => '/about-us',
            'promo_left_media' => 'feb/img/9ee0811b605f4e118041a3a9b1a2fb3d.mp4',
            'promo_left_url' => '/shop-new',
            'promo_right_media' => 'feb/img/c05bff0974554daeb5f5f024112564f4.avif',
            'promo_right_url' => '/shop-new',
            'bulk_title' => 'Bulk Order / Wholesale',
            'bulk_description' => 'We provide plain t-shirts and apparel for all your custom branding needs from the top brands worldwide at unbeatable wholesale prices. With no minimum orders, everyone can enjoy the benefits of buying bulk t-shirts without ordering bulk quantities.',
            'bulk_image' => 'feb/image-gallery/5edc1d60d1b41.jpg',
            'bulk_url' => '/corporate',
            'partners_title' => 'Work with us Today',
            'partners_subtitle' => 'We are the official merchandising partner of',
            'partners_description' => 'We are proud to work with over a thousand brands and organizations that we call friends. As your partner, we value long-term relationships and collaborate toward results.',
            'featured_partner_logo' => 'feb/img/clients/gp.png',
            'partner_logos' => json_encode(array_map(fn ($number) => "feb/img/clients/{$number}.jpg", range(1, 54))),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('home_page_settings');
    }
};
