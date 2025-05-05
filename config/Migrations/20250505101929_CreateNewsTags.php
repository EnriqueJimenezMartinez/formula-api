<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateNewsTags extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/4/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('news_tags');
        $table->addColumn('news_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('tags_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addForeignKey('news_id','news','id');
        $table->addForeignKey('tags_id','tags','id');
        $table->create();
    }
}
