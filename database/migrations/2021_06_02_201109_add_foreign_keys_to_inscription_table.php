<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToInscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inscription', function (Blueprint $table) {
            $table->foreign('CNE', 'fk_ins_etu')->references('CNE')->on('etudiant')->onUpdate('CASCADE')->onDelete('NO ACTION');
            $table->foreign('id_filiere', 'fk_ins_fil')->references('id_filiere')->on('filière')->onUpdate('CASCADE')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inscription', function (Blueprint $table) {
            $table->dropForeign('fk_ins_etu');
            $table->dropForeign('fk_ins_fil');
        });
    }
}
