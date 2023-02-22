<?php

use App\Enums\PembelianKategoryEnums;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->integer('qty')->nullable();
            $table->integer('harga')->nullable();
            $table->enum('kategori',PembelianKategoryEnums::toArray());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('qty');
            $table->dropColumn('harga');
            $table->dropColumn('kategori');
        });
    }
};
