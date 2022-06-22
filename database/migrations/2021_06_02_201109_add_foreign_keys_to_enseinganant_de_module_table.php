<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToEnseinganantDeModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enseinganant_de_module', function (Blueprint $table) {
            $table->foreign('id_element', 'fk_endm_ele')->references('id_element')->on('elements')->onUpdate('CASCADE')->onDelete('NO ACTION');
            $table->foreign('id_enseignant', 'fk_endm_ens')->references('CIN')->on('enseignant')->onUpdate('CASCADE')->onDelete('NO ACTION');
            $table->foreign('id_filiere', 'fk_endm_fil')->references('id_filiere')->on('filière')->onUpdate('CASCADE')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enseinganant_de_module', function (Blueprint $table) {
            $table->dropForeign('fk_endm_ele');
            $table->dropForeign('fk_endm_ens');
            $table->dropForeign('fk_endm_fil');
        });
    }
}
