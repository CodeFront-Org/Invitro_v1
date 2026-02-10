<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'origin')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('origin')->nullable()->after('name');
            });
        }
        if (!Schema::hasColumn('stocks', 'origin')) {
            Schema::table('stocks', function (Blueprint $table) {
                $table->string('origin')->nullable()->after('source');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('origin');
        });
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn('origin');
        });
    }
};
