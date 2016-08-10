<?php

/**
 * 物料预览
 */
class ClientController extends BaseController {

    /**
     * 预览
     */
    public function actionCbad() {
        $setArray = array(
            'type' => isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : 0,
            'data' => array()
        );
        if (!empty($_GET['val']) && !empty($_GET['type']) && !empty($_GET['ad_type'])) {
            $id = $_GET['val'];
            $type = $_GET['type'];
            $adType = $_GET['ad_type'];
            $data = array();
            switch($type) {
            case 1: // 文字
                if ($adType==1) {
                    $data = MaterialText::model()->find(array(
                        'select' => 'text',
                        'condition' => 'material_id=:id',
                        'params' => array(':id' => $id)
                    ));
                } else if ($adType==2) {
                    $data = MaterialAppText::model()->find(array(
                        'select' => 'text',
                        'condition' => 'material_id=:id',
                        'params' => array(':id' => $id)
                    ));
                } else if ($adType==3) {
                    $data = MaterialVtext::model()->find(array(
                        'select' => 'text',
                        'condition' => 'material_id=:id',
                        'params' => array(':id' => $id)
                    ));
                }
                break;
            case 2: // 图片
                if ($adType==1) {
                    $data = MaterialPic::model()->find(array(
                        'select' => 'url,pic_x,pic_y',
                        'condition' => 'material_id=:id',
                        'params' => array(':id' => $id)
                    ));
                } else if ($adType==2) {
                    $data = MaterialAppPic::model()->find(array(
                        'select' => 'url,pic_x,pic_y',
                        'condition' => 'material_id=:id',
                        'params' => array(':id' => $id)
                    ));
                } else if ($adType==3) {
                    $data = MaterialVpic::model()->find(array(
                        'select' => 'url,pic_x,pic_y',
                        'condition' => 'material_id=:id',
                        'params' => array(':id' => $id)
                    ));
                }
                break;
            case 3: // flash
                if ($adType==1) {
                    $data = MaterialFlash::model()->find(array(
                        'select' => 'url,flash_x,flash_y',
                        'condition' => 'material_id=:id',
                        'params' => array(':id' => $id)
                    ));
                } else if ($adType==3) {
                    $data = MaterialVflash::model()->find(array(
                        'select' => 'url,flash_x,flash_y',
                        'condition' => 'material_id=:id',
                        'params' => array(':id' => $id)
                    ));
                }
                break;
            case 4: // 富媒体
                if (isset($_GET['is_template'])&&$_GET['is_template']) {
                    $data = MaterialTemplate::model()->find(array(
                        'select' => 'html',
                        'condition' => 'id=:id',
                        'params' => array(':id' => $id)
                    ));
                } else {
                    $data = MaterialMedia::model()->find(array(
                        'select' => 'content',
                        'condition' => 'material_id=:id',
                        'params' => array(':id' => $id)
                    ));
                    if ($data) {
                        $data['content'] = stripslashes($data['content']);
                    }
                }
                break;
            case 5: // 视频
                $modleArr = array(
                    1=>'MaterialVideo',//站点
                    2=>'MaterialAppVideo',//客户端
                    3=>'MaterialVvideo'//视频
                );
                $modleName = isset($modleArr[$adType]) ? $modleArr[$adType] : '';
                if ($modleName) {
                    $rdata = $modleName::model()->find(array(
                        'select' => 'url,player_code',
                        'condition' => 'material_id=:id',
                        'params' => array(':id' => $id)
                    ));
                    if ($rdata) {
                        $data['url'] = $rdata->url;
                        $data['pic'] = $rdata->reserve_pic_url;
                        $data['width'] = $rdata->video_x > 0 ? $rdata->video_x : 400;
                        $data['height'] = $rdata->video_y > 0 ? $rdata->video_y : 300;
                        $data['player_code'] = isset($rdata->player_code)?substr($rdata->player_code,9,-3):'';
                    }
                }
                break;
            }
            $setArray['data'] = $data;
        }
        $this->renderPartial('cbad', $setArray);
    }

}