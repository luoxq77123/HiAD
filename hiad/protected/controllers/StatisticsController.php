<?php

/**
 * 统计控制器
 */
class StatisticsController extends BaseController {

    public function actions() {
        return array(
            'site' => 'application.controllers.statistics.SiteAction', //站点广告
            'app' => 'application.controllers.statistics.AppAction', //客户端广告
            'video' => 'application.controllers.statistics.VideoAction', //客户端广告
            'thrid' => 'application.controllers.statistics.ThridAction', //联盟广告
        );
    }
    
    public function actionGetStatList() {
        $this->renderPartial('getStatList');
    }
    
    public function actionGetAppStatList() {
        $this->renderPartial('getAppList');
    }
}