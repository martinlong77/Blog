<?php
defined('THINK_PATH') or exit('No permission resources.'); 
class CommonAction extends Action {
    function _initialize(){
    	if($this->isAjax() && $this->isGet()) C('DEFAULT_AJAX_RETURN', 'html');
		$this->priv_db = M('admin_role_priv');
		$this->log_db = M('log');
		self::check_admin();
		self::check_priv();
		self::manage_log();
		self::lock_screen();
    }
    
	/**
	 * 判断用户是否已经登陆
	 */
	final public function check_admin() {
		if(MODULE_NAME =='Index' && in_array(ACTION_NAME, array('login', 'code')) ) {
			return true;
		} else {
			if(!session('userid') || !session('roleid')){
				$this->error('请先登录后台管理', U('Index/login'));
			}
		}
	}
	
	/**
	 * 权限判断
	 */
	final public function check_priv() {
		if(MODULE_NAME =='Index' && in_array(ACTION_NAME, array('login', 'code'))) return true;
		if(session('roleid') == 1) return true;
		$action = ACTION_NAME;
		if(preg_match('/^public_/', ACTION_NAME)) return true;
		if(preg_match('/^ajax_([a-z]+)_/', ACTION_NAME,$_match)) {
			$action = $_match[1];
		}
		$r = $this->priv_db->where(array('m'=>MODULE_NAME, 'a'=>$action, 'roleid'=>session('roleid')))->find();
		if(!$r){
			if($this->isAjax() && $this->isGet()){
				exit('<div style="padding:6px">您没有权限操作该项</div>');
			}else {
				$this->error('您没有权限操作该项');
			}
		}
	}

	/**
	 * 
	 * 记录日志 
	 */
	final private function manage_log(){
		//判断是否记录
 		if(C('SAVE_LOG_OPEN')){
 			$action = ACTION_NAME;
 			if($action == '' || strchr($action,'public') || in_array($action, array('login','code'))) {
				return false;
			}else {
				$ip = get_client_ip();
				$username = cookie('admin_username');
				$userid = session('userid');
				$time = date('Y-m-d H-i-s');
				$url = __URL__;
				$data = array('GET'=>$this->_get());
				if($this->isPost()){
					$data['POST'] = $this->_post();
				}
				$data_json = json_encode($data);
				$this->log_db->add(array('username'=>$username,'userid'=>$userid,'module'=>MODULE_NAME,'action'=>ACTION_NAME, 'querystring'=>$data_json,'time'=>$time,'ip'=>$ip));
			}
	  	}
	}
 	
 	/**
 	 * 检查锁屏状态
 	 */
	final private function lock_screen() {
		if(session('lock_screen')==1) {
			if(preg_match('/^public_/', ACTION_NAME) || (ACTION_NAME == 'login') || (MODULE_NAME == 'Index' && ACTION_NAME=='index')) return true;
			$this->error('请先登录后台管理', U('Index/login'));
		}
	}
}
