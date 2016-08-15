<?php

/**
 * 七瑞控制器
 */
class QiRuiController extends CController {

    /**
     * 七瑞申请用户注册 
    */
    public function actionReg() {
		$com = new Company();
        $comUser = new User();
        if ($_POST['reginForm']) {
            $userData = User::model()->findByAttributes(array('email'=>$_POST['reginForm']['email']));
            if (($userData)) {
                echo json_encode(array('code'=>-1, 'message'=>'你的邮箱已经被注册了'));
                exit;
            }
			$uid = 0;
            $com_id = 0;
            // 添加管理用户
            $comUser->attributes = $_POST['reginForm'];
            $comUser->name = $_POST['reginForm']['name'];
            $comUser->email = $_POST['reginForm']['email'];
            $comUser->role_id = 1;
            if ($comUser->validate()) {
                $comUser->com_id = $com_id;
                $comUser->salt = $comUser->generateSalt();
                $comUser->password = $comUser->hashPassword($comUser->password_first, $comUser->salt);
                $comUser->createtime = time();
                $comUser->department_id = 0;
                if (!$comUser->save()) {
                    echo "用户信息插入失败";
                    exit;
                }
                $uid = $comUser->attributes['uid'];
            }
            if ($comUser->hasErrors()) {
                foreach ($comUser->errors as $item) {
                    foreach ($item as $one)
                        echo '<p>' . $one . '</p>';
                }
                exit;
            }
            // 添加公司信息
            $com->attributes = $_POST['reginForm'];
            if ($com->validate()) { 
                $com->createtime = time();
                $com->name = $_POST['reginForm']['name'];
                /*$com->description = $_POST['Company']['description'];
                $com->contact_name = $_POST['Company']['contact_name'];
                $com->phone = $_POST['Company']['phone'];*/
                //$com->com_key = $_POST['Company']['com_key'];
                $com->super_uid = $uid;
                if (!$com->save()) {
                    echo "公司信息插入失败";
                    exit;
                }
                $com_id = $com->attributes['id'];
                // 更新用户com_id
                $comUser->com_id = $com_id;
                 if ($comUser->save()) {
					echo json_encode(array('code'=>1, 'message'=>'账号申请成功！请等待审核','url'=>'index.html'));
					exit;
				} else {
					echo json_encode(array('code'=>-1, 'message'=>'账号申请失败'));
					exit;
				}
            }
            if ($com->hasErrors()) {
                foreach ($com->errors as $item) {
                    foreach ($item as $one)
                        echo '<p>' . $one . '</p>';
                }
                exit;
            }
        }
    }
    /**
     * 提交申请合作伙伴
    */
    public function actionPartnerApply(){
        if ($_POST['partner']) {
            $partnerApply = new PartnerApply();
            $partnerApply->email = $_POST['partner']['email'];
            $partnerApply->name = $_POST['partner']['name'];
            $partnerApply->telephone = $_POST['partner']['telephone'];
            $partnerApply->company = $_POST['partner']['company'];
            $partnerApply->matter = $_POST['partner']['matter'];
            $partnerApply->content = $_POST['partner']['content'];
            $partnerApply->idea = $_POST['partner']['idea'];
            $partnerApply->demand = $_POST['partner']['demand'];
            $partnerApply->status = 1;
            $partnerApply->createtime = time();
            if ($partnerApply->save()) {
                echo json_encode(array('code'=>1, 'message'=>'申请提交成功！请等待客服人员与你联系','url'=>'index.html'));
                exit;
            } else {
                echo json_encode(array('code'=>-1, 'message'=>'申请提交失败！'));
                exit;
            }
        }
    }
}