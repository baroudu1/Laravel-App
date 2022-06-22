<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscription', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('CNE', 12);
            $table->unsignedSmallInteger('id_filiere')->index('fk_ins_fil');
            $table->char('année_universitaire', 9);
            $table->unsignedTinyInteger('niveau')->default(1);
            $table->char('section', 2)->default('--');
            $table->unique(['CNE', 'id_filiere', 'année_universitaire', 'niveau'], 'CNE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inscription');
    }
}
