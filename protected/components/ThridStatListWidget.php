<?php

class ThridStatListWidget extends CWidget {
    // 获取内容地址
    public $route;
    // 设置每页条数限制组
    public $arrPageSize;

    public function init() {
        if ($this->route === null)
            $this->route = 'statistics/getStatList';
        if ($this->arrPageSize === null)
            $this->arrPageSize = array(10 => 10, 20 => 20, 50 => 50);
    }

    public function run() {
        // 统计
        $set = $this->getStatisticsInfo();
        $type = isset($_GET['type'])&&$_GET['type']!=""? $_GET['type'] : "ad";
        $arrType = ThridStatistics::model()->getStatTypeName();
        $typeName = $arrType[$type];
        $set['type'] = $type;
        $set['typeName'] = $typeName;
        $this->render('thridStatList', $set);
    }
    
    // 组合广告统计数据
    public function getStatisticsInfo() {
        $params = ThridStatistics::model()->parseParams();
        $return = array();
        if (empty($params['typeid']) && !empty($_GET['ad_name'])) {
            $return = ThridStatistics::model()->combineData(array(), $params['type']);
            return  $return;
        }
        // 根据条件获取统计数据
        $statisticsData = ThridStatistics::model()->getPageListByType($params['type'], $params['typeid'], $params['startDate'], $params['endDate'], $this->route);
        // 组合数据
        $return = ThridStatistics::model()->combineData($statisticsData, $params['type']);
        return  $return;
    }
}