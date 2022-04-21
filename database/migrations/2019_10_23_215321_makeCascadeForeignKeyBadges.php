<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeCascadeForeignKeyBadges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement("ALTER TABLE badges DROP CONSTRAINT badges_rewards_fk;");
        DB::statement("ALTER TABLE badges ADD  CONSTRAINT badges_rewards_fk FOREIGN KEY (reward_id) REFERENCES rewards(id) ON UPDATE CASCADE ON DELETE CASCADE;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::statement("ALTER TABLE badges DROP CONSTRAINT badges_rewards_fk;");
        DB::statement("ALTER TABLE badges ADD  CONSTRAINT badges_rewards_fk FOREIGN KEY (reward_id) REFERENCES rewards(id) ON UPDATE CASCADE ON DELETE RESTRICT;");
    }
}
