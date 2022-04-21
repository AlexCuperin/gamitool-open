<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InstanceResourceIdVarchar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE resource_deploy ALTER COLUMN instance_resource_id TYPE character varying(150);");
        DB::statement("ALTER TABLE resource_import ALTER COLUMN instance_resource_id TYPE character varying(150);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
