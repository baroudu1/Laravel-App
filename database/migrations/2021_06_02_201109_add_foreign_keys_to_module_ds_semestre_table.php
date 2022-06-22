<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToModuleDsSemestreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('module_ds_semestre', function (Blueprint $table) {
            $table->foreign('id_filiere', 'fk_mds_fil')->references('id_filiere')->on('filière')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('id_module', 'fk_mds_mod')->references('id_module')->on('module')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('id_semestre', 'fk_mds_sem')->references('id_semestre')->on('semetre')->onUpdate('CASCADE')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('module_ds_semestre', function (Blueprint $table) {
            $table->dropForeign('fk_mds_fil');
            $table->dropForeign('fk_mds_mod');
            $table->dropForeign('fk_mds_sem');
        });
    }
}
