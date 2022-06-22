<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnseinganantDeModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enseinganant_de_module', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('id_enseignant', 8);
            $table->unsignedSmallInteger('id_element')->index('fk_endm_ele');
            $table->unsignedSmallInteger('id_filiere')->index('fk_endm_fil');
            $table->char('section',1)->nullable();
            $table->char('année',9);
            $table->unsignedSmallInteger('blocker')->default(0);
            $table->unique(['id_element', 'année', 'section'], 'id_enseignant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enseinganant_de_module');
    }
}
