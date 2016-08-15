<?php

/**
 * 客户端应用控制器
 */
class AppPositionController extends BaseController {

    /**
     * 客户端应用首页
     */
    public function actionIndex() {
        $this->renderPartial('index');
    }

    /**
     * 客户端应用列表
     */
    public function actionList() {
        $this->renderPartial('list');
    }

    /**
     * 添加客户端应用
     */
    public function actionAdd() {
        $position = new Position('add');
        $appPosition = new AppPosition();
        $adShows = AdShow::model()->getListByTypeId(2);
        $apps = App::model()->getAppList();//应用
        $apps = empty($apps)? array(0 => '请选择') : array(0 => '请选择') + $apps;
        if (isset($_POST['AppPosition']) && isset($_POST['Position'])) {
            $return = array('code' => 1, 'message' => '添加成功');
            $position->attributes = $_POST['Position'];
            // 自定义广告大小
            if (in_array($_POST['AppPosition']['app_id'],array_keys($apps['Android应用']))) {
                $position->position_size = $_POST['scale_x'] .':'. $_POST['scale_y'];
            } else if ($_POST['size_defined']) {
                $position->position_size = $_POST['size_x'] . '*' . $_POST['size_y'];
            }

            $position->com_id = Yii::app()->session['user']['com_id'];
            $position->createtime = time();
            $position->ad_type_id = 2;
            //设置客户端应用model场景
            $appPosition->setScenario($adShows[$_POST['Position']['ad_show_id']]['code']);
            $appPosition->attributes = $_POST['AppPosition'];
            if ($position->validate() && $appPosition->validate()) {
                if ($position->save()) {
                    // 保存客户端广告位属性
                    $appPosition->position_id = $position->id;
                    $params = $this->setAppPositionParams();
                    $appPosition->params = serialize($params);
                    if ($appPosition->save()) {
                        Yii::app()->oplog->add(); //添加日志
                    }
                }
            }

            if ($position->hasErrors() || $appPosition->hasErrors()) {
                $return['code'] = -1;
                $return['message'] = '<p style="color:red;">添加失败</p>';
                foreach ($position->errors as $item) {
                    foreach ($item as $one)
                        $return['message'] .= '<p>' . $one . '</p>';
                }
                foreach ($appPosition->errors as $item) {
                    foreach ($item as $one)
                        $return['message'] .= '<p>' . $one . '</p>';
                }
            }

            die(json_encode($return));
        }

        $sizes = PositionSize::model()->getSizes(2);
        $set = array(
            'appPosition' => $appPosition,
            'position' => $position,
            'sizes' => $sizes,
            'apps' => $apps,
            'adShows' => $adShows
        );
        $this->renderPartial('add', $set);
    }

    /**
     * 编辑客户端应用
     */
    public function actionEdit() {
        $id = $_GET['id'];
        $position = Position::model()->findByPk($id);
        $appPosition = AppPosition::model()->find('position_id=:position_id', array(':position_id' => $id));
        $position->setScenario('edit');
        $appPosition->setScenario('edit');
        $adShows = AdShow::model()->getListByTypeId(2);
        $apps = App::model()->getAppList();
        if (isset($_POST['AppPosition']) && isset($_POST['Position'])) {
            $return = array('code' => 1, 'message' => '修改成功');
            $position->attributes = $_POST['Position'];
            // 自定义广告大小
            if ($_POST['size_defined']) {
                $position->position_size = $_POST['size_x'] . '*' . $_POST['size_y'];
            }
            //设置客户端应用model场景
            $appPosition->setScenario($adShows[$_POST['Position']['ad_show_id']]['code']);
            $appPosition->attributes = $_POST['AppPosition'];

            if ($position->validate() && $appPosition->validate()) {
                if ($position->save()) {
                    $params = $this->setAppPositionParams();
                    $appPosition->params = serialize($params);
                    if ($appPosition->save()) {
                        Yii::app()->oplog->add(); //添加日志
                    }
                    // 同步广告中的广告位名称
                    $attribe = array(
                        'position_name' => $_POST['Position']['name']
                    );
                    Ad::model()->updateAll($attribe, 'position_id=:position_id', array(':position_id' => $id));
                }
            }

            if ($position->hasErrors() || $appPosition->hasErrors()) {
                $return['code'] = -1;
                $return['message'] = '<p style="color:red;">修改失败</p>';
                foreach ($user->errors as $item) {
                    foreach ($item as $one)
                        $return['message'] .= '<p>' . $one . '</p>';
                }
                foreach ($user->errors as $item) {
                    foreach ($item as $one)
                        $return['message'] .= '<p>' . $one . '</p>';
                }
            }

            die(json_encode($return));
        }
        $appParams = unserialize($appPosition->params);
        $sizes = PositionSize::model()->getSizes(2);
        $set = array(
            'appPosition' => $appPosition,
            'appParams' => $appParams,
            'position' => $position,
            'sizes' => $sizes,
            'apps' => $apps,
            'adShows' => $adShows
        );
        $this->renderPartial('edit', $set);
    }
    
    public function setAppPositionParams() {
        $params = array();
        $apps = App::model()->getAppList();
        // ios系统
        if (in_array($_POST['AppPosition']['app_id'],array_keys($apps['IOS应用']))) {
            $attr = array();
            if ($_POST['size_defined']) {
                $attr['width'] = $_POST['size_x'];
                $attr['height'] = $_POST['size_y'];
            } else {
                $size = explode("*", $_POST['Position']['position_size']);
                $attr['width'] = $size[0];
                $attr['height'] = $size[1];
            }
            // 插播广告位左上角偏移距离
            if ($_POST['Position']['ad_show_id'] == 5) {
                $attr['left'] = $_POST['left'];
                $attr['top'] = $_POST['top'];
            }
            $params['appType'] = 'ios';
            $params['attr'] = $attr;
        } else if (in_array($_POST['AppPosition']['app_id'],array_keys($apps['Android应用']))) { //android
            $attr = array();
            $attr['scale_xs'] = $_POST['scale_xs'];
            $attr['scale_x'] = $_POST['scale_x'];
            $attr['scale_y'] = $_POST['scale_y'];
            // 插播广告位左上角偏移距离
            if ($_POST['Position']['ad_show_id'] == 5) {
                $attr['offset_left'] = $_POST['offset_left'];
                $attr['offset_top'] = $_POST['offset_top'];
            }
            $params['appType'] = 'android';
            $params['attr'] = $attr;
        }
        return $params;
    }

    /**
     * 删除客户端应用 
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
            Position::model()->updateAll(array('status' => $status), $criteria);
            Yii::app()->oplog->add(); //添加日志
        } else {
            $return = array('code' => -1, 'message' => '未选择用户');
        }
        die(json_encode($return));
    }

    function initialization($type, $appPosition) {
        switch ($type) {
            case 1:
                $appPosition->poptime = 0;
                $appPosition->scroll = 0;
                $appPosition->float_x = 1;
                $appPosition->float_y = 1;
                $appPosition->space_x = 0;
                $appPosition->space_y = 0;
                break;
            case 2:
                $appPosition->idle_take = 0;
                $appPosition->poptime = 0;
                break;
            case 3:
                $appPosition->idle_take = 0;
                $appPosition->scroll = 0;
                break;
        }
        return $appPosition;
    }

    /**
     * 广告位投放视图 
     */
    public function actionTable() {
        $this->renderPartial('table');
    }

}