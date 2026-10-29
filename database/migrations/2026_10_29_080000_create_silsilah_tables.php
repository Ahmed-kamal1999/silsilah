<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_categories', function (Blueprint $t) {
            $t->id();
            $t->json('name');
            $t->string('slug')->unique();
            $t->json('description')->nullable();
            $t->unsignedInteger('sort')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('services', function (Blueprint $t) {
            $t->id();
            $t->foreignId('category_id')->constrained('service_categories')->cascadeOnDelete();
            $t->json('title');
            $t->string('slug')->unique();
            $t->json('excerpt')->nullable();
            $t->json('body')->nullable();
            $t->json('deliverables')->nullable();
            $t->json('methodology')->nullable();
            $t->string('icon')->nullable();
            $t->unsignedInteger('sort')->default(0);
            $t->boolean('is_active')->default(true);
            $t->json('seo')->nullable();
            $t->timestamps();
        });

        Schema::create('post_categories', function (Blueprint $t) {
            $t->id();
            $t->json('name');
            $t->string('slug')->unique();
            $t->timestamps();
        });

        Schema::create('posts', function (Blueprint $t) {
            $t->id();
            $t->json('title');
            $t->string('slug')->unique();
            $t->json('excerpt')->nullable();
            $t->json('body')->nullable();
            $t->foreignId('category_id')->nullable()->constrained('post_categories')->nullOnDelete();
            $t->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamp('published_at')->nullable();
            $t->boolean('is_published')->default(false);
            $t->json('seo')->nullable();
            $t->timestamps();
        });

        foreach (['clients', 'partners'] as $table) {
            Schema::create($table, function (Blueprint $t) {
                $t->id();
                $t->string('name');
                $t->string('url')->nullable();
                $t->unsignedInteger('sort')->default(0);
                $t->boolean('is_active')->default(true);
                $t->timestamps();
            });
        }

        Schema::create('stats', function (Blueprint $t) {
            $t->id();
            $t->json('label');
            $t->unsignedInteger('value')->default(0);
            $t->string('suffix', 8)->nullable();
            $t->unsignedInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('pages', function (Blueprint $t) {
            $t->id();
            $t->json('title');
            $t->string('slug')->unique();
            $t->json('body')->nullable();
            $t->json('seo')->nullable();
            $t->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('email');
            $t->string('phone', 32);
            $t->text('message')->nullable();
            $t->string('ip', 45)->nullable();
            $t->text('user_agent')->nullable();
            $t->timestamp('read_at')->nullable();
            $t->timestamps();
        });

        Schema::create('settings', function (Blueprint $t) {
            $t->id();
            $t->string('key')->unique();
            $t->json('value')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['settings', 'contact_messages', 'pages', 'stats', 'partners', 'clients', 'posts', 'post_categories', 'services', 'service_categories'] as $t) {
            Schema::dropIfExists($t);
        }
    }
};
