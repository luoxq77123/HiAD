<?php

class AppGroup extends CActiveRecord {

     public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{app_group}}';
    }

    public function rules() {
        return array(
            array('name,sort', 'required', 'message' => '{attribute}不能为空', 'on' => 'add,edit'),
            array('name', 'acheckName', 'on' => 'add'),
            array('name', 'echeckName', 'on' => 'edit'),
            array('description', 'safe', 'on' => 'add,edit')
        );
    }

    public function attributeLabels() {
        return array(
            'name' => '应用组名',
            'sort' => '显示顺序',
            'description' => '说明'
        );
    }

    function afterSave() {
        parent::afterSave();
        // 删除缓存
        $user = Yii::app()->session['user'];
        $getByIds_cache = md5('model_appGroup_getAppgroup_'.$user['com_id']);
        Yii::app()->memcache->delete($getByIds_cache);
    }

    public function getAppgroup($com_id){
        $cache_name = md5('model_appGroup_getAppgroup_'.$com_id);
        $Appgroup = Yii::app()->memcache->get($cache_name);
        if(!$Appgroup){
            $data = $this->findAll(array(
                'select'=>'id,name',
                'condition'=>'com_id=:com_id',
                'order'=>'createtime desc',
                'params'=>array(':com_id'=>$com_id)
            ));
            $Appgroup=array();
            foreach ($data as $one) {
                $Appgroup[$one->id] =$one->name;
            }
            Yii::app()->memcache->set($cache_name, $Appgroup, 300);
        }
        return $Appgroup;
    }

     /**
     * 名称唯一
     */
    public function acheckName() {
        if (!$this->hasErrors()) {
            $com_id = Yii::app()->session['user']['com_id'];
            
            $name=$this->count(array(
                'condition'=>'com_id=:com_id and name =:name',
                'params'=>array(':com_id' => $com_id,':name'=>$this->name)
            ));
            if($name > 0){
                $this->addError('name', '此名称已存在，请填入其他名称！');
            }
        }
    }

    /**
     * 名称唯一
     */
    public function echeckName() {
        if (!$this->hasErrors()) {
            $com_id = Yii::app()->session['user']['com_id'];
            
            $name=$this->count(array(
                'condition'=>'com_id=:com_id and name =:name and id !=:id',
                'params'=>array(':com_id' => $com_id,':name'=>$this->name,':id'=>$this->id)
            ));
            if($name > 0){
                $this->addError('name', '此名称已存在，请填入其他名称！');
            }
        }
    }
}