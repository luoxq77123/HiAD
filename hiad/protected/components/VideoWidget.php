<?php

class VideoWidget extends CWidget {

    public $route;

    public function init() {
        if ($this->route == null)
            $this->route = "video/ajaxGetList";
    }

    public function run() {
        $selectMode = isset($_GET['selectMode']) ? $_GET['selectMode'] : 'multiple';
        $user = Yii::app()->session['user'];
        $pageSize = (isset($_GET['pagesize']) && $_GET['pagesize']) ? $_GET['pagesize'] : 10;
        $page = (isset($_GET['page']) && $_GET['page']) ? $_GET['page'] : 1;
        $startTime = '2013-01-01 00:00:00';
        $endTime = date('Y-m-d 23:59:59', time());
        if (isset($_GET['startDate'])) {
            $startTime = $_GET['startDate'] . ' 00:00:00';
        }
        if (isset($_GET['endDate'])) {
            $endTime = $_GET['endDate'] . ' 23:59:59';
        }
        Yii::import("application.interface.sobey.vms.*");
        $VMS = new SobeyVMS();
        $vmsAuths = $VMS->getAuths();
        $catalogPath = isset($_GET['catalogName']) ? $vmsAuths['siteNames'] . '@' . urldecode($_GET['catalogName']) : $vmsAuths['siteNames'];
        $config = array(
            'catalogStyle' => 0,
            'catalogPath' => $catalogPath, //.'@默认栏目',
            'getAllData' => 1,
            'pageNum' => $page,
            'pageSize' => $pageSize,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'status' => '1,0',
            'keywords' => isset($_GET['name']) ? $_GET['name'] : '',
            'sortField' => 'publishDate',
            'sort' => 'DESC'
        );
        $data = $VMS->getVedios($config);
        // 分页
        $count = isset($data['total']) ? $data['total'] : 0;
        $pager = new CPagination($count);
        $pager->pageSize = $pageSize;
        $pager->route = $this->route;
        $list = empty($data['video']) ? array() : $data['video'];
        $set = array(
            'selectMode' => $selectMode,
            'list' => $list,
            'pager' => $pager
        );
        $this->render('video', $set);
    }

}
