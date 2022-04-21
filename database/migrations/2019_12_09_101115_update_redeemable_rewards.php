<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRedeemableRewards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE rr_types SET name='Allow to Skip' WHERE name='Skip';");
        DB::statement("UPDATE rr_types SET name='Pass with Lower Score' WHERE name='Lower Score';");

        DB::statement("INSERT INTO rr_types(name, extra_parameters, input_type, tip) VALUES ('Do a Different Number of Revisions', 1, 'number', 'How many revisions?')");
        DB::statement("INSERT INTO resource_rr(resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Peer Review'), (SELECT id FROM rr_types WHERE name='Do a Different Number of Revisions'))");
        DB::statement("INSERT INTO resource_rr(resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Peer Review'), (SELECT id FROM rr_types WHERE name='Allow to Skip'))");

        DB::statement("UPDATE rule_types SET name='Get a score equal or higher than X' WHERE name='Get an upper or equal score than X';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
