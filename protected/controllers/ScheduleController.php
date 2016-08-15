<?php

/**
 * 排期控制器
 */
class ScheduleController extends BaseController {

    /**
     * 排期列表 
     */
    public function actionList() {
        $user = Yii::app()->session['user'];

        $criteria = new CDbCriteria();
        $criteria->order = 'createtime desc';
        $criteria->select = 'id,name,client_company_id,position_id,multi_time,status,createtime';
        $criteria->addColumnCondition(array('com_id' => $user['com_id']));
        // 附加搜索条件
        if (isset($_GET['status']) && $_GET['status']) {
            $criteria->addColumnCondition(array('status' => $_GET['status']));
        }
        if (isset($_GET['name']) && $_GET['name']) {
            $criteria->addSearchCondition('name', urldecode($_GET['name']));
        }

        // 分页
        $count = Schedule::model()->count($criteria);
        $pageSize = (isset($_GET['pagesize']) && $_GET['pagesize']) ? $_GET['pagesize'] : 10;
        $pager = new CPagination($count);
        $pager->pageSize = $pageSize;
        $pager->applyLimit($criteria);
        $schedulelist = Schedule::model()->findAll($criteria);

        //$com = array(0 => '-请选择-') + @CHtml::listData(ClientCompany::model()->getCom($user['com_id']), 'id', 'name');
        $com = ClientCompany::model()->getCom($user['com_id']);
        $position = Position::model()->getPositionInfo($user['com_id']);
        $adType = AdType::model()->getAdTypeName();
        $multi_time = array(0 => '否', 1 => '是');
        $status = array(1 => '启用', 0 => '删除', -1 => '禁用');

        $setArray = array(
            'schedulelist' => $schedulelist,
            'pages' => $pager,
            'com' => $com,
            'position' => $position,
            'multi_time' => $multi_time,
            'adType' => $adType,
            'status' => $status
        );

        $this->renderPartial('list', $setArray);
    }

    /**
     * 排期视图
     */
    public function actionListView() {
        $user = Yii::app()->session['user'];
        $ym = isset($_GET['ym']) && intval($_GET['ym']) > 100000 && intval($_GET['ym']) < 999999 ? $_GET['ym'] : date('Ym', time());

        $criteria = new CDbCriteria();
        $criteria->order = 'createtime desc';
        $criteria->addColumnCondition(array('com_id' => $user['com_id']));

        // 附加搜索条件
        if (isset($_GET['ad_type']) && $_GET['ad_type']) {
            $criteria->addColumnCondition(array('ad_type_id' => $_GET['ad_type']));
        }

        // 分页
        $count = Position::model()->count($criteria);
        $pageSize = (isset($_GET['pagesize']) && $_GET['pagesize']) ? $_GET['pagesize'] : 10;
        $pager = new CPagination($count);
        $pager->pageSize = $pageSize;
        $pager->applyLimit($criteria);

        $positions = Position::model()->findAll($criteria);
        $position_ids = $positions ? @CHtml::listData($positions, 'id', 'id') : array();

        $schedules = Schedule::model()->getScheduleByMonth($user['com_id'], $ym);

        //$orders = Orders::model()->getByIds($user['com_id'], $schedules['orders_ids']);
        $orders = array();
        $com = ClientCompany::model()->getCom($user['com_id']);
        foreach ($schedules['list'] as $pid => $one) {
            if (in_array($pid, $position_ids)) {
                foreach ($one as $o) {
                    $orders[$o['id']] = array(
                        'company_id' => @$o['client_company_id'],
                        'company_name' => @$com[$o['client_company_id']]['name'],
                        'id' => $o['id'],
                        'name' => $o['name']
                    );
                }
            } else {
                unset($schedules['list'][$pid]);
            }
        }

        $set = array(
            'schedules' => $schedules['list'],
            'positions' => $positions,
            'orders' => $orders,
            'pages' => $pager,
            'ym' => $ym,
            'week_name' => array(1 => '一', 2 => '二', 3 => '三', 4 => '四', 5 => '五', 6 => '六', 7 => '日')
        );
        $this->renderPartial('listView', $set);
    }

    /**
     * 广告位投放视图 
     */
    public function actionTable() {
        
    }

    /**
     * 投放任务 
     */
    public function actionTask() {
        $user = Yii::app()->session['user'];

        $criteria = new CDbCriteria();
        $criteria->order = 'createtime desc';
        $criteria->select = 'id,name,position_id,multi_time,taskstatus,client_company_id,salesman_id';
        $criteria->addColumnCondition(array('com_id' => $user['com_id']));
        $criteria->addColumnCondition(array('status' => 1));
        // 附加搜索条件
        if (isset($_GET['status']) && $_GET['status']) {
            $criteria->addColumnCondition(array('taskstatus' => $_GET['status']));
        }

        if (isset($_GET['name']) && $_GET['name']) {
            $criteria->addSearchCondition('name', urldecode($_GET['name']));
        }

        // 分页
        $count = Schedule::model()->count($criteria);
        $pageSize = (isset($_GET['pagesize']) && $_GET['pagesize']) ? $_GET['pagesize'] : 10;
        $pager = new CPagination($count);
        $pager->pageSize = $pageSize;
        $pager->applyLimit($criteria);
        $schedulelist = Schedule::model()->with('ScheduleTime')->findAll($criteria);
        $position = Position::model()->getPositionInfo($user['com_id']);
        //$scheduletime=ScheduleTime::model()->getScheduleTime($user['com_id']);
        $com = ClientCompany::model()->getCom($user['com_id']);
        $roleuser = User::model()->getUserByRole($user['com_id'], 3);
        //$status = array(1 => '已投放', -1 => '未投放');
        $scheduletime = array();
        foreach ($schedulelist as $one) {
            if (count($one->ScheduleTime) > 1) {
                $scheduletime[$one->id]['start_time'] = '多时间段';
                $scheduletime[$one->id]['end_time'] = '多时间段';
            } else {
                if (isset($one->ScheduleTime[0]->start_time))
                    $scheduletime[$one->id]['start_time'] = date('Y-m-d H:i:s', $one->ScheduleTime[0]->start_time);
                if (isset($one->ScheduleTime[0]->end_time))
                    $scheduletime[$one->id]['end_time'] = date('Y-m-d H:i:s', $one->ScheduleTime[0]->end_time);
            }
        }

        $setArray = array(
            'schedulelist' => $schedulelist,
            'pages' => $pager,
            'position' => $position,
            'scheduletime' => $scheduletime,
            'com' => $com,
            'roleuser' => $roleuser
        );

        $this->renderPartial('task', $setArray);
    }

    /**
     * 选择需要排期的广告位 
     */
    public function actionCheckPosition() {
        $this->renderPartial('checkPosition');
    }

    /**
     * 排期广告位列表
     */
    public function actionPositionList() {
        $this->renderPartial('positionList');
    }

    /**
     * 添加排期
     */
    public function actionAdd() {
        $user = Yii::app()->session['user'];
        $positionids = explode(',', $_GET['postions']);
        array_shift($positionids);
        $criteria = new CDbCriteria();
        $criteria->order = 'createtime desc';
        $criteria->select = 'id,name,position_size,ad_type_id';
        $criteria->addInCondition('id', $positionids);
        $positions = Position::model()->findAll($criteria);

        $schedule = new Schedule('add');
        $scheduletime = new ScheduleTime();
        if (isset($_POST['Schedule'])) {
            $return = array('code' => 1, 'message' => '添加成功');
            Yii::app()->oplog->add(); //添加日志

            if ($_GET['type'] == 1) {
                for ($j = 0; $j < count($positionids); $j++) {
                    $schedule1 = new Schedule('add');
                    $schedule1->attributes = $_POST['Schedule'];
                    $schedule1->com_id = $user['com_id'];
                    $schedule1->createtime = time();
                    $schedule1->multi_time = $_POST['Schedule']['time_type'];
                    $schedule1->position_id = $positionids[$j];
                    if ($schedule1->save()) {
                        if ($_POST['Schedule']['time_type'] == 0) {
                            $scheduletime1 = new ScheduleTime();
                            $scheduletime1->schedule_id = $schedule1->id;
                            $scheduletime1->start_time = strtotime($_POST['Schedule_start']);
                            $scheduletime1->end_time = strtotime($_POST['Schedule_end']);
                            $scheduletime1->save();
                        } else {
                            $times = array();
                            $time = explode("\n", trim($_POST['Schedule']['gap_time']));
                            foreach ($time as $one) {
                                $times = explode(" ~ ", $one);
                                $scheduletime1 = new ScheduleTime();
                                $scheduletime1->schedule_id = $schedule1->id;
                                $scheduletime1->start_time = strtotime($times[0] . ' 00:00:00');
                                $scheduletime1->end_time = strtotime($times[1] . ' 23:59:59');
                                $scheduletime1->save();
                            }
                        }
                    }
                }
            } else if ($_GET['type'] == 2) {
                for ($j = 0; $j < count($positionids); $j++) {
                    if (isset($_POST['Schedule' . $positionids[$j]])) {
                        $schedule1 = new Schedule('add');
                        $schedule1->attributes = $_POST['Schedule'];
                        $schedule1->com_id = $user['com_id'];
                        $schedule1->createtime = time();
                        $schedule1->multi_time = $_POST['Schedule' . $positionids[$j]]['time_type'];
                        $schedule1->position_id = $positionids[$j];
                        if ($schedule1->save()) {
                            if ($_POST['Schedule' . $positionids[$j]]['time_type'] == 0) {
                                $scheduletime1 = new ScheduleTime();
                                $scheduletime1->schedule_id = $schedule1->id;
                                $scheduletime1->start_time = strtotime($_POST['Schedule_' . $positionids[$j] . '_start'][0]);
                                $scheduletime1->end_time = strtotime($_POST['Schedule_' . $positionids[$j] . '_end'][0]);
                                $scheduletime1->save();
                            } else {
                                $times = array();
                                $time = explode("\n", trim($_POST['Schedule' . $positionids[$j]]['gap_time']));
                                foreach ($time as $one) {
                                    $times = explode(" ~ ", $one);
                                    $scheduletime1 = new ScheduleTime();
                                    $scheduletime1->schedule_id = $schedule1->id;
                                    $scheduletime1->start_time = strtotime($times[0] . ' 00:00:00');
                                    $scheduletime1->end_time = strtotime($times[1] . ' 23:59:59');
                                    $scheduletime1->save();
                                }
                            }
                        }
                    }
                }
            }

            if ($schedule1->hasErrors()) {
                $return['code'] = -1;
                $return['message'] = '<p style="color:red;">添加失败</p>';
                foreach ($schedule1->errors as $item) {
                    foreach ($item as $one)
                        $return['message'] .= '<p>' . $one . '</p>';
                }
            }
            die(json_encode($return));
        }
        $adShows = AdShow::model()->getPositionAdShows(1);
        $com = array('' => '-请选择-') + @CHtml::listData(ClientCompany::model()->getCom($user['com_id']), 'id', 'name');
        $roleuser = array(0 => '-请选择-') + @CHtml::listData(User::model()->getUserByRole($user['com_id'], 3), 'id', 'name');
        $contact = array(0 => '-请选择-') + @CHtml::listData(ClientContact::model()->getClientContactById($user['com_id']), 'id', 'name');
        $set = array(
            'schedule' => $schedule,
            //'order'=>$order,
            'positions' => $positions,
            'type' => $_GET['type'],
            'adShows' => $adShows,
            'com' => $com,
            'roleuser' => $roleuser,
            'contact' => $contact
        );
        $this->renderPartial('add', $set);
    }

    /**
     * 编辑排期
     */
    public function actionEdit() {
        $user = Yii::app()->session['user'];
        $id = $_GET['id'];
        $schedule = Schedule::model()->findByPk($id);
        $schedule->setScenario('edit');
        $scheduleTime = ScheduleTime::model()->findAll('schedule_id=:schedule_id', array(':schedule_id' => $schedule->id));
        if (isset($_POST['Schedule'])) {
            $return = array('code' => 1, 'message' => '修改成功');
            $schedule->attributes = $_POST['Schedule'];
            $schedule->multi_time = $_POST['Schedule']['time_type'];
            if ($schedule->save()) {
                Yii::app()->oplog->add(); //添加日志

                ScheduleTime::model()->deleteAll('schedule_id=:schedule_id', array(':schedule_id' => $schedule->id));
                if ($_POST['Schedule']['time_type'] == 0) {
                    $scheduletime1 = new ScheduleTime();
                    $scheduletime1->schedule_id = $schedule->id;
                    $scheduletime1->start_time = strtotime($_POST['Schedule_start']);
                    $scheduletime1->end_time = strtotime($_POST['Schedule_end']);
                    $scheduletime1->save();
                } else {
                    $times = array();
                    $time = explode("\n", trim($_POST['Schedule']['gap_time']));
                    foreach ($time as $one) {
                        $times = explode(" ~ ", $one);
                        $scheduletime1 = new ScheduleTime();
                        $scheduletime1->schedule_id = $schedule->id;
                        $scheduletime1->start_time = strtotime($times[0] . ' 00:00:00');
                        $scheduletime1->end_time = strtotime($times[1] . ' 23:59:59');
                        $scheduletime1->save();
                    }
                }
            }

            if ($scheduletime1->hasErrors()) {
                $return['code'] = -1;
                $return['message'] = '<p style="color:red;">修改失败</p>';
                foreach ($scheduletime1->errors as $item) {
                    foreach ($item as $one)
                        $return['message'] .= '<p>' . $one . '</p>';
                }
            }

            die(json_encode($return));
        }
        $times = array('start_time' => '', 'end_time' => '', 'str_time' => '', 'days' => 0);

        if ($schedule->multi_time) {
            foreach ($scheduleTime as $one) {
                $times['str_time'].=date('Y-m-d', $one->start_time) . " ~ " . date('Y-m-d', $one->end_time) . "\n";
                $times['days']+=ceil(($one->end_time - $one->start_time) / 86400);
            }
        } else {
            foreach ($scheduleTime as $one) {
                $times['start_time'] = date('Y-m-d H:i:s', $one->start_time);
                $times['end_time'] = date('Y-m-d H:i:s', $one->end_time);
            }
        }
        $position = Position::model()->findByPk($schedule->position_id);
        $adShows = AdShow::model()->getPositionAdShows(1);
        $com = array('' => '-请选择-') + @CHtml::listData(ClientCompany::model()->getCom($user['com_id']), 'id', 'name');
        $roleuser = array(0 => '-请选择-') + @CHtml::listData(User::model()->getUserByRole($user['com_id'], 3), 'id', 'name');
        $contact = array(0 => '-请选择-') + @CHtml::listData(ClientContact::model()->getClientContactById($user['com_id']), 'id', 'name');
        $data = array(
            'schedule' => $schedule,
            'times' => $times,
            'position' => $position,
            'com' => $com,
            'roleuser' => $roleuser,
            'contact' => $contact,
            'adShows' => $adShows
        );
        $this->renderPartial('edit', $data);
    }

    /**
     * 删除排期 
     */
    public function actionDel() {
        $user = Yii::app()->session['user'];
        $return = array('code' => 1, 'message' => '删除成功');
        if (isset($_POST['order']) && count($_POST['order'])) {
            $_POST['ids'] = (array) $_POST['order'];
            $criteria = new CDbCriteria();
            $criteria->addInCondition('id', $_POST['ids']);
            $criteria->addColumnCondition(array('com_id' => $user['com_id']));
            Schedule::model()->deleteAll($criteria);
            Yii::app()->oplog->add(); //添加日志
        } else {
            $return = array('code' => -1, 'message' => '未选择排期');
        }
        die(json_encode($return));
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
            Schedule::model()->updateAll(array('status' => $status), $criteria);
            Yii::app()->oplog->add(); //添加日志
        } else {
            $return = array('code' => -1, 'message' => '未选择排期');
        }
        die(json_encode($return));
    }

    // 生成excel统计报告文件
    public function actionScheduleExportList() {
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        // Set properties
        $objPHPExcel->getProperties()->setCreator("IHIMI")
                ->setLastModifiedBy("IHIMI system")
                ->setTitle("Office 2003 XLS Test Document")
                ->setSubject("Office 2003 XLS Test Document")
                ->setDescription("Test document for Office 2003 XLS, generated using PHP classes.")
                ->setKeywords("office 2003 openxml php")
                ->setCategory("Statistics file");

        // Add some data
        $data = array(1 => array('排期名称', '广告位名称', '广告客户', '状态', '多时间段', '创建时间'));
        if (!empty($_POST['strExportList'])) {
            $list = json_decode($_POST['strExportList']);
            foreach ($list as $key => $val) {
                array_push($data, array($val->name, $val->position_name, $val->com_name, $val->status, $val->multi_time, $val->createtime));
            }
        }
        foreach ($data as $key => $val) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $key, $val[0])
                    ->setCellValue('B' . $key, $val[1])
                    ->setCellValue('C' . $key, $val[2])
                    ->setCellValue('D' . $key, $val[3])
                    ->setCellValue('E' . $key, $val[4])
                    ->setCellValue('F' . $key, $val[5]);
        }
        // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $file_name = '排期列表';
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE"))
            header('Content-Disposition:attachment;filename="' . urlencode($file_name) . '.xls"');
        else
            header('Content-Disposition: attachment;filename="' . $file_name . '.xls"');

        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    // 生成excel统计报告文件
    public function actionTaskExportList() {
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        // Set properties
        $objPHPExcel->getProperties()->setCreator("IHIMI")
                ->setLastModifiedBy("IHIMI system")
                ->setTitle("Office 2003 XLS Test Document")
                ->setSubject("Office 2003 XLS Test Document")
                ->setDescription("Test document for Office 2003 XLS, generated using PHP classes.")
                ->setKeywords("office 2003 openxml php")
                ->setCategory("Statistics file");

        // Add some data
        $data = array(1 => array('排期名称', '广告位名称', '状态', '开始时间', '结束时间', '广告客户', '销售人员'));
        if (!empty($_POST['strExportList'])) {
            $list = json_decode($_POST['strExportList']);
            foreach ($list as $key => $val) {
                array_push($data, array($val->name, $val->position_name, $val->status, $val->starttime, $val->endtime, $val->com_name, $val->seller_name));
            }
        }
        foreach ($data as $key => $val) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $key, $val[0])
                    ->setCellValue('B' . $key, $val[1])
                    ->setCellValue('C' . $key, $val[2])
                    ->setCellValue('D' . $key, $val[3])
                    ->setCellValue('E' . $key, $val[4])
                    ->setCellValue('F' . $key, $val[5])
                    ->setCellValue('G' . $key, $val[6]);
        }
        // Rename sheet
        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $file_name = '投放任务列表';
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE"))
            header('Content-Disposition:attachment;filename="' . urlencode($file_name) . '.xls"');
        else
            header('Content-Disposition: attachment;filename="' . $file_name . '.xls"');

        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

}