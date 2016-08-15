<?php

class HelpContent extends CActiveRecord {
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
        return '{{help_content}}';
    }

    public function rules() {
        return array(
            array('name', 'required', 'message' => '{attribute}不能为空')
        );
    }
    
    // 获取所有帮助内容
    public function getAll(){
        $cache_name = md5('model_HelpContent_getList');
        $arrData = Yii::app()->memcache->get($cache_name);
        if (!$arrData) {
            $criteria = new CDbCriteria();
            $criteria->order = 'sort asc';
            $criteria->select = 'id,name,node_id,content,create_time';
            $criteria->addColumnCondition(array('status' => 1));
            $list = $this->findAll($criteria);
            if ($list) {
                foreach($list as $val) {
                    $arrData[$val->id]['id'] = $val->id;
                    $arrData[$val->id]['name'] = $val->name;
                    $arrData[$val->id]['node_id'] = $val->node_id;
                    $arrData[$val->id]['content'] = $val->content;
                    $arrData[$val->id]['create_time'] = $val->create_time;
                }
            }
            Yii::app()->memcache->set($cache_name, $arrData, 300);
        }
        return $arrData;
    }
    
    // 获取内容列表根据节点id
    public function getListByNodeId($nodeId) {
        $arrDetail = $this->getAll();
        $list = array();
        foreach($arrDetail as $id=>$detail) {
            if ($detail['node_id'] == $nodeId) {
                $temp = array();
                $temp['id'] = $detail['id'];
                $temp['name'] = $detail['name'];
                $list[$id] = $temp;
            }
        }
        return $list;
    }
}