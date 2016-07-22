<?php

class AppStatistics extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @return CActiveRecord the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{app_statistics}}';
    }

    public function relations() {
        return array(
            'ad' => array(self::HAS_ONE, 'Ad', 'id')
        );
    }
    
    /**
     * 获取站点广告统计列表信息
     * @$typeField 	统计类型字段，如广告统计 广告位统计
     * @$typeid 统计方式条件id，如广告id 广告位id
     * @$startDate 开始日期
     * @$endDate 结束日期
     */
    public function getAllList($type = 'ad', $typeid = array(), $startDate = "", $endDate = "") {
        // 查询一天数据时 显示其24小时详细信息 
        if ($startDate != "" && $startDate == $endDate) {
            return $this->getOneDayAllList($type, $typeid, $startDate);
        }
        // 根据条件查询多天的统计信息
        $user = Yii::app()->session['user'];
        $typeField = $this->getStatType($type);
        $criteria = new CDbCriteria();
        $criteria->select = $typeField.' as id,'.$typeField.',sum(show_num) as show_num,sum(click_num) as click_num,sum(unique_users) as unique_users,sum(dedicgotd_ip) as dedicgotd_ip,sum(cpd_cost) as cpd_cost,sum(cpm_cost) as cpm_cost,sum(cpc_cost) as cpc_cost,sum(cost) as cost,dates';
        $criteria->group = $typeField;
        $criteria->addColumnCondition(array('com_id' => $user['com_id']));
        $criteria->addColumnCondition(array('ad_type' => 2));
        // append condition of search
        if (!empty($typeid)) {
            $criteria->addInCondition($typeField, $typeid);
        }
        if ($startDate != "" && $endDate != "") {
            $criteria->addBetweenCondition('dates', $startDate, $endDate);
        } else if ($startDate != "" && $endDate = "") {
            $criteria->addBetweenCondition('dates', $startDate, date("Y-m-d", time()));
        }
        $list = array();
        switch($type) {
        case 'ad': // 广告
            $list = StatisticsAd::model()->findAll($criteria);
            break;
        case 'position': // 广告位
            $list = StatisticsPosition::model()->findAll($criteria);
            break;
        case 'order': // 订单
            $list = StatisticsOrder::model()->findAll($criteria);
            break;
        case 'client': //客户
            $list = StatisticsClient::model()->findAll($criteria);
            break;
        case 'seller': // 销售
            $list = StatisticsSeller::model()->findAll($criteria);
            break;
        case 'material': // 物料
            $list = StatisticsMaterial::model()->findAll($criteria);
            break;
        }
        $data = array();
        $data['list'] = $list;
        return $data;
    }
    
    public function getOneDayAllList($type, $typeid, $date) {
        $user = Yii::app()->session['user'];
        $cache_name = md5('model_AppStatistics_getOneDayAllList_'.$user['com_id'].'_'.$type.'_'.$date);
        if (empty($typeid)) {
            // only cached data of yesterday or today
            if ($date==date("Y-m-d", time()) || $date==date("Y-m-d", time()-86400)) {
                $data = Yii::app()->memcache->get($cache_name);
                if ($data)
                    return $data;
            }
        }
        $typeField = $this->getStatType($type);
        // 根据条件查询统计信息
        $criteria = new CDbCriteria();
        $criteria->group = $typeField;
        $criteria->addColumnCondition(array("com_id" => $user['com_id']));
        $criteria->addCondition($typeField."!=0");
        if (is_array($typeid) && !empty($typeid)) {
            $criteria->addInCondition($typeField, $typeid);
        }
        $startTime = strtotime($date . " 00:00:00");
        $endTime = strtotime($date . " 23:59:59");
        $table = str_replace("-", "", $date);
        $criteria->addBetweenCondition('create_time', $startTime, $endTime);
        // paging
        $criteria->select = $typeField.' as id,'.$typeField.',count('.$typeField.') as show_num, sum(is_click) as click_num, count(distinct(ip)) as dedicgotd_ip, if(cost_mode=1, cost, 0) as cpd_cost, if(cost_mode=2, sum(cost), 0) as cpm_cost, if(cost_mode=3, sum(cost), 0) as cpc_cost, sum(cost) as cost, com_id';

        if ($type == 'material') {
            //$list = SiteStatMaterial::model()->findAll($criteria);
            $list = AppStatMate::model($table)->findAll($criteria);
        } else {
            $list = AppStat::model($table)->findAll($criteria);
        }
        // 将数据库选择到主库
        CActiveRecord::$db = Yii::app()->db;
        $data = array();
        $data['list'] = $list;
        if (empty($typeid)) {
            // only cached data of yesterday or today
            if ($date==date("Y-m-d", time()) || $date==date("Y-m-d", time()-86400)) {
                Yii::app()->memcache->set($cache_name, $data, 300);
            }
        }
        return $data;
    }

    /**
     * 获取站点广告统计列表信息
     * @$typeField 	统计类型字段，如广告统计 广告位统计
     * @$typeid 统计方式条件id，如广告id 广告位id
     * @$startDate 开始日期
     * @$endDate 结束日期
     */
    public function getPageListByType($type = 'ad', $typeid = array(), $startDate = "", $endDate = "", $route=null) {
        // 查询一天数据时 显示其24小时详细信息 
        if ($startDate != "" && $startDate == $endDate) {
            return $this->getDayPageListByType($type, $typeid, $startDate, $route);
        }
        // 根据条件查询统计信息
        switch($type) {
        case 'ad': // 广告
            return StatisticsAd::model()->getPageList($typeid, 2, $startDate, $endDate, $route);
        case 'position': // 广告位
            return StatisticsPosition::model()->getPageList($typeid, 2, $startDate, $endDate, $route);
        case 'order': // 订单
            return StatisticsOrder::model()->getPageList($typeid, 2, $startDate, $endDate, $route);
        case 'client': //客户
            return StatisticsClient::model()->getPageList($typeid, 2, $startDate, $endDate, $route);
        case 'seller': // 销售
            return StatisticsSeller::model()->getPageList($typeid, 2, $startDate, $endDate, $route);
        case 'material': // 物料
            return StatisticsMaterial::model()->getPageList($typeid, 2, $startDate, $endDate, $route);
        }
        return array();
    }
    
    public function getDayPageListByType($type, $typeid, $date, $route=null) {
        $user = Yii::app()->session['user'];
        $cache_name = md5('model_AppStatistics_getDayPageListByType_'.$user['com_id'].'_'.$type.'_'.$date);
        if (empty($typeid) && !isset($_GET['page']) && !isset($_GET['pagesize'])) {
            // only cached data of yesterday or today
            if ($date==date("Y-m-d", time()) || $date==date("Y-m-d", time()-86400)) {
                $data = Yii::app()->memcache->get($cache_name);
                if ($data)
                    return $data;
            }
        }
        $typeField = $this->getStatType($type);
        // 根据条件查询统计信息
        $criteria = new CDbCriteria();
        $criteria->group = $typeField;
        $criteria->addColumnCondition(array("com_id" => $user['com_id']));
        $criteria->addCondition($typeField."!=0");
        if (is_array($typeid) && !empty($typeid)) {
            $criteria->addInCondition($typeField, $typeid);
        }
        $startTime = strtotime($date . " 00:00:00");
        $endTime = strtotime($date . " 23:59:59");
        $table = str_replace("-", "", $date);
        $criteria->addBetweenCondition('create_time', $startTime, $endTime);
        // paging
        if ($type == 'material') {
            //$count = SiteStatMaterial::model()->count($criteria);
            $count = AppStatMate::model($table)->count($criteria);
        } else {
            $count = AppStat::model($table)->count($criteria);
        }
        $criteria->select = $typeField.' as id,'.$typeField.',count('.$typeField.') as show_num, sum(is_click) as click_num, count(distinct(ip)) as dedicgotd_ip, if(cost_mode=1, cost, 0) as cpd_cost, if(cost_mode=2, sum(cost), 0) as cpm_cost, if(cost_mode=3, sum(cost), 0) as cpc_cost, sum(cost) as cost, com_id';
        $pageSize = (isset($_GET['pagesize']) && $_GET['pagesize']) ? $_GET['pagesize'] : 10;
        $pager = new CPagination($count);
        $pager->pageSize = $pageSize;
        $pager->applyLimit($criteria);
        if ($route != null) {
            $pager->route = $route;
        }
        if ($type == 'material') {
            //$list = SiteStatMaterial::model()->findAll($criteria);
            $list = AppStatMate::model($table)->findAll($criteria);
        } else {
            $list = AppStat::model($table)->findAll($criteria);
        }

        $data = array();
        $data['list'] = $list;
        $data['pager'] = $pager;
        // 将数据库选择到主库
        CActiveRecord::$db = Yii::app()->db;
         if (empty($typeid) && !isset($_GET['page']) && !isset($_GET['pagesize'])) {
            // only cached data of yesterday or today
            if ($date==date("Y-m-d", time()) || $date==date("Y-m-d", time()-86400)) {
                Yii::app()->memcache->set($cache_name, $data, 300);
            }
        }
        return $data;
    }

    /**
     * 获取站点广告统计信息
     * @$type 	统计类型，如广告统计 广告位统计
     * @$typeid 统计方式条件id，如广告id 广告位id
     * @$startDate 开始日期
     * @$endDate 结束日期
     */
    public function getStatisticsByDate($type = 'ad', $typeid = array(), $startDate = "", $endDate = "") {
        $return = array();
        // 查询一天数据时 显示其24小时详细信息 
        if ($startDate != "" && $startDate == $endDate) {
            $return['statTotal'] = $this->getTotalDataByOneDay($type, $typeid, $startDate);
            $return['statChart'] = $this->getChartDataByHour($type, $typeid, $startDate);
        } else {
            $return['statTotal'] = $this->getTotalDataByMoreDay($type, $typeid, $adType=2, $startDate, $endDate);
            $return['statChart'] = $this->getChartDataByDay($type, $typeid, $adType=2, $startDate, $endDate);
        }
        return $return;
    }
    
    /**
     * 查询某天总的展现量和点击量以及其他数据
     */
    public function getTotalDataByOneDay($type = 'ad', $typeid = array(), $date = ""){
        $user = Yii::app()->session['user'];
        $cache_name = md5('model_AppStatistics_getTotalDataByOneDay_'.$user['com_id'].'_'.$type.'_'.$date);
        if (empty($typeid)) {
            // only cached data of yesterday or today
            if ($date==date("Y-m-d", time()) || $date==date("Y-m-d", time()-86400)) {
                $data = Yii::app()->memcache->get($cache_name);
                if ($data)
                    return $data;
            }
        }
        $typeField = $this->getStatType($type);
        // 根据条件查询统计信息
        $criteria = new CDbCriteria();
        $criteria->select = "count($typeField) as show_num, sum(is_click) as click_num, if(cost_mode=1, cost, 0) as cpd_cost, if(cost_mode=2, sum(cost), 0) as cpm_cost, if(cost_mode=3, sum(cost), 0) as cpc_cost, if(cost_mode>1,sum(cost),cost) as cost";
        $criteria->addColumnCondition(array("com_id" => $user['com_id']));
        $criteria->addCondition($typeField."!=0");
        if (is_array($typeid) && !empty($typeid)) {
            $criteria->addInCondition($typeField, $typeid);
        }
        $startTime = strtotime($date . " 00:00:00");
        $endTime = strtotime($date . " 23:59:59");
        $table = str_replace("-", "", $date);
        $criteria->addBetweenCondition('create_time', $startTime, $endTime);
        if ($type == 'material') {
            //$rData = SiteStatMaterial::model()->find($criteria);
            $rData = AppStatMate::model($table)->find($criteria);
        } else {
            $rData = AppStat::model($table)->find($criteria);
        }
        $arrData = array();
        if (!empty($rData)) {
            $arrData['totalShow'] = intval($rData->show_num);
            $arrData['totalClick'] = intval($rData->click_num);
            $arrData['totalCtr'] = ($arrData['totalShow'] == 0) ? "-" : round($arrData['totalClick'] / $arrData['totalShow'], 3) * 100.0;
            $arrData['totalCpdCost'] = ($rData->cpd_cost>0)? $rData->cpd_cost : 0;
            $arrData['totalCpmCost'] = ($rData->cpm_cost>0)? $rData->cpm_cost : 0;
            $arrData['totalCpcCost'] = ($rData->cpc_cost>0)? $rData->cpc_cost : 0;
            $arrData['totalCost'] = ($rData->cost)? $rData->cost : 0;
        }
        unset($rData);
        // 将数据库选择到主库
        CActiveRecord::$db = Yii::app()->db;
        if (empty($typeid)) {
            // only cached data of yesterday or today
            if ($date==date("Y-m-d", time()) || $date==date("Y-m-d", time()-86400)) {
                Yii::app()->memcache->set($cache_name, $arrData, 300);
            }
        }
        return $arrData;
    }
    
    /**
     * 查询多天总的展现量和点击量以及其他数据
     */
    public function getTotalDataByMoreDay($type = 'ad', $typeid = array(), $adType=1, $startDate, $endDate){
        $user = Yii::app()->session['user'];
        $typeField = $this->getStatType($type);
        // 根据条件查询统计信息
        $criteria = new CDbCriteria();
        $criteria->select = "sum(show_num) as show_num, sum(click_num) as click_num, sum(cpd_cost) as cpd_cost, sum(cpm_cost) as cpm_cost, sum(cpc_cost) as cpc_cost, sum(cost) as cost";
        $criteria->addColumnCondition(array("com_id" => $user['com_id']));
        $criteria->addColumnCondition(array("ad_type" => $adType));
        if (is_array($typeid) && !empty($typeid)) {
            $criteria->addInCondition($typeField, $typeid);
        }
        if ($startDate != "" && $endDate != "") {
            $criteria->addBetweenCondition('dates', $startDate, $endDate);
        } else if ($startDate != "" && $endDate = "") {
            $criteria->addBetweenCondition('dates', $startDate, date("Y-m-d", time()));
        }
        // 根据条件查询统计信息
        switch($type) {
        case 'ad': // 广告
            $rData = StatisticsAd::model()->find($criteria);
            break;
        case 'position': // 广告位
            $rData = StatisticsPosition::model()->find($criteria);
            break;
        case 'order': // 订单
            $rData = StatisticsOrder::model()->find($criteria);
            break;
        case 'client': //客户
            $rData = StatisticsClient::model()->find($criteria);
            break;
        case 'seller': // 销售
            $rData = StatisticsSeller::model()->find($criteria);
            break;
        case 'material': // 物料
            $rData = StatisticsMaterial::model()->find($criteria);
            break;
        }
        $arrData = array();
        if (!empty($rData)) {
            $arrData['totalShow'] = intval($rData->show_num);
            $arrData['totalClick'] = intval($rData->click_num);
            $arrData['totalCtr'] = ($rData->show_num == 0) ? "-" : round($rData->click_num / $rData->show_num, 3) * 100.0;
            $arrData['totalCpdCost'] = round($rData->cpd_cost, 2);
            $arrData['totalCpmCost'] = round($rData->cpm_cost, 2);
            $arrData['totalCpcCost'] = round($rData->cpc_cost, 2);
            $arrData['totalCost'] = round($rData->cost, 2);
        }
        unset($rData);
        return $arrData;
    }
    
    /**
     * 查询某天展现量和点击量按小时统计
     */
    public function getChartDataByHour($type = 'ad', $typeid = array(), $date = ""){
        $user = Yii::app()->session['user'];
        $cache_name = md5('model_AppStatistics_getChartDataByHour_'.$user['com_id'].'_'.$type.'_'.$date);
        if (empty($typeid)) {
            // only cached data of yesterday or today
            if ($date==date("Y-m-d", time()) || $date==date("Y-m-d", time()-86400)) {
                $data = Yii::app()->memcache->get($cache_name);
                if ($data)
                    return $data;
            }
        }
        $typeField = $this->getStatType($type);
        // 根据条件查询统计信息
        $criteria = new CDbCriteria();
        $criteria->select = "count($typeField) as show_num, sum(is_click) as click_num, DATE_FORMAT(FROM_UNIXTIME(create_time),'%H') as time_alias";
        $criteria->addColumnCondition(array("com_id" => $user['com_id']));
        $criteria->addCondition($typeField."!=0");
        if (is_array($typeid) && !empty($typeid)) {
            $criteria->addInCondition($typeField, $typeid);
        }
        $startTime = strtotime($date . " 00:00:00");
        $endTime = strtotime($date . " 23:59:59");
        $table = str_replace("-", "", $date);
        $criteria->addBetweenCondition('create_time', $startTime, $endTime);
        $criteria->group = 'time_alias';
        if ($type == 'material') {
            //$rData = SiteStatMaterial::model()->findAll($criteria);
            $rData = AppStatMate::model($table)->findAll($criteria);
        } else {
            $rData = AppStat::model($table)->findAll($criteria);
        }
        $arrData = array();
        if (!empty($rData)) {
            foreach($rData as $key=>$val) {
                $arrData[$val->time_alias]['show_num'] = $val->show_num; 
                $arrData[$val->time_alias]['click_num'] = $val->click_num;
            }
        }
        unset($rData);
        // 将数据库选择到主库
        CActiveRecord::$db = Yii::app()->db;
        if (empty($typeid)) {
            // only cached data of yesterday or today
            if ($date==date("Y-m-d", time()) || $date==date("Y-m-d", time()-86400)) {
                Yii::app()->memcache->set($cache_name, $arrData, 300);
            }
        }
        return $arrData;
    }
    
    /**
     * 查询某天展现量和点击量按小时统计
     */
    public function getChartDataByDay($type = 'ad', $typeid = array(), $adType=1, $startDate, $endDate){
        $user = Yii::app()->session['user'];
        $typeField = $this->getStatType($type);
        // 根据条件查询统计信息
        $criteria = new CDbCriteria();
        $criteria->select = "sum(show_num) as show_num, sum(click_num) as click_num, dates";
        $criteria->group = 'dates';
        $criteria->addColumnCondition(array("com_id" => $user['com_id']));
        $criteria->addColumnCondition(array("ad_type" => $adType));
        if (is_array($typeid) && !empty($typeid)) {
            $criteria->addInCondition($typeField, $typeid);
        }
        if ($startDate != "" && $endDate != "") {
            $criteria->addBetweenCondition('dates', $startDate, $endDate);
        } else if ($startDate != "" && $endDate = "") {
            $criteria->addBetweenCondition('dates', $startDate, date("Y-m-d", time()));
        }
        // 根据条件查询统计信息
        switch($type) {
        case 'ad': // 广告
            $rData = StatisticsAd::model()->findAll($criteria);
            break;
        case 'position': // 广告位
            $rData = StatisticsPosition::model()->findAll($criteria);
            break;
        case 'order': // 订单
            $rData = StatisticsOrder::model()->findAll($criteria);
            break;
        case 'client': //客户
            $rData = StatisticsClient::model()->findAll($criteria);
            break;
        case 'seller': // 销售
            $rData = StatisticsSeller::model()->findAll($criteria);
            break;
        case 'material': // 物料
            $rData = StatisticsMaterial::model()->findAll($criteria);
            break;
        }
        $arrData = array();
        if (!empty($rData)) {
            foreach($rData as $key=>$val) {
                $arrData[$val->dates]['show_num'] = $val->show_num; 
                $arrData[$val->dates]['click_num'] = $val->click_num;
            }
        }
        unset($rData);
        return $arrData;
    }

    // 统计时间选择参数
    public function timePeriodsParams() {
        $time = array();
        $time['t1']['name'] = '今天';
        $time['t1']['start_time'] = $this->mstrToTime(date("Y-m-d 00:00:00", time()));
        $time['t1']['end_time'] = time();

        $time['t2']['name'] = '昨天';
        $time['t2']['start_time'] = $this->mstrToTime(date("Y-m-d 00:00:00", time() - 86400));
        $time['t2']['end_time'] = $this->mstrToTime(date("Y-m-d 23:59:59", time() - 86400));

        $time['t3']['name'] = '上周';
        $time['t3']['start_time'] = mktime(0, 0, 0, date("m"), date("d") - date("w") + 1 - 7, date("Y"));
        $time['t3']['end_time'] = mktime(23, 59, 59, date("m"), date("d") - date("w") + 7 - 7, date("Y"));

        $time['t4']['name'] = '前七天';
        $time['t4']['start_time'] = $this->mstrToTime(date("Y-m-d 00:00:00", time() - (86400 * 7)));
        $time['t4']['end_time'] = time();

        $time['t5']['name'] = '本月';
        $time['t5']['start_time'] = $this->mstrToTime(date("Y-m-1 00:00:00", time()));
        $time['t5']['end_time'] = $this->mstrToTime(date("Y-m-t 23:59:59", time()));

        $time['t6']['name'] = '上月';
        $time['t6']['start_time'] = $this->mstrToTime(date("Y-m-1 00:00:00", strtotime("-1 month", time())));
        $time['t6']['end_time'] = $this->mstrToTime(date("Y-m-t 23:59:59", strtotime("-1 month", time())));

        return $time;
    }
    
    // 统计类型字段
    public function getStatType($type = 'ad') {
        $arrType = array(
            'ad' => 'ad_id',
            'position' => 'position_id',
            'order' => 'order_id',
            'client' => 'client_id',
            'seller' => 'seller_id',
            'material' => 'material_id',
        );
        $typeField = isset($arrType[$type])? $arrType[$type] : 'ad_id';
        return $typeField;
    }
    
    // 统计类型名称
    public function getStatTypeName($type = 'ad') {
        $arrName = array(
            'ad' => '广告',
            'position' => '广告位',
            'order' => '订单',
            'client' => '客户',
            'seller' => '销售',
            'material' => '物料',
        );
        $typeName = isset($arrName[$type])? $arrName[$type] : '广告';
        return $typeName;
    }
    
    public function filterTypeId($type = 'ad') {
        $typeid = isset($_GET['ad_id']) && $_GET['ad_id'] != "" ? array($_GET['ad_id']) : array();
        if (empty($typeid) && !empty($_GET['ad_name'])) {
            $searchName = urldecode($_GET['ad_name']);
            switch($type) {
            case 'ad': // 广告
                $list = Ad::model()->getDataByNameOrIds(array(), $searchName);
                if (!empty($list)) {
                    foreach ($list as $one) {
                        array_push($typeid, $one->id);
                    }
                }
                break;
            case 'position': // 广告位
                $list = Position::model()->getDataByNameOrIds(array(), $searchName);
                if (!empty($list)) {
                    foreach ($list as $one) {
                        array_push($typeid, $one->id);
                    }
                }
                break;
            case 'order': // 订单
                $list = Orders::model()->getDataByNameOrIds(array(), $searchName);
                if (!empty($list)) {
                    foreach ($list as $one) {
                        array_push($typeid, $one->id);
                    }
                }
                break;
            case 'client': //客户
                $list = ClientCompany::model()->getDataByNameOrIds(array(), $searchName);
                if (!empty($list)) {
                    foreach ($list as $one) {
                        array_push($typeid, $one->id);
                    }
                }
                break;
            case 'seller': // 销售
                $list = User::model()->getDataByNameOrIds(array(), $searchName);
                if (!empty($list)) {
                    foreach ($list as $one) {
                        array_push($typeid, $one->id);
                    }
                }
                break;
            case 'material': // 物料
                $list = Material::model()->getDataByNameOrIds(array(), $searchName);
                if (!empty($list)) {
                    foreach ($list as $one) {
                        array_push($typeid, $one->id);
                    }
                }
                break;
            }
        }
        return $typeid;
    }

    // 时间字符串转换时间戳
    public function mstrToTime($strTime) {
        $times = explode(" ", $strTime);
        $date = explode("-", $times[0]);
        $time = explode(":", $times[1]);
        $time[2] = isset($time[2]) ? $time[2] : 0;
        return mktime($time[0], $time[1], $time[2], $date[1], $date[2], $date[0]);
    }

}