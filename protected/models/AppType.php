<?php

class AppType extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return CActiveRecord the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{app_type}}';
    }
    function afterSave() {
        parent::afterSave();
        // 删除缓存
        $user = Yii::app()->session['user'];
        $getByIds_cache = md5('model_apptype_getAppTypename');
        Yii::app()->memcache->delete($getByIds_cache);
    }

    public function getAppTypename(){
        $cache_name = md5('model_apptype_getAppTypename');
        $typeName = Yii::app()->memcache->get($cache_name);
        if (!$typeName) {
            $data = $this->findAll();
             $typeName = array();
            foreach ($data as $one) {
                $typeName[$one->id] = $one->name;
            }
            Yii::app()->memcache->set($cache_name, $typeName, 300);
        }
        return $typeName;
    }

}