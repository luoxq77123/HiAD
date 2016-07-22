<?php

/**
 * 站点广告位控制器
 */
class ThridPositionController extends BaseController {

    /**
     * 站点广告位首页
     */
    public function actionIndex() {
        $this->renderPartial('index');
    }

    /**
     * 站点广告位列表
     */
    public function actionList() {
        $this->renderPartial('list');
    }

    /**
     * 添加站点广告位
     */
    public function actionAdd() {
        $position = new Position('add');
        $adShows = AdShow::model()->getListByTypeId(4);
        if (isset($_POST['Position'])) {
            $return = array('code' => 1, 'message' => '添加成功');
            $position->attributes = $_POST['Position'];
            $position->com_id = Yii::app()->session['user']['com_id'];
            $position->position_size = $_POST['Position']['position_size'];
            $position->createtime = time();
            $position->ad_type_id = 4;
            //设置站点广告位model场景
            if ($position->validate()) {
                // 自定义广告大小
                if ($_POST['size_defined']) {
                    $position->position_size = $_POST['size_x'] . '*' . $_POST['size_y'];
                }
                if ($position->save()) {
                    if ($position->ad_show_id == 10) {
                        if ($position->position_size!="") {
                            $size = explode("*", $position->position_size);
                            $setting = Setting::model()->getSettings();
                            $return['data'] = "<iframe frameborder='0' height='".$size[1]."' width='".$size[0]."' style='overflow:hidden;border:none;' src='".$setting['INTERFACE_URL']."thridAd?pid=".$position->id."'></iframe>";
                        }
                    }
                    Yii::app()->oplog->add(); //添加日志
                }
            }

            if ($position->hasErrors()) {
                $return['code'] = -1;
                $return['message'] = '<p style="color:red;">添加失败</p>';
                foreach ($position->errors as $item) {
                    foreach ($item as $one)
                        $return['message'] .= '<p>' . $one . '</p>';
                }
            }

            die(json_encode($return));
        }
        $sizes = PositionSize::model()->getSizes(1);
        $set = array(
            'position' => $position,
            'adShows' => $adShows,
            'sizes' => $sizes
        );
        $this->renderPartial('add', $set);
    }

    /**
     * 编辑站点广告位
     */
    public function actionEdit() {
        $id = $_GET['id'];
        $position = Position::model()->findByPk($id);
        $position->setScenario('edit');
        $adShows = AdShow::model()->getListByTypeId(4);
        if (isset($_POST['Position'])) {
            $return = array('code' => 1, 'message' => '修改成功');
            $position->attributes = $_POST['Position'];
            $position->position_size = $_POST['Position']['position_size'];
            if ($position->validate()) {
                // 自定义广告大小
                if ($_POST['size_defined']) {
                    $position->position_size = $_POST['size_x'] . '*' . $_POST['size_y'];
                }
                if ($position->save()) {
                    if ($position->ad_show_id == 10) {
                        if ($position->position_size!="") {
                            $size = explode("*", $position->position_size);
                            $setting = Setting::model()->getSettings();
                            $return['data'] = "<iframe frameborder='0' height='".$size[1]."' width='".$size[0]."' style='overflow:hidden;border:none;' src='".$setting['INTERFACE_URL']."thridAd?pid=".$position->id."'></iframe>";
                        }
                    }
                    Yii::app()->oplog->add(); //添加日志
                }
            }
            
            if ($position->hasErrors()) {
                $return['code'] = -1;
                $return['message'] = '<p style="color:red;">修改失败</p>';
                foreach ($position->errors as $item) {
                    foreach ($item as $one)
                        $return['message'] .= '<p>' . $one . '</p>';
                }
            }

            die(json_encode($return));
        }
        $adCode = "";
        $sizes = PositionSize::model()->getSizes(1);
        if ($position->ad_show_id==10) {
            if ($position->position_size != "") {
                $size = explode("*", $position->position_size);
                $setting = Setting::model()->getSettings();
                $adCode = "<iframe frameborder='0' height='".$size[1]."' width='".$size[0]."' style='overflow:hidden;border:none;' src='".$setting['INTERFACE_URL']."thridAd?pid=".$position->id."'></iframe>";
            }
        }
        $set = array(
            'position' => $position,
            'adShows' => $adShows,
            'adCode' => $adCode,
            'sizes' => $sizes
        );
        $this->renderPartial('edit', $set);
    }

    /**
     * 删除站点广告位 
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

    function initialization($type, $sitePosition) {
        switch ($type) {
            case 1:
                $sitePosition->poptime = 0;
                $sitePosition->scroll = 0;
                $sitePosition->float_x = 1;
                $sitePosition->float_y = 1;
                $sitePosition->space_x = 0;
                $sitePosition->space_y = 0;
                break;
            case 2:
                $sitePosition->idle_take = 0;
                $sitePosition->poptime = 0;
                break;
            case 3:
                $sitePosition->idle_take = 0;
                $sitePosition->scroll = 0;
                break;
        }
        return $sitePosition;
    }

    /**
     * 生成360联盟广告链接
     */
    private function _get360UnionUrl($positonId, $size) {
        $size = explode("*", $size);
        $user = Yii::app()->session['user'];
        $config = Config::model()->getConfigs();
        $arrUrlInfo = parse_url($config['360union_ad_url']);
        parse_str($arrUrlInfo['query']);
        $hiadInfo = array("com_id" => $user['com_id'], "positionId" => $positonId);
        $hiadInfo = 
        
        $return['data'] = "http://".$arrUrlInfo['host'].$arrUrlInfo['path']."?sx=".$size[0]."&sy=".$size[0]."&qihoo_id=".$qihoo_id."&uid=".$uid."&HIAD=";
    }

    /**
     * 获取代码
     */
    public function actionGetCode() {
        $user = Yii::app()->session['user'];
        $positionName = Position::model()->getPosition($user['com_id']);
        $setting = Setting::model()->getSettings();

        $set = array(
            'positionName' => $positionName,
            'interfaceUrl' => $setting['INTERFACE_URL'],
            'postion_method' => 'HIMI_POSITION_INIT',
            'pre_method' => 'HIMI_PERLOAD',
            'player_method' => 'HIMI_PLAYER_POSITION',
            'player_html_method' => 'HIMI_PLAYER_POSITION_HTML'
        );

        $this->renderPartial('getCode', $set);
    }

}