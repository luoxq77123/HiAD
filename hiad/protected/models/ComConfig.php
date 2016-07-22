<?php

class ComConfig extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{com_config}}';
    }
    
    public function rules() {
    }

    public function attributeLabels() {
        return array(
            'name' => '参数名称:',
            'key' => '参数键名:',
            'val' => '参数值:',
        );
    }

    public function relations() {
        return array(
            'Company' => array(self::HAS_ONE, 'Company', 'id')
        );
    }
    
    function afterSave() {
        parent::afterSave();
        // 删除缓存
        $getSettings_cache = md5('model_ComConfig_getConfigsByComId'.$this->com_id);
        Yii::app()->memcache->delete($getSettings_cache);
    }

    public function getConfigsByComId($com_id){
        $cache_name = md5('model_ComConfig_getConfigsByComId'.$com_id);
        $confings = Yii::app()->memcache->get($cache_name);
        if (!$confings) {
            $confings = $this->findByPk($com_id);
            Yii::app()->memcache->set($cache_name, $confings, 300);
        }
        return $confings;
    }
}