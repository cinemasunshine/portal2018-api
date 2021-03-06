<?php

declare(strict_types=1);

use App\Doctrine\Entities\AdminUser;
use Illuminate\Database\Seeder;

class AdminUserTableSeeder extends Seeder
{
    private const TABLE = 'admin_user';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table(self::TABLE)->insert([
            'id' => 1,
            'theater_id' => null,
            'name' => 'master',
            'display_name' => 'マスター',
            'password' => AdminUser::encryptPassword('password'),
            'group' => AdminUser::GROUP_MASTER,
            'is_deleted' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
