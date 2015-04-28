<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAclUsersRolesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('acl_users_roles', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('role_id')->unsigned();
            $table->char('main', 1)->default('N');
			
			$table->timestamps();
			
			$table->index('user_id');
			$table->index('role_id');

			$table->foreign('user_id')
                  ->references('id')->on('system_users')
                  ->onUpdate('cascade')
				  ->onDelete('cascade');
			
            $table->foreign('role_id')
                  ->references('id')->on('acl_roles')
                  ->onUpdate('cascade')
				  ->onDelete('cascade');
				  
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('acl_users_roles', function (Blueprint $table) {
            $table->dropForeign('acl_users_roles_user_id_foreign');
			$table->dropForeign('acl_users_roles_role_id_foreign');
        });
        Schema::drop('acl_users_roles');
	}

}
