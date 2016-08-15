<?php

class Company extends CActiveRecord {

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
        return '{{company}}';
    }
    
    public function getComById($com_id){
        $cache_name = md5('model_Company_getComById' . $com_id);

        $company = Yii::app()->memcache->get($cache_name);
        if (!$company) {
            $company_data = $this->findByPk($com_id);
            $company = array(
                'id' => $company_data->id,
                'name' => $company_data->name,
                'status' => $company_data->status,
                'super_uid' => $company_data->super_uid
            );
            Yii::app()->memcache->set($cache_name, $company, 300);
        }
        return $company;
    }
}