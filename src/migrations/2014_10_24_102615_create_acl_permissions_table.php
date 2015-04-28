<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAclPermissionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('acl_permissions', function (Blueprint $table) {
            $table->string('id', 16);
            $table->string('name', 32);
            $table->string('description')->nullable();
            $table->timestamps();

            $table->primary('id');
            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('acl_permissions');
    }

}
