<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('badge')->nullable();
            $table->json('sizes')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('stock')->default(0);
            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('cpf')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('cep')->nullable();
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->json('items');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('shipping', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('coupon_code')->nullable();
            $table->string('payment_method')->default('card');
            $table->string('payment_status')->default('pending');
            $table->string('order_status')->default('pending');
            $table->string('stripe_pi_id')->nullable();
            $table->string('tracking_code')->nullable();
            $table->json('shipping_address')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name');
            $table->string('size')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['percent', 'fixed', 'frete'])->default('percent');
            $table->decimal('value', 10, 2)->default(0);
            $table->decimal('min_value', 10, 2)->default(0);
            $table->integer('max_uses')->default(100);
            $table->integer('used_count')->default(0);
            $table->date('expires_at')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('gallery', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('url');
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('size_bytes')->default(0);
            $table->timestamps();
        });

        // Seed default settings
        $defaults = [
            'store_name'          => 'A.Seller',
            'store_email'         => 'contato@assisseller.com',
            'store_phone'         => '5562994250683',
            'wa_number'           => '5562994250683',
            'wa_name'             => 'A.Seller · Suporte',
            'brand_color'         => '#0a0a0a',
            'accent_color'        => '#c8a96e',
            'bg_color'            => '#faf9f7',
            'font_display'        => 'Cormorant Garamond',
            'font_head'           => 'Syne',
            'topbar_text'         => '🇧🇷 Frete Grátis acima de R$99 · Pix com 5% desconto · Parcele em até 3x',
            'hero_title_1'        => 'Havaianas',
            'hero_title_2'        => 'Brancas',
            'hero_title_3'        => 'do Brasil',
            'hero_desc'           => 'Sandálias originais com design icônico. Conforto incomparável que representa o espírito brasileiro em cada passo.',
            'hero_image'          => '',
            'hero_video'          => '',
            'hero_video_opts'     => 'autoplay',
            'frete_fixo'          => '12.90',
            'frete_gratis_acima'  => '99.00',
            'frete_prazo_min'     => '3',
            'frete_prazo_max'     => '8',
            'stripe_pk'           => '',
            'stripe_sk'           => '',
            'stripe_webhook'      => '',
            'social_instagram'    => '',
            'social_facebook'     => '',
            'social_tiktok'       => '',
            'social_shopee'       => '',
        ];

        foreach ($defaults as $key => $value) {
            DB::table('settings')->insert(['key' => $key, 'value' => $value]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('gallery');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('products');
        Schema::dropIfExists('settings');
    }
};
