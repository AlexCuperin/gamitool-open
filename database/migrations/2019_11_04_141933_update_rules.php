<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE action_rule DROP CONSTRAINT action_rule_actions_fk;");
        DB::statement("ALTER TABLE action_rule ADD CONSTRAINT action_rule_actions_fk  FOREIGN KEY (rule_type_id)       REFERENCES rule_types      (id) ON UPDATE CASCADE ON DELETE CASCADE;");

        DB::statement("ALTER TABLE rules DROP CONSTRAINT rule_type_fk;");
        DB::statement("ALTER TABLE rules ADD CONSTRAINT rule_type_fk    FOREIGN KEY (type_id)    REFERENCES rule_types  (id) ON UPDATE CASCADE ON DELETE CASCADE;");

        DB::statement("DELETE FROM rule_types WHERE name='Do the action several times before a specific date' OR name='Be one of the first participants doing the action several times';");
        DB::statement("DELETE FROM rule_types WHERE name='Get a validity score lower than X' OR name='Get a reliability score lower than X';");
        DB::statement("DELETE FROM rule_types WHERE name='At least some group members have to perform the action';");

        DB::statement("UPDATE rule_types SET extra_parameters=1, tip= 'What score? (%)' WHERE name='Get an upper or equal score than X';");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE action_rule DROP CONSTRAINT action_rule_actions_fk;");
        DB::statement("ALTER TABLE action_rule ADD CONSTRAINT action_rule_actions_fk  FOREIGN KEY (rule_type_id)       REFERENCES rule_types      (id) ON UPDATE CASCADE ON DELETE RESTRICT;");

        DB::statement("ALTER TABLE rules DROP CONSTRAINT rule_type_fk;");
        DB::statement("ALTER TABLE rules ADD CONSTRAINT rule_type_fk    FOREIGN KEY (type_id)    REFERENCES rule_types  (id) ON UPDATE CASCADE ON DELETE RESTRICT;");

        DB::statement("INSERT INTO rule_types(id, name, extra_parameters, input_type, tip) VALUES (4, 'Do the action several times before a specific date', 2, 'text', 'How many times and which date?');");
        DB::statement("INSERT INTO rule_types(id, name, extra_parameters, input_type, tip) VALUES (7, 'Be one of the first participants doing the action several times', 2, 'text', 'How many participants and which date?');");

        DB::statement("INSERT INTO action_rule(id, action_type_id, rule_type_id) VALUES
                            (4,1,4), (12,2,4), (20,3,4), (28,4,4), (36,5,4), (44,6,4), (52,7,4), (60,8,4), (68,9,4), (76,10,4), (84,11,4),
                            (95,12,4), (103,13,4), (111,14,4), (119,15,4), (126,16,4), (135,17,4), (143,18,4), (151,19,4), (159,20,4),
                            (167,21,4), (175,22,4), (183,23,4), (191,24,4), (199,25,4), (207,26,4), (215,27,4), (222, 28, 4), (229,30,4), (237,31,4);");
        DB::statement("INSERT INTO action_rule(id, action_type_id, rule_type_id) VALUES
                            (7,1,7), (15,2,7), (23,3,7), (31,4,7), (39,5,7), (47,6,7), (55,7,7), (63,8,7), (71,9,7), (79,10,7), (87,11,7),
                            (98,12,7), (106,13,7), (114,14,7), (122,15,7), (130,16,7), (138,17,7), (146,18,7), (154,19,7), (162,20,7),
                            (170,21,7), (178,22,7), (186,23,7), (194,24,7), (202,25,7), (210,26,7), (218,27,7), (225, 28, 7), (232,30,7), (240,31,7);");

        DB::statement("INSERT INTO rule_types(id, name, extra_parameters, input_type, tip) VALUES (9, 'Get a validity score lower than X', 1, 'number', 'Which score?');");
        DB::statement("INSERT INTO rule_types(id, name, extra_parameters, input_type, tip) VALUES (10, 'Get a reliability score lower than X', 1, 'number', 'Which score?');");

        DB::statement("INSERT INTO action_rule(id, action_type_id, rule_type_id) VALUES
                            (90,11,9), (243,31,9);");
        DB::statement("INSERT INTO action_rule(id, action_type_id, rule_type_id) VALUES
                            (91,11,10), (244,31,10);");

        DB::statement("INSERT INTO rule_types(id, name, extra_parameters, input_type, tip) VALUES (8, 'At least some group members have to perform the action', 1, 'number', 'How many members?');");

        DB::statement("UPDATE rule_types SET extra_parameters=2, tip= 'Which score? Rubric id? (empty if no rubric)' WHERE name='Get an upper or equal score than X';");

    }
}
