<?php

class ThridCodePositionList extends CWidget {

    public function run() {
        $user = Yii::app()->session['user'];

        $criteria = new CDbCriteria();
        $criteria->order = 'createtime desc';
        $criteria->addColumnCondition(array('com_id' => $user['com_id'], 'status' => 1));
        $criteria->addColumnCondition(array('ad_type_id' => 4));

        // 附加搜索条件
        if (isset($_GET['name']) && $_GET['name']) {
            $criteria->addSearchCondition('name', $_GET['name']);
        }

        // 分页
        //$count = SitePosition::model()->with('Position')->count($criteria);
        $count = Position::model()->count($criteria);
        $pageSize = (isset($_GET['pagesize']) && $_GET['pagesize']) ? $_GET['pagesize'] : 10;
        $pager = new CPagination($count);
        $pager->route = 'schedule/positionList';
        $pager->pageSize = $pageSize;
        $pager->applyLimit($criteria);

        $positions_data = Position::model()->findAll($criteria);
        $positions = array();
        foreach ($positions_data as $one) {
            $positions[] = array(
                'id' => $one->id,
                'name' => $one->name,
                'position_size' => $one->position_size,
                'ad_type_id' => $one->ad_type_id,
                'ad_show_id' => $one->ad_show_id
            );
        }
        $adType=AdType::model()->getAdTypeName();
        $adShows = AdShow::model()->getAdShows();
        $set = array(
            'positions' => $positions,
            'pages' => $pager,
            'adType'=>$adType,
            'adShows' => $adShows
        );
        $this->render('thridCodePositionList', $set);
    }

}