<?php

class SobeyVMS {

    // token
    private $_token;
    // vms接口地址
    private $_interface_url;
    // 数据类型 json / xml
    public $data_type;

    public function __construct($interface_url = null, $token = null) {
        $this->_interface_url = $interface_url;
        $this->_token = $token;
        if($token===null && $interface_url===null){
            $this->setConfig();
        }
        $this->data_type = 'json';
    }

    public function setConfig() {
        $user = Yii::app()->session['user'];
        if ($user) {
            $conf = Config::model()->getConfigs();
            $com_conf = ComConfig::model()->getConfigsByComId($user['com_id']);
            $this->_interface_url = (isset($com_conf['interface_url']) && $com_conf['interface_url']!="") ? $com_conf['interface_url'] : $conf['vms_url'];
            $this->_token = isset($com_conf['vms_token']) ? $com_conf['vms_token'] : '';
        }
    }

    /**
     * 设置数据类型
     */
    public function setDataType($data_type) {
        $this->data_type = $data_type;
    }

    /**
     * 获取vms栏目 返回数据对象
     * @config[catalogType] 栏目枚举类型：5.视频栏目、6.音频栏目、7.电视剧栏目
     * @config[catalogStyle] 栏目路径传参方式：1.ID查询、0.全路径查询
     * @config[catalogPath] 栏目名称
     * @config[getAllData] 是否获取所有内容：1.获取栏目下所有内容、0.获取栏目下一级内容
     */
    public function getCatalogs($config = null) {
        $params = array(
            'method' => 'getCatalogList',
            'partnerToken' => $this->_token,
            'dataType' => $this->data_type,
            'catalogType' => $config['catalogType'],
            'catalogStyle' => $config['catalogStyle'],
            'catalogPath' => $config['catalogPath'],
            'getAllData' => $config['getAllData']
        );
        $parameter = $this->array2Url($params);
        Yii::import("application.helpers.Ccrul");
        $returnData = Ccrul::post($this->_interface_url, $parameter);
        if ($this->data_type == 'json') {
            $returnData = @json_decode($returnData, true);
        } else if ($this->data_type == 'xml') {
            $returnData = @simplexml_load_string($returnData);
            $returnData = @json_decode(@json_encode($returnData), true);
        }
        return $returnData;
    }

    /**
     * 获取vms栏目 返回数据对象
     * @config[defaultFlag] 是否默认播放器：1.默认、0.非默认
     * @config[type] 播放器类型(老版)：0.点播播放器、1.直播
     * @config[type] 播放器类型(新版)：5.点播播放器、8.直播
     */
    public function getPlayerList($config = null) {
        $params = array(
            'method' => 'getPlayerList',
            'partnerToken' => $this->_token,
            'dataType' => $this->data_type,
            //'defaultFlag' => $config['defaultFlag'],//不传表示全部
            'type' => $config['type']
        );
        $parameter = $this->array2Url($params);
        Yii::import("application.helpers.Ccrul");
        $returnData = Ccrul::post($this->_interface_url, $parameter);
        if ($this->data_type == 'json') {
            $returnData = @json_decode($returnData, true);
        } else if ($this->data_type == 'xml') {
            $returnData = @simplexml_load_string($returnData);
            $returnData = @json_decode(@json_encode($returnData), true);
        }

        return $returnData;
    }

    public function getChannelList($config = null) {
        $params = array(
            'method' => 'getChannelList',
            'partnerToken' => $this->_token,
            'dataType' => $this->data_type,
            //'type' => $config['type']
        );
        $parameter = $this->array2Url($params);
        Yii::import("application.helpers.Ccrul");
        $returnData = Ccrul::post($this->_interface_url, $parameter);
        if ($this->data_type == 'json') {
            $returnData = @json_decode($returnData, true);
        } else if ($this->data_type == 'xml') {
            $returnData = @simplexml_load_string($returnData);
            $returnData = @json_decode(@json_encode($returnData), true);
        }

        return $returnData;
    }

    /**
     * 删除VMS数据接口
     * @param $ids 删除的数据id多个用“，”隔开
     * @param $type 数据类型 5: 视频； 6 ：音频； 7 ：电视剧
     * @param $delType 0 ：彻底删除； 1 ：删除至回收站
     */
    public function deleteDataByIds($ids,$type=5,$delType=0){
        $params = array(
            'method' => 'deleteDataByIds',
            'partnerToken' => $this->_token,
            'dataType' => $this->data_type,
            'sourceIDs' => $ids,
            'sourceType' => $type,
            'delType' => $delType
        );
        $parameter = $this->array2Url($params);
        Yii::import("application.helpers.Ccrul");
        $returnData = Ccrul::post($this->_interface_url, $parameter);
        if ($this->data_type == 'json') {
            $returnData = @json_decode($returnData, true);
        } else if ($this->data_type == 'xml') {
            $returnData = @simplexml_load_string($returnData);
            $returnData = @json_decode(@json_encode($returnData), true);
        }
        return $returnData;        
    }
    
    /**
    *更新上传视频的标题
    */
    public function updateDataById($sourceId,$sourceTitle,$sourceType=5) {
        $params = array(
            'method' => 'updateDataById',
            'partnerToken' => $this->_token,
            'dataType' => $this->data_type,
            'sourceId' => $sourceId,
            'sourceTitle' => $sourceTitle,
            'sourceType' => $sourceType
        );
        $parameter = $this->array2Url($params);
        Yii::import("application.helpers.Ccrul");
        $returnData = Ccrul::post($this->_interface_url, $parameter);
        if ($this->data_type == 'json') {
            $returnData = @json_decode($returnData, true);
        } else if ($this->data_type == 'xml') {
            $returnData = @simplexml_load_string($returnData);
            $returnData = @json_decode(@json_encode($returnData), true);
        }
        return $returnData;    
    }

    /**
     * 获取vms音频 
     * @config[catalogStyle] 栏目路径传参方式：1.ID查询、0.全路径查询
     * @config[catalogPath] 栏目名称
     * @config[getAllData] 是否获取所有内容：1.获取栏目下所有内容、0.获取栏目下一级内容
     * @config[pageNum] 当前页码
     * @config[pageSize] 每页显示条数
     * @config[startTime] 开始时间
     * @config[endTime] 结束时间
     * @config[keywords] 关键词搜索
     * @config[sortField] 排序字段
     * @config[sort] 排序方式 大写
     */
    public function getAudios($config = null) {
        $params = array(
            'method' => 'getAudioList',
            'partnerToken' => $this->_token,
            'dataType' => $this->data_type,
            'catalogStyle' => $config['catalogStyle'],
            'catalogPath' => $config['catalogPath'],
            'getAllData' => $config['getAllData'],
            'pageNum' => $config['pageNum'],
            'pageSize' => $config['pageSize'],
            'startTime' => $config['startTime'],
            'endTime' => $config['endTime'],
            'keywords' => $config['keywords'],
            'sortField' => $config['sortField'],
            'sort' => $config['sort']
        );
        $parameter = $this->array2Url($params);
        Yii::import("application.helpers.Ccrul");
        $returnData = Ccrul::post($this->_interface_url, $parameter);
        if ($this->data_type == 'json') {
            $returnData = @json_decode($returnData, true);
        } else if ($this->data_type == 'xml') {
            $returnData = @simplexml_load_string($returnData);
            $returnData = @json_decode(@json_encode($returnData), true);
        }
        return $returnData;
    }

    /**
     * 获取vms视频 
     * @config[catalogStyle] 栏目路径传参方式：1.ID查询、0.全路径查询
     * @config[catalogPath] 栏目名称
     * @config[getAllData] 是否获取所有内容：1.获取栏目下所有内容、0.获取栏目下一级内容
     * @config[pageNum] 当前页码
     * @config[pageSize] 每页显示条数
     * @config[startTime] 开始时间
     * @config[endTime] 结束时间
     * @config[keywords] 关键词搜索
     * @config[sortField] 排序字段
     * @config[sort] 排序方式 大写
     */
    public function getVedios($config = null) {
        $params = array(
            'method' => 'getVideoList',
            'partnerToken' => $this->_token,
            'dataType' => $this->data_type,
            'catalogStyle' => $config['catalogStyle'],
            'catalogPath' => $config['catalogPath'],
            'getAllData' => $config['getAllData'],
            'pageNum' => $config['pageNum'],
            'pageSize' => $config['pageSize'],
            'startTime' => isset($config['startTime']) ? $config['startTime'] : '',
            'endTime' => isset($config['endTime']) ? $config['endTime'] : '',
            'keywords' => $config['keywords'],
            'sortField' => $config['sortField'],
            'sort' => $config['sort'],
            'status' => isset($config['status']) ? $config['status'] : 1,
            'sourceFlag'=>'true'
        );
     
        $parameter = $this->array2Url($params);
        Yii::import("application.helpers.Ccrul"); 
        $returnData = Ccrul::post($this->_interface_url, $parameter);  
        // print_r($returnData);exit;
        if ($this->data_type == 'json') {
            $returnData = @json_decode($returnData, true);
        } else if ($this->data_type == 'xml') {
            $returnData = @simplexml_load_string($returnData);
            $returnData = @json_decode(@json_encode($returnData), true);
        }
    
        return $returnData;
    }
    /**
     * 获取某个点播视频信息
     */
    function getVideoById($id=null){        
        $params = array(
            'method' => 'getVideoById',
            'partnerToken' => $this->_token,
            'dataType' => $this->data_type,
            'sourceFlag'=>'true',
            'videoId' =>$id      
        );
        $parameter = $this->array2Url($params);
        Yii::import("application.helpers.Ccrul");
        $returnData = Ccrul::post($this->_interface_url, $parameter); 
        if ($this->data_type == 'json') {
            $returnData = @json_decode($returnData, true);
        } else if ($this->data_type == 'xml') {
            $returnData = @simplexml_load_string($returnData);
            $returnData = @json_decode(@json_encode($returnData), true);
        }
        return $returnData;
    } 
    /**
     * 获取vms直播 
     * @config[type] 直播频道直播流类型
     */
    public function getLives($config = null) {
        $params = array(
            'method' => 'getChannelList',
            'partnerToken' => $this->_token,
            'dataType' => $this->data_type,
            'type' => $config['type']
        );
        $parameter = $this->array2Url($params);
        Yii::import("application.helpers.Ccrul");
        $returnData = Ccrul::post($this->_interface_url, $parameter);
        if ($this->data_type == 'json') {
            $returnData = @json_decode($returnData, true);
        } else if ($this->data_type == 'xml') {
            $returnData = @simplexml_load_string($returnData);
            $returnData = @json_decode(@json_encode($returnData), true);
        }
        return $returnData;
    }

    /**
     * 获取认证
     */
    public function getAuths() {
        $cache_name = md5('SobeyVMS_getAuths_'.$this->_token);
        $returnData = Yii::app()->memcache->get($cache_name);
        if (!$returnData) {
            $params = array(
                'method' => 'authenticate',
                'partnerToken' => $this->_token,
                'dataType' => $this->data_type
            );
            $parameter = $this->array2Url($params);
            Yii::import("application.helpers.Ccrul");
            $returnData = Ccrul::post($this->_interface_url, $parameter);
            if ($this->data_type == 'json') {
                $returnData = @json_decode($returnData, true);
            } else if ($this->data_type == 'xml') {
                $returnData = @simplexml_load_string($returnData);
                $returnData = @json_decode(@json_encode($returnData), true);
            }
        }
        if (!isset($returnData['siteNames'])) {
            $returnData['siteNames'] = "";
        }
        return $returnData;
    }

    /**
     * 获取站点名
     */
    public function getSiteName() {
        $cache_name = md5('SobeyVMS_getSiteName_'.$this->_token);
        $site_name = Yii::app()->memcache->get($cache_name);
        if (!$site_name) {
            $data = $this->getAuths();
            if ($data) {
                $site_name = $data['siteNames'];
            }
            Yii::app()->memcache->set($cache_name, $site_name, 300);
        }
        return $site_name;
    }
    /**
     * 获取空间
     */
    public function getSpace(){
         $params = array(
            'method' => 'getSiteInfo',
            'partnerToken' => $this->_token,
            'dataType' => $this->data_type
        );
        $parameter = $this->array2Url($params);
        Yii::import("application.helpers.Ccrul");    
        $returnData = Ccrul::post($this->_interface_url, $parameter);  
        
        if ($this->data_type == 'json') {
            $returnData = @json_decode($returnData, true);
        } else if ($this->data_type == 'xml') {
            $returnData = @simplexml_load_string($returnData);
            $returnData = @json_decode(@json_encode($returnData), true);
        }
        return $returnData;
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
    
    public static function getStatus($status=0){
        /*$code = array(
            'all' => '全部状态',
            '-1' => '提取失败',
            '0' => '上传成功',
            '1' => '正在提取',
            '2' => '提取完成',    
        );*/
        $code = array(
            '-2' => '全部状态',
            '2' => '正在转码',
            '1' => '准备完成',
            '3' => '转码失败'            
        );
        return $code;
    }
    
    public function getCatalogIdByName($catalogName){
        $cache_name = md5('SobeyVMS_getCatalogIdByName_'.$this->_token.'_'.$catalogName);
        $catalog_id = Yii::app()->memcache->get($cache_name);
        if (!$catalog_id) {
            // 获取普通视频栏目列表
            $config = array(
                'catalogType' => 5,
                'catalogStyle' => 1,
                'catalogPath' => 0,
                'getAllData' => 1
            );
            $catalogs = $this->getCatalogs($config);
            if (is_array($catalogs)) {
                foreach($catalogs as $catalog) {
                    if ($catalog['name'] == $catalogName) {
                        $catalog_id = $catalog['catalogId'];
                        break;
                    }
                }
            }
            Yii::app()->memcache->set($cache_name, $catalog_id, 300);
        }
        return $catalog_id;
    }

    
    /**
     * 获取转码率接口
     */
    public function getTransCode(){
        $auths = $this->getAuths();
        $params = array(
            'method' => 'getTranscode',
            'partnerToken' => $this->_token
        );
        $parameter = $this->array2Url($params);
        Yii::import("application.helpers.Ccrul");
        $returnData = Ccrul::post($this->_interface_url, $parameter);
        $returnData = @json_decode($returnData, true);
        return $returnData;
    }

    /**
     * vms视频上传代码封装
     */
    /*public function getUploadHtml($catalogName, $ispublish = 1, $pic_type = 1){
        $auths = $this->getAuths();
        if (!isset($auths['siteId'])) {
            return false;
        }
        $site_id = base64_encode($auths['siteId']);
        $partner_code = $auths['partnerCode'];
        $catalog_id = $this->getCatalogIdByName($catalogName);
        if (!$catalog_id) {
            return false;
        }
        $html = '<script src="http://pic.vms.sobeycache.com/common/default/upload/uploadVideo.js"></script><a href="javascript:void();" onclick="uploadVideo(\'http://user.vms.sobeycache.com:8080/vms/Upload\',\'http://pic.vms.sobeycache.com/common/default/upload\',\'http://user.vms.sobeycache.com:8080/vms/servlet/transcodeUploadServlet.jsp?type=video&realmname=vod.sobeycache.com/'.$partner_code.'\',\''.$site_id.'\',\''.$ispublish.'\',\''.$catalog_id.'\');"><img src="http://pic.vms.sobeycache.com/common/default/upload/vms_upload_btn'.$pic_type.'.gif"/></a>';
        return $html;
    }*/

    /**
     * 本站视频上传代码封装
     */
    public function getUploadHtml($catalogName, $ispublish = 1, $pic_type = 1){
        $auths = $this->getAuths();
        if (!isset($auths['siteId'])) {
            return false;
        }
        $site_id = $auths['siteId'];
        $partner_code = $auths['partnerCode'];
        $catalog_id = $this->getCatalogIdByName($catalogName);
        if (!$catalog_id) {
            return false;
        }
        $transCode = reset($this->getTransCode());
        $transCodeId = isset($transCode['transgroupId'])? $transCode['transgroupId'] : 1;
        $conf = Config::model()->getConfigs();
        $html = '
        <script type="text/javascript" src="'.Yii::app()->request->baseUrl.'/player/swfobject.js"></script>
        <script type="text/javascript">
        makeUploadWindowHtml();
        var swfVersionStr = "11.1.0";
        var xiSwfUrlStr = "";
        var flashvars = {};
        flashvars.fileType="*.mp4;*.MPG;*.flv;*.wmv;*.3gp;*.avi;*.rmvb;*.asf;*.wm;*.asx;*.rm;*.ra;*.ram;*.mpg;*.mpeg;*.mpe;*.vob;*.dat;*.mov;*.mp4v;*.m4v;*.mkv;*.f4v;*.mts;";
        flashvars.fileDescription="音频视频文件(*.mp4;*.MPG;*.flv;*.wmv;*.3gp;*.avi;*.rmvb;*.asf;*.wm;*.asx;*.rm;*.ra;*.ram;*.mpg;*.mpeg;*.mpe;*.vob;*.dat;*.mov;*.mp4v;*.m4v;*.mkv;*.f4v;*.mts)";
        //flashvars.host=encodeURIComponent("http://113.142.30.108:8080/vms/Upload?siteId='.$site_id.'&catalogIds=1,6&isPublish='.$ispublish.'");
        flashvars.server=encodeURIComponent("'.$conf['vms_upload_url'].'");
        flashvars.alphaTest=0;//当前按钮的alpha值
        flashvars.buttonMode=true;//是否显示手型
        
        flashvars.fileProgressFun="onFileProgressChangge";//配置上传进度回调方法
        flashvars.addFileFun="onFileListChange";//配置添加文件回调方法
        flashvars.uploadErrorFun="onUploadError";//配置上传错误回调方法
        flashvars.stateChanggeFun="onStateChange";//配置上传状态改变方法
        
        var params = {};
        params.quality = "high";
        params.bgcolor = "#ebf4ff";
        params.wmode="transparent"
        params.allowscriptaccess = "sameDomain";
        params.allowfullscreen = "true";
        var attributes = {};
        attributes.id = "Uploader";
        attributes.name = "Uploader";
        attributes.align = "middle";
        swfobject.embedSWF(
            "'.Yii::app()->request->baseUrl.'/player/Uploader.swf", "flashContent", 
            "107", "37", 
            swfVersionStr, xiSwfUrlStr, 
            flashvars, params, attributes);
        swfobject.createCSS("#flashContent", "display:block;text-align:left;");
        </script>
        <a href="javascript:void(0);" onclick="showUploadWindow()">
            <img src="'.Yii::app()->request->baseUrl.'/images/btn_upload_video.png" style="border:none;"/>
            <input type="hidden" name="videoUpload_siteId" id="videoUpload_siteId" value="'.$site_id.'" />
            <input type="hidden" name="videoUpload_catalogId" id="videoUpload_catalogId" value="'.$catalog_id.'" />
            <input type="hidden" name="videoUpload_transCodeId" id="videoUpload_transCodeId" value="'.$transCodeId.'" />
        </a>
        ';
        return $html;
    }
}