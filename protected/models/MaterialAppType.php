<?php

class MaterialAppType extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{material_atype}}';
    }

    public function getMaterialaTypes() {
        $cache_name = md5('model_AdType_MaterialaTypes_');
        $MaterialaTypes = Yii::app()->memcache->get($cache_name);
        if (!$MaterialaTypes) {
            $data = $this->findAll(array(
                    'select'=>'id, name,code',
                    'order'=>'id asc'
                ));
            foreach ($data as $one) {
                $MaterialaTypes[$one->id] = array(
                    'id' => $one->id,
                    'name' => $one->name,
                    'code' => $one->code
                );
            }
            Yii::app()->memcache->set($cache_name, $MaterialaTypes, 300);
        }
        return $MaterialaTypes;
    }

}