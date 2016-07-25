<?php

/**
 * 站点广告位控制器
 */
class VideoPositionController extends BaseController {

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
        $sitePosition = new SitePosition();
        $adShows = AdShow::model()->getListByTypeId(3);
        if (isset($_POST['Position'])) {
            $return = array('code' => 1, 'message' => '添加成功');
            $position->attributes = $_POST['Position'];
            $position->com_id = Yii::app()->session['user']['com_id'];
            $position->createtime = time();
            $position->ad_type_id = 3;
            //设置站点广告位model场景
            if ($position->validate()) {
                if ($position->save()) {
                    if ($position->ad_show_id == 8) { // 播放器
                        // 删除之前绑定的栏目广告位
                        $criteria = new CDbCriteria;
                        $criteria->addInCondition('player_id', $_POST['player_id']);
                        VpPlayer::model()->deleteAll($criteria);
                        foreach($_POST['player_id'] as $k => $v) {
                            $player = new VpPlayer();
                            $player->position_id = $position->id;
                            $player->player_id = $v;
                            $player->player_name = $_POST['player_name'][$k];
                            $player->save();
                        }
                    } else if ($position->ad_show_id == 9) { // 栏目
                        // 删除之前绑定的栏目广告位
                        $criteria = new CDbCriteria;
                        $criteria->addInCondition('catalog_id', $_POST['catalog_id']);
                        VpCatalog::model()->deleteAll($criteria);
                        foreach($_POST['catalog_id'] as $k => $v) {
                            $catalog = new VpCatalog();
                            $catalog->position_id = $position->id;
                            $catalog->catalog_id = $v;
                            $catalog->catalog_name = $_POST['catalog_name'][$k];
                            $catalog->save();
                        }
                    } else if ($position->ad_show_id == 11) { // 频道
                        // 删除之前绑定的栏目广告位
                        $criteria = new CDbCriteria;
                        $criteria->addInCondition('channel_id', $_POST['channel_id']);
                        VpChannel::model()->deleteAll($criteria);
                        foreach($_POST['channel_id'] as $k => $v) {
                            $catalog = new VpChannel();
                            $catalog->position_id = $position->id;
                            $catalog->channel_id = $v;
                            $catalog->channel_name = $_POST['channel_name'][$k];
                            $catalog->save();
                        }
                    }
                    Yii::app()->oplog->add(); //添加日志
                }
            }

            if ($position->hasErrors() || $sitePosition->hasErrors()) {
                $return['code'] = -1;
                $return['message'] = '<p style="color:red;">添加失败</p>';
                foreach ($position->errors as $item) {
                    foreach ($item as $one)
                        $return['message'] .= '<p>' . $one . '</p>';
                }
                foreach ($sitePosition->errors as $item) {
                    foreach ($item as $one)
                        $return['message'] .= '<p>' . $one . '</p>';
                }
            }

            die(json_encode($return));
        }

        $set = array(
            'sitePosition' => $sitePosition,
            'position' => $position,
            'adShows' => $adShows
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
        $sitePosition = new SitePosition();
        $adShows = AdShow::model()->getListByTypeId(3);
        if (isset($_POST['Position'])) {
            $return = array('code' => 1, 'message' => '修改成功');
            $position->attributes = $_POST['Position'];
            if ($position->validate()) {
                if ($position->save()) {
                    // 同步广告中的广告位名称
                    $attribe = array(
                        'position_name' => $_POST['Position']['name']
                    );
                    Ad::model()->updateAll($attribe, 'position_id=:position_id', array(':position_id' => $id));
                    if ($position->ad_show_id == 8) { // 播放器
                        // 删除之前绑定的栏目广告位
                        $criteria = new CDbCriteria;
                        $criteria->addInCondition('player_id', $_POST['player_id']);
                        $criteria->addCondition('position_id='.$position->id,'OR');
                        VpPlayer::model()->deleteAll($criteria);
                        foreach($_POST['player_id'] as $k => $v) {
                            $player = new VpPlayer();
                            $player->position_id = $position->id;
                            $player->player_id = $v;
                            $player->player_name = $_POST['player_name'][$k];
                            $player->save();
                        }
                    } else if ($position->ad_show_id == 9) { // 栏目
                        // 删除之前绑定的栏目广告位
                        $criteria = new CDbCriteria;
                        $criteria->addInCondition('catalog_id', $_POST['catalog_id']);
                        $criteria->addCondition('position_id='.$position->id,'OR');
                        VpCatalog::model()->deleteAll($criteria);
                        foreach($_POST['catalog_id'] as $k => $v) {
                            $catalog = new VpCatalog();
                            $catalog->position_id = $position->id;
                            $catalog->catalog_id = $v;
                            $catalog->catalog_name = $_POST['catalog_name'][$k];
                            $catalog->save();
                        }
                    } else if ($position->ad_show_id == 11) { // 频道
                        // 删除之前绑定的栏目广告位
                        $criteria = new CDbCriteria;
                        $criteria->addInCondition('channel_id', $_POST['channel_id']);
                        $criteria->addCondition('position_id='.$position->id,'OR');
                        VpChannel::model()->deleteAll($criteria);
                        foreach($_POST['channel_id'] as $k => $v) {
                            $catalog = new VpChannel();
                            $catalog->position_id = $position->id;
                            $catalog->channel_id = $v;
                            $catalog->channel_name = $_POST['channel_name'][$k];
                            $catalog->save();
                        }
                    }
                    Yii::app()->oplog->add(); //添加日志
                }
            }
            
            if ($position->hasErrors() || $sitePosition->hasErrors()) {
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
        $bindData = array();
        if ($position->ad_show_id==8) {
            $criteria = new CDbCriteria;
            $criteria->addColumnCondition(array('position_id' => $position->id));
            $bindData = VpPlayer::model()->findAll($criteria);
        } else  if ($position->ad_show_id==9) {
            $criteria = new CDbCriteria;
            $criteria->addColumnCondition(array('position_id' => $position->id));
            $bindData = VpCatalog::model()->findAll($criteria);
        } else if ($position->ad_show_id == 11) { // 频道
            $criteria = new CDbCriteria;
            $criteria->addColumnCondition(array('position_id' => $position->id));
            $bindData = VpChannel::model()->findAll($criteria);
        }
        //$sitePosition = $this->initialization($position->ad_show_id, $sitePosition);
        $set = array(
            'sitePosition' => $sitePosition,
            'position' => $position,
            'adShows' => $adShows,
            'bindData' => $bindData
        );
        $this->renderPartial('edit', $set);
    }

    public function actionGetVmsCatalog() {
        $cache_name = md5('controller_VideoPosition_getVmsCatalog');
        $html = Yii::app()->memcache->get($cache_name);
        if (!$html) {
            $user = Yii::app()->session['user'];
            
            Yii::import("application.interface.sobey.vms.*");
            $VMS = new SobeyVMS();
            $vmsAuths = $VMS->getAuths();
            $config = array(
                'catalogType' => 5,
                'catalogStyle' => 0,
                'catalogPath' => $vmsAuths['siteNames'],//.'@默认栏目',
                'getAllData' => 1
            );
            $data = $VMS->getCatalogs($config);
            $html = '
                <tr>
                    <th class="c1">序号</th>
                    <th class="c2">栏目名称</th>
                    <th class="c3">选择绑定</th>
                </tr>';
            $html .= $this->_combineCatalog($data);
            Yii::app()->memcache->set($cache_name, $html, 300);
        }
        echo $html;
        exit;
    }
    
    private function _combineCatalog($data, $pid=0) {
        static $index=1;
        $return = '';
        if (!empty($data)) {
            foreach($data as $one) {
                if ($one['parentId'] == $pid) {
                    $level = $one['treeLevel']>1? ' class="tr-level-'.$one['treeLevel'].'"' : '';
                    $return .= '
            <tr'.$level.'>
                <td class="c1">'.$index.'</td>
                <td class="c2">'.$one['name'].'</td>
                <td class="c3"><input type="checkbox" value="'.$one['catalogId'].'" name="catalog[]" /></td>
            </tr>';
                    $index ++;
                    $return .= $this->_combineCatalog($data,$one['catalogId']);
                }
            }
        }
        return $return;
    }
    
    public function actionGetChannelList() {
        Yii::import("application.interface.sobey.vms.*");
        $VMS = new SobeyVMS();
        $channelList = $VMS->getChannelList();
        //print_r($channelList);
        $html = '
            <tr>
                <th class="c1">序号</th>
                <th class="c2">栏目名称</th>
                <th class="c3">选择绑定</th>
            </tr>';
        if (!empty($channelList)) {
            foreach($channelList as $key => $one) {
                $html .= '
                <tr>
                    <td class="c1">'.($key+1).'</td>
                    <td class="c2">'.$one['name'].'</td>
                    <td class="c3"><input type="checkbox" value="'.$one['id'].'" name="channel[]" /></td>
                </tr>';
            }
        }
        echo $html;
        exit;
    }

    public function actionGetVmsPlayer() {
        $cache_name = md5('controller_VideoPosition_getVmsPlayer');
        $html = false;//Yii::app()->memcache->get($cache_name);
        if (!$html) {
            Yii::import("application.interface.sobey.vms.*");
            $VMS = new SobeyVMS();
            $vmsAuths = $VMS->getAuths();
            $config = array(
                'defaultFlag' => 0,
                'type' => 5
            );
            $videoPlayer = $VMS->getPlayerList($config);
            $config['type'] = 8;
            $livePlayer = $VMS->getPlayerList($config);
            
            $html = '
                <ul style="margin-right:0px;">';
            if (!empty($videoPlayer['player'])) {
                foreach($videoPlayer['player'] as $one) {
                    $html .= '
                    <li>
                        <img src="'.Yii::app()->request->baseUrl.'/images/tu2.jpg" width="125" height="105" alt="" />
                        <p><input type="hidden" name="player_name[]" value="'.$one['name'].'"/><label><input type="checkbox" name="player_id[]" value="'.$one['guid'].'"/>'.$one['name'].'</label></p>
                    </li>';
                }
            }
            $html .= '
                </ul>';
            $html .= '
                <ul style="margin-right:0px;display:none">';
            if (!empty($livePlayer['player'])) {
                foreach($livePlayer['player'] as $one) {
                    $html .= '
                    <li>
                        <img src="'.Yii::app()->request->baseUrl.'/images/tu2.jpg" width="125" height="105" alt="" />
                        <p><label><input type="hidden" name="player_name[]" value="'.$one['name'].'"/><label><input type="checkbox" name="player_id[]" value="'.$one['guid'].'"/>'.$one['name'].'</label></p>
                    </li>';
                }
            }
            $html .= '
                </ul>';
            Yii::app()->memcache->set($cache_name, $html, 300);
        }
        echo $html;
        exit;
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
     * 广告位投放视图 
     */
    public function actionTable() {
        $this->renderPartial('table');
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
            'js' => $setting['AD_MAIN_JS_URL'],
            'postion_method' => 'HIMI_POSITION_INIT',
            'pre_method' => 'HIMI_PERLOAD',
            'player_method' => 'HIMI_PLAYER_POSITION',
            'player_html_method' => 'HIMI_PLAYER_POSITION_HTML'
        );

        $this->renderPartial('getCode', $set);
    }

}