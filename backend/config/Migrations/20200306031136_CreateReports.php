<?php
use Migrations\AbstractMigration;

class CreateReports extends AbstractMigration
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
        $table = $this->table('reports');
        $table->addColumn("user_id", "integer");
        $table->addColumn("team", "integer", [
            "comment" => "team",
            "null" => true
        ]);
        $table->addColumn("date_report", "date", [
            "comment" => "Ngày báo cáo"
        ]);
        $table->addColumn("work_content", "text", [
            "comment" => "Nội dung công việc"
        ]);
        $table->addColumn("next_date_report", "date", [
            "comment" => "Ngày tiếp"
        ]);
        $table->addColumn("next_work_content", "text", [
            "comment" => "Nội dung công việc ngày tiếp theo"
        ]);
        $table->addColumn("difficult", "text", [
            "comment" => "Khó khăn",
            "null" => true
        ]);
        $table->addColumn("hight_light", "boolean", [
            "default" => 0
        ]);
        $table->addColumn("created", "datetime");
        $table->addColumn("modified", "datetime");
        $table->create();
    }
}
