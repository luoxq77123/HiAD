<?php

class PositionSize extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{position_size}}';
    }

    public function rules() {
        return array(
        );
    }

    public function getSizes($ad_type_id) {
        $cache_name = md5('model_PositionSize_getSizes_'.$ad_type_id);
        $sizes = Yii::app()->memcache->get($cache_name);
        if (!$sizes) {
            $sizes = array();
            $data = $this->findAll(array(
                    //'select'=>'id, name',
                    'condition'=>'ad_type_id=:ad_type_id',
                    'order'=>'id asc',
                    'params'=>array(':ad_type_id' => $ad_type_id)
                ));
            foreach ($data as $one) {
                $sizes[$one->width . '*' . $one->height] = $one->width . '*' . $one->height;
            }
            Yii::app()->memcache->set($cache_name, $sizes, 300);
        }
        return $sizes;
    }

}