<?php

/**
 * 角色权限管理
 */
class SetRoleacaController extends CController {

    function beforeAction($action) {
        parent::beforeAction($action);
        $user = Yii::app()->session['user'];

        if (!isset($user['role_id']) || $user['role_id'] != 'super') {
            $message = '当前用户无权限进行此操作。';
            if (Yii::app()->request->isPostRequest && Yii::app()->request->isAjaxRequest) {
                echo json_encode(array('code' => -102, 'message' => $message));
            } else if (Yii::app()->request->isAjaxRequest) {
                echo '<div class="error_message">' . $message . '</div>';
            } else {
                echo '<div class="error_message">' . $message . '</div>';
            }
            exit;
        }
        return true;
    }

    /**
     * 角色权限管理列表
     */
    public function actionList() {
        $criteria = new CDbCriteria();
        $criteria->order = 'id desc';
        $criteria->select = 'id,name';
        $criteria->addColumnCondition(array('com_id' => 0));
        // 附加搜索条件
        /*
          if (isset($_GET['role']) && $_GET['role']) {
          $criteria->addColumnCondition(array('role_id' => $_GET['role']));
          }
          if (isset($_GET['name']) && $_GET['name']) {
          $criteria->addSearchCondition('name', urldecode($_GET['name']));
          }
         */
        // 分页
        $count = Role::model()->count($criteria);
        $pageSize = (isset($_GET['pagesize']) && $_GET['pagesize']) ? $_GET['pagesize'] : 10;
        $pager = new CPagination($count);
        $pager->pageSize = $pageSize;
        $pager->applyLimit($criteria);

        $rolelist = Role::model()->findAll($criteria);

        $setArray = array(
            'rolelist' => $rolelist,
            'pages' => $pager
        );
        $this->renderPartial('list', $setArray);
    }

    function actionEdit() {
        $id = $_GET['id'];
        if (isset($_POST['edit'])) {
            $return = array('code' => 1, 'message' => '修改成功');

            RoleAca::model()->deleteAll('role_id=:role_id', array(':role_id' => $id));
            if (isset($_POST['RoleAca'])) {
                foreach ($_POST['RoleAca'] as $one) {
                    $obj = new RoleAca();
                    $obj->role_id = $id;
                    $obj->aca_id = $one;
                    $obj->save();
                }
                if ($obj->hasErrors()) {
                    $return['code'] = -1;
                    $return['message'] = '<p style="color:red;">修改失败</p>';
                    foreach ($obj->errors as $item) {
                        foreach ($item as $one)
                            $return['message'] .= '<p>' . $one . '</p>';
                    }
                }
            }
            die(json_encode($return));
        }
        $roleaca = RoleAca::model()->getRoleAca($id);
        $parents = Aca::model()->getParents();
        $acas = array();
        foreach ($parents as $key => $one) {
            $acas[$key] = Aca::model()->getAcachild($key);
        }

        $this->renderPartial('edit', array('roleaca' => $roleaca, 'acas' => $acas, 'parents' => $parents));
    }

}