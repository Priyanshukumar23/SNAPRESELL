<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'has_bill')) {
                $table->boolean('has_bill')->default(false)->after('image');
            }
            if (!Schema::hasColumn('products', 'has_replacement')) {
                $table->boolean('has_replacement')->default(false)->after('has_bill');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['has_bill', 'has_replacement']);
        });
    }
};
