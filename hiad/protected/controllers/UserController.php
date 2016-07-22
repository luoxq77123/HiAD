<?php

/**
 * 用户登陆控制器
 */
class UserController extends CController {

    /**
     * 用户登陆
     */
    public function actionLogin() {
        if (isset(Yii::app()->session['user'])) {
            $this->redirect($this->createUrl('backend/index'));
            exit;
        }
        $form = new LoginForm;
        if (isset($_POST['LoginForm'])) {
            $return = array('code' => 1, 'message' => '');
            // 收集用户输入的数据 
            $form->attributes = $_POST['LoginForm'];
            // 验证用户输入，如果无效则重定位到前个页面
            if ($form->validate()) {
                $company = Company::model()->getComById($form->userInfo->com_id);
                $role_id = $form->userInfo->uid == $company['super_uid'] ? 'super' : $form->userInfo->role_id;

                Yii::app()->session['user'] = array(
                    'uid' => $form->userInfo->uid,
                    'email' => $form->userInfo->email,
                    'name' => $form->userInfo->name,
                    'role_id' => $role_id,
                    'department_id' => $form->userInfo->department_id,
                    'com_id' => $form->userInfo->com_id,
                );
                $return['message'] = '您已经登录成功，页面将转向首页！';
                $return['url'] = $this->createUrl('backend/index');
            } else {
                $return['code'] = -1;
                foreach ($form->errors as $one) {
                    $return['message'] .= join("\n", $one);
                    $return['message'] .= "\n";
                }
            }
            die(json_encode($return));
        }
		if(strstr('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], 'http://ad.superqr.cn')) {
            $redirectUrl = Yii::app()->request->baseUrl.'/qirui/index.html';

            $this->redirect($redirectUrl);
            exit;
        }
		
        // 显示登陆表单 
        $this->layout = false;
        $this->render('login', array('model' => $form));
    }

    /**
     * 用于临时登陆-展会版
     */
    public function actionDemoLogin() {
        $redirectUrl = 'http://media.vms.sobeycache.com/birtv2013/login.html';
        if (isset(Yii::app()->session['user'])) {
            $this->redirect($this->createUrl('backend/index'));
            exit;
        }
        $return = array('code' => 1, 'message' => '');
        if (isset($_POST['hiad']) && isset($_POST['hiad']['email']) && isset($_POST['hiad']['password'])) {
            $post = $_POST['hiad'];
            $user = User::model()->findByAttributes(array('email' => $post['email']));
            if (!$user) {
                $redirectUrl .= '?type=hiad&code=1&message='.urlencode('用户名不存在');
                $this->redirect($redirectUrl);
            } else if ($user->password != $user->hashPassword($post['password'], $user->salt)){
                $redirectUrl .= '?type=hiad&code=2&message='.urlencode('密码不正确');
                $this->redirect($redirectUrl);
            } else {
                $company = Company::model()->getComById($user->com_id);
                $role_id = $user->uid == $company['super_uid'] ? 'super' : $user->role_id;
                Yii::app()->session['user'] = array(
                    'uid' => $user->uid,
                    'email' => $user->email,
                    'name' => $user->name,
                    'role_id' => $role_id,
                    'department_id' => $user->department_id,
                    'com_id' => $user->com_id
                );
                $this->redirect($this->createUrl('backend/index'));
            }
        } else {
            $redirectUrl .= '?type=hiad&code=3&message='.urlencode('参数不正确');
            $this->redirect($redirectUrl);
        }
    }
    
    /**
     * 莫云用户 
     */
    public function actionLoginMoYun() {
        $form = new LoginForm;
        if (isset($_POST['LoginForm'])) {
            $return = array('code' => 1, 'message' => '');
            // 收集用户输入的数据 
            $form->attributes = $_POST['LoginForm'];
            // 验证用户输入，如果无效则重定位到前个页面 
            if ($form->validate()) {
                $company = Company::model()->getComById($form->userInfo->com_id);
                $role_id = $form->userInfo->uid == $company['super_uid'] ? 'super' : $form->userInfo->role_id;

                Yii::app()->session['user'] = array(
                    'uid' => $form->userInfo->uid,
                    'email' => $form->userInfo->email,
                    'name' => $form->userInfo->name,
                    'role_id' => $role_id,
                    'department_id' => $form->userInfo->department_id,
                    'com_id' => $form->userInfo->com_id,
					'app_id' => 1
                );
                
            } else {
                $return['code'] = -1;
                foreach ($form->errors as $one) {
                    $return['message'] .= join("\n", $one);
                    $return['message'] .= "\n";
                }
            }
            $this->redirect($this->createUrl('backend/index'));
        }
        // 显示登陆表单 
        $this->layout = false;
        $this->render('login', array('model' => $form));
    }
	
	/**
     * 七瑞用户 
     */
    public function actionLoginQiRui() {
        if (isset(Yii::app()->session['user'])) {
            $return['code'] = 1;
            $return['message'] = '您已经登录成功，页面将转向首页！';
            $return['url'] = $this->createUrl('backend/index');
            die(json_encode($return));
            exit;
        }
        $form = new LoginForm;
        if (isset($_POST['LoginForm'])) {
            $return = array('code' => 1, 'message' => '');
            // 收集用户输入的数据 
            $form->attributes = $_POST['LoginForm'];
            // 验证用户输入，如果无效则重定位到前个页面 
            if ($form->validate()) {
                $company = Company::model()->getComById($form->userInfo->com_id);
                //$role_id = $form->userInfo->uid == $company['super_uid'] ? 'super' : $form->userInfo->role_id;

                Yii::app()->session['user'] = array(
                    'uid' => $form->userInfo->uid,
                    'email' => $form->userInfo->email,
                    'name' => $form->userInfo->name,
                    'role_id' => 1,
					'app_qirui' => 7,//表示七瑞
                    //'department_id' => $form->userInfo->department_id,
                    'com_id' => $form->userInfo->com_id
                );
                $return['message'] = '您已经登录成功，页面将转向首页！';
                $return['url'] = $this->createUrl('backend/index');
            } else {
                $return['code'] = -1;
                foreach ($form->errors as $one) {
                    $return['message'] .= join("\n", $one);
                    $return['message'] .= "\n";
                }
            }
            die(json_encode($return));
        }
    }

    /**
     * 用户登出 
     */
    public function actionLogout() {
        unset(Yii::app()->session['user']);
        $this->redirect($this->createUrl('user/login'));
    }

}