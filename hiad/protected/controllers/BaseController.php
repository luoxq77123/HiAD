<?php

/**
 * 基础controller
 */
class BaseController extends CController {

    public function beforeAction($action) {
        parent::beforeAction($action);
        
        $this->checkPurview();
        return true;
    }
    
    public function checkPurview(){
        $this->checkLogin();
        
        $controller = $this->getId();
        $action = $this->getAction()->id;
        $status = Yii::app()->authority->checkAca($controller, $action);
        if($status == Yii::app()->authority->getStatus('FAILED')){
            $message = '当前用户无权限进行此操作。';
            if(Yii::app()->request->isPostRequest && Yii::app()->request->isAjaxRequest){
                echo json_encode(array('code' => -102, 'message' => $message));
            }else if(Yii::app()->request->isAjaxRequest){
                echo '<div class="error_message">'.$message.'</div>';
            }else{
                echo '<div class="error_message">'.$message.'</div>';
            }
            exit;
        }
        return true;
    }
    
    public function checkLogin(){
        if(Yii::app()->authority->isLogin()  == Yii::app()->authority->getStatus('NOTLOGIN')){
            $url = $this->createUrl('user/login');
            if(Yii::app()->request->isPostRequest && Yii::app()->request->isAjaxRequest){
                echo json_encode(array('code' => -101, 'message' => '用户未登录。', 'callback' => 'window.location="'.$url.'";'));
            }else if(Yii::app()->request->isAjaxRequest){
                echo '<script language="javascript">window.location="'.$url.'";</script>';
            }else {
                $this->redirect($url);
            }
            exit;
        }
        return true;
    }
}