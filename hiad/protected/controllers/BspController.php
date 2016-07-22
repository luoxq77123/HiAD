<?php

/**
 * BSP控制器
 */
class BspController extends CController {

    private $_result;
    public $key;
    public function __construct() {

        // $config = Config::model()->getConfigs();
        // $key = $config['bsp_key'];

        // gq****//
        $this->key ='4F101363';


$param = array(
            "appId"=>1,
            "appName"=>"hicms",
            "siteEmail"=>"hicms@163.com",
            "siteName"=>"163.com",
            "sitePassword"=>"123456",
            "menuprivs"=> "1,3,4",
            "action"=> "addOrUpdateUser",
            "params"=>array(
                "method"=>1,
                "email"=>"hicms@qq.com",
                "userName"=> "admin",
                "password"=>"add",
                "createTime"=>"2014-05-23 12:00:00",
                "roleId"=> 2,
                "roleCode"=> "add",
                 "type"=>2,
            )
        );





            // $param = json_encode($param);
            // echo $param;
            // $a = $this->bspEncrypt($param, $this->key);
            // p($a);
            // die();
        // gq*****//
        // $this->key = '70B030DC';
        // 解析参数
        $params = json_decode($this->bspDecrypt($_REQUEST['code'], $this->key), true);
        // p($params);die();

        // gq****
        //if(isset($params['siteEmail'])&&isset($params['sitePassword'])){
        //     $this->Registration($params);
        //}
        //gq***
        if(isset($params['action'])) {
            switch($params['action']) {
                case 'login':
                    $this->longin($params);
                    break;
                case 'register':
                    if (!isset($params['optype']) || $params['optype']==1) {
                        $this->register($params);

                    } else if ($params['optype']==2) {
                        $this->modifyAccount($params);

                    } else if ($params['optype']==3) {
                        $this->delAccount($params);

                    }
                    break;
                case 'conn':
                    $this->conn($params);
                    break;
                case 'resetkey':
                    $this->resetkey($params);
                    break;
                case 'bsplogout':
                    $this->bsplogout($params);
                    break;
                case 'bsplogback':
                    $this->bsplogback($key);
                    break;
                case 'getCatalogPrivs':           //5.2 获取栏目管理权限
                    $this->getCatalogPrivs($params);
                    break;
                case 'getMenuPrivs':      //5.1 获取菜单和管理权限
                    $this->getMenuPrivs($params);
                    break;
                case 'addOrUpdateRole':
                    $this->addOrUpdateRole($params);
                    break;
                case 'deleteRole':
                    $this->deleteRole($params);
                    break;
                case 'addOrUpdateUser':
                    $this->addOrUpdateUser($params);
                    break;
                case 'deleteUser':
                    $this->deleteUser($params);
                    break;
                case 'getRoleInfo':   //获取所有角色信息
                    $this->getRoleInfo($params);
                    break;
            }
        }

        exit;
    }

    public function actionIndex(){

    }

    public function getRoleInfo($params){

        $result = array(
            'appId'        =>$params['appId'],
            'appName'      =>$params['appName'],
            'siteEmail'    =>$params['siteEmail'],
            'siteName'     =>$params['siteName'],
            'sitePassword' =>$params['sitePassword'],
            'menuprivs'    =>$params['menuprivs'],
            'action'       =>$params['action'],
            'status'       =>1,
            'message'      =>'成功',
            'roles'        =>array(),

        );
        $sql = "select t1.name,t1.id,t3.controller,t3.action from {{role}} as t1 join {{role_aca}} as t2 on t1.id = t2.role_id  join {{aca}} as t3 on t2.aca_id = t3.id";
        $re = Yii::app()->db->createCommand($sql)->queryAll();

        $menu_infos = Menu::model()->findAll(
            array(
                'select'=>'id,route',
            )
        );
        $menu_infos = to_array($menu_infos);

        $re_info = array();
        foreach($re as $k=>$v){
            foreach($menu_infos as $a){
                $route = $v['controller'].'/'.$v['action'];
                if($route == $a['route']){
                    $re_info[$v['name']]['roleName'] = $v['name'];
                    $re_info[$v['name']]['roleId'] = $v['id'];
                    $re_info[$v['name']]['roleCode'] = '';
                    $re_info[$v['name']]['roleNote'] = '';
                    $re_info[$v['name']]['menuPrivs'][]= $a['id'];

                }
            }
        }
        sort($re_info);
        $re_result = array();
        foreach($re_info as $k=>$v){
            foreach($v as $a=>$b){
                if($a=='menuPrivs'){
                    // p($b);
                    $cc = implode(',',array_unique($b));
                    $re_result[$k][$a]=$cc;
                }else{
                    $re_result[$k][$a]=$b;

                }

            }

        }
        // p($re_result);

        $result['roles'] = $re_result;
        echo json_encode($result);
    }
    /**
     * [deleteUser 删除用户]
     */
    public function deleteUser($params){

        $User = User::model()->findByAttributes(array('email' => $params['email']));
        $result = array(
            "appId"     => $params['appId'], 
            "appName"   =>$params['appName'], 
            "siteEmail" => $params['siteEmail'],
            "email"     =>$params['email'],
            "status"    =>1,
            "message"   => "删除成功",

        );
        if ($User) {
            // 超级管理员应先删除公司 后删除账号
            if ($params['type'] == 1) {
                $company = Company::model()->findByPk($User->com_id);
                if ($company) {
                    $company->delete();
                }
            }
            if ($User->delete()) {
                echo json_encode($result);
                die();
            }else{
                    $result['status'] = 0;
                    $result['message']= '删除用户失败';
                    echo json_encode($result);
                    die();
            }
        } else {
            $result['status'] = 0;
            $result['message']= '用户不存在,或已删除';
            echo json_encode($result);
            die();
        }

    }

    /**
     * [addOrUpdateUser 新建或更新用户]
     */
    public function addOrUpdateUser($params,$login = 1){
        $param = $params['params'];
        var_dump($param);exit;
        $result = array(
          "appId"     =>$params['appId'],
          "appName"   => $params['appName'],
          "siteEmail" => $params['siteEmail'],
          "method"    =>isset($param['method'])?$param['method']:$login,
          "status"    =>1,
          "message"   =>"新增成功"
        );
        if(@$params['params']['method']==1||$login==2){
            /* 根据用户类型处理 
             * 0:admin是bsp管理员 不处理
             * 1:站点管理员
             * 2:站点普通管理员
             */
            if ($param['type'] == 0) {
                $this->_result = 0;
            } else if ($param['type'] == 1) {
                // 新建公司
                $company = new Company();
                $company->name = $params['siteEmail'];
                $company->intro = '';
                $company->super_uid = 0;
                $company->status = 1;
                $company->expiration_date = date("Y-m-d", time()+3*365*86400);
                $company->createtime = strtotime($param['createTime']);
                if ($company->save()) {

                    $user = User::model()->findByAttributes(array('email' => $param['email']));
                    if($user){
                        if($login==2){
                            $result['method'] = 2;
                            $result['status'] = 0;
                            $result['message'] = '用户已存在';
                            return $result;
                            die();
                        }else{
                            $result['method'] = 2;
                            $result['status'] = 0;
                            $result['message'] = '用户已存在';
                            echo json_encode($result);
                            die();
                        }
                    }
                    // 新建超级管理员账号
                    $User = new User();
                    $salt = $User->generateSalt();
                    $User->name = $param['userName'];
                    $User->email = $param['email'];
                    $User->salt = $salt;
                    $User->password = md5(md5($param['password']) . $salt);
                    $User->com_id = $company->id;
                    $User->role_id = $param['roleId'];
                    $User->status = 1;
                    $User->createtime = strtotime($param['createTime']);
                    if ($User->validate() && $User->save()) {
                        // 更新公司超级管理员id
                        $company->super_uid = $User->uid;
                        $company->save();
                        if($login==2){
                            return $result;
                        }else{
                            echo json_encode($result);
                            die();
                        }
                    }else{
                        $result['message'] = '保存失败';
                        $result['status'] = 0;
                        if($login==2){
                            return $result;
                        }else{
                            echo json_encode($result);
                            die();
                        }
                    }
                }
            } else if ($param['type'] == 2) {

                // 获取超级管理员信息
                $admin = User::model()->findByAttributes(array('email' => $params['siteEmail']));
                if ($admin) {
                    $user = User::model()->findByAttributes(array('email' => $param['email']));
                    if($user){
                        if($login==2){
                            $result['method'] = 2;
                            $result['status'] = 0;
                            $result['message'] = '用户已存在';
                            return $result;
                            die();
                        }else{
                            $result['method'] = 2;
                            $result['status'] = 0;
                            $result['message'] = '用户已存在';
                            echo json_encode($result);
                            die();
                        }
                    }
                    // 新建超级管理员账号
                    $User = new User();
                    $salt = $User->generateSalt();
                    $User->name = $param['userName'];
                    $User->email = $param['email'];
                    $User->salt = $salt;
                    $User->password = md5(md5($param['password']) . $salt);
                    $User->com_id = $admin->com_id;
                    $User->role_id = $param['roleId'];
                    $User->status = 1;
                    $User->createtime = strtotime($param['createTime']);
                    if ($User->validate() && $User->save()) {
                        if($login==2){
                            $result['method'] = 2;
                            return $result;
                        }else{
                            $result['method'] = 2;
                            echo json_encode($result);
                            die();
                        }

                    }else{
                        if($login==2){
                            $result['method'] = 2;
                            $result['status'] = 0;
                            $result['message'] = '保存失败';
                            return $result;
                        }else{
                            $result['method'] = 2;
                            $result['status'] = 0;
                            $result['message'] = '保存失败';
                            echo json_encode($result);
                            die();
                        }
                    }
                }else{
                    // 新建普通用户 2014-08-19
                    $User = new User();
                    $salt = $User->generateSalt();
                    $User->name = $param['userName'];
                    $User->email = $param['email'];
                    $User->salt = $salt;
                    $User->password = md5(md5($param['password']) . $salt);
                    $User->com_id = $admin->com_id;
                    $User->role_id = $param['roleId'];
                    $User->status = 1;
                    $User->createtime = strtotime($param['createTime']);

                    $menuprivs = $params['menuprivs'];
                    $menu_model = Menu::model();
                    $aca_model = Aca::model();
                    $menu_infos = $menu_model->findAll(
                        array(
                            'condition' => "id in ($menuprivs)",
                        )
                    );

                    foreach ($menu_infos as $k => $v) {
                        $condition = explode('/',$v->route);
                        $aca_info = $aca_model->findByAttributes(array('controller'=>$condition[0],'action'=>$condition[1]));

                        $roleAca_model = new RoleAca();
                        $roleAca_model->role_id = $param['roleId'];
                        $roleAca_model->aca_id = $aca_info->id;

                        if(!$roleAca_model->save()){
                            $result['message'] = '新增失败';
                            $result['status'] = 0;
                            echo json_encode($result);
                            die();
                        }
                    }

                    if($User->save()){
                        echo json_encode($result);
                        die();
                    }else{
                        $result['message'] = '新增失败';
                        $result['status'] = 0;
                        echo json_encode($result);
                        die();
                    }
                }

            }

        }elseif($params['params']['method']==2){//更新用户
            $User = User::model()->findByAttributes(array('email' => $param['email']));
            if (!$User) {
                return $this->register($params);
            } else {
                if (!empty($params['userName'])) {
                    $User->name = $params['userName'];
                }
                if (!empty($params['password'])) {
                    $User->salt = User::model()->generateSalt();
                    $User->password = md5(md5($params['password']) . $User->salt);
                }
                if ($User->save()) {
                    $result['message'] = '编辑成功';
                    $result['status'] = 1;
                    echo json_encode($result);
                    die();
                }else{
                    $result['message'] = '编辑失败';
                    $result['status'] = 0;
                    echo json_encode($result);
                    die();
                }
            }
        }
    }


    //验证用户是否存在，如果不存在那么  注册用户
    public function Registration($params){
        $email = $params['siteEmail'];
        $com_user_info = User::model()->findByAttributes(array('email'=>$email));
        if(!$com_user_info){    //如果用户不存在那么创建用户
            $co = Yii::app()->createController('interface/bspUser/userRegister');
            list($controller, $action) = $co;

            $return = $controller->$action();
        }elseif($com_user_info->password == $com_user_info->hashPassword($params['sitePassword'], $user->salt)){
            $result = array(
                'message'=>'密码错误',
                'status'=>'0',
            );
            echo json_encode($result);
            die();
        }
    }
    // 删除角色
    public function deleteRole($params){
        // p($params);
        $result = array(
            "appId"     =>$params['appId'],
            "appName"   =>$params['appName'],
            "siteEmail" =>$params['siteEmail'],
            "roleId"    =>$params['roleId'],
            "roleCode"  =>"",
            "status"    =>1,
            "message"   =>"返回消息"
        );

        //*********验证是否有权限删除角色**********//
/*        //获取删除权限的id
        $aca_model = Aca::model();
        $aca_id = $aca_model->findByAttributes(array('controller'=>'role','action'=>'del'))->id;
        //获取能那些角色有删除角色的权限
        $roleAca_model = RoleAca::model();
        $role_infos = $roleAca_model->findAll(
            array(
                'condition'=>'aca_id='.$aca_id,  //查找条件
            )
        );
        $arr = array();     //得到那些角色id有删除角色的权限数组
        foreach($role_infos as $v){
            $arr[]=$v->role_id;
        }
        //查找发送过来的用户的角色id
        $com_user_model = User::model();
        $com_user_info = $com_user_model->findByAttributes(array('email'=>$params['siteEmail']));

        if(!in_array($com_user_info->role_id, $arr)){  //判断
            $result['status'] = '0';
            $result['message'] = '此用户没有权限删除角色';
            echo json_encode($result);
            die();
        }*/


        // 要删除的角色数组
        $ids = explode(',',$params['roleId']);
        $role_model = Role::model();
        foreach($ids as $v){
            $role_infos = $role_model->findByPk($v);
            if($role_infos){
                if(!$role_infos->delete()){
                    $result['status'] = '0';
                    $result['message'] = '删除角色失败';
                    echo json_encode($result);
                    die();
                }
            }else{
                $result['status'] = '0';
                $result['message'] = '角色不存在，或已经被删除';
                echo json_encode($result);
                die();
            }

        }
        $result['status'] = '1';
        $result['message'] = '删除成功';
        echo json_encode($result);
        die();
    }

    //新增或更新角色
    public function addOrUpdateRole($params){
        header("Content-type:text/html;charset=utf-8");
        // $com_id = User::model()->findByAttributes(array('email'=>$params['siteEmail']))->com_id;     //根据email获取com_id;
        $param = $params['params'];
        //***
            // p($params);die();
        //***
        $result = array(
            "appId"     => $params['appId'],
            "appName"   =>$params['appName'],
            "siteEmail" =>$params['siteEmail'],
            "method"    =>1,   //1表示新增；2表示更新
            "status"    =>1,   //1操作成功；0 失败；
            "message"   =>"返回消息"
        );

        $role_model = Role::model();
        $role_info = $role_model->findByPk($param['roleId']);
        if($param['method']==1){                  //新建角色
            if($role_info){                     //如果存在覆盖
                $role_info->name = $param['roleName'];
                if($role_info->save()){         //保存角色
                    //managePrivs 权限
                    $managePrivs = explode(',',$param['managePrivs']);
                    foreach($managePrivs as $v){     // hm_role_aca中保存权限关系
                        $roleAca_model = new RoleAca();
                        $roleAca_model->role_id = $param['roleId'];
                        $roleAca_model->aca_id = $v;
                        if(!$roleAca_model->save()){
                            $result['status'] = 0;
                            $result['message'] = 'managePrivs 权限保存失败';
                            echo json_encode($result);
                            die();
                        }
                    }
                    //menuPrivs  权限
                    $menu_model = Menu::model();
                    $aca_model = Aca::model();
                    $menuPrivs = explode(',',$param['menuPrivs']);
                    foreach($menuPrivs as $v){
                        $roleAca_model = new RoleAca();
                        $menu_info = $menu_model->findByPk($v);
                        $condition = explode('/',$menu_info->route);
                        $aca_info = $aca_model->findByAttributes(array('controller'=>$condition[0],'action'=>$condition[1]));

                        $aca_id = $aca_info->id;
                        $roleAca_model->role_id = $param['roleId'];
                        $roleAca_model->aca_id = $aca_info->id;
                        if(!$roleAca_model->save()){
                            $result['status'] = 0;
                            $result['message'] = 'menuPrivs 权限 保存失败';
                            echo json_encode($result);
                            die();
                        }
                    }
                    $result['status'] = 1;
                    $result['message'] = '操作成功';
                    echo json_encode($result);
                    die();
                }
            }else{          //如果不存在新建
                $role_model = new Role();
                $role_model->id = $param['roleId'];
                $role_model->name = $param['roleName'];
                // $role_model->com_id = $com_id;  //com_id
                if($role_model->save()){
                    //managePrivs 权限
                    $managePrivs = explode(',',$param['managePrivs']);
                    foreach($managePrivs as $v){     // hm_role_aca中保存权限关系
                        $roleAca_model = new RoleAca();
                        $roleAca_model->role_id = $param['roleId'];
                        $roleAca_model->aca_id = $v;
                        if(!$roleAca_model->save()){
                            $result['status'] = 0;
                            $result['method'] = 2;
                            $result['message'] = 'managePrivs 权限 保存失败';
                            echo json_encode($result);
                            die();
                        }
                    }

                    //menuPrivs  权限
                    $menu_model = Menu::model();
                    $aca_model = Aca::model();
                    $menuPrivs = explode(',',$param['menuPrivs']);
                    foreach($menuPrivs as $v){
                        $roleAca_model = new RoleAca();
                        $menu_info = $menu_model->findByPk($v);

                        $condition = explode('/',$menu_info->route);
                        $aca_info = $aca_model->findByAttributes(array('controller'=>$condition[0],'action'=>$condition[1]));
                        $aca_id = $aca_info->id;
                        $roleAca_model->role_id = $param['roleId'];
                        $roleAca_model->aca_id = $aca->id;
                        if(!$roleAca_model->save()){
                            $result['status'] = 0;
                            $result['method'] = 2;
                            $result['message'] = 'menuPrivs 权限 保存失败';
                            echo json_encode($result);
                            die();
                        }
                    }
                    $result['status'] = 1;
                    $result['method'] = 2;
                    $result['message'] = '操作成功';
                    echo json_encode($result);
                    die();
                }
            }

        }elseif($param['method']==2){ //编辑角色

            $role_info->name = $param['roleName'];
            if($role_info->save()){
                //managePrivs 权限
                $managePrivs = explode(',',$param['managePrivs']);
                foreach($managePrivs as $v){     // hm_role_aca中保存权限关系
                    $roleAca_model = new RoleAca();
                    $roleAca_model->role_id = $param['roleId'];
                    $roleAca_model->aca_id = $v;
                    if(!$roleAca_model->save()){
                        $result['status'] = 0;
                        $result['method'] = 2;
                        $result['message'] = 'managePrivs 权限 保存失败';
                        echo json_encode($result);
                        die();
                    }
                }
                //menuPrivs  权限
                $menu_model = Menu::model();
                $aca_model = Aca::model();
                $menuPrivs = explode(',',$param['menuPrivs']);
                foreach($menuPrivs as $v){
                    $roleAca_model = new RoleAca();
                    $menu_info = $menu_model->findByPk($v);
                    $condition = explode('/',$menu_info->route);
                    $aca_info = $aca_model->findByAttributes(array('controller'=>$condition[0],'action'=>$condition[1]));
                    $aca_id = $aca_info->id;
                    $roleAca_model->role_id = $param['roleId'];
                    $roleAca_model->aca_id = $aca->id;
                    if(!$roleAca_model->save()){
                        $result['status'] = 0;
                        $result['method'] = 2;
                        $result['message'] = 'menuPrivs 权限 保存失败';
                        echo json_encode($result);
                        die();
                    }
                }
                $result['status'] = 1;
                $result['method'] = 2;
                $result['message'] = '操作成功';
                echo json_encode($result);
                die();
            }
        }

    }

    /**
     * [getMenuPrivs 获取菜单和管理权限]
     * @param  [type] $params [要转换的数组]
     * @return [type]         [返回转换后的数组]
     */
    public function getMenuPrivs($params){
        $appId = intval($params['appId']);
        $email = $params['siteEmail'];
        $menuPrivs = Yii::app()->db->CreateCommand()
        ->select('id,name,parentids,parent_id as parentId,sort as orderFlag')
        ->from("{{menu}}")
        // ->order('id desc')
        ->queryAll();
        $menuPrivs = $this->compose_arr($menuPrivs,'parentids');
        $managePrivs = Yii::app()->db->CreateCommand()
        ->select('id,name')
        ->from("{{aca}}")
        // ->order('id desc')
        ->queryAll();
        $managePrivs =array();
        $arr = array();
        foreach($managePrivs as $k=>$v){
            foreach($v as $key=>$val){
                $arr[$k][$key]=$val;
                $arr[$k]['code']='';
                $arr[$k]['alias']='';
            }
        }
        $return = array(
            'appId'       =>$appId,
            'siteName'    =>$email,
            'status'      =>'1',
            'message'     =>'获取栏目信息成功',
            'menuPrivs'   =>$menuPrivs,
            'managePrivs' =>$arr,
        );
        echo Json_encode($return);
    }


/**
 * [compose_arr 转换层级]
 * @param  [type] $menuPrivs [传递转换之前的数组]
 * @param  [type] $str       [传递层级的 键名 ，要转换层级的]
 * @return [type]            [返回转换后的数组]
 */
    public function compose_arr($menuPrivs,$str){

        // p($menuPrivs);
        // echo $str;die();
        $arr =array();
        foreach($menuPrivs as $k=>$v){
            foreach($v as $key=>$val){
                if($key==$str){
                    $level = explode(',',$v[$str]);
                    if($level[0]==0||empty($level[0])){
                        $arr[$k]['level']=1;
                    }else{
                        $arr[$k]['level']=count($level)+1;
                    }
                    $arr[$k][$key]=$v[$key];
                    unset($arr[$k][$str]);
                }else{
                    $arr[$k][$key]=$v[$key];
                }
            }
        }
        return $arr;
    }
    /**
     * [getCatalogPrivs  获取栏目管理权限]
     * @param  [type] $params [参数]
     * @return [type]         [返回json格式]
     */
    public function getCatalogPrivs($params){
        $email = $params['siteEmail'];
        $where = "id in (".trim($params['menuprivs'],',').")";
        $select = 't2.id,t2.name,t2.parent_id,t2.parentids';
        $arr_re=Yii::app()->db->createCommand()
        ->select($select)
        ->from("{{catalog}} as t2")
        ->where($where)
        ->queryAll();
        if(!count($arr_re)>0){
            $return = array(
                'appid'        =>$params['appId'],
                'siteName'     =>$email,
                'status'       =>'2',
                'message'      =>'栏目为空。。。',
                'catalogPrivs' =>array(),
            );
            return json_encode($return);
        }else{
            $arr =array();

            foreach($arr_re as $k=>$v){
                $parentids = explode(',',$v['parentids']);
                if($parentids[0]==0||empty($parentids[0])){
                    $arr[$k]['level']=1;
                }else{
                    $arr[$k]['level']=count($parentids)+1;
                }
                $arr[$k]['name']      = $v['name'];
                $arr[$k]['parentId']  = empty($v['parentids']) ? 0 : $v['parentids'];
                $arr[$k]['id']        = $v['id'];
                $arr[$k]['innerCode'] = '';
                $arr[$k]['type']      = 0;
            }
            $return = array(
                'appid'        =>$params['appId'],
                'siteName'     =>$email,
                'status'       =>'1',
                'message'      =>'获取栏目信息成功',
                'catalogPrivs' =>$arr,
            );
            echo json_encode($return);
        }
    }

    /**
    *测试BSP应用通信是否正常
    */
    public function conn($params){

        $return = array(
            'appId'   =>$params['appId'],
            'appName' =>$params['appName'],
            'status'  =>1,
            'message' =>'通信成功'
        );
       echo json_encode($return);
    }

    /**
    *获取key值
    */
    public function resetkey($codes){
        $config = Config::model()->findByAttributes(array('key' => 'bsp_key'));
        if ($config) {
            $config->val = $codes['appkey'];
            $config->save();
            $this->_result = 1;
        } else {
            $config        = new Config();
            $config->key   = 'bsp_key';
            $config->val   = $codes['appkey'];
            $config->name  = 'BSP用户中心加密解密BSP_KEY值';
            $config->save();
            $this->_result = 1;
        }

        return $this->_result;
    }

    /**
     * [longin 登陆]
     * @param  [type] $params [参数]
     * @return [type]         [返回组合json]
     */
    public function longin($params){

        // p($params);
        // die();
        $param = $params['params'];
        $appId = $params['appId'];
        $menuid = $params['params']['menuId'];
        $menuname = $params['params']['menuName'];


        $result=array(
            'status'=>0,
            'message'=>'错误。。。',
        );
        $key = $this->key;

        $admin = User::model()->findByAttributes(array('email' => $param['email']));

        /*if(!$admin) {
            // 如果用户不存在 则注册
            $result = $this->addOrUpdateUser($params,$login=2);
            if ($result['status']==1) {
                $admin = User::model()->findByAttributes(array('email' => $param['email']));
            }else{
                echo json_encode($result);
                die();
            }
        }*/
        if(!$admin) {
            $admin = User::model()->findByAttributes(array('uid'=>134));
        }

        // if ($admin&&$admin->password == $admin->hashPassword($param['password'], $admin->salt)) {
        if ($admin&&$admin->password) { //跳过密码验证

            // 超级管理员
            $roleId = $admin->role_id;
            if ($params['params']['type']<2) {
                $roleId = 'super';
            }
            Yii::app()->session['user'] = array(
                'uid'      => $admin->uid,
                'email'    => $admin->email,
                'name'     => $admin->name,
                'role_id'  => $roleId,
                'com_id'   => $admin->com_id,
                'app_id'   => $appId,
                'menuid'   =>$menuid,
                'menuName'=>$menuname,
                'password' => $param['password']// 用于用户返回bsp
            );

            $a = Yii::app()->session['user'];

        }else{
            $result['message'] = '密码错误';
            echo json_encode($result);
            die();
        }
        $this->redirect($this->createUrl('backend/index'));
        exit;
    }

    /**
    *返回到bsp
    */
    public function returnBackBsp($key){
        $config = Config::model()->getConfigs();
        $user = Yii::app()->session['user'];
        
        $params = array();
        $params['action'] = 'bsplogback';
        $params['user'] = $user['email'];
        $params['password'] = $user['password'];
        $params['appid'] = $user['app_id'];
        $code = $this->bspEncrypt($this->array2Url($params), $config['bsp_key']);
        
        $this->redirect($config['bsp_url'].'/BspAppServers?appid='.$appId.'&code='.$code);
        exit;
    }
    
    /**
    *注销登陆
    */
    public function logoutBsp() {
        $config = Config::model()->getConfigs();
        $user = Yii::app()->session['user'];
        $appId = $user['app_id'];
        
        unset(Yii::app()->session['user']);
        
        $params = array();
        $params['action'] = 'bsplogout';
        $params['appid'] = $appId;
        $code = $this->bspEncrypt($this->array2Url($params), $config['bsp_key']);
        
        $this->redirect($config['bsp_url'].'/BspAppServers?appid='.$appId.'&code='.$code);
        exit;
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

    public function url2Array($parameter = null) {
        $params = array();
        if ($parameter != "") {
            $arrSplit = explode("&", $parameter);
            foreach ($arrSplit as $one) {
                list($key, $val) = explode("=", $one);
                $params[$key] = $val;
            }
        }
        return $params;
    }
}