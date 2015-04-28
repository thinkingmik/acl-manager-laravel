<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAclUsersPoliciesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('acl_users_policies', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->string('resource_id', 16);
			$table->string('permission_id', 16);
			$table->dateTime('expiration')->nullable();
			
			$table->timestamps();
			
			$table->unique(['user_id', 'resource_id', 'permission_id']);
			
			$table->index('user_id');
			$table->index('resource_id');
			$table->index('permission_id');

			$table->foreign('user_id')
                  ->references('id')->on('system_users')
                  ->onUpdate('cascade')
				  ->onDelete('cascade');
			
            $table->foreign('resource_id')
                  ->references('id')->on('acl_resources')
                  ->onUpdate('cascade')
				  ->onDelete('cascade');
				  
			$table->foreign('permission_id')
                  ->references('id')->on('acl_permissions')
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
		Schema::table('acl_users_policies', function (Blueprint $table) {
            $table->dropForeign('acl_users_policies_user_id_foreign');
			$table->dropForeign('acl_users_policies_resource_id_foreign');
			$table->dropForeign('acl_users_policies_permission_id_foreign');
        });
        Schema::drop('acl_users_policies');
	}

}
