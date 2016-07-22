<?php

/**
 * 客户公司控制器
 */
class ClientCompanyController extends BaseController {

    /**
     * 客户公司列表
     */

	 
	 
    public function actionList() {
        $user = Yii::app()->session['user'];

        $criteria = new CDbCriteria();
        $criteria->order = 'createtime desc';
        $criteria->select = 'id,name,description,type,createtime';
        $criteria->addColumnCondition(array('com_id' => $user['com_id']));
		
        if (isset($_GET['status'])) {
            $criteria->addColumnCondition(array('status' => $_GET['status']));
        } else {
            $_GET['status'] = 1;
            $criteria->addColumnCondition(array('status' => $_GET['status']));
        }
        if (isset($_GET['type']) && $_GET['type']) {
            $criteria->addColumnCondition(array('type' => $_GET['type']));
        }
        if (isset($_GET['name']) && $_GET['name']) {
            $criteria->addSearchCondition('name', urldecode($_GET['name']));
        }

        // 分页
        $count = ClientCompany::model()->count($criteria);
        $pageSize = (isset($_GET['pagesize']) && $_GET['pagesize']) ? $_GET['pagesize'] : 10;
        $pager = new CPagination($count);
        $pager->pageSize = $pageSize;
        $pager->applyLimit($criteria);

        $clientcompanylist = ClientCompany::model()->findAll($criteria);
        $type = array(1 => '广告客户', 2 => '代理机构');
        $searchType = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : 0;
        $searchName = isset($_GET['name']) && $_GET['name'] ? urldecode($_GET['name']) : '';
        $setArray = array(
            'clientcompanylist' => $clientcompanylist,
            'pages' => $pager,
            'type' => $type,
            'searchType' => $searchType,
            'searchName' => $searchName
        );
        $this->renderPartial('list', $setArray);
    }
	
     /**
     * 修改客户->公司的状态
     */
    public function actionSetStatus() {
        $id = $_POST['order'];
        $status = $_POST['status'];
        $return = array('code' => 1, 'message' => '操作成功', 'id' => 0, 'com_name' => '');
        if (!ClientCompany::model()->setZt($id, $status)) {
            $return['code'] = -1;
            $return['message'] = '操作失败';  
        }
         die(json_encode($return));
    }

    /**
     * 添加客户公司
     */
    public function actionAdd() {
        $com = new ClientCompany('add');

        if (isset($_POST['ClientCompany'])) {
            $return = array('code' => 1, 'message' => '添加成功', 'id' => 0, 'com_name' => '', 'addType' => $_GET['addType']);

            $com->attributes = $_POST['ClientCompany'];
            if ($com->validate()) {
                $com->com_id = Yii::app()->session['user']['com_id'];
                $com->createtime = time();
                if ($com->save()) {
                    Yii::app()->oplog->add(); //添加日志
                }
                $return['id'] = $com->attributes['id'];
                $return['com_name'] = $_POST['ClientCompany']['name'];
            }
            if ($com->hasErrors()) {
                $return['code'] = -1;
                $return['message'] = '<p style="color:red;">添加失败</p>';
                foreach ($com->errors as $item) {
                    foreach ($item as $one)
                        $return['message'] .= '<p>' . $one . '</p>';
                }
            }
            die(json_encode($return));
        }
        $this->renderPartial('add', array('com' => $com));
    }

    /**
     * 编辑客户公司
     */
    public function actionEdit() {
        $id = $_GET['id'];
        $com = ClientCompany::model()->findByPk($id);
        $com->setScenario('edit');

        if (isset($_POST['ClientCompany'])) {
            $return = array('code' => 1, 'message' => '操作成功');

            $com->attributes = $_POST['ClientCompany'];
            if ($com->validate()) {
                if ($com->save()) {
                    Yii::app()->oplog->add(); //添加日志
                }
            }
            if ($com->hasErrors()) {
                $return['code'] = -1;
                $return['message'] = '<p style="color:red;">操作失败</p>';
                foreach ($com->errors as $item) {
                    foreach ($item as $one)
                        $return['message'] .= '<p>' . $one . '</p>';
                }
            }
            die(json_encode($return));
        }
        $this->renderPartial('edit', array('com' => $com));
    }

    /**
     * 删除客户公司 
     */
    public function actionDel() {
        $return = array('code' => 1, 'message' => '删除成功');
        if (isset($_POST['com']) && count($_POST['com'])) {
            $_POST['com'] = (array) $_POST['com'];
            $criteria = new CDbCriteria();
            $criteria->addInCondition('id', $_POST['com']);
            $criteria->addColumnCondition(array('com_id' => Yii::app()->session['user']['com_id']));
            ClientCompany::model()->deleteAll($criteria);
            Yii::app()->oplog->add(); //添加日志
        } else {
            $return = array('code' => -1, 'message' => '未选择用户');
        }
        die(json_encode($return));
    }
}