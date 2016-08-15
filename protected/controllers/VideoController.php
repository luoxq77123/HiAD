<?php
/**
 * Vms视频集
 *
 */
class VideoController extends BaseController {

    //视频
    public function actionPopupList()
    {
        Yii::import("application.interface.sobey.vms.*");

        $VMS = new SobeyVMS();

        $vmsAuths = $VMS->getAuths(); //获取认证
        $config = array(
            'catalogType' => 5,
            'catalogStyle' => 0,
            'catalogPath' => $vmsAuths['siteNames'],
            'getAllData' => 1
        );
        $catalogs = json_encode($VMS->getCatalogs($config)); //获取vms栏目 返回数据对象
        $set = array(
            'catalogs' => $catalogs
        );
        $this->render('popupList', $set);
    }

    public function actionAjaxGetList() {
        	header("Content-type:text/html;charset=utf-8");
            $this->render('ajaxGetList');
    }

    public function actionGetRealUrl() {
    		set_time_limit(30);
            Yii::import("application.interface.sobey.vms.*");
            $VMS = new SobeyVMS();
            echo $VMS->getRealUrl($_POST['host'], $_POST['url']);
            exit;
        }
    //获取电视剧栏目
        public function actionSerieCategoryList(){
        	Yii::import("application.interface.sobey.vms.*");
        	$VMS = new SobeyVMS();
        	$vmsAuths = $VMS->getAuths();
        	$config = array(
        			'catalogType' => 7,
        			'catalogStyle' => 0,
        			'catalogPath' => $vmsAuths['siteNames'],
        			'getAllData' => 1
        	);
        	$catalogs = json_encode($VMS->getCatalogs($config));
        	$set = array(
        			'catalogs' => $catalogs
        	);
        	$this->render('seriecategorylist', $set);
        }
}