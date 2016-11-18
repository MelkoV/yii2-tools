<?php

use melkov\components\console\Migration;

class m141016_150234_geo extends Migration
{

    // yii migrate --migrationPath=@melkov/migrations/geo

    public function getNewTables()
    {
        return [
            "geo_country" => [
                "id" => $this->primaryKey(),
                "name" => $this->string(50)->notNull()
            ],
            "geo_federal_district" => [
                "id" => $this->primaryKey(),
                "name" => $this->string(100)->notNull(),
                "country_id" => $this->foreignKey("geo_country", "id", true)
            ],
            "geo_region" => [
                "id" => $this->primaryKey(),
                "name" => $this->string(100)->notNull(),
                "federal_district_id" => $this->foreignKey("geo_federal_district", "id", true)
            ],
            "geo_city" => [
                "id" => $this->primaryKey(),
                "geo_id" => $this->integer()->notNull(),
                "name" => $this->string(100)->notNull(),
                "is_capital" => $this->boolean()->notNull()->defaultValue(false),
                "weight" => $this->integer()->notNull()->defaultValue(0),
                "region_id" => $this->foreignKey("geo_region", "id", true),
                "lat" => $this->string(20),
                "lon" => $this->string(20),
            ],
            "geo_ip" => [
                "id" => $this->primaryKey(),
                "city_id" => $this->foreignKey("geo_city", "id", true),
                "start" => "inet",
                "end" => "inet"
            ],
        ];
    }



    public function safeUp()
    {
        $path = Yii::getAlias("@melkov/migrations/geo");
        $this->createTables($this->getNewTables());
        $fp = fopen($path . DIRECTORY_SEPARATOR . "cities.txt", "r");

        $model = new \melkov\models\GeoCountry();
        $model->name = "Россия";
        $model->save();
        $ru = $model->id;
        $model = new \melkov\models\GeoCountry();
        $model->name = "Украина";
        $model->save();
        $ua = $model->id;

        $uaEnd = 478;
        $districts = [];
        $regions = [];
        $cities = [];

        while (($line = fgets($fp, 4096)) !== false) {
            $data = explode("\t", $line);
            $city = new \melkov\models\GeoCity();
            $city->geo_id = $data[0];
            if (!isset($districts[$data[3]])) {
                $d = new \melkov\models\GeoFederalDistrict();
                $d->name = $data[3];
                $d->country_id = $city->geo_id > $uaEnd ? $ru : $ua;
                $d->save();
                $districts[$data[3]] = $d->id;
            }
            if (!isset($regions[$data[2]])) {
                $d = new \melkov\models\GeoRegion();
                $d->name = $data[2];
                $d->federal_district_id = $districts[$data[3]];
                $d->save();
                $regions[$data[2]] = $d->id;
            }
            $city->region_id = $regions[$data[2]];
            $city->name = $data[1];
            $city->lat = $data[4];
            $city->lon = $data[5];
            $city->save();
            $cities[$data[0]] = $city->id;
        }
        fclose($fp);

        $fp = fopen($path . DIRECTORY_SEPARATOR . "cidr_optim.txt", "r");

        while (($line = fgets($fp, 4096)) !== false) {
            $data = explode("\t", $line);
            $ips = explode("-", $data[2]);
            $data[4] = trim($data[4]);
            if (trim($data[4]) == "-") {
                continue;
            }
            if (!isset($cities[$data[4]])) {
                echo $data[4] . "|" . PHP_EOL;
                continue;
            }
            $model = new \melkov\models\GeoIp();
            $model->city_id = $cities[$data[4]];
            $model->start = trim($ips[0]);
            $model->end = trim($ips[1]);
            $model->save();
        }
        fclose($fp);

    }

    public function safeDown()
    {
        $this->dropTables($this->getNewTables());
    }


}


