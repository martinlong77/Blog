<?php
defined('THINK_PATH') or exit('No permission resources.'); 
class AdminAction extends CommonAction {
	public function _initialize(){
		parent::_initialize();
		$this->admin_db      = D('Admin');
		$this->admin_role_db = D('Admin_role');
		$this->admin_role_priv_db = M('admin_role_priv');
		$this->menu_db       = D('Menu');
	}
	
	//管理员列表
	public function index($sort = 'userid', $order = 'asc'){
		if($this->isPost()){
			$total = $this->admin_db->count();
			$order = $sort.' '.$order;
			$list = $this->admin_db->table(C('DB_PREFIX').'admin A')->join(C('DB_PREFIX').'admin_role AR on AR.roleid = A.roleid')->field("A.userid,A.username,A.lastloginip,A.email,A.realname,AR.rolename,FROM_UNIXTIME(A.lastlogintime, '%Y-%m-%d %H:%i:%s') as lastlogintime")->order($order)->select();
			$data = array('total'=>$total, 'rows'=>$list);
			$this->ajaxReturn($data);
		}else {
			$currentpos = $this->menu_db->currentPos($this->_get('menuid'));  //栏目位置
			$this->assign(currentpos, $currentpos);
			$this->display('admin_list');
		}
	}
	
	//添加管理员
	public function add(){
		if($this->isPost()){
			$data = I('post.info');
			if($this->admin_db->where(array('username'=>$info['username']))->field('username')->find()){
				$this->error('管理员名称已经存在');
			}
			$data['password'] = password($data['password']);
			//$data['encrypt'] = $passwordinfo['encrypt'];

    		$id = $this->admin_db->add($data);
    		if($id){
    			$this->success('添加成功');
    		}else {
    			$this->error('添加失败');
    		}
		}else {
			$rolelist = $this->admin_role_db->field('roleid,rolename')->where(array('disabled'=>'0'))->select();
			$this->assign('rolelist', $rolelist);
			$this->display('admin_add');
		}
	}
	
	//编辑管理员
	public function edit($id){
		if($this->isPost()){
			$data = I('post.info');
			if($data['password']){
				$data['password'] = password($data['password']);
				//$data['encrypt'] = $passwordinfo['encrypt'];
			}else{
				unset($data['password']);
			}
    		$result = $this->admin_db->where(array('userid'=>$id))->save($data);
    		if($result){
    			$this->success('修改成功');
    		}else {
    			$this->error('修改失败');
    		}
		}else {
			$info = $this->admin_db->where(array('userid'=>$id))->find();
			$rolelist = $this->admin_role_db->field('roleid,rolename')->where(array('disabled'=>'0'))->select();
			$this->assign('info', $info);
			$this->assign('rolelist', $rolelist);
			$this->display('admin_edit');
		}
	}
	
	// 删除管理员
	public function delete($id) {
		if($id == '1') $this->error('该对象不能被删除');
		$result = $this->admin_db->where(array('userid'=>$id))->delete();
		if ($result){
			$this->success('删除成功');
		}else {
			$this->error('删除失败');
		}
	}
	
	//角色列表
	public function roleList($sort = 'listorder', $order = 'asc'){
		if($this->isPost()){
			$total = $this->admin_role_db->count();
			$order = $sort.' '.$order;
			$list = $this->admin_role_db->field('*,roleid as id')->order($order)->select();
			foreach ($list as &$v){
				$v['id_order'] = implode('_', array($v['roleid'], $v['listorder']));
			}
			$data = array('total'=>$total, 'rows'=>$list);
			$this->ajaxReturn($data);
		}else {
			$currentpos = $this->menu_db->currentPos($this->_get('menuid'));  //栏目位置
			$this->assign(currentpos, $currentpos);
			$this->display('role_list');
		}
	}
	
	//角色添加
	public function roleAdd(){
		if($this->isPost()){
			$data = $this->_post('info');
			if($this->admin_role_db->where(array('rolename'=>$data['rolename']))->field('rolename')->find()){
				$this->error('角色名称已存在');
			}
    		$id = $this->admin_role_db->add($data);
    		if($id){
    			$this->success('添加成功');
    		}else {
    			$this->error('添加失败');
    		}
		}else {
			$this->display('role_add');
		}
	}
	
	//角色编辑
	public function roleEdit($id){
		if($this->isPost()){
			$data = $this->_post('info');
    		$id = $this->admin_role_db->where(array('roleid'=>$id))->save($data);
    		if($id){
    			$this->success('修改成功');
    		}else {
    			$this->error('修改失败');
    		}
		}else {
			$info = $this->admin_role_db->where(array('roleid'=>$id))->find();
			$this->assign('info', $info);
			$this->display('role_edit');
		}
	}
	
	// 删除管理员
	public function roleDelete($id) {
		if($id == '1') $this->error('该对象不能被删除');
		$result = $this->admin_role_db->where(array('roleid'=>$id))->delete();
		if ($result){
			$this->success('删除成功');
		}else {
			$this->error('删除失败');
		}
	}
	
	//角色排序
	public function roleOrder(){
		if($this->isPost()) {
			foreach($this->_post('order') as $roleid=>$listorder) {
				$this->admin_role_db->where(array('roleid'=>$roleid))->save(array('listorder'=>$listorder));
			}
			$this->success('操作成功');
		} else {
			$this->error('操作失败');
		}
    }
    
	//权限设置
	public function roleSet($id){
		if($this->isPost()) {
			//保存权限设置
			if ($this->_get('dosubmit')){
				$this->admin_role_priv_db->where(array('roleid'=>$id))->delete();
				$menuids = explode(',', $this->_post('menuids'));
				$menuids = array_unique($menuids);
				if(!empty($menuids)){
					$menuList = array();
					$menuinfo = $this->menu_db->field(array('id','m','a','data'))->select();
					foreach ($menuinfo as $v) $menuList[$v['id']] = $v;
					foreach ($menuids as $menuid){
						$info = $menuList[$menuid];
						$info['roleid'] = $id;
						$this->admin_role_priv_db->add($info);
					}
				}
				$this->success('权限设置成功');
			//获取列表数据
			}else{
				$data = $this->menu_db->getRoleTree(0, $id);
				$this->ajaxReturn($data);
			}
		} else {
			$this->assign('id', $id);
			$this->display('role_set');
		}
    }
	
	//修改密码
	public function public_editPwd(){
		$userid = session('userid');
		if ($this->isPost()){
			$r = $this->admin_db->where(array('userid'=>$userid))->getField('password');
			if(password(I('post.old_password')) !== $r ) $this->error('旧密码输入错误');
			if(I('post.new_password')) {
				$state = $this->admin_db->where(array('userid'=>$userid))->setField('password',password(I('post.new_password')));
				if(!$state) $this->error('密码修改失败');
			}
			$this->success('密码修改该成功,请使用新密码重新登录', U('Index/public_logout'));
		}else {
			$currentpos = $this->menu_db->currentPos($this->_get('menuid'));  //栏目位置
			$info = $this->admin_db->where(array('userid'=>$userid))->find();
			
			$this->assign('info',$info);
	    	$this->assign(currentpos, $currentpos);
			$this->display('public_password');
		}
	}
	
	//修改个人信息
	public function public_editInfo($info = array()){
		$userid = $_SESSION['userid'];
		if ($this->isPost()){
			$fields = array('email','realname');
			foreach ($info as $k=>$value) {
				if (!in_array($k, $fields)){
					unset($info[$k]);
				}
			}
			$state = $this->admin_db->where(array('userid'=>$userid))->save($info);
			$state ? $this->success('修改成功') : $this->error('修改失败');
		}else {
			$currentpos = $this->menu_db->currentPos($this->_get('menuid'));  //栏目位置
			$info = $this->admin_db->where(array('userid'=>$userid))->find();
			
			$this->assign('info',$info);
	    	$this->assign(currentpos, $currentpos);
			$this->display('public_info');
		}
	}

	//验证用户名
	public function public_checkName($name){
		if ($this->_post('default') == $name) {
            $this->error('用户名相同');
        }
        $exists = $this->admin_db->where(array('username'=>$name))->field('username')->find();
        if ($exists) {
            $this->success('用户名存在');
        }else{
            $this->error('用户名不存在');
        }
	}
	
	//验证密码
	public function public_checkPassword($password = 0){
		$userid = session('userid');
		$r = array();
		$r = $this->admin_db->where(array('userid'=>$userid))->getField('password');
		if (password($password) == $r ) {
			$this->success('验证通过');
		}else {
			$this->error('验证失败');
		}
	}
	
	//验证邮箱是否存在
	public function public_checkEmail($email = 0){
		if ($this->_post('default') == $email) {
            $this->error('邮箱相同');
        }
        $exists = $this->admin_db->where(array('email'=>$email))->field('email')->find();
        if ($exists) {
            $this->success('邮箱存在');
        }else{
            $this->error('邮箱不存在');
        }
	}
	
	//验证角色名称是否存在
	public function public_checkRoleName($rolename){
	if ($this->_post('default') == $rolename) {
            $this->error('角色名称相同');
        }
        $exists = $this->admin_role_db->where(array('rolename'=>$rolename))->field('rolename')->find();
        if ($exists) {
            $this->success('角色名称存在');
        }else{
            $this->error('角色名称不存在');
        }
	}
	
}
