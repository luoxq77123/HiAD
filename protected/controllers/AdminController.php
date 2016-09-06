<?php

/**
 * 管理员控制器
 */
class AdminController extends BaseController {

    /**
     * 管理员列表
     */
    public function actionList() {
        $user = Yii::app()->session['user'];
        $com = Company::model()->getComById($user['com_id']);

        $criteria = new CDbCriteria();
        $criteria->order = 'createtime desc';
        $criteria->select = 'uid,name,email,role_id,status,createtime';
        $criteria->addColumnCondition(array('com_id' => $user['com_id']));
        $criteria->addNotInCondition('uid', array($com['super_uid']));
        // 附加搜索条件
        if (isset($_GET['status']) && $_GET['status']) {
            $criteria->addColumnCondition(array('status' => $_GET['status']));
        }
        if (isset($_GET['role']) && $_GET['role']) {
            $criteria->addColumnCondition(array('role_id' => $_GET['role']));
        }
        if (isset($_GET['name']) && $_GET['name']) {
            $criteria->addSearchCondition('name', urldecode($_GET['name']));
        }

        // 分页
        $count = User::model()->count($criteria);
        $pageSize = (isset($_GET['pagesize']) && $_GET['pagesize']) ? $_GET['pagesize'] : 10;
        $pager = new CPagination($count);
        $pager->pageSize = $pageSize;
        $pager->applyLimit($criteria);

        $adminlist = User::model()->findAll($criteria);

        $status = array(1 => '已启用', -1 => '已禁用');
        $roles = array(0 => '-请选择-') + @CHtml::listData(Role::model()->getRoles(), 'id', 'name');

        $setArray = array(
            'adminlist' => $adminlist,
            'pages' => $pager,
            'status' => $status,
            'roles' => $roles
        );
        $this->renderPartial('list', $setArray);
    }

    /**
     * 添加管理员
     */
    public function actionAdd() {
        $user = new User('add');
        if (isset($_POST['User'])) {
            $user->attributes = $_POST['User'];
            if ($user->validate()) {
                $user->salt = $user->generateSalt();//干扰码
                $user->password = $user->hashPassword($user->password_first, $user->salt);
                $user->com_id = Yii::app()->session['user']['com_id'];
                $user->createtime = time();
                if ($user->save()) {
                    Yii::app()->oplog->add(); //添加日志
                }
            }
            $user->hasErrors()?$this->ajaxResponse(-1,$user->errors):$this->ajaxResponse();
        }
        $roles = Role::model()->getRoles();
        $this->renderPartial('add', array('user' => $user, 'roles' => $roles));
    }

    /**
     * 编辑管理员
     */
    public function actionEdit() {
        $uid = $_GET['uid'];
        $user = User::model()->findByPk($uid);
        $user->setScenario('edit');
        if (isset($_POST['User'])) {
            if (isset($_POST['User']['email'])){
                unset($_POST['User']['email']);
            }
            $user->attributes = $_POST['User'];
            if ($user->validate()) {
                if ($_POST['User']['password_first'])
                    $user->password = $user->hashPassword($_POST['User']['password_first'], $user->salt);
                if ($user->save()) {
                    Yii::app()->oplog->add(); //添加日志
                }
            }
            $user->hasErrors()?$this->ajaxResponse(-1,$user->errors):$this->ajaxResponse();
        }
        $roles = Role::model()->getRoles();
        $this->renderPartial('edit', array('user' => $user, 'roles' => $roles));
    }

    /**
     * 删除管理员 
     */
    public function actionDel() {
        $return = array('code' => 1, 'message' => '删除成功');
        if (isset($_POST['uids']) && count($_POST['uids'])) {
            $company = Company::model()->getComById(Yii::app()->session['user']['com_id']);
            $_POST['uids'] = (array) $_POST['uids'];
            if (in_array($company['super_uid'], $_POST['uids'])) {
                $return = array('code' => -1, 'message' => '超级用户不能删除');
            } else {
                $criteria = new CDbCriteria();
                $criteria->addInCondition('uid', $_POST['uids']);
                $criteria->addColumnCondition(array('com_id' => Yii::app()->session['user']['com_id']));
                User::model()->deleteAll($criteria);
                Yii::app()->oplog->add(); //添加日志
            }
        } else {
            $return = array('code' => -1, 'message' => '未选择用户');
        }
        die(json_encode($return));
    }

    /**
     * 禁用、启用账号
     */
    public function actionStatus() {
        $return = array('code' => 1, 'message' => '设置成功');
        if (isset($_POST['uids']) && count($_POST['uids'])) {
            $company = Company::model()->getComById(Yii::app()->session['user']['com_id']);
            $_POST['uids'] = (array) $_POST['uids'];
            if (in_array($company['super_uid'], $_POST['uids'])) {
                $return = array('code' => -1, 'message' => '超级用户不能修改状态');
            } else {
                $status = isset($_POST['status']) && $_POST['status'] == 1 ? 1 : -1;
                $criteria = new CDbCriteria();
                $criteria->addInCondition('uid', $_POST['uids']);
                $criteria->addColumnCondition(array('com_id' => Yii::app()->session['user']['com_id']));
                User::model()->updateAll(array('status' => $status), $criteria);
                Yii::app()->oplog->add(); //添加日志
            }
        } else {
            $return = array('code' => -1, 'message' => '未选择用户');
        }
        die(json_encode($return));
    }

}