<?php

/**
 * 客户端物料控制器
 */
class MaterialAppController extends BaseController {

    /**
     * 客户端物料列表
     */
    public function actionList() {
        $user = Yii::app()->session['user'];

        $criteria = new CDbCriteria();
        $criteria->order = 'createtime desc';
        $criteria->select = 'id,name,material_type_id,status,material_size';
        $criteria->addColumnCondition(array('com_id' => $user['com_id']));
        $criteria->addColumnCondition(array('ad_type_id' => 2));

        // 附加搜索条件
        if (isset($_GET['status']) && $_GET['status']) {
            $criteria->addColumnCondition(array('status' => $_GET['status']));
        }

        if (isset($_GET['type']) && $_GET['type']) {
            $criteria->addColumnCondition(array('material_type_id' => $_GET['type']));
        }

        if (isset($_GET['size']) && $_GET['size']) {
            $criteria->addColumnCondition(array('material_size' => $_GET['size']));
        }

        if (isset($_GET['name']) && $_GET['name']) {
            $criteria->addSearchCondition('name', urldecode($_GET['name']));
        }

        if (isset($_GET['aid']) && $_GET['aid']) {
            $materialid = AppAd::model()->find('ad_id=:ad_id', array(':ad_id' => $_GET['aid']));
            $materialids = array();
            if ($materialid) {
                $materialids = unserialize($materialid->material);
                $id = array();
                if (!empty($materialids)) {
                    foreach ($materialids as $one)
                        $id[] = $one['id'];
                    $criteria->addInCondition('id', $id);
                } else {
                    $criteria->addInCondition('id', array('0' => 0));
                }
            } else {
                $criteria->addInCondition('id', array('0' => 0));
            }
        }

        $count = Material::model()->count($criteria);
        $pageSize = (isset($_GET['pagesize']) && $_GET['pagesize']) ? $_GET['pagesize'] : 10;
        $pager = new CPagination($count);
        $pager->pageSize = $pageSize;
        $pager->applyLimit($criteria);
        $materiallist = Material::model()->findAll($criteria);

        $materialaType = array(0 => '-请选择-') + @CHtml::listData(MaterialAppType::model()->getMaterialaTypes(), 'id', 'name');
        $usedSize = Material::model()->getUsedSize($user['com_id']);
        $usedSize = array('' => '-请选择-') + $usedSize;
        $status = array(1 => '启用', -1 => '禁用');

        $setArray = array(
            'materiallist' => $materiallist,
            'pages' => $pager,
            'materialType' => $materialaType,
            'usedSize' => $usedSize,
            'status' => $status
        );

        $this->renderPartial('list', $setArray);
    }

    /**
     * 添加客户端物料
     */
    public function actionAdd() {

        $material = new Material('add');
        $materialText = new MaterialAppText('add');
        $materialPic = new MaterialAppPic('add');
        //$materialFlash = new MaterialFlash('add');
        $materialVideo = new MaterialAppVideo('add');
        if (isset($_POST['Material'])) {
            $return = array('code' => 1, 'message' => '添加成功');
            $material->attributes = $_POST['Material'];
            $flag = array();
            //echo '<pre>';print_r($_POST);exit;
            if ($material->validate()) {
                $material->com_id = Yii::app()->session['user']['com_id'];
                $material->ad_type_id = 2;
                $material->createtime = time();
                if ($_POST['Material']['material_type_id'] == 1) {//文字
                    $materialText->attributes = $_POST['MaterialAppText'];
                    if ($materialText->validate()) {
                        if ($material->save()) {
                            Yii::app()->oplog->add(); //添加日志

                            $materialText->material_id = $material->attributes['id'];

                            $materialText->save();
                        }
                    }
                    if ($materialText->hasErrors()) {
                        $flag = $materialText->errors;
                    }
                } else if ($_POST['Material']['material_type_id'] == 2) {//图片
                    $materialPic->attributes = $_POST['MaterialAppPic'];
                    if ($materialPic->validate()) {
                        if ($_POST['MaterialAppPic']['pic_x'] && $_POST['MaterialAppPic']['pic_y']){
                            $material->material_size = $_POST['MaterialAppPic']['pic_x'] . '*' . $_POST['MaterialAppPic']['pic_y'];
                            $materialPic->pic_x=$_POST['MaterialAppPic']['pic_x'];
                            $materialPic->pic_y=$_POST['MaterialAppPic']['pic_y'];                        
                        }
                        if ($material->save()) {
                            Yii::app()->oplog->add(); //添加日志

                            $materialPic->material_id = $material->attributes['id'];

                            $materialPic->save();
                        }
                    }
                    if ($materialPic->hasErrors()) {
                        $flag = $materialPic->errors;
                    }
                } else if ($_POST['Material']['material_type_id'] == 3) {//flash
                } else if ($_POST['Material']['material_type_id'] == 4) {//富媒体
                } else if ($_POST['Material']['material_type_id'] == 5) {//video
                    $materialVideo->attributes = $_POST['MaterialAppVideo'];
                    if ($materialVideo->validate()) {
                        if ($_POST['MaterialAppVideo']['video_x'] && $_POST['MaterialAppVideo']['video_y'])
                            $material->material_size = $_POST['MaterialAppVideo']['video_x'] . '*' . $_POST['MaterialAppVideo']['video_y'];
                        else if ($_POST['MaterialAppVideo']['videopic_x'] && $_POST['MaterialAppVideo']['videopic_y'])
                            $material->material_size = $_POST['MaterialAppVideo']['videopic_x'] . '*' . $_POST['MaterialAppVideo']['videopic_y'];

                        if ($material->save()) {
                            Yii::app()->oplog->add(); //添加日志
                            $materialVideo->material_id = $material->attributes['id'];
                            if (!isset($_POST['MaterialAppVideo']['monitor_video']))
                                $materialVideo->click_link = 'http://';
                            if (!isset($_POST['MaterialAppVideo']['reserve'])) {
                                $materialVideo->reserve_pic_url = NULL;
                                $materialVideo->reserve_pic_link = 'http://';
                            }
                            if (!isset($_POST['MaterialAppVideo']['monitor']))
                                $materialVideo->monitor_link = 'http://';
                            $materialVideo->save();
                        }
                    }
                    if ($materialVideo->hasErrors()) {
                        $flag = $materialVideo->errors;
                    }
                }
            }

            if ($material->hasErrors()) {
                $return['code'] = -1;
                $return['message'] = '<p style="color:red;">添加失败</p>';
                foreach ($material->errors as $item) {
                    foreach ($item as $one)
                        $return['message'] .= '<p>' . $one . '</p>';
                }
            } else if ($flag) {
                $return['code'] = -1;
                $return['message'] = '<p style="color:red;">添加失败</p>';
                foreach ($flag->errors as $item) {
                    foreach ($item as $one)
                        $return['message'] .= '<p>' . $one . '</p>';
                }
            }
            die(json_encode($return));
        }


        $set = array(
            'material' => $material,
            'materialText' => $materialText,
            'materialPic' => $materialPic,
            // 'materialFlash' => $materialFlash
            'materialVideo' => $materialVideo
        );
        $this->renderPartial('add', $set);
    }

    /**
     * 编辑客户端物料
     */
    public function actionEdit() {
        $user = Yii::app()->session['user'];
        $id = $_GET['id'];
        $material = Material::model()->findByPk($id);
        $material->setScenario('edit');

        if ($material->material_type_id == 1) {
            $materialText = MaterialAppText::model()->find('material_id=:material_id', array(':material_id' => $id));
            $materialText->setScenario('edit');
            $old_type = $materialText;
            $materialPic = new MaterialAppPic('add');
            $materialVideo = new MaterialAppVideo('add');
        } else if ($material->material_type_id == 2) {
            $materialPic = MaterialAppPic::model()->find('material_id=:material_id', array(':material_id' => $id));
            $materialPic->setScenario('edit');
            $old_type = $materialPic;
            $materialText = new MaterialAppText('add');
            $materialVideo = new MaterialAppVideo('add');
        } else if ($material->material_type_id == 5) {
            $materialVideo = MaterialAppVideo::model()->find('material_id=:material_id', array(':material_id' => $id));
            $materialVideo->setScenario('edit');
            $old_type = $materialVideo;
            $materialText = new MaterialAppText('add');
            $materialPic = new MaterialAppPic('add');
        }
        if (isset($_POST['Material'])) {
            $return = array('code' => 1, 'message' => '编辑成功');
            $material->attributes = $_POST['Material'];
            $flag = array();
            if ($material->validate()) {
                if ($_POST['Material']['material_type_id'] == 1) {//文字
                    $materialText->attributes = $_POST['MaterialAppText'];
                    if ($materialText->validate()) {
                        $material->material_size = NULL;
                        if ($material->save()) {
                            Yii::app()->oplog->add(); //添加日志

                            $materialText->material_id = $material->id;

                            $materialText->save();
                        }
                    }
                    if ($materialText->hasErrors()) {
                        $flag = $materialText->errors;
                    }
                } else if ($_POST['Material']['material_type_id'] == 2) {//图片
                    $materialPic->attributes = $_POST['MaterialAppPic'];
                    if ($materialPic->validate()) {
                        if ($_POST['MaterialAppPic']['pic_x'] && $_POST['MaterialAppPic']['pic_y']){
                            $material->material_size = $_POST['MaterialAppPic']['pic_x'] . '*' . $_POST['MaterialAppPic']['pic_y'];
                            $materialPic->pic_x=$_POST['MaterialAppPic']['pic_x'];
                            $materialPic->pic_y=$_POST['MaterialAppPic']['pic_y'];                                                
                        }
                        if ($material->save()) {
                            Yii::app()->oplog->add(); //添加日志

                            $materialPic->material_id = $material->id;
                            $materialPic->save();
                        }
                    }
                    if ($materialPic->hasErrors()) {
                        $flag = $materialPic->errors;
                    }
                } else if ($_POST['Material']['material_type_id'] == 5) {//视频
                    $materialVideo->attributes = $_POST['MaterialAppVideo'];
                    if ($materialVideo->validate()) {
                        if ($_POST['MaterialAppVideo']['video_x'] && $_POST['MaterialAppVideo']['video_y']) {
                            $material->material_size = $_POST['MaterialAppVideo']['video_x'] . '*' . $_POST['MaterialAppVideo']['video_y'];
                        }
                        if ($_POST['MaterialAppVideo']['videopic_x'] && $_POST['MaterialAppVideo']['videopic_y']) {
                            $material->material_size = $_POST['MaterialAppVideo']['videopic_x'] . '*' . $_POST['MaterialAppVideo']['videopic_y'];
                        }

                        if ($material->save()) {
                            Yii::app()->oplog->add(); //添加日志

                            $materialVideo->material_id = $material->id;
                            if (!isset($_POST['MaterialAppVideo']['monitor_video'])) {
                                $materialVideo->monitor_video = 0;
                                $materialVideo->click_link = 'http://';
                            }
                            if (!isset($_POST['MaterialAppVideo']['reserve'])) {
                                $materialVideo->reserve = 0;
                                $materialVideo->reserve_pic_url = NULL;
                                $materialVideo->reserve_pic_link = 'http://';
                            }
                            if (!isset($_POST['MaterialAppVideo']['monitor']))
                                $materialVideo->monitor_link = 'http://';
                            $materialVideo->save();
                        }
                    }
                    if ($materialVideo->hasErrors()) {
                        $flag = $materialVideo->errors;
                    }
                }
            }

            if ($material->hasErrors()) {
                $return['code'] = -1;
                $return['message'] = '<p style="color:red;">编辑失败</p>';
                foreach ($material->errors as $item) {
                    foreach ($item as $one)
                        $return['message'] .= '<p>' . $one . '</p>';
                }
            } else if ($flag) {
                $return['code'] = -1;
                $return['message'] = '<p style="color:red;">添加失败</p>';
                foreach ($flag->errors as $item) {
                    foreach ($item as $one)
                        $return['message'] .= '<p>' . $one . '</p>';
                }
            } else {
                if ($_POST['Material']['material_type_id'] != $_POST['Material']['old_type']) {
                    if ($_POST['Material']['old_type'] == 1)
                        $materialText->delete();
                    else if ($_POST['Material']['old_type'] == 2)
                        $materialPic->delete();
                    else if ($_POST['Material']['old_type'] == 5)
                        $materialVideo->delete();
                }
            }
            die(json_encode($return));
        }
        $set = array(
            'material' => $material,
            'materialText' => $materialText,
            'materialPic' => $materialPic,
            'materialVideo' => $materialVideo
        );
        $this->renderPartial('edit', $set);
    }

    /**
     * 删除客户端物料
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
            Material::model()->updateAll(array('status' => $status), $criteria);
            Yii::app()->oplog->add(); //添加日志
        } else {
            $return = array('code' => -1, 'message' => '未选择站点');
        }
        die(json_encode($return));
    }

}