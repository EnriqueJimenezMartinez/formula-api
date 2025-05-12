<?php
declare(strict_types=1);

use Migrations\BaseSeed;

/**
 * Users seed.
 */
class UsersSeed extends BaseSeed
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
                'name' => 'kike',
                'surname' => 'Jimenez',
                'nickname' => 'elkike',
                'password' => password_hash('admin', PASSWORD_DEFAULT),
                'email' => 'kike@jimenez.com',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
        ];

        /*
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->table('users')->truncate();
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');*/

        $table = $this->table('users');
        $table->insert($data)->save();

}
}
