<?php

class Aca extends CActiveRecord {

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
        return '{{aca}}';
    }

    public function getAcaMap() {
        $cache_name = md5('model_Aca_getAcaMap');

        $acaMap = Yii::app()->memcache->get($cache_name);
        if (!$acaMap) {
            $acaMap = array();
            $acas = $this->findAll();
            foreach ($acas as $one) {
                if (trim($one->controller) && trim($one->action))
                    $acaMap[$one->controller][$one->action] = $one->id;
            }
            Yii::app()->memcache->set($cache_name, $acaMap, 300);
        }
        return $acaMap;
    }
    
    public function getAcaList() {
        $cache_name = md5('model_Aca_getAcaList');

        $acaMap = Yii::app()->memcache->get($cache_name);
        if (!$acaMap) {
            $acaMap = array();
            $acas = $this->findAll('parent_id!=:parent_id', array(':parent_id'=>0));
            foreach ($acas as $one) {
                if (trim($one->controller) && trim($one->action)) {
                    $acaMap[$one->id]['controller'] = $one->controller;
                    $acaMap[$one->id]['action'] = $one->action;
                }
            }
            Yii::app()->memcache->set($cache_name, $acaMap, 300);
        }
        return $acaMap;
    }

    public function getAcaName() {
        $cache_name = md5('model_Aca_getAcaName');

        $acaName = Yii::app()->memcache->get($cache_name);
        if (!$acaName) {
            $acaName = array();
            $acas = $this->findAll();
            foreach ($acas as $one) {
                if (trim($one->controller) && trim($one->action))
                    $acaName[$one->id] = $one->name;
            }
            Yii::app()->memcache->set($cache_name, $acaName, 300);
        }
        return $acaName;
    }

    public function getParents() {
        $cache_name = md5('model_Aca_getParents');
        $Parents = Yii::app()->memcache->get($cache_name);
        if (!$Parents) {
            $Parents = array();
            $acas = $this->findAll('parent_id=:parent_id',array(':parent_id'=>0));
            foreach ($acas as $one) {
              //  if (trim($one->controller) && trim($one->action))
                    $Parents[$one->id] = $one->name;
            }
            Yii::app()->memcache->set($cache_name, $Parents, 300);
        }
        return $Parents;
    }

     public function getAcachild($parentid) {
        $cache_name = md5('model_Aca_getAcachild_'.$parentid);
        $acaName = Yii::app()->memcache->get($cache_name);
        if (!$acaName) {
            $acaName = array();
            $acas = $this->findAll('parent_id=:parent_id',array(':parent_id'=>$parentid));
            foreach ($acas as $one) {
                if (trim($one->controller) && trim($one->action))
                    $acaName[$one->id] = $one->name;
            }
            Yii::app()->memcache->set($cache_name, $acaName, 300);
        }
        return $acaName;
    }

}