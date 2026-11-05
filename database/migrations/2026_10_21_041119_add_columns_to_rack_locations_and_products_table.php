<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rack_locations', function (Blueprint $table) {
            $table->string('name')->unique()->after('id');
            $table->integer('capacity')->default(100)->after('name');
            $table->string('zone')->nullable()->after('capacity');
            $table->text('description')->nullable()->after('zone');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('rack_location_id')->nullable()->after('supplier_id')->constrained('rack_locations')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['rack_location_id']);
            $table->dropColumn('rack_location_id');
        });

        Schema::table('rack_locations', function (Blueprint $table) {
            $table->dropColumn(['name', 'capacity', 'zone', 'description']);
        });
    }
};
