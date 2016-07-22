<?php

/**
 * 系统设置控制器
 */
class SettingController extends BaseController {

    /**
     * 系统设置列表
     */
    public function actionList() {
        $criteria = new CDbCriteria();
        // 分页
        $count = Setting::model()->count($criteria);
        $pageSize = (isset($_GET['pagesize']) && $_GET['pagesize']) ? $_GET['pagesize'] : 10;
        $pager = new CPagination($count);
        $pager->pageSize = $pageSize;
        $pager->applyLimit($criteria);
        $setlist = Setting::model()->findAll($criteria);

        $setArray = array(
            'setlist' => $setlist,
            'pages' => $pager
        );
        $this->renderPartial('list', $setArray);
    }

    /**
     * 修改系统设置
     */
    public function actionEdit() {
        $key = $_GET['key'];
        $set = Setting::model()->find('set_key=:key', array(':key' => $key));
        $set->setScenario('edit');
        if (isset($_POST['Setting'])) {
            $return = array('code' => 1, 'message' => '修改成功');
            $set->updateAll(array('set_val' => $_POST['Setting']['set_val']), 'set_key=:key', array(':key' => $key));
            if ($set->hasErrors()) {
                $return['code'] = -1;
                $return['message'] = '<p style="color:red;">修改失败</p>';
                foreach ($set->errors as $item) {
                    foreach ($item as $one)
                        $return['message'] .= '<p>' . $one . '</p>';
                }
            }
            die(json_encode($return));
        }

        $this->renderPartial('edit', array('set' => $set));
    }

}