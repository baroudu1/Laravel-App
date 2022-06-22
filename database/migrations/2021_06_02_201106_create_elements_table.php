<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elements', function (Blueprint $table) {
            $table->smallIncrements('id_element');
            $table->unsignedSmallInteger('id_module')->index('fk_edm_mod');
            $table->string('nom_element', 100);
            $table->decimal('Co_element', 5);
            $table->decimal('Co_cntr', 5);
            $table->decimal('Co_tp', 5);
            $table->decimal('Co_examen', 5);
            $table->decimal('Co_mini_project', 5);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('elements');
    }
}
