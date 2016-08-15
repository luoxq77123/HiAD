<?php

/**
 * 用户个人信息控制器
 */
class PersonalController extends BaseController {

    public function beforeAction($action) {
        return $this->checkLogin();
    }

    /**
     * 编辑个人信息 
     */
    public function actionEditInfo() {
        $user = Yii::app()->session['user'];
        $userInfo = User::model()->find('uid=:uid', array(':uid' => $user['uid']));
        if (isset($_POST['User'])) {
            $return = array('code' => -1, 'message' => '修改失败,请稍后再试！');
            if ($userInfo) {
                $userInfo->name = $_POST['User']['name'];
                $userInfo->cellphone = $_POST['User']['cellphone'];
                $userInfo->qq = $_POST['User']['qq'];
                if ($userInfo->save()) {
                    $return['code'] = 1;
                    $return['message'] = '修改成功！';
                }
            }
            die(json_encode($return));
        }

        $this->renderPartial('editInfo', array('userInfo' => $userInfo));
    }

    /**
     * 重置密码 
     */
    public function actionResetPassword() {
        $user = Yii::app()->session['user'];

        if (isset($_POST['Pwd'])) {
            $return = array('code' => -1, 'message' => '修改失败,请稍后再试！');
            $userInfo = User::model()->find('uid=:uid', array(':uid' => $user['uid']));
            if (User::model()->hashPassword($_POST['Pwd']['oldpwd'], $userInfo->salt) === $userInfo->password) {
                $userInfo->password = User::model()->hashPassword($_POST['Pwd']['newpwd'], $userInfo->salt);
                if ($userInfo->save()) {
                    $return['code'] = 1;
                    $return['message'] = "修改成功！";
                } else {
                    $return['message'] = "网络忙,请稍后再试";
                }
            } else {
                $return['message'] = "请输入正确的原密码！";
            }
            die(json_encode($return));
        }
        $this->renderPartial('resetPassword');
    }

    /**
     * 我的权限 
     */
    public function actionMyPurview() {
        $user = Yii::app()->session['user'];
        $roles = Role::model()->getRoles();
        $company = Company::model()->getComById($user['com_id']);
        if (in_array($company['super_uid'], (array) $user['uid'])) {
            $role_id = 1;
        } else {
            $role_id = $user['role_id'];
        }
        if (isset($roles[$role_id]['id'])) {
            $role_name = $roles[$role_id]['name'];
            $role_description = $roles[$role_id]['description'];
        } else {
            $role_name = '--';
            $role_description = '--';
        }
        $data = array('email' => $user['email'], 'role_name' => $role_name, 'role_description' => $role_description);
        $this->renderPartial('myPurview', $data);
    }

    public function actionIndex() {
        $this->renderPartial('index');
    }

}