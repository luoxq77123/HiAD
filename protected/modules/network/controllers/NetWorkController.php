<?php
class NetWorkController extends NBaseController {
    public $appSecret = 'e2e12518e1ea7f1c77d5d6ceb22426dd1a9fcd25';
    public $netWorkUrl = 'http://test.ida.sobeyyun.com/interface';
    //对接函数
    public function actionindex(){
        $parameter = array(
            'action' => 'getPermission',
            'appKey' => '12345697',
            'timestamp' => time(),
            'token' => $_GET['token'],
            'type' => 1,
            'uid' => $_GET['uid'],
            );
        $serverhost = explode('.', $_SERVER['HTTP_HOST']);
        $parameter['tenantid'] = $serverhost[0];

        $publicParams = array( 'appKey', 'action', 'timestamp', 'sign' ,'tenantid');
        $pArr = array_diff_key( $parameter, array_flip($publicParams) );
        ksort( $pArr );  //按参数名对参数进行升序排序

        $pStr2 = '';
        foreach ( $pArr as $k => $v ) {
            $pStr2 .= $k . $v ;  
        }
        $sign = md5( $pStr2 . $this->appSecret );

        $parameter['sign'] = $sign;

        $parameter = json_encode($parameter);

        $parameter = array('parameter'=>$parameter);


        Yii::import("application.helpers.*");
        $permis = Ccrul::post($this->netWorkUrl, $parameter);
        $permis = json_decode($permis,true);
        //获取用户
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('name' => $permis['returnData']['info']['username']));
        $user = User::model()->find($criteria);
        if(empty($user)){ 
            $user = $this->userRegister($permis['returnData']['info']);
        }
        Yii::app()->session['user'] = array(
                    'uid' => $user->uid,
                    'email' => $user->email,
                    'name' => $user->name,
                    'role_id' => $user->role_id,
                    'department_id' => $user->department_id,
                    'com_id' => $user->com_id,
                );
        header('location:http://'.$_SERVER['HTTP_HOST']);
    }
    /**
     * 用户注册接口
     */
    public function userRegister($params) {

        $member = new User;
        $member->name = isset($params['username']) ? $params['username'] : '';
        $member->email = isset($params['email']) ? $params['email'] : '';
        $member->cellphone = isset($params['mobile']) ? $params['mobile'] : '';
        $member->password = isset($params['password']) ? $params['password'] : '';
        $member->salt = $member->generateSalt();
        $member->role_id = 1;
        
        //$params['com_id'];
        //$member->regip = HmNetwork::getIP();
        $member->createtime = time();
        //$member->lastloginip = HmNetwork::getIP();
        //$member->lastlogintime = time();
        //$member->token = md5(uniqid('new') . rand(100, 999));
        //$member->token_expired = time() + 2592000; //登陆过期时间默认为1个月，30天 30*24*60*60=2592000
        $member->status = 1;
        //获取租户信息
        $serverhost = explode('.', $_SERVER['HTTP_HOST']);
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('contact_name' => $serverhost[0]));
        $company = Company::model()->find($criteria);
        if(empty($company)){ 
            return json_encode(array('status'=>1,'message'=>'租户'.$serverhost[0].'不存在，请先建立对应租户'));
        }
        $member->com_id = $company->id;
        /*if (!$member->validate()) {
            $this->return['returnCode'] = 301;
            $error_array = array();
            if ($member->hasErrors('username'))
                array_push($error_array, $member->getError('username'));
            if ($member->hasErrors('mobile'))
                array_push($error_array, $member->getError('mobile'));
            if ($member->hasErrors('password'))
                array_push($error_array, $member->getError('password'));
            $this->return['returnDesc'] = join(',', $error_array);
            return $this->return;
        }*/

        /*if (!$this->checkPassword($params['password'])) {
            $this->return['returnCode'] = 301;
            $this->return['returnDesc'] = '密码在6-16位之间';
            return $this->return;
        }*/

        // 注册到bsp会员中心
        /*$bspReturn = $this->updateUserInfo(1);
        if(!$bspReturn['status']) {
            $this->return['returnCode'] = 200;
            $this->return['returnDesc'] = '注册到BSP会员中心失败。' . $bspReturn['message'];
            return $this->return;
        }*/

        //$member->password = md5(md5($member->password) . $member->salt);
        if($member->save()){ 
            return $member;
        }
        
    }

    
}
