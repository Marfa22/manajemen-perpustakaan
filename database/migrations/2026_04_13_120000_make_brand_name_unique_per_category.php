<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('brands')) {
            return;
        }

        Schema::table('brands', function (Blueprint $table) {
            if (Schema::hasIndex('brands', 'brands_name_unique', 'unique')) {
                $table->dropUnique('brands_name_unique');
            }

            if (! Schema::hasIndex('brands', 'brands_category_id_name_unique', 'unique')) {
                $table->unique(['category_id', 'name'], 'brands_category_id_name_unique');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('brands')) {
            return;
        }

        Schema::table('brands', function (Blueprint $table) {
            if (Schema::hasIndex('brands', 'brands_category_id_name_unique', 'unique')) {
                $table->dropUnique('brands_category_id_name_unique');
            }

            if (! Schema::hasIndex('brands', 'brands_name_unique', 'unique')) {
                $table->unique('name', 'brands_name_unique');
            }
        });
    }
};
