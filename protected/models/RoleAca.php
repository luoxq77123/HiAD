<?php

class RoleAca extends CActiveRecord {

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
        return '{{role_aca}}';
    }

    public function rules() {
        return array(
            array('role_id,aca_id', 'required', 'message' => '{attribute}不能为空','on' => 'add,edit')
        );
    }
    public function getAcaIdsByRole($role_id) {
        $cache_name = md5('model_RoleAca_getAcaIdsByRole' . $role_id);

        $acaList = Yii::app()->memcache->get($cache_name);
        if (!$acaList) {
            $acaList = array();
            $aca_ids = $this->findAll('role_id=:role_id', array(':role_id' => intval($role_id)));

            foreach ($aca_ids as $one) {
                $acaList[] = $one->aca_id;
            }
            Yii::app()->memcache->set($cache_name, $acaList, 300);
        }
        return $acaList;
    }

    public function getRoleAca($role_id) {
        $acaList = array();
        if ($role_id) {
            $aca_ids = $this->findAll('role_id=:role_id', array(':role_id' => intval($role_id)));
            foreach ($aca_ids as $one) {
                $acaList[] = $one->aca_id;
            }
        }
        return $acaList;
    }

}