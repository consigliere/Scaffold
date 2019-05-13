<?php
/**
 * Copyright(c) 2019. All rights reserved.
 * Last modified 5/13/19 7:11 AM
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Webpatser\Uuid\Uuid;

class AddUuidToPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Step 1: add the uuid field without unique()
        Schema::table('permissions', function(Blueprint $table) {
            $table->string('uuid')->after('id');
        });

        // Step 2: Update each row to populate the uuid field
        DB::table('permissions')->get()->each(function($permission) {
            DB::table('permissions')
                ->where('id', $permission->id)
                ->update(['uuid' => Uuid::generate(5, $permission->id, Uuid::NS_DNS)]);
        });

        // Step 3: add the unique constraint to uuid
        Schema::table('permissions', function(Blueprint $table) {
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
        Schema::table('permissions', function(Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
}
