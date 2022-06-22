<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToFiliReTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('filière', function (Blueprint $table) {
            $table->foreign('id_cycle', 'fk_filiere_cycle')->references('id_cycle')->on('cycle')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('filière', function (Blueprint $table) {
            $table->dropForeign('fk_filiere_cycle');
        });
    }
}
