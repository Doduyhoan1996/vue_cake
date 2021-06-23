<?php
use Migrations\AbstractMigration;

class CreateUsers extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('users');
        $table->addColumn("first_name", "string");
        $table->addColumn("last_name", "string");
        $table->addColumn("email", "string");
        $table->addColumn("birthday", "date");
        $table->addColumn("gender", "integer", [
            "default" => 1,
            "length" => 1
        ]);
        $table->addColumn("phone", "string", [
            "null" => true,
            "length" => 50
        ]);
        $table->addColumn("address", "string", [
            "null" => true
        ]);
        $table->addColumn("password", "string");
        $table->addColumn("position", "string", [
            "null" => true
        ]);
        $table->addColumn("type", "integer", [
            "default" => 1,
            "comment" => '1: Chinh thuc - 9: Thuc tap'
        ]);
        $table->addColumn("role", "integer", [
            "default" => 9,
            "comment" => '1: Quan tri - 2: Hanh chinh - 9: Nhan vien'
        ]);
        $table->addColumn("can_create_project", "boolean", [
            "default" => false
        ]);
        $table->addColumn("salary", "integer", [
            "default" => 0
        ]);
        $table->addColumn("st_tz", "string", [
            "null" => true
        ]);
        $table->addColumn("st_date_format", "string", [
            "null" => true
        ]);
        $table->addColumn("status", "integer", [
            "default" => 1
        ]);
        $table->addColumn("created", "datetime");
        $table->addColumn("modified", "datetime");
        
        $table->addIndex("email");
        $table->create();
    }
}