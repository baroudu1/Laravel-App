<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToNoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('note', function (Blueprint $table) {
            $table->foreign('id_element', 'fk_note_ele')->references('id_element')->on('elements')->onUpdate('CASCADE')->onDelete('NO ACTION');
            $table->foreign('CNE', 'fk_note_etud')->references('CNE')->on('etudiant')->onUpdate('CASCADE')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('note', function (Blueprint $table) {
            $table->dropForeign('fk_note_ele');
            $table->dropForeign('fk_note_etud');
        });
    }
}
