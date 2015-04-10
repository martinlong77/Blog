<?php
defined('THINK_PATH') or exit('No permission resources.'); 
class IndexAction extends CommonAction {
	public function _initialize(){
		parent::_initialize();
		$this->admin_db      = D('Admin');
		$this->admin_role_db = D('Admin_role');
		$this->times_db      = M('times');
		$this->menu_db       = D('Menu');
		$this->now           = time();
	}
	
    function index(){
    	$userid = session('userid');
    	$roleid = session('roleid');
		$admin_username = cookie('admin_username');
		$rolename = $this->admin_role_db->getRoleName($roleid);
		
		$this->admin_db->where(array('userid'=>$userid))->save(array('lastlifetime'=>$this->now));
		
		$menu_array = $this->menu_db->getMenu(0);	//头部菜单
		$info = $this->admin_db->field('`lastlogintime`,`lastloginip`')->where(array('userid'=>$userid))->find();
		
		$this->assign('userid',$userid);
		$this->assign('admin_username',$admin_username);
		$this->assign('rolename',$rolename);
        $this->assign('menu_array',$menu_array);
        $this->assign('info',$info);
		$this->display('index');
    }
    
    function login(){
    	if ($this->_get('dosubmit')){
    		//验证码判断
			$username = trim($this->_post('username')) ? trim($this->_post('username')) : $this->error('用户名不能为空',HTTP_REFERER, 1);
			//$code = trim($this->_post('code')) ? trim($this->_post('code')) : $this->error('请输入验证码', HTTP_REFERER, 1);
			//if (session('code') != strtolower($code)) $this->error('验证码错误！', HTTP_REFERER, 1);
			
			//密码错误剩余重试次数
			$rtime = $this->times_db->where(array('username'=>$username, 'isadmin'=>1))->find();
			if($rtime['times'] >= C('MAX_LOGIN_TIMES')) {
				$minute = C('LOGIN_WAIT_TIME') - floor(($this->now-$rtime['logintime'])/60);
				if ($minute > 0) {
					$this->error("密码重试次数太多，请过{$minute}分钟后重新登录！");
				}else {
					$this->times_db->where(array('username'=>$username))->delete();
				}
			}
			
			//查询帐号
			$r = $this->admin_db->where(array('username'=>$username))->find();
			if(!$r) $this->error('管理员不存在', HTTP_REFERER, 1);
			$password = md5(trim($_POST['password']));
			
			$ip = get_client_ip();
			if($r['password'] != $password) {
				if($rtime && $rtime['times'] < C('MAX_LOGIN_TIMES')) {
					$times = C('MAX_LOGIN_TIMES') - intval($rtime['times']);
					$this->times_db->where(array('username'=>$username))->save(array('ip'=>$ip,'isadmin'=>1));
					$this->times_db->where(array('username'=>$username))->setInc('times');
				} else {
					$this->times_db->where(array('username'=>$username,'isadmin'=>1))->delete();
					$this->times_db->add(array('username'=>$username,'ip'=>$ip,'isadmin'=>1,'logintime'=>$this->now,'times'=>1));
					$times = C('MAX_LOGIN_TIMES');
				}
				$this->error("密码错误，您还有{$times}次尝试机会！", HTTP_REFERER);
			}
			$this->times_db->where(array('username'=>$username))->delete();
			$this->admin_db->where(array('userid'=>$r['userid']))->save(array('lastloginip'=>$ip,'lastlogintime'=>$this->now,'lastlifetime'=>$this->now));
			
			import('ORG.Util.String');
			session('userid', $r['userid']);
			session('roleid', $r['roleid']);
			session('lock_screen', 0);
			cookie('admin_username',$username);
			cookie('userid', $r['userid']);
			
			$this->success('登录成功', U('Index/index'));
    	}else {
    		//dump($_SESSION);
    		$this->display();
    	}
    }
    
	//退出登录
	public function public_logout() {
		$userid = session('userid');
		//清空在线信息
		if($userid) $this->admin_db->where(array('userid'=>$userid))->save(array('lastlifetime'=>''));
		
		session(null);
		cookie(null);
		$this->success('安全退出！', U('Index/login'));
	}
	
	//验证码
	public function code() {
		load('@.Class.Checkcode#class');
		$checkcode = new checkcode();
		if ($this->_get('code_len')) $checkcode->code_len = intval($this->_get('code_len'));
		if ($checkcode->code_len > 8 || $checkcode->code_len < 2) {
			$checkcode->code_len = 4;
		}
		if ($this->_get('font_size')) $checkcode->font_size = intval($this->_get('font_size'));
		if ($this->_get('width')) $checkcode->width = intval($this->_get('width'));
		if ($checkcode->width <= 0) {
			$checkcode->width = 130;
		}
		if ($this->_get('height')) $checkcode->height = intval($this->_get('height'));
		if ($checkcode->height <= 0) {
			$checkcode->height = 50;
		}
		if ($this->_get('font_color') && preg_match('/(^#[a-z0-9]{6}$)/im', trim(urldecode($this->_get('font_color'))))) $checkcode->font_color = trim(urldecode($this->_get('font_color')));
		if ($this->_get('background') && preg_match('/(^#[a-z0-9]{6}$)/im', trim(urldecode($this->_get('background'))))) $checkcode->background = trim(urldecode($this->_get('background')));
		$checkcode->doimage();
		session('code', $checkcode->get_code());
	}
	
	//后台首页
	public function public_main(){
		load('@.Func.Sys#func');
		$userid = session('userid');
    	$roleid = session('roleid');
		$admin_username = cookie('admin_username');
		$rolename = $this->admin_role_db->getRoleName($roleid);
		$r = $this->admin_db->where(array('userid'=>$userid))->find();
		$logintime = $r['lastlogintime'];
		$loginip = $r['lastloginip'];
		
		//检测框架目录可写性
		$is_writeable = is_writable(THINK_PATH.'ThinkPHP.php');
		$sysinfo = get_sysinfo();
		$os = explode(' ', php_uname());
		//网络使用状况
		$net_state = null;
		if ($sysinfo['sysReShow']=='show' && false !== ($strs = @file("/proc/net/dev"))){
			for ($i = 2; $i < count($strs); $i++ ){
				preg_match_all( "/([^\s]+):[\s]{0,}(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/", $strs[$i], $info );
				$net_state.="{$info[1][0]} : 已接收 : <font color=\"#CC0000\"><span id=\"NetInput{$i}\">".$sysinfo['NetInput'.$i]."</span></font> GB &nbsp;&nbsp;&nbsp;&nbsp;已发送 : <font color=\"#CC0000\"><span id=\"NetOut{$i}\">".$sysinfo['NetOut'.$i]."</span></font> GB <br />";
			}
		}
		
		//当前在线人数
		$onlineList = $this->admin_db->field('username,realname')->where('`lastlifetime` >= '.strtotime('-5 minute'))->order('lastlifetime desc')->select();
		
		$this->assign('admin_username',$admin_username);
		$this->assign('rolename',$rolename);
		$this->assign('logintime',$logintime);
		$this->assign('loginip',$loginip);
		$this->assign('is_writeable',$is_writeable);
		$this->assign('sysinfo',$sysinfo);
		$this->assign('os',$os);
		$this->assign('net_state',$net_state);
		$this->assign('onlineList', $onlineList);
		$this->display('main');
	}
	
	//左侧菜单
	public function public_menuLeft($menuid = 0) {
		$datas = array();
		$list = $this->menu_db->getMenu($menuid);
		foreach ($list as $k=>$v){
			$datas[$k]['name'] = $v['name'];
			$son_datas = $this->menu_db->getMenu($v['id']);
			foreach ($son_datas as $k2=>$v2){
				$datas[$k]['son'][$k2]['name'] = $v2['name'];
				$datas[$k]['son'][$k2]['url'] = U($v2['m'].'/'.$v2['a'].'?menuid='.$v2['id'].'&'.$v2['data']);
			}
		}
		$this->ajaxReturn($datas);
	}
	
	//防止登录超时和后台在线人数统计
	public function public_sessionLife(){
		$userid = session('userid');
		$this->admin_db->where(array('userid'=>$userid))->save(array('lastlifetime'=>$this->now));
	}
}
