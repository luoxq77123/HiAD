<?php

class MaterialVflash extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{material_vflash}}';
    }
    
    public function rules() {
        return array(
            array('url', 'required', 'message' => '{attribute}不能为空', 'on' => 'add,edit'),
            array('monitor_flash,monitor_flash_type,click_link,reserve,reserve_pic_url,reserve_pic_link,monitor,monitor_link,target_window,flash_x,flash_y,flashpic_x,flashpic_y', 'safe', 'on' => 'add,edit')
        );
    }

    public function attributeLabels() {
        return array(
           'url' => 'Flash文件:',
           'monitor_flash' => '允许对Flash进行点击监控:',
            'click_link' => 'flash点击链接:',
            'reserve' => 'Flash无法展现时显示后备图片:',
            'reserve_pic_url' => '备用图片:',
            'reserve_pic_link' => '图片点击链接:',
            'monitor' => '设置第三方展现监控:',
            'monitor_link' => '监控链接监控链接:'
        );
    }

    public function getWindowOption(){
        return array(1 => '新窗口', 2 => '原窗口');
    }
    public function getFlashTypeOption(){
        return array(1 => '普通', 2 => 'clickTAG');
    }
    public function getFlashbgOption(){
        return array(1 => '透明', -1 => '不透明');
    }

}