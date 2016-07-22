<?php

class HelpNode extends CActiveRecord {
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
        return '{{help_node}}';
    }

    public function rules() {
        return array(
            array('name', 'required', 'message' => '{attribute}不能为空')
        );
    }
    
    public function attributeLabels() {
        return array(
            'name' => '节点名称',
            'parent_id' => '父节点',
            'sort' => '排序'
        );
    }
    
    /**
     * 获取所有节点
     */
    public function getAllNote(){
        $cache_name = md5('model_HelpNode_getAllNote');
        $arrData = Yii::app()->memcache->get($cache_name);
        if (!$arrData) {
            $criteria = new CDbCriteria();
            $criteria->order = 'sort asc';
            $criteria->select = 'id,name,parent_id, child_ids';
            $list = $this->findAll($criteria);
            if ($list) {
                foreach($list as $val) {
                    $arrData[$val->id] = $val;
                }
            }
            Yii::app()->memcache->set($cache_name, $arrData, 300);
        }
        return $arrData;
    }
    
    // 获取内容列表根据节点id
    public function getNodeByParentId($pid) {
        $arrNode = $this->getAllNote();
        $arrChild = explode(",", $arrNode[$pid]['child_ids']);
        $list = array();
        if (!empty($arrChild)) {
            foreach($arrChild as $one) {
                $temp = array();
                $temp['id'] = $arrNode[$one]['id'];
                $temp['name'] = $arrNode[$one]['name'];
                $list[$one] = $temp;
            }
        }
        return $list;
    }
    
    /**
     * 获取最终节点
     */
    public function getNoChildNote(){
        $criteria = new CDbCriteria();
        $criteria->order = 'sort asc';
        $criteria->select = 'id,name,parent_id';
        $criteria->addCondition("child_ids is null");
        $list = $this->findAll($criteria);
        return $list;
    }
}