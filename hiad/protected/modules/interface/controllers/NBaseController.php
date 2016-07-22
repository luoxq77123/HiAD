<?php

/**
 * 基础controller
 */
class NBaseController extends CController {

    public $app;
    public $key;
    public $params = array();
    public $return = array('returnCode' => 100, 'returnDesc' => '成功');

    function __construct($id, $module = null) {
        parent::__construct($id, $module);
        
    }

    public function checkPublicParams($interfaces) {
        $pass = FALSE;
        $this->return['returnCode'] = 200;
        if (!Yii::app()->request->isPostRequest) {
            $this->return['returnDesc'] = '请求方式错误';
        } else if (!isset($_POST['method']) || !isset($_POST['parameter'])) {
            $this->return['returnDesc'] = '接口参数错误';
        } else if (!array_key_exists($_POST['method'], $interfaces)) {
            $this->return['returnDesc'] = '接口名错误';
        } else if (!$this->app) {
            $this->return['returnDesc'] = '应用ID无效';
        } else if (!isset($this->params['appKey']) || $this->params['appKey'] != $this->app->appkey) {
            $this->return['returnDesc'] = 'appKey参数错误';
        } else if (!is_array($this->params) || count($this->params) < 1) {
            $this->return['returnDesc'] = '应用密钥无效';
        } else if (!isset($this->params['unique']) || !preg_match('/^([0-9a-f]){12}$/', $this->params['unique'])) {
            $this->return['returnDesc'] = '缺少客户端唯一码参数，或唯一参数错误';
        } else if (!isset($this->params['time'])) {
            $this->return['returnDesc'] = '缺少时间参数';
        } else if (!isset($this->params['terminalType'])) {
            $this->return['returnDesc'] = '缺少终端类型参数';
        } else {
            $this->return['returnCode'] = 100;
            $pass = TRUE;
        }
        return $pass;
    }

    //注册用户
    public function Registration($params){
        $email = $params['siteEmail'];
        $com_user_info = User::model()->findByAttributes(array('email'=>$email));
        if($com_user_info){ 

        }
        if(!$com_user_info&&isset($params['isself'])){    //如果用户不存在那么创建用户
            ob_start();
            Yii::app()->runController('/interface/bspUser/userRegister');
            $result = ob_get_contents();
            ob_end_clean();
            if(isset($params['isself'])){ 
                ob_start();
                Yii::app()->runController('/interface/bspUser/userRegister');
                $result = ob_get_contents();
                ob_end_clean();
            }else{ 
                $params['isself'] = 1;
                $this->Registration($params);
            }
            //$co = Yii::app()->createController('bspUser/userRegister');
            //list($controller, $action) = $co;
            //$return = $controller->userRegister();
        }

    }

    /**
     * 数组参数转url字符串
     */
    public function array2Url($array = null) {
        $url = "";
        if (!empty($array)) {
            foreach ($array as $k => $v) {
                $url .= $k . '=' . $v . '&';
            }
            $url = substr($url, 0, -1);
        }
        return $url;
    }
}
