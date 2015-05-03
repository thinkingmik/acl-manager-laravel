<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAclResourcesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('acl_resources', function (Blueprint $table) {
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
        Schema::drop('acl_resources');
    }

}
