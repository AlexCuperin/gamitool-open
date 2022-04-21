<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableImports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement("
            CREATE TABLE import_metadata (
                  id               serial primary key,
                  instance_type_id integer NOT NULL REFERENCES deploy_types(id) ON UPDATE CASCADE ON DELETE RESTRICT,
                  instance_url     character varying(300) NOT NULL,
                  course_id        integer NOT NULL,
                  course_name      character varying(300),
                  bearer           character varying(200) NOT NULL,
                
                  learning_id      integer NOT NULL REFERENCES learning_designs(id) ON UPDATE CASCADE ON DELETE CASCADE,
                
                  -- Laravel ORM dates
                  created_at TIMESTAMP,
                  updated_at TIMESTAMP
            );"
        );

        DB::statement("
            CREATE TABLE resource_import (
                  id                    serial,
                  resource_id           integer NOT NULL REFERENCES resources(id) ON UPDATE CASCADE ON DELETE CASCADE,
                  import_id             integer NOT NULL REFERENCES import_metadata(id) ON UPDATE CASCADE ON DELETE CASCADE,
                  instance_resource_id  integer NOT NULL,
                
                  -- Laravel ORM dates
                  created_at TIMESTAMP,
                  updated_at TIMESTAMP
                );"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::statement("DROP TABLE resource_import;");
        DB::statement("DROP TABLE import_metadata;");
    }
}
