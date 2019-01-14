<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUniqueConstraintOnRefNumberInCertificateReferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certificate_references', function (Blueprint $table) {
            $table->dropUnique('certificate_references_ref_number_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certificate_references', function (Blueprint $table) {
            $table->unique(['ref_number']);
        });
    }
}
