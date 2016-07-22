<?php

class SiteAdUrl extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{site_ad_url}}';
    }

    public function rules() {
        return array(
        );
    }

    public function getOneByUrl($url){
        return $this->find('url=:url', array(':url'=>$url));
    }
    
    public function addOneByUrl($url){
        $this->url = $url;
        return $this->save();
    }

}