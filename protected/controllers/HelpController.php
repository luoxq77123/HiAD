<?php
/**
 * 帮助中心
 */
class HelpController extends BaseController {
    /**
     * 操作流程
     */
    public function actionOprocess() {
        $cntMenu = '操作流程';
        $nodeList = $this->_nodeTree();
        $subMenu = 2;
        $setArray = array(
            'nodeList' => $nodeList,
            'cntMenu' => $cntMenu,
            'subMenu' => $subMenu
        );
        $this->renderPartial('index',$setArray);
    }
    
    /**
     * 常见问题
     */
    public function actionFaq() {
        $cntMenu = '常见问题';
        $nodeList = $this->_nodeTree();
        $subMenu = 3;
        $setArray = array(
            'nodeList' => $nodeList,
            'cntMenu' => $cntMenu,
            'subMenu' => $subMenu
        );
        $this->renderPartial('index',$setArray);
    }
    
    /**
     * 帮助子菜单列表
     * 需获取两级菜单显示，如果不够则从内容页获取内容列表
     */
    public function actionSubMenu() {
        $nid = isset($_GET['nid'])? intval($_GET['nid']) : 2;
        $arrNode = HelpNode::model()->getAllNote();
        $arrData = array();
        // 有子节点 则获取子节点列表
        if ($arrNode[$nid]['child_ids']!='') {
            $arrData = HelpNode::model()->getNodeByParentId($nid);
            foreach($arrData as $id=>$one) {
                // 有子节点 则获取子节点列表
                if ($arrNode[$id]['child_ids']!='') {
                    $arrData[$id]['child'] = HelpNode::model()->getNodeByParentId($id);
                } else { // 获取内容列表
                    $arrData[$id]['list'] = HelpContent::model()->getListByNodeId($id);
                }
            }
        } else {
            $arrData[$nid]['id'] = $arrNode[$nid]['id'];
            $arrData[$nid]['name'] = $arrNode[$nid]['name'];
            // 获取内容列表
            $arrData[$nid]['list'] = HelpContent::model()->getListByNodeId($nid);
        }
        $strMap = $this->_nodeMap($nid);
        $set = array(
            'menu' => $arrData,
            'strMap' => $strMap
        );
        $this->renderPartial('subMenuList',  $set);
    }
    
    /**
     * 帮助详细内容
     */
    public function actionDetail() {
        $id = isset($_GET['id'])? intval($_GET['id']) : 0;
        $arrContent = HelpContent::model()->getAll();
        $content = $arrContent[$id];
        $strMap = $this->_nodeMap($content['node_id']);
        $set = array(
            'content' => $content,
            'strMap' => $strMap
        );
        $this->renderPartial('detail',$set);
    }
    
    /**
     * 面包削
     */
    private function _nodeMap($nid) {
        $arrNode = HelpNode::model()->getAllNote();
        $arrData = $this->_getParentNode($nid, $arrNode);
        $strMap = "";
        foreach($arrData as $node){
            if ($node['id'] == $nid)
                $strMap .= '<a href="javascript:void(0);" onclick="ajax_load(\'info_nav_box\',\''.$this->createUrl('help/subMenu?nid='.$node['id']).'\');" style="font-weight: bold;">'.$node['name'].'</a>';
            else 
                $strMap .= '<a href="javascript:void(0);" onclick="ajax_load(\'info_nav_box\',\''.$this->createUrl('help/subMenu?nid='.$node['id']).'\');" style="font-weight: bold;">'.$node['name'].'</a> <img src="'.Yii::app()->request->baseUrl.'/images/dbArral.gif" style="vertical-align:inherit;" /> ';
        }
        return $strMap;
    }
    
    private function _getParentNode($nid, $arrNode){
        $arrData = array();
        if ($arrNode[$nid]['parent_id']!=1){
            $arrData = $this->_getParentNode($arrNode[$nid]['parent_id'], $arrNode);
            $arrData[] = $arrNode[$nid];
        } else {
            $arrData[] = $arrNode[$nid];
        }
        return $arrData;
    }
    
    /**
     * 节点树
     */
    private function _nodeTree() {
        $nodeList = array();
        $arrNode = HelpNode::model()->getAllNote();
        foreach($arrNode as $val) {
            if($val->parent_id == 1){
                $nodeList[$val->id]['id'] = $val->id;
                $nodeList[$val->id]['name'] = $val->name;
                if ($val->child_ids!='') {
                    $arrChild = explode(",", $val->child_ids);
                    foreach($arrChild as $one) {
                        $nodeList[$val->id]['child'][$one]['id'] = $arrNode[$one]['id'];
                        $nodeList[$val->id]['child'][$one]['name'] = $arrNode[$one]['name'];
                    }
                }
            }
        }
        return $nodeList;
    }
}