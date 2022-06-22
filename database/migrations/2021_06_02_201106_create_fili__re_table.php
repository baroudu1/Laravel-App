<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiliReTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filière', function (Blueprint $table) {
            $table->smallIncrements('id_filiere');
            $table->unsignedTinyInteger('id_cycle')->index('fk_filiere_cycle');
            $table->string('nom_filiere', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filière');
    }
}
