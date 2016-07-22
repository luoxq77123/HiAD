<?php

class VideoStatistics extends CActiveRecord {
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
        return '{{site_statistics}}';
    }

    /**
     * ���ݹ������ ����ͳ�Ʒֱ�
     */
    public function addStatDetail($data) {
        // ��ȡ����id
        $adData = Ad::model()->getOneById($data['ad_id']);
        $data['order_id'] = $adData->order_id;
        $orderData = Orders::model()->getOneById($data['order_id']);
        // ��ȡ�ͻ�id
        $data['client_id'] = ($orderData)? $orderData->client_company_id : 0;
        // ��ȡ������Աid
        $data['seller_id'] = ($orderData)? $orderData->salesman_id : 0;
        // ����ͳ�Ʊ�
        $this->createStatTable();
        // ����չʾ���ߵ������
        $data['cost'] = $this->getRowCost($data['cost_mode'], $data['price']);
        // ���ͳ����Ϣ
        $statData = $this->addSubStatData($data);
        // �������ͳ����Ϣ
        VideoStatMate::model()->createStatTable();
        $this->addMaterialData($data, $statData);
        // ������Ʒ�ģʽ��չ���Ҷ������� ����¶�������
        if ($data['cost_mode']==2 && $data['order_id']>0 && $data['cost']>0) {
            Orders::model()->updateCostById($data['order_id'], $data['cost']);
        }
        return $statData;
    }
    
    /**
     * ��ӷֱ�ͳ�� ���HimiController����ȡͳ�ƣ���������ͳ�ƣ�������ɸѡ������ͳ��
     */
    public function addStatDetailForSite($data) {
        // ��ȡ����id
        $adData = Ad::model()->getOneById($data['ad_id']);
        $data['order_id'] = $adData->order_id;
        $orderData = Orders::model()->getOneById($data['order_id']);
        // ��ȡ�ͻ�id
        $data['client_id'] = ($orderData)? $orderData->client_company_id : 0;
        // ��ȡ������Աid
        $data['seller_id'] = ($orderData)? $orderData->salesman_id : 0;
        // ����ͳ�Ʊ�
        $this->createStatTable();
        // ����չʾ���ߵ������
        $data['cost'] = $this->getRowCost($data['cost_mode'], $data['price']);
        // ���ͳ����Ϣ
        $statData = $this->addSubStatData($data);
        // ������Ʒ�ģʽ��չ���Ҷ������� ����¶�������
        if ($data['cost_mode']==2 && $data['order_id']>0 && $data['cost']>0) {
            Orders::model()->updateCostById($data['order_id'], $data['cost']);
        }
        unset($data);
        return $statData;
    }
    
    /**
     * ������Ϸֱ�ͳ�� ���HimiController����ȡͳ��
     */
    public function addMaterialStatForSite($data, $statData) {
        // ����չʾ���ߵ������
        $data['cost'] = $this->getRowCost($data['cost_mode'], $data['price']);
        // �������ͳ����Ϣ
        $this->addMaterialData($data, $statData);
    }
    
    /**
     * ����ֱ�����
     */
    public function addSubStatData($data) {
        $date = date("Ymd", $data['create_time']);
        // ���ͳ����Ϣ
        $AppStat = new VideoStat($date);
        $AppStat->ad_id = $data['ad_id'];
        $AppStat->position_id = $data['position_id'];
        $AppStat->order_id = $data['order_id'];
        $AppStat->client_id = $data['client_id'];
        $AppStat->seller_id = $data['seller_id'];
        $AppStat->material_ids = $data['material_ids'];
        $AppStat->ip = $data['ip'];
        $AppStat->region_id = $data['region_id'];
        $AppStat->connect_id = $data['connect_id'];
        $AppStat->browser_id = $data['browser_id'];
        $AppStat->language_id = $data['language_id'];
        $AppStat->system_id = $data['system_id'];
        $AppStat->resolution_id = $data['resolution_id'];
        $AppStat->referer_id = $data['referer_id'];
        $AppStat->accessurl_id = $data['accessurl_id'];
        $AppStat->is_click = 0;
        $AppStat->create_time = $data['create_time'];
        $AppStat->click_time = 0;
        $AppStat->cost_mode = $data['cost_mode'];
        $AppStat->cost = $data['cost'];
        $AppStat->info = $data['info'];
        $AppStat->com_id = $data['com_id'];
        $AppStat->save();
        $return = array();
        $return['sid'] = $AppStat->id;
        $return['time'] = $data['create_time'];
        // �����ݿ�ѡ������
        CActiveRecord::$db = Yii::app()->db;
        return $return;
    }
    
    /**
     * �������ϱ����� ����һ������Ӧ������� Ϊ�����������ͳ���������ʱ������Ϣ
     */
    public function addMaterialData($data, $statData) {
        if ($data['material_ids']) {
            $arrMaterial = explode(",", $data['material_ids']);
            if (!empty($arrMaterial)) {
                $date = date("Ymd", $data['create_time']);
                $table = 'hm_videomaterial_'.$date;
                $sql = "insert into $table (material_id, stat_id, com_id, ip, is_click, click_count, cost_mode, cost, create_time) values ";
                foreach($arrMaterial as $mid) {
                    $sql .= "(".$mid.", ".$statData['sid'].", ".$data['com_id'].", ".$data['ip'].", 0, 0, ".$data['cost_mode'].", ".$data['cost'].", ".$data['create_time']."),";
                }
                $sql = substr($sql, 0, -1);
                Yii::app()->db_stat_videomate->createCommand($sql)->execute();
            }
        }
    }

    /**
     * ����ÿ��չʾ���ߵ������
     */
    public function getRowCost($costMode, $cost) {
        $cost = ($cost>0)? $cost : 0.00;
        $reCost = 0.00;
        if ($costMode == 2) { // cpm
            // ��ѯչ�ּ��� ÿһǧ����һ������
            /*$sql = "select show_count from ad_show_count where ad_id=".$data['ad_id'];
            $result = Yii::app()->db_stat_site->createCommand($sql)->queryRow();
            if (!empty($result)) {
                if ($result['show_count']==999) {
                    $reCost = $cost;
                    $sql = "update ad_show_count set show_count=0 where ad_id=".$data['ad_id'];
                } else {
                    $sql = "update ad_show_count set show_count=show_count+1 where ad_id=".$data['ad_id'];
                }
            } else {
                $sql = "insert into ad_show_count (ad_id, show_count) values (".$data['ad_id'].", 1)";
            }
            Yii::app()->db_stat_site->createCommand($sql)->execute();
            */
            // ��ÿ�μƷѰ����õ�cpm/1000������
            $reCost = $cost/1000;
        } else { // cpc cpd
            $reCost = $cost;
        }
        return $reCost;
    }

    /**
     * ����ͳ�Ʒֱ�
     */
    public function createStatTable() {
        $date = date("Ymd", time());
        $table = 'video_' . $date;
        $query = "
        CREATE TABLE IF NOT EXISTS `$table` (
          `id` int(10) NOT NULL auto_increment,
          `ad_id` int(10) default NULL COMMENT '���id',
          `position_id` int(10) default NULL COMMENT '���λid',
          `order_id` int(10) default '0' COMMENT '����id',
          `client_id`  int(10) NULL DEFAULT 0 COMMENT '�ͻ�id',
          `seller_id` int(10) default '0' COMMENT '����id',
          `material_ids` varchar(255) default NULL COMMENT '����չʾ����id',
          `ip` int(10) default '0' COMMENT 'ipת������',
          `region_id` int(10) default '0' COMMENT '����id',
          `connect_id` int(5) default '0' COMMENT '���뷽ʽid',
          `browser_id` int(5) default '0' COMMENT '���������',
          `language_id` int(5) default '0' COMMENT '���������',
          `system_id` int(5) default '0' COMMENT 'ϵͳ',
          `resolution_id` int(5) default '0' COMMENT '�ֱ���',
          `referer_id` int(10) default '0' COMMENT '��Դ��',
          `accessurl_id` int(10) default '0' COMMENT '����url',
          `is_click` tinyint(1) default '0' COMMENT '�Ƿ��� 1����  0 ����',
          `create_time` int(11) default NULL COMMENT '�����ʾʱ��',
          `click_time` int(11) default NULL COMMENT '�����ʱ��',
          `cost_mode` tinyint(1) default '0' COMMENT '�Ʒ�ģʽ��1.CPD; 2.CPM; 3.CPC',
          `cost` float(8,2) default '0' COMMENT '�Ʒ�',
          `info` mediumtext COMMENT '������Ϣ��չ',
          `com_id` int(11) default NULL,
          UNIQUE KEY `id` (`id`),
          KEY `index` USING BTREE (`ad_id`,`position_id`,`order_id`,`client_id`,`seller_id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
        ";
        Yii::app()->db_stat_site->createCommand($query)->execute();
    }
    
    public function addClickStatLog($params) {
        $time = time();
        $db_name = date('Ymd', $params['time']);
        $model = SiteStat::model($db_name)->findByPk($params['sid']);
        if ($model) {
            $orderId = $model->order_id;
            $costMode = $model->cost_mode;
            $orderCost = $model->cost;
            $model->is_click = 1;
            $model->click_time = time();
            $model->save();
            // �����ݿ�ѡ������
            CActiveRecord::$db = Yii::app()->db;
            // ��������ӳ���
            $table = 'hm_sitematerial_'.$db_name;
            $sql = "update $table set is_click=1, click_count=click_count+1, click_time=".$time." where stat_id=".$params['sid']." and create_time=".$params['time'];
            Yii::app()->db_stat_sitemate->createCommand($sql)->execute();
            // ���¶�������
            if ($orderId>0 && $costMode==3 && $orderCost>0) {
                Orders::model()->updateCostById($orderId, $orderCost);
            }
        } else {
            // �����ݿ�ѡ������
            CActiveRecord::$db = Yii::app()->db;
        }
    }
}