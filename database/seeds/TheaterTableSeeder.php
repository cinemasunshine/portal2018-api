<?php

use App\Doctrine\Entities\Theater;
use Illuminate\Database\Seeder;

class TheaterTableSeeder extends Seeder
{
    private const TABLE = 'theater';

    private const TABLE_META = 'theater_meta';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->insertIkebukuro(1);
        $this->insertHeiwajima(2);
        $this->insertNumazu(6);
        $this->insertKitajima(7);
        $this->insertKinuyama(8);
        $this->insertOkaido(9);
        $this->insertOzu(11);
        $this->insertShigenobu(12);
        $this->insertTsuchiura(13);
        $this->insertKahoku(14);
        $this->insertMasaki(15);
        $this->insertYamatokoriyama(16);
        $this->insertShimonoseki(17);
        $this->insertAira(18);
        $this->insertYukarigaoka(19);
        $this->insertGdcs(20);
        $this->insertLalaportnumazu(21);
    }

    /**
     * @param integer $id
     * @return void
     */
    protected function insertIkebukuro(int $id)
    {
        DB::table(self::TABLE)->insert([
            'id' => $id,
            'name' => 'ikebukuro',
            'name_ja' => '池袋',
            'area' => 1,
            'master_version' => Theater::MASTER_VERSION_V2,
            'master_code' => '101',
            'display_order' => 2,
            'status' => Theater::STATUS_CLOSED,
            'is_deleted' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $metaId = $id;
        $this->insertTheaterMeta($metaId, $id);
    }

    /**
     * @param integer $id
     * @return void
     */
    protected function insertHeiwajima(int $id)
    {
        DB::table(self::TABLE)->insert([
            'id' => $id,
            'name' => 'heiwajima',
            'name_ja' => '平和島',
            'area' => 1,
            'master_version' => Theater::MASTER_VERSION_V2,
            'master_code' => '102',
            'display_order' => 3,
            'status' => Theater::STATUS_OPEN,
            'is_deleted' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $metaId = $id;
        $this->insertTheaterMeta($metaId, $id);
    }

    /**
     * @param integer $id
     * @return void
     */
    protected function insertNumazu(int $id)
    {
        DB::table(self::TABLE)->insert([
            'id' => $id,
            'name' => 'numazu',
            'name_ja' => '沼津',
            'area' => 2,
            'master_version' => Theater::MASTER_VERSION_V2,
            'master_code' => '106',
            'display_order' => 6,
            'status' => Theater::STATUS_OPEN,
            'is_deleted' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $metaId = $id;
        $this->insertTheaterMeta($metaId, $id);
    }

    /**
     * @param integer $id
     * @return void
     */
    protected function insertKitajima(int $id)
    {
        DB::table(self::TABLE)->insert([
            'id' => $id,
            'name' => 'kitajima',
            'name_ja' => '北島',
            'area' => 4,
            'master_version' => Theater::MASTER_VERSION_V2,
            'master_code' => '112',
            'display_order' => 16,
            'status' => Theater::STATUS_OPEN,
            'is_deleted' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $metaId = $id;
        $this->insertTheaterMeta($metaId, $id);
    }

    /**
     * @param integer $id
     * @return void
     */
    protected function insertKinuyama(int $id)
    {
        DB::table(self::TABLE)->insert([
            'id' => $id,
            'name' => 'kinuyama',
            'name_ja' => '衣山',
            'area' => 4,
            'master_version' => Theater::MASTER_VERSION_V2,
            'master_code' => '108',
            'display_order' => 12,
            'status' => Theater::STATUS_OPEN,
            'is_deleted' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $metaId = $id;
        $this->insertTheaterMeta($metaId, $id);
    }

    /**
     * @param integer $id
     * @return void
     */
    protected function insertOkaido(int $id)
    {
        DB::table(self::TABLE)->insert([
            'id' => $id,
            'name' => 'okaido',
            'name_ja' => '大街道',
            'area' => 4,
            'master_version' => Theater::MASTER_VERSION_V2,
            'master_code' => '107',
            'display_order' => 11,
            'status' => Theater::STATUS_OPEN,
            'is_deleted' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $metaId = $id;
        $this->insertTheaterMeta($metaId, $id);
    }

    /**
     * @param integer $id
     * @return void
     */
    protected function insertOzu(int $id)
    {
        DB::table(self::TABLE)->insert([
            'id' => $id,
            'name' => 'ozu',
            'name_ja' => '大洲',
            'area' => 4,
            'master_version' => Theater::MASTER_VERSION_V1,
            'master_code' => null,
            'display_order' => 15,
            'status' => Theater::STATUS_CLOSED,
            'is_deleted' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $metaId = $id;
        $this->insertTheaterMeta($metaId, $id);
    }

    /**
     * @param integer $id
     * @return void
     */
    protected function insertShigenobu(int $id)
    {
        DB::table(self::TABLE)->insert([
            'id' => $id,
            'name' => 'shigenobu',
            'name_ja' => '重信',
            'area' => 4,
            'master_version' => Theater::MASTER_VERSION_V2,
            'master_code' => '109',
            'display_order' => 13,
            'status' => Theater::STATUS_OPEN,
            'is_deleted' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $metaId = $id;
        $this->insertTheaterMeta($metaId, $id);
    }

    /**
     * @param integer $id
     * @return void
     */
    protected function insertTsuchiura(int $id)
    {
        DB::table(self::TABLE)->insert([
            'id' => $id,
            'name' => 'tsuchiura',
            'name_ja' => '土浦',
            'area' => 1,
            'master_version' => Theater::MASTER_VERSION_V2,
            'master_code' => '113',
            'display_order' => 5,
            'status' => Theater::STATUS_OPEN,
            'is_deleted' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $metaId = $id;
        $this->insertTheaterMeta($metaId, $id);
    }

    /**
     * @param integer $id
     * @return void
     */
    protected function insertKahoku(int $id)
    {
        DB::table(self::TABLE)->insert([
            'id' => $id,
            'name' => 'kahoku',
            'name_ja' => 'かほく',
            'area' => 2,
            'master_version' => Theater::MASTER_VERSION_V2,
            'master_code' => '114',
            'display_order' => 8,
            'status' => Theater::STATUS_OPEN,
            'is_deleted' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $metaId = $id;
        $this->insertTheaterMeta($metaId, $id);
    }

    /**
     * @param integer $id
     * @return void
     */
    protected function insertMasaki(int $id)
    {
        DB::table(self::TABLE)->insert([
            'id' => $id,
            'name' => 'masaki',
            'name_ja' => 'エミフルMASAKI',
            'area' => 4,
            'master_version' => Theater::MASTER_VERSION_V2,
            'master_code' => '115',
            'display_order' => 14,
            'status' => Theater::STATUS_OPEN,
            'is_deleted' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $metaId = $id;
        $this->insertTheaterMeta($metaId, $id);
    }

    /**
     * @param integer $id
     * @return void
     */
    protected function insertYamatokoriyama(int $id)
    {
        DB::table(self::TABLE)->insert([
            'id' => $id,
            'name' => 'yamatokoriyama',
            'name_ja' => '大和郡山',
            'area' => 3,
            'master_version' => Theater::MASTER_VERSION_V2,
            'master_code' => '116',
            'display_order' => 9,
            'status' => Theater::STATUS_OPEN,
            'is_deleted' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $metaId = $id;
        $this->insertTheaterMeta($metaId, $id);
    }

    /**
     * @param integer $id
     * @return void
     */
    protected function insertShimonoseki(int $id)
    {
        DB::table(self::TABLE)->insert([
            'id' => $id,
            'name' => 'shimonoseki',
            'name_ja' => '下関',
            'area' => 4,
            'master_version' => Theater::MASTER_VERSION_V2,
            'master_code' => '117',
            'display_order' => 10,
            'status' => Theater::STATUS_OPEN,
            'is_deleted' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $metaId = $id;
        $this->insertTheaterMeta($metaId, $id);
    }

    /**
     * @param integer $id
     * @return void
     */
    protected function insertAira(int $id)
    {
        DB::table(self::TABLE)->insert([
            'id' => $id,
            'name' => 'aira',
            'name_ja' => '姶良',
            'area' => 5,
            'master_version' => Theater::MASTER_VERSION_V2,
            'master_code' => '118',
            'display_order' => 17,
            'status' => Theater::STATUS_OPEN,
            'is_deleted' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $metaId = $id;
        $this->insertTheaterMeta($metaId, $id);
    }

    /**
     * @param integer $id
     * @return void
     */
    protected function insertYukarigaoka(int $id)
    {
        DB::table(self::TABLE)->insert([
            'id' => $id,
            'name' => 'yukarigaoka',
            'name_ja' => 'ユーカリが丘',
            'area' => 1,
            'master_version' => Theater::MASTER_VERSION_V2,
            'master_code' => '119',
            'display_order' => 4,
            'status' => Theater::STATUS_OPEN,
            'is_deleted' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $metaId = $id;
        $this->insertTheaterMeta($metaId, $id);
    }

    /**
     * @param integer $id
     * @return void
     */
    protected function insertGdcs(int $id)
    {
        DB::table(self::TABLE)->insert([
            'id' => $id,
            'name' => 'gdcs',
            'name_ja' => 'グランドシネマサンシャイン',
            'area' => 1,
            'master_version' => Theater::MASTER_VERSION_V2,
            'master_code' => '120',
            'display_order' => 1,
            'status' => Theater::STATUS_OPEN,
            'is_deleted' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $metaId = $id;
        $this->insertTheaterMeta($metaId, $id);
    }

    /**
     * @param integer $id
     * @return void
     */
    protected function insertLalaportnumazu(int $id)
    {
        DB::table(self::TABLE)->insert([
            'id' => $id,
            'name' => 'lalaportnumazu',
            'name_ja' => 'ららぽーと沼津',
            'area' => 2,
            'master_version' => Theater::MASTER_VERSION_V2,
            'master_code' => '121',
            'display_order' => 7,
            'status' => Theater::STATUS_OPEN,
            'is_deleted' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $metaId = $id;
        $this->insertTheaterMeta($metaId, $id);
    }

    /**
     * @param integer $id
     * @param integer $theaterId
     * @return void
     */
    protected function insertTheaterMeta(int $id, int $theaterId)
    {
        DB::table(self::TABLE_META)->insert([
            'id' => $id,
            'theater_id' => $theaterId,
            'opening_hours' => '[]',
            'twitter' => 'example_tw',
            'facebook' => 'example_fb',
            'oyako_cinema_url' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
