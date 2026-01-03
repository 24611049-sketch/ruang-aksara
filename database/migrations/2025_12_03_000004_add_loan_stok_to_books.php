<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->integer('loan_stok')->default(0)->after('stok');
        });

        // Initialize loan_stok from stok where meaningful
        try {
            DB::statement('UPDATE books SET loan_stok = stok');
        } catch (\Exception $e) {
            // skip if something goes wrong
        }
    }

    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('loan_stok');
        });
    }
};
