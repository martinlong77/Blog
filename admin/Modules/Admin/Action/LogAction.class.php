<?php
defined('THINK_PATH') or exit('No permission resources.'); 
class LogAction extends CommonAction {
	public function _initialize(){
		parent::_initialize();
		$this->menu_db       = D('Menu');
		$this->log_db        = M('log');
		$this->admin_db      = D('Admin');
	}
	
	//日志列表
	public function index($page=1, $rows=10, $search = array(), $sort = 'time', $order = 'desc'){
		if($this->isPost()){
			//搜索
			$where = array();
			foreach ($search as $k=>$v){
				if(!$v) continue;
				switch ($k){
					case 'username':
					case 'module':
						$where[] = "`{$k}` = '{$v}'";
						break;
					case 'begin':
						if(!preg_match("/^\d{4}(-\d{2}){2}$/", $v)){
							unset($search[$k]);
							continue;
						}
						if($search['end'] && $search['end'] < $v) $v = $search['end'];
						$where[] = "`time` >= '{$v}'";
						break;
					case 'end':
						if(!preg_match("/^\d{4}(-\d{2}){2}$/", $v)){
							unset($search[$k]);
							continue;
						}
						if($search['begin'] && $search['begin'] > $v) $v = $search['begin'];
						$where[] = "`time` <= '{$v}'";
						break;
				}
			}
			$where = implode(' and ', $where);
			
			$limit=($page - 1) * $rows . "," . $rows;
			$total = $this->log_db->where($where)->count();
			$order = $sort.' '.$order;
			$list = $total ? $this->log_db->where($where)->order($order)->limit($limit)->select() : array();
    		$data = array('total'=>$total, 'rows'=>$list);
    		$this->ajaxReturn($data);
    	}else{
    		$currentpos = $this->menu_db->currentPos($this->_get('menuid'));  //栏目位置
    		$this->assign(currentpos, $currentpos);
    		$adminList = $this->admin_db->field('username')->order('`lastlogintime` DESC')->select();
    		$moduleList = $this->menu_db->field('m')->order('m')->group('m')->select();
    		
    		$this->assign('adminList', $adminList);
    		$this->assign('moduleList', $moduleList);
			$this->display('list');
    	}
	}
	
	// 操作日志删除
	public function delete($week = 4) {
		$start = SYS_TIME - $week*7*24*3600;
		$d = date("Y-m-d",$start); 
		$where = "left(`time`, 10) <= '$d'";
		$result = $this->log_db->where($where)->delete();
		$result ? $this->success('删除成功') : $this->error('没有数据或已删除过了，请稍后再试');;
	}
	
}
