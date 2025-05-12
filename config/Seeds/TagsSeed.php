<?php
declare(strict_types=1);

use Migrations\BaseSeed;

/**
 * Tags seed.
 */
class TagsSeed extends BaseSeed
{
    /**
     * Run Method.
     
     * More information on writing seeds is available here:
     * https://book.cakephp.org/migrations/4/en/seeding.html
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'tag1',
                'description' => 'tag1 description',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'tag2',
                'description' => 'tag2 description',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'tag3',
                'description' => 'tag3 description',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
        ];

        /*$this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->table('users')->truncate();
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');*/

        $table = $this->table('tags');
        $table->insert($data)->save();
    }
}
