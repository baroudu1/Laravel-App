<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('note', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('CNE', 12);
            $table->unsignedSmallInteger('id_element')->index('fk_note_ele');
            $table->char('année',9);
            $table->float('N_tp', 10, 0)->nullable();
            $table->float('N_cntr', 10, 0)->nullable();
            $table->float('N_mini_project', 10, 0)->nullable();
            $table->float('N_examen_nor', 10, 0)->nullable();
            $table->float('N_examen_ratt', 10, 0)->nullable();
            $table->unique(['CNE', 'id_element', 'année'],"note_cne_id_element_anne_unique");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('note');
    }
}
