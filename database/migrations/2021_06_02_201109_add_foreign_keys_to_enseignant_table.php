<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToEnseignantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enseignant', function (Blueprint $table) {
            $table->foreign('id_departement', 'fk_ens_dep')->references('id_departement')->on('département')->onUpdate('CASCADE')->onDelete('NO ACTION');
            $table->foreign('CIN', 'fk_ens_usr')->references('CIN')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('coordinateur', 'fk_ens_fil')->references('id_filiere')->on('filière')->onUpdate('CASCADE')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enseignant', function (Blueprint $table) {
            $table->dropForeign('fk_ens_dep');
            $table->dropForeign('fk_ens_usr');
        });
    }
}
