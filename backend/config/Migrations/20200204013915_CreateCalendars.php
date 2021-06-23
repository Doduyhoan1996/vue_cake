<?php
use Migrations\AbstractMigration;

class CreateCalendars extends AbstractMigration
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
        $table = $this->table('calendars');
        $table->addColumn("user_id", "integer");
        $table->addColumn("type", "integer");
        $table->addColumn("date_start", "datetime");
        $table->addColumn("date_end", "datetime");
        $table->addColumn("title", "text", [
            "null" => false
        ]);
        $table->addColumn("status", "integer", [
            "default" => 0
        ]);
        $table->addColumn("confirm_by", "integer", [
            "null" => true
        ]);
        $table->addColumn("created", "datetime");
        $table->addColumn("modified", "datetime");
        $table->create();
    }
}
