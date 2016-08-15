<?php

/**
 * 站点分组控制器
 */
class AppGroupController extends BaseController {

    /**
     * 站点分组列表
     */
    public function actionList() {
        $user = Yii::app()->session['user'];

        $criteria = new CDbCriteria();
        $criteria->order = 'sort asc,createtime desc';
        $criteria->select = 'id,name,status';
        $criteria->addColumnCondition(array('com_id' => $user['com_id']));

        // 附加搜索条件
        if (isset($_GET['status']) && $_GET['status']) {
            $criteria->addColumnCondition(array('status' => $_GET['status']));
        }
        if (isset($_GET['name']) && $_GET['name']) {
            $criteria->addSearchCondition('name', urldecode($_GET['name']));
        }

        // 分页
        $count = AppGroup::model()->count($criteria);
        $pageSize = (isset($_GET['pagesize']) && $_GET['pagesize']) ? $_GET['pagesize'] : 10;
        $pager = new CPagination($count);
        $pager->pageSize = $pageSize;
        $pager->applyLimit($criteria);

        $appGrouplist = AppGroup::model()->findAll($criteria);
        $status = array(1 => '启用', 0 => '删除', -1 => '禁用');
        $setArray = array(
            'appGrouplist' => $appGrouplist,
            'pages' => $pager,
            'status' => $status
        );
        $this->renderPartial('list', $setArray);
    }

    /**
     * 添加站点分组
     */
    public function actionAdd() {
        $user = Yii::app()->session['user'];
        $appGroup = new AppGroup('add');

        if (isset($_POST['AppGroup'])) {
            $return = array('code' => 1, 'message' => '添加成功');

            $appGroup->attributes = $_POST['AppGroup'];
            if ($appGroup->validate()) {
                $appGroup->com_id = $user['com_id'];
                $appGroup->createtime = time();
                if ($appGroup->save()) {
                    Yii::app()->oplog->add(); //添加日志
                }
            }
            if ($appGroup->hasErrors()) {
                $return['code'] = -1;
                $return['message'] = '<p style="color:red;">添加失败</p>';
                foreach ($appGroup->errors as $item) {
                    foreach ($item as $one)
                        $return['message'] .= '<p>' . $one . '</p>';
                }
            }
            die(json_encode($return));
        }
        $this->renderPartial('add', array('appGroup' => $appGroup));
    }

    /**
     * 编辑站点分组
     */
    public function actionEdit() {
        $user = Yii::app()->session['user'];
        $id = $_GET['id'];
        $appGroup = AppGroup::model()->findByPk($id);
        $appGroup->setScenario('edit');
        if (isset($_POST['AppGroup'])) {
            $return = array('code' => 1, 'message' => '修改成功');

            $appGroup->attributes = $_POST['AppGroup'];
            if ($appGroup->validate()) {
                if ($appGroup->save()) {
                    Yii::app()->oplog->add(); //添加日志
                }
            }
            if ($appGroup->hasErrors()) {
                $return['code'] = -1;
                $return['message'] = '<p style="color:red;">修改失败</p>';
                foreach ($appGroup->errors as $item) {
                    foreach ($item as $one)
                        $return['message'] .= '<p>' . $one . '</p>';
                }
            }
            die(json_encode($return));
        }
        $this->renderPartial('edit', array('appGroup' => $appGroup));
    }

    /**
     * 删除站点分组 
     */
    public function actionDel() {
        
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
            AppGroup::model()->updateAll(array('status' => $status), $criteria);
            Yii::app()->oplog->add(); //添加日志
        } else {
            $return = array('code' => -1, 'message' => '未选择站点');
        }
        die(json_encode($return));
    }

}