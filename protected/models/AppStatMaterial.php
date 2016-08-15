<?php
class AppStatMaterial extends CActiveRecord {
    // attribute used select data
    public $id;
    public $show_num;
    public $click_num;
    public $cpm_cost;
    public $cpc_cost;
    public $name;
    public $ctr;
    public $dedicgotd_ip;
    public $unique_users;

    public static function model($className = __CLASS__) {
        // 将数据库选择到主库
        CActiveRecord::$db = Yii::app()->db_stat_client;
        return parent::model($className);
    }

    public function tableName() {
        return 'map_stat_material';
    }
}