<?php

use App\Http\Middleware\EnsureAdminAccess;
use App\Http\Middleware\IsLogin;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Schema::dropIfExists('brands');
    Schema::dropIfExists('categories');

    Schema::create('categories', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });

    Schema::create('brands', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->nullable();
        $table->foreignId('category_id')->nullable();
        $table->timestamps();
        $table->unique(['category_id', 'name'], 'brands_category_id_name_unique');
    });

    $this->withoutMiddleware([
        IsLogin::class,
        EnsureAdminAccess::class,
    ]);
});

afterEach(function () {
    Schema::dropIfExists('brands');
    Schema::dropIfExists('categories');
});

it('allows storing the same brand name in different categories', function () {
    $elektronik = Category::create(['name' => 'Elektronik']);
    $atk = Category::create(['name' => 'ATK']);

    Brand::create([
        'name' => 'ABC',
        'slug' => 'abc',
        'category_id' => $elektronik->id,
    ]);

    $response = $this->post('/merek', [
        'brand_name' => 'ABC',
        'category_id' => $atk->id,
    ]);

    $response->assertRedirect('/merek');
    $response->assertSessionHasNoErrors();

    expect(
        Brand::query()->where('name', 'ABC')->count()
    )->toBe(2);

    $this->assertDatabaseHas('brands', [
        'name' => 'ABC',
        'category_id' => $atk->id,
    ]);
});

it('rejects storing the same brand name in the same category', function () {
    $elektronik = Category::create(['name' => 'Elektronik']);

    Brand::create([
        'name' => 'ABC',
        'slug' => 'abc',
        'category_id' => $elektronik->id,
    ]);

    $response = $this->from('/merek/create')->post('/merek', [
        'brand_name' => 'ABC',
        'category_id' => $elektronik->id,
    ]);

    $response->assertRedirect('/merek/create');
    $response->assertSessionHasErrors(['brand_name']);

    expect(Brand::count())->toBe(1);
});

it('rejects updating a brand into a category that already has the same name', function () {
    $elektronik = Category::create(['name' => 'Elektronik']);
    $atk = Category::create(['name' => 'ATK']);

    $sourceBrand = Brand::create([
        'name' => 'ABC',
        'slug' => 'abc',
        'category_id' => $elektronik->id,
    ]);

    Brand::create([
        'name' => 'ABC',
        'slug' => 'abc',
        'category_id' => $atk->id,
    ]);

    $response = $this->from('/merek')->put('/merek/' . $sourceBrand->id, [
        'name' => 'ABC',
        'category_id' => $atk->id,
    ]);

    $response->assertRedirect('/merek');
    $response->assertSessionHasErrors(['name']);

    expect($sourceBrand->fresh()->category_id)->toBe($elektronik->id);
});
