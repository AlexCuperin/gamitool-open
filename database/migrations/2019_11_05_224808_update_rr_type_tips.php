<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRrTypeTips extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE rr_types SET tip='How much discount? (% of final price)' WHERE name='Final Certificate Discount';");
        DB::statement("UPDATE rr_types SET tip='How many extra attempts?' WHERE name='Extra Attempts';");
        DB::statement("UPDATE rr_types SET tip='How much time? (seconds)' WHERE name='Extra Time';");
        DB::statement("UPDATE rr_types SET tip='What is the new minimum score for passing the activity? (%)' WHERE name='Lower Score';");
        DB::statement("UPDATE rr_types SET tip='Which is the new deadline? (date)' WHERE name='Extending Due Date';");
        DB::statement("UPDATE rr_types SET tip='How many extra days?' WHERE name='Re-open';");
        DB::statement("UPDATE rr_types SET tip='How students must perform the activity? (type individual or group)' WHERE name='Individual or Collective';");

        DB::statement("UPDATE rr_types SET input_type='number' WHERE name='Re-open';");
        DB::statement("UPDATE rr_types SET name='Individual or Group' WHERE name='Individual or Collective';");
        DB::statement("UPDATE rr_types SET name='Deadline Extension' WHERE name='Extending Due Date';");
        DB::statement("UPDATE rr_types SET name='Instructor Revision' WHERE name='Teachers Evaluation';");

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
