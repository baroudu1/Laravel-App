<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuleDsSemestreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_ds_semestre', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('id_module');
            $table->unsignedSmallInteger('id_filiere')->index('fk_mds_fil');
            $table->unsignedTinyInteger('id_semestre')->index('fk_mds_sem');
            $table->unique(['id_module', 'id_filiere'], 'id_module');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_ds_semestre');
    }
}
