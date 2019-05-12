<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/12/19 7:19 AM
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Webpatser\Uuid\Uuid;

class AddUuidToRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Step 1: add the uuid field without unique()
        Schema::table('roles', function(Blueprint $table) {
            $table->string('uuid')->after('id');
        });

        // Step 2: Update each row to populate the uuid field
        DB::table('roles')->get()->each(function($role) {
            DB::table('roles')
                ->where('id', $role->id)
                ->update(['uuid' => Uuid::generate(5, $role->id, Uuid::NS_DNS)]);
        });

        // Step 3: add the unique constraint to uuid
        Schema::table('roles', function(Blueprint $table) {
            $table->unique('uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function(Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
}
