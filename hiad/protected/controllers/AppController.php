<?php

/**
 * 应用控制器
 */
class AppController extends BaseController {

    /**
     * 应用列表
     */
    public function actionList() {
        $user = Yii::app()->session['user'];

        $criteria = new CDbCriteria();
        $criteria->order = 'sort asc,createtime desc';
        $criteria->select = 'id,name,status,app_group_id,app_type_id,app_key';
        $criteria->addColumnCondition(array('com_id' => $user['com_id']));

        // 附加搜索条件
        if (isset($_GET['status']) && $_GET['status']) {
            $criteria->addColumnCondition(array('status' => $_GET['status']));
        }
        if (isset($_GET['name']) && $_GET['name']) {
            $criteria->addSearchCondition('name', urldecode($_GET['name']));
        }

        $count = App::model()->count($criteria);
        $pageSize = (isset($_GET['pagesize']) && $_GET['pagesize']) ? $_GET['pagesize'] : 10;
        $pager = new CPagination($count);
        $pager->pageSize = $pageSize;
        $pager->applyLimit($criteria);

        $applist = App::model()->findAll($criteria);
        $appgroup = AppGroup::model()->getAppgroup($user['com_id']);
        $appType = AppType::model()->getAppTypename();
        $status = array(1 => '启用', 0 => '删除', -1 => '禁用');
        $setArray = array(
            'applist' => $applist,
            'pages' => $pager,
            'appgroup' => $appgroup,
            'appType' => $appType,
            'status' => $status
        );

        $this->renderPartial('list', $setArray);
    }

    /**
     * 添加应用
     */
    public function actionAdd() {
        $user = Yii::app()->session['user'];
        $app = new App('add');

        if (isset($_POST['App'])) {
            $return = array('code' => 1, 'message' => '添加成功');

            $app->attributes = $_POST['App'];
            if ($app->validate()) {
                $key = 'ihimi-' . $user['uid'] . '-appkey-' . time();
                $app->app_key = md5($key);
                $app->com_id = $user['com_id'];
                $app->createtime = time();
                if ($app->save()) {
                    Yii::app()->oplog->add(); //添加日志
                }
            }
            if ($app->hasErrors()) {
                $return['code'] = -1;
                $return['message'] = '<p style="color:red;">添加失败</p>';
                foreach ($app->errors as $item) {
                    foreach ($item as $one)
                        $return['message'] .= '<p>' . $one . '</p>';
                }
            }
            die(json_encode($return));
        }

        $appgroup = array('0' => '请选择') + @AppGroup::model()->getAppgroup($user['com_id']);
        $appType = array('' => '-请选择-') + @AppType::model()->getAppTypename();
        $this->renderPartial('add', array('app' => $app, 'appgroup' => $appgroup, 'appType' => $appType));
    }

    /**
     * 编辑应用
     */
    public function actionEdit() {
        $user = Yii::app()->session['user'];
        $id = $_GET['id'];
        $app = App::model()->findByPk($id);
        $app->setScenario('edit');
        if (isset($_POST['App'])) {
            $return = array('code' => 1, 'message' => '修改成功');

            $app->attributes = $_POST['App'];
            if ($app->validate()) {
                if ($app->save()) {
                    Yii::app()->oplog->add(); //添加日志
                }
            }
            if ($app->hasErrors()) {
                $return['code'] = -1;
                $return['message'] = '<p style="color:red;">修改失败</p>';
                foreach ($app->errors as $item) {
                    foreach ($item as $one)
                        $return['message'] .= '<p>' . $one . '</p>';
                }
            }
            die(json_encode($return));
        }
        $appgroup = array('0' => '请选择') + AppGroup::model()->getAppgroup($user['com_id']);

        $appType = array('' => '-请选择-') + @AppType::model()->getAppTypename();
        $this->renderPartial('edit', array('app' => $app, 'appgroup' => $appgroup, 'appType' => $appType));
    }

    /**
     * 删除应用 
     */
    public function actionDel() {
        $return = array('code' => 1, 'message' => '删除成功');
        if (isset($_POST['app']) && count($_POST['app'])) {
            $_POST['app'] = (array) $_POST['app'];
            $criteria = new CDbCriteria();
            $criteria->addInCondition('id', $_POST['app']);
            $criteria->addColumnCondition(array('com_id' => Yii::app()->session['user']['com_id']));
            // App::model()->deleteAll($criteria);
            App::model()->updateAll(array('status' => -1), $criteria);
            Yii::app()->oplog->add(); //添加日志
        } else {
            $return = array('code' => -1, 'message' => '未选择用户');
        }
        die(json_encode($return));
    }

    /**
     * 修改状态
     */
    public function actionStatus() {
        $user = Yii::app()->session['user'];
        $return = array('code' => 1, 'message' => '设置成功');
        if (isset($_POST['ids']) && count($_POST['ids'])) {
            $_POST['ids'] = (array) $_POST['ids'];
            $status = isset($_POST['status']) && $_POST['status'] == 1 ? 1 : -1;
            $criteria = new CDbCriteria();
            $criteria->addInCondition('id', $_POST['ids']);
            $criteria->addColumnCondition(array('com_id' => $user['com_id']));
            App::model()->updateAll(array('status' => $status), $criteria);
            Yii::app()->oplog->add(); //添加日志
        } else {
            $return = array('code' => -1, 'message' => '未选择应用');
        }
        die(json_encode($return));
    }

}