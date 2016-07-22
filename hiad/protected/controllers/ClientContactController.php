<?php

/**
 * 客户联系人控制器
 */
class ClientContactController extends BaseController {

    /**
     * 客户联系人列表
     */
    public function actionList() {
        $user = Yii::app()->session['user'];

        $criteria = new CDbCriteria();
        $criteria->order = 'createtime desc';
        $criteria->select = 'id,name,email,client_company_id,cellphone,createtime';
        $criteria->addColumnCondition(array('com_id' => $user['com_id']));

        // 附加搜索条件
        if (isset($_GET['com']) && $_GET['com']) {
            $criteria->addColumnCondition(array('client_company_id' => $_GET['com']));
        }
        if (isset($_GET['name']) && $_GET['name']) {
            $criteria->addSearchCondition('name', urldecode($_GET['name']));
        }

        // 分页
        $count = ClientContact::model()->count($criteria);
        $pageSize = (isset($_GET['pagesize']) && $_GET['pagesize']) ? $_GET['pagesize'] : 10;
        $pager = new CPagination($count);
        $pager->pageSize = $pageSize;
        $pager->applyLimit($criteria);

        $clientcontactlist = ClientContact::model()->findAll($criteria);
        $com = array(0 => '-请选择-') + @CHtml::listData(ClientCompany::model()->getCom($user['com_id']), 'id', 'name');
        $setArray = array(
            'clientcontactlist' => $clientcontactlist,
            'pages' => $pager,
            'com' => $com
        );
        $this->renderPartial('list', $setArray);
    }

    /**
     * 添加客户联系人
     */
    public function actionAdd() {
        $user = Yii::app()->session['user'];
        $contact = new ClientContact('add');

        if (isset($_POST['ClientContact'])) {
            $return = array('code' => 1, 'message' => '添加成功');

            $contact->attributes = $_POST['ClientContact'];
            if ($contact->validate()) {
                $contact->com_id = $user['com_id'];
                $contact->createtime = time();
                if ($contact->save()) {
                    Yii::app()->oplog->add(); //添加日志
                }
            }
            if ($contact->hasErrors()) {
                $return['code'] = -1;
                $return['message'] = '<p style="color:red;">添加失败</p>';
                foreach ($contact->errors as $item) {
                    foreach ($item as $one)
                        $return['message'] .= '<p>' . $one . '</p>';
                }
            }
            die(json_encode($return));
        }

        $com = array(0 => '-请选择-') + @CHtml::listData(ClientCompany::model()->getCom($user['com_id']), 'id', 'name');
        $this->renderPartial('add', array('contact' => $contact, 'com' => $com));
    }

    /**
     * 编辑客户联系人
     */
    public function actionEdit() {
        $user = Yii::app()->session['user'];
        $id = $_GET['id'];
        $contact = ClientContact::model()->findByPk($id);
        $contact->setScenario('edit');
        $contact->email_re = $contact->email;
        if (isset($_POST['ClientContact'])) {
            $return = array('code' => 1, 'message' => '修改成功');

            $contact->attributes = $_POST['ClientContact'];
            if ($contact->validate()) {
                if ($contact->save()) {
                    Yii::app()->oplog->add(); //添加日志
                }
            }
            if ($contact->hasErrors()) {
                $return['code'] = -1;
                $return['message'] = '<p style="color:red;">修改失败</p>';
                foreach ($contact->errors as $item) {
                    foreach ($item as $one)
                        $return['message'] .= '<p>' . $one . '</p>';
                }
            }
            die(json_encode($return));
        }
        $com = array(0 => '-请选择-') + @CHtml::listData(ClientCompany::model()->getCom($user['com_id']), 'id', 'name');
        $this->renderPartial('edit', array('contact' => $contact, 'com' => $com));
    }

    /**
     * 删除客户联系人 
     */
    public function actionDel() {
        $return = array('code' => 1, 'message' => '删除成功');
        if (isset($_POST['contact']) && count($_POST['contact'])) {
            $_POST['contact'] = (array) $_POST['contact'];
            $criteria = new CDbCriteria();
            $criteria->addInCondition('id', $_POST['contact']);
            $criteria->addColumnCondition(array('com_id' => Yii::app()->session['user']['com_id']));
            ClientContact::model()->deleteAll($criteria);
            Yii::app()->oplog->add(); //添加日志
        } else {
            $return = array('code' => -1, 'message' => '未选择用户');
        }
        die(json_encode($return));
    }

}