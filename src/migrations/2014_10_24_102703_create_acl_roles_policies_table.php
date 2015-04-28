<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAclRolesPoliciesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('acl_roles_policies', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('role_id')->unsigned();
			$table->string('resource_id', 16);
			$table->string('permission_id', 16);
			$table->dateTime('expiration')->nullable();
			
			$table->timestamps();
			
			$table->unique(['role_id', 'resource_id', 'permission_id']);
			
			$table->index('role_id');
			$table->index('resource_id');
			$table->index('permission_id');

			$table->foreign('role_id')
                  ->references('id')->on('acl_roles')
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
		Schema::table('acl_roles_policies', function (Blueprint $table) {
            $table->dropForeign('acl_roles_policies_role_id_foreign');
			$table->dropForeign('acl_roles_policies_resource_id_foreign');
			$table->dropForeign('acl_roles_policies_permission_id_foreign');
        });
        Schema::drop('acl_roles_policies');
	}

}
