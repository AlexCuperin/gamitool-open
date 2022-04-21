<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableResources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){

        try {
            Schema::create('modules', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 200)->nullable();
                $table->timestamps();
                $table->integer('position');
                $table->integer('learning_id')->nullable();
                $table->foreign('learning_id')->nullable()
                    ->references('id')
                    ->on('learning_designs')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            });

            Schema::table('resources', function (Blueprint $table) {
                $table->string('name', 200)->nullable();
                $table->integer('module_id')->nullable();
                $table->foreign('module_id')->nullable()
                    ->references('id')
                    ->on('modules')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            });

            DB::statement("UPDATE deploy_types SET enabled = TRUE WHERE name = 'Moodle';");
        }catch(PDOException $e){
            $this->down();
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){

        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('module_id');
        });

        Schema::dropIfExists('modules');

        DB::statement("UPDATE deploy_types SET enabled = FALSE WHERE name = 'Moodle';");
    }
}