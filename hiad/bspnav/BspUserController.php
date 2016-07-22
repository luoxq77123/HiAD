<?php

class BspUserController extends IBaseController {

    //入口函数
    public function index(){ 
        
    }
    /**
     * 用户注册接口
     */
    public function userRegister() {

        $params = $this->params;
        // 检查BSP会员名是否存在
        if (!$this->userCheck($params['username'])) {
            $this->return['returnCode'] = 301;
            $this->return['returnDesc'] = '用户名已存在';
  
            return $this->return;
        }

        $member = new Member('add');
        $member->username = isset($params['username']) ? $params['username'] : '';
        $member->mobile = isset($params['mobile']) ? $params['mobile'] : '';
        $member->password = isset($params['password']) ? $params['password'] : '';
        $member->salt = $member->generateSalt();
        $member->com_id = $params['com_id'];
        $member->regip = HmNetwork::getIP();
        $member->regtime = time();
        $member->lastloginip = HmNetwork::getIP();
        $member->lastlogintime = time();
        $member->token = md5(uniqid('new') . rand(100, 999));
        $member->token_expired = time() + 2592000; //登陆过期时间默认为1个月，30天 30*24*60*60=2592000
        $member->logintimes = 1;

        if (!$member->validate()) {
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
        }

        if (!$this->checkPassword($params['password'])) {
            $this->return['returnCode'] = 301;
            $this->return['returnDesc'] = '密码在6-16位之间';
            return $this->return;
        }

        // 注册到bsp会员中心
        $bspReturn = $this->updateUserInfo(1);
        if(!$bspReturn['status']) {
            $this->return['returnCode'] = 200;
            $this->return['returnDesc'] = '注册到BSP会员中心失败。' . $bspReturn['message'];
            return $this->return;
        }

        $member->password = md5(md5($member->password) . $member->salt);
        if ($member->save()) {
            // 添加用户详细信息
            $memberDetail = new MemberDetail('add');
            $memberDetail->userid = $member->userid;
            $memberDetail->name = isset($params['name']) ? $params['name'] : '';
            $memberDetail->avatar = isset($params['avatar']) ? $params['avatar'] : '';
            $memberDetail->gender = isset($params['gender']) && in_array(intval($params['gender']), array(1, 2)) ? $params['gender'] : 0;
            $memberDetail->birthday = isset($params['birthday']) ? $params['birthday'] : '';
            $memberDetail->save();

            // 默认登陆，加入登陆日志
            $memberLoginLog = new MemberLoginLog();
            $memberLoginLog->userid = $member->userid;
            $memberLoginLog->username = $member->username;
            $memberLoginLog->ip = HmNetwork::getIP();
            $memberLoginLog->time = time();
            $memberLoginLog->succeed = 1;
            $memberLoginLog->save();

            // 返回uid及登陆验证
            $this->return['uid'] = $member->userid;
            $this->return['loginToken'] = $member->token;
            $this->return['userInfo'] = array(
                'username' => $member->username,
                'mobile' => $member->mobile,
                'gender' => $memberDetail->gender,
                'name' => $memberDetail->name,
                'avatar' => $memberDetail->getAvatarUrl(),
                'birthday' => $memberDetail->birthday
            );
        }
        return $this->return;
    }

    /**
     * 用户登陆接口
     */
    public function userLogin() {

        $params = $this->params;
        if (isset($params['account']) && is_numeric($params['account']) && strlen($params['account']) == 11) {
            $attr = array('mobile' => $params['account']);
        } else if (isset($params['account']) && !is_numeric($params['account'])) {
            $attr = array('username' => $params['account']);
        } else {
            $this->return['returnCode'] = 302;
            $this->return['returnDesc'] = '登陆失败，请输入正确的账号';
            return $this->return;
        }

        if (!isset($params['password'])) {
            $this->return['returnCode'] = 302;
            $this->return['returnDesc'] = '登陆失败，请输入用户密码';
            return $this->return;
        }

        $attr['com_id'] = $params['com_id'];
        $member = Member::model()->findByAttributes($attr);
        if ($member) {
            $success = 1;
            if ($member->status != 1) {
                $this->return['returnCode'] = 302;
                $this->return['returnDesc'] = '登陆失败，账号被禁用或删除';
                $success = 0;
            } else if ($member->password != md5(md5($params['password']) . $member->salt)) {
                $this->return['returnCode'] = 302;
                $this->return['returnDesc'] = '登陆失败，密码错误';
                $success = 0;
            } else {
                $loginToken = md5(uniqid($member->userid . '_'));
                $this->return['uid'] = $member->userid;
                $this->return['loginToken'] = $loginToken;

                $memberDetail = MemberDetail::model()->findByPk($member->userid);
                $this->return['userInfo'] = array(
                    'username' => $member->username,
                    'mobile' => $member->mobile,
                    'gender' => $memberDetail->gender,
                    'name' => $memberDetail->name,
                    'avatar' => $memberDetail->getAvatarUrl(),
                    'birthday' => $memberDetail->birthday
                );

                // 跟新登陆表信息
                $member->lastloginip = HmNetwork::getIP();
                $member->lastlogintime = time();
                $member->token = $loginToken;
                $member->token_expired = time() + 2592000;
                $member->logintimes = $member->logintimes + 1;
                $member->save();
            }

            // 加入登陆日志
            $memberLoginLog = new MemberLoginLog();
            $memberLoginLog->userid = $member->userid;
            $memberLoginLog->username = $member->username;
            $memberLoginLog->ip = HmNetwork::getIP();
            $memberLoginLog->time = time();
            $memberLoginLog->succeed = $success;
            $memberLoginLog->save();
        } else {
            $this->return['returnCode'] = 302;
            $this->return['returnDesc'] = '登陆失败，用户不存在';
        }
        return $this->return;
    }

    /**
     * 用户修改个人信息接口
     */
    public function editUserInfo() {
        $params = $this->params;
        if (!isset($params['uid']) || !isset($params['loginToken'])) {
            $this->return['returnCode'] = 200;
            $this->return['returnDesc'] = '参数不全';
            return $this->return;
        }
        $member = Member::model()->findByPk($params['uid']);
        if ($params['loginToken'] != $member->token || $member->token_expired < time()) {
            $this->return['returnCode'] = 303;
            $this->return['returnDesc'] = '登陆过期，请重新登陆';
            return $this->return;
        }

        $member_detail_attr = array();
        if (isset($params['name'])) {
            $member_detail_attr['name'] = $params['name'];
        }
        if (isset($params['gender'])) {
            $member_detail_attr['gender'] = in_array(intval($params['gender']), array(1, 2)) ? $params['gender'] : 0;
        }
        if (isset($params['birthday'])) {
            $member_detail_attr['birthday'] = $params['birthday'];
        }
        if (isset($params['avatar'])) {
            $member_detail_attr['avatar'] = $params['avatar'];
        }

        if (count($member_detail_attr)) {
            // 同步到bsp会员中心
            $bspReturn = $this->updateUserInfo(2);
            if(!$bspReturn['status']) {
                $this->return['returnCode'] = 200;
                $this->return['returnDesc'] = '更新BSP会员中心信息失败。' . $bspReturn['message'];
                return $this->return;
            }
            MemberDetail::model()->updateByPk($member->userid, $member_detail_attr);
        }
        return $this->return;
    }

    public function userResetPassword() {
        $params = $this->params;
        if (!isset($params['uid']) || !isset($params['loginToken']) || !isset($params['password']) || !isset($params['newPassword'])) {
            $this->return['returnCode'] = 200;
            $this->return['returnDesc'] = '参数不全';
            return $this->return;
        }

        $member = Member::model()->findByPk($params['uid']);
        if ($params['loginToken'] != $member->token || $member->token_expired < time()) {
            $this->return['returnCode'] = 303;
            $this->return['returnDesc'] = '登陆过期，请重新登陆';
            return $this->return;
        }

        if ($member->password != md5(md5($params['password']) . $member->salt)) {
            $this->return['returnCode'] = 200;
            $this->return['returnDesc'] = '原密码错误';
            return $this->return;
        }

        if (!$this->checkPassword($params['newPassword'])) {
            $this->return['returnCode'] = 200;
            $this->return['returnDesc'] = '密码在6-16位之间';
            return $this->return;
        }

        $member->password = md5(md5($params['newPassword']) . $member->salt);
        $member->setScenario('resetPassword');

        // 将新密码赋值给变量保存
        $this->params['username'] = $member['username'];
        $this->params['password'] = $params['newPassword'];
        // 同步到bsp会员中心
        $bspReturn = $this->updateUserInfo(2);
        if(!$bspReturn['status']) {
            $this->return['returnCode'] = 200;
            $this->return['returnDesc'] = '更新BSP会员中心密码失败。' . $bspReturn['message'];
            return $this->return;
        }
        if (!$member->save()) {
            $this->return['returnCode'] = 200;
            $this->return['returnDesc'] = '修改密码失败，请稍后重试';
        }

        return $this->return;
    }

    private function checkPassword($password) {
        return strlen($password) >= 6 && strlen($password) <= 12;
    }
    
    /**
     * 获取用户信息
     * @users 用户名数组
     */
    public function getUserInfo($users) {
        $params = array();
        $params['action'] = 'getuserinfo';
        $params['users'] = $users;
        $params['sitename'] = $this->getSiteName();

        $result = @json_decode($this->submitToUms($params), true);
        $userList = isset($result['users'])? $result['users'] : array();
        return $userList;
    }

    /**
     * 更新BSP用户信息
     * @optype 操作类型：1增 2 改 3 删
     */
    public function updateUserInfo($optype) {
        $postData = $this->params;

        $sex = 'X';
        if (isset($postData['gender'])) {
            $sex = ($postData['gender']==2)? '女' : '男';
        }

        $params = array();
        $params['action'] = 'updateuserinfo';
        $params['username'] = isset($postData['username'])? $postData['username'] : 'X';
        $params['password'] = isset($postData['password'])? $postData['password'] : 'X';
        $params['realname'] = isset($postData['name'])? $postData['name'] : 'X';
        $params['emali'] = isset($postData['emali'])? $postData['emali'] : 'X';
        $params['sex'] = $sex;
        $params['birth'] = isset($postData['birthday'])? $postData['birthday'] : '1986-10-10';
        $params['telephone'] = isset($postData['telephone'])? $postData['telephone'] : 'X';
        $params['mobilphone'] = isset($postData['mobile'])? $postData['mobile'] : 'X';
        $params['occupation'] = isset($postData['occupation'])? $postData['occupation'] : 'X';
        $params['address'] = isset($postData['address'])? $postData['address'] : 'X';
        $params['postcode'] = isset($postData['postcode'])? $postData['postcode'] : 'X';
        $params['qq'] = isset($postData['qq'])? $postData['qq'] : 'X';
        $params['favorites'] = isset($postData['favorites'])? $postData['favorites'] : 'X';
        $params['remarks'] = isset($postData['remarks'])? $postData['remarks'] : 'X';
        $params['idnumber'] = isset($postData['idnumber'])? $postData['idnumber'] : 'X';
        $params['education'] = isset($postData['education'])? $postData['education'] : 'X';
        $params['work'] = isset($postData['work'])? $postData['work'] : 'X';
        $params['description'] = isset($postData['description'])? $postData['description'] : 'X';
        $params['optype'] = $optype;
        $params['sitename'] = $this->getSiteName();

        $result = @json_decode($this->submitToUms($params), true);
        return $result;
    }
    
    /**
     * 检查bsp用户是否可用
     * return 1：无该用户，可以使用该用户名；0：存在该用户，不能使用
     */
    public function userCheck($userName = '') {
        $params = array();
        $params['action'] = 'usercheck';
        $params['username'] = $userName;
        $params['sitename'] = $this->getSiteName();

        $result = @json_decode($this->submitToUms($params), true);
        return $result['status'];
    }
    
    public function submitToUms($arrParam){
        // 参数加密
        $config = Config::model()->getConfigs();
        $strParameter = $this->array2Url($arrParam);
        $code = $this->bspEncrypt($strParameter, $config['bsp_ums_key']);

        $params = array();
        $params['appid'] = $config['bsp_ums_appid'];
        $params['code'] = $code;
        $parameter = $this->array2Url($params);

        Yii::import("application.helpers.Ccrul");
        return Ccrul::post($config['bsp_ums_url'].'/BspAppServers', $parameter);
    }

    // 站点名称是指超级用户email
    public function getSiteName(){
        $company = Company::model()->findByPk($this->params['com_id']);
        if ($company) {
            $user = ComUser::model()->findByPk($company->super_uid);
            if ($user) {
                return $user->email;
            }
        }
        // 测试用
        return 'test1@qq.com';
    }

    /************************ bsp加密函数 ******/
    public function bspEncrypt($parameter, $key) {
        return str_replace('+', '-BSP-', $this->encrypt($parameter, $key));
    }

    public function bspDecrypt($parameter, $key) {
        return $this->decrypt(str_replace('-BSP-', '+', $parameter), $key);
    }

    function encrypt($input,$key) {
        $size = mcrypt_get_block_size('des','ecb');  
        $input = $this->pkcs5_pad($input, $size);  
        $td = mcrypt_module_open('des', '', 'ecb', '');  
        $iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);  
        @mcrypt_generic_init($td, $key, $iv);  
        $data = mcrypt_generic($td, $input);  
        mcrypt_generic_deinit($td);  
        mcrypt_module_close($td);  
        $data = base64_encode($data);  
        return $data;  
    }

    function decrypt($encrypted,$key) {
        $encrypted = base64_decode($encrypted);  
        $td = mcrypt_module_open('des','','ecb','');
        //使用MCRYPT_DES算法,cbc模式  
        $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);  
        $ks = mcrypt_enc_get_key_size($td);  
        @mcrypt_generic_init($td, $key, $iv);  
        //初始处理                 
        $decrypted = mdecrypt_generic($td, $encrypted);  
        //解密  
        mcrypt_generic_deinit($td);  
        //结束               
        mcrypt_module_close($td);  
        $y=$this->pkcs5_unpad($decrypted);  
        return $y;     
    }

    function pkcs5_pad ($text, $blocksize) {         
        $pad = $blocksize - (strlen($text) % $blocksize);  
        return $text . str_repeat(chr($pad), $pad);  
    }

    function pkcs5_unpad($text) {         
        $pad = ord($text{strlen($text)-1});  
        if ($pad > strlen($text))  
        {  
           return false;  
        }  
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)  
        {  
          return false;  
        }  
        return substr($text, 0, -1 * $pad);  
    }

}
