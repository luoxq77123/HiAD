<?php

/**
 * 操作日志控制器
 */
class OplogController extends BaseController {

    /**
     * 操作日志列表
     */
    public function actionList() {
        $user = Yii::app()->session['user'];

        $criteria = new CDbCriteria();
        $criteria->order = 'createtime desc';
        $criteria->select = 'id,aca_id,url,uid,ip,createtime';
        $criteria->addColumnCondition(array('com_id' => $user['com_id']));
        //$criteria->addNotInCondition('uid', array($com['super_uid']));
        // 附加搜索条件
        if (isset($_GET['ip']) && $_GET['ip']) {
            //$criteria->addSearchCondition('ip' => urldecode($_GET['ip']));
            $criteria->addSearchCondition('ip', urldecode($_GET['ip']));
        }
        if (isset($_GET['name']) && $_GET['name']) {
            $criteria->addColumnCondition(array('aca_id' => $_GET['name']));
        }

        // 分页
        $count = Oplogs::model()->count($criteria);
        $pageSize = (isset($_GET['pagesize']) && $_GET['pagesize']) ? $_GET['pagesize'] : 10;
        $pager = new CPagination($count);
        $pager->pageSize = $pageSize;
        $pager->applyLimit($criteria);

        $oploglist = Oplogs::model()->findAll($criteria);

        $acas = Aca::model()->getAcaName();
        $admins = User::model()->getComUser($user['com_id']);
        $acaid = Oplogs::model()->getAcaId($user['com_id']);
        $acaidlist = array(0 => '-请选择-');
        foreach ($acaid as $one) {
            if (isset($acas[$one]))
                $acaidlist[$one] = $acas[$one];
        }

        $setArray = array(
            'oploglist' => $oploglist,
            'pages' => $pager,
            'acas' => $acas,
            'admins' => $admins,
            'acaid' => $acaid,
            'acaidlist' => $acaidlist
        );
        $this->renderPartial('list', $setArray);
    }

    /**
     * 删除操作日志 
     */
    public function actionDel() {
        
    }

}