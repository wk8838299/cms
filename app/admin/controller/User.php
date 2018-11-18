<?php
namespace app\admin\controller;
use think\Db;
use think\Session;
use think\Request;

class User extends Admin 
{
	public function index()
	{
		$request = Request();
		if($request->get('field') && !in_array($request->get('field'), ['username','mobile','icard','truename'])){
			$this->error('非法参数');
		}
		$model = model('User','logic');
		$list = $model->getMemberList($request);
		$this->assign('list', $list);
		return $this->fetch('index');
	}

	public function edit()
	{
		$model = model('User','model');
		$user_model = Db::name('UserInfo');
		if(Request::instance()->isPost())
		{
			$data = $post = Request::instance()->post();
			// 调用当前模型对应的User验证器类进行数据验证
			$validate =  \think\Loader::validate('User');
			if(!$validate->check($data)){
    			$this->error($validate->getError());
			}
			$data['password'] = passwords($data['password']);
			$data['reg_time'] = time();
			unset($data['realname']);
			unset($data['icard']);
			$type = isset($data['userid'])?true:false;

			if($model->isUpdate($type)->save($data)){
				if(!isset($data['userid'])){
					$data['uid'] = $model->userid;
					$user_model->field('username,password,mobile',true)->insert($data);
				}else{
					$uid = (int) $data['userid'];
					$user_model->field('userid,username,password,mobile',true)->where('uid',$uid)->update($post);
				}
				$this->success(isset($data['userid'])?'更新成功':'添加成功','user/index');
			}else{
				$this->error(isset($data['userid'])?'没有任何修改':'添加失败');
			}
		}else{
			$userid =(int) input('id');
			$data = $model->getOne($userid);
			$this->assign('data', $data);
			return $this->fetch('edit');
		}
	}

	/**
	 * 用户修改状态
	 * @author 凡客
	 */
	public function status()
	{
		if(Request::instance()->isAjax())
		{
			$post = Request::instance()->param();
			$id = (isset($post['id']))?$post['id']:'';
			$type =(isset($post['type']))?$post['type']:'';

			if (empty($id)) {
				$this->error('请选择会员！');
			}

			if (empty($type)) {
				$this->error('参数错误！');
			}

			$where['userid'] = array('in', $id);
			
			switch (strtolower($type)) {
			case 'forbid':
				$data = array('status' => 0);
				break;
			case 'resume':
				$data = array('status' => 1);
				break;

			case 'delete':
				$data = array('status' => -1);
				break;

			case 'del':
				if (Db::name('user')->where($where)->delete()) {
	                Db::name('UserInfo')->where("uid",$uid)->delete();
					$this->success('操作成功！');
				}
				else {
					$this->error('操作失败！');
				}
				break;
			default:
				$this->error('操作失败！');
			}
			
			if (Db::name('user')->where($where)->update($data)) {
				$this->success('操作成功！');
			}
			else {
				$this->error('操作失败！');
			}
		}
	}

	public function admin($name = NULL, $field = NULL, $status = NULL)
	{
		$where = [];

		if ($field && $name) {
			$where[$field] = $name;
		}

		if ($status) {
			$where['status'] = $status - 1;
		}
		$list = Db::name('admin')->where($where)->paginate(10);
		$this->assign('list', $list);
		return $this->fetch('admin');
	}

	public function adminedit()
	{
		if($this->request->isPost()){
			$data = input('post.');
			if(!check($data['username'],'username')) 
			{
				$this->error('用户名格式错误!');
			}
			if ($data['nickname'] && !check($data['nickname'], 'A')) {
				$this->error('昵称格式错误！');
			}
			if ($data['password'] && !check($data['password'], 'password')) {
				$this->error('登录密码格式错误！');
			}
			if ($data['mobile'] && !check($data['mobile'], 'mobile')) {
				$this->error('手机号码格式错误！');
			}
			if ($data['email'] && !check($data['email'], 'email')) {
				$this->error('邮箱格式错误！');
			}
			if ($data['password']) {
				$data['password'] = passwords($data['password']);
			}
			else {
				unset($data['password']);
			}
			$model = Db::name('admin');
			if(isset($data['id'])){
				$rs = $model->where('id',$data['id'])->update($data);
			}else{
				$rs = $model->insert($data);
			}

			if ($rs) {
				$this->success('编辑成功!',url('user/admin'));
			}
			else {
				$this->error('编辑失败！');
			}

		}else{
			if($this->request->param('id'))
			{
				$data = Db::name('admin')->where('id',$this->request->param('id'))->find();
			}else{
				$data = ['status'=>1];
			}
			$this->assign('data', $data);
			return $this->fetch('adminedit');
		}
	}

	public function adminstatus($id = NULL, $type = NULL, $model='admin')
	{
		if (empty($id)) {
			$this->error('参数错误！');
		}

		if (empty($type)) {
			$this->error('参数错误1！');
		}

		if (is_string($id) && strpos(',', $id)) {
			$id = implode(',', $id);
		}
		
		$where['id'] = ['in', $id];

		switch (strtolower($type)) {
		case 'forbid':
			$data = array('status' => 0);
			break;

		case 'resume':
			$data = array('status' => 1);
			break;

		case 'repeal':
			$data = array('status' => 2, 'endtime' => time());
			break;

		case 'delete':
			$data = array('status' => -1);
			break;

		case 'del':
			if (Db::name($model)->where($where)->delete()) {
				$this->success('操作成功！');
			}
			else {
				$this->error('操作失败！');
			}

			break;

		default:
			$this->error('操作失败！');
		}
		
		if (Db::name($model)->where($where)->update($data)) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}

	public function setpwd()
	{
		if($this->request->isPost())
		{
			$data = $this->request->post();

			$oldpassword = $data['oldpassword'];
			$newpassword = $data['newpassword'];
			$repassword = $data['repassword'];

			if (!check($oldpassword, 'password')) {
				$this->error('旧密码格式错误！');
			}

			if (passwords($oldpassword) != session('admin_password')) {
				$this->error('旧密码错误！');
			}

			if (!check($newpassword, 'password')) {
				$this->error('新密码格式错误！');
			}

			if ($newpassword != $repassword) {
				$this->error('确认密码错误！');
			}

			if (Db::name('admin')->where(array('id' => Session::get('admin_id')))->update(array('password' => passwords($newpassword)))) {
				$this->success('登陆密码修改成功！', url('login/logout'));
			}
			else {
				$this->error('登陆密码修改失败！');
			}
		}
		return $this->fetch('setpwd');
	}

	public function auth()
	{
		$model = model('AuthGroup','model');
		$this->assign('_list', $model->getGroupList());
		$this->assign('_use_tip', true);
		$this->assign('meta_title', '权限管理');
		return $this->fetch('auth');
	}

	public function authedit()
	{
		if($this->request->isPost()) 
		{	
			$post = $this->request->post();

			// 调用当前模型对应的User验证器类进行数据验证
			$validate =  \think\Loader::validate('AuthGroup');
			if(!$validate->check($post)){
    			$this->error($validate->getError());
			}
			$post['module'] = 'admin';
			$post['type'] = 1;
			$model = Db::name('AuthGroup');
			if(empty($post['id'])){
				$rs = $model->insert($post); 
			}else{
				$rs = $model->where('id',$post['id'])->update($post);
			}
			if($rs){
				$this->success('操作成功!');
			}else{
				$this->error('操作失败');
			}
		}else{
			if(empty(input('id'))) $data = ['title'=>'','description'=>''];
			else{$data = Db::name('AuthGroup')->where(['module'=>'admin','type'=>1,'id'=>$this->request->param('id')])->find();}
			$this->assign('data', $data);
			return	$this->fetch('authedit');
		}
	}

	public function authstatus($id = NULL, $type = NULL)
	{
		if (empty($id)) {
			$this->error('参数错误！');
		}

		if (empty($type)) {
			$this->error('参数错误1！');
		}

		if (is_string($id) && strpos(',', $id)) {
			$id = implode(',', $id);
		}

		$where['id'] = ['in', $id];

		switch (strtolower($type)) {
		case 'forbid':
			$data = array('status' => 0);
			break;

		case 'resume':
			$data = array('status' => 1);
			break;

		case 'repeal':
			$data = array('status' => 2, 'endtime' => time());
			break;

		case 'delete':
			$data = array('status' => -1);
			break;

		case 'del':
			if (Db::name('AuthGroup')->where($where)->delete()) {
				$this->success('操作成功！');
			}
			else {
				$this->error('操作失败！');
			}

			break;

		default:
			$this->error('操作失败！');
		}

		if (Db::name('AuthGroup')->where($where)->update($data)) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}

	public function authstart()
	{
		if (!Db::query('TRUNCATE `yo_auth_rule`')) {
			$this->success('操作成功！');
		}
		else {
			$this->error('操作失败！');
		}
	}

	public function authaccess()
	{

		$this->updaterules();
		$menu = model('Menu','model');
		$auth_group_model = Db::name('AuthGroup');
		$auth_rules_model = Db::name('AuthRule');

		$auth_group = $auth_group_model->where(['status'=>['egt','0'],'module'=>'admin','type'=>1])->column('id,id,title,rules');
		$node_list = $menu->returnNodes();

		$map = [ 'module' => 'admin','type' => 2,'status' => 1];

		$main_rules = $auth_rules_model->where($map)->column('name,id');

		$map = ['module' => 'admin','type' => 1,'status' => 1];
		$child_rules = $auth_rules_model->where($map)->column('name,id');

		$this->assign('main_rules', $main_rules);
		$this->assign('auth_rules', $child_rules);
		$this->assign('node_list', $node_list);
		$this->assign('auth_group', $auth_group);
		$this->assign('this_group', $auth_group[(int) input('group_id')]);
		$this->meta_title = '访问授权';
		return $this->fetch('authaccess');
	}

	protected function updaterules()
	{
		$menu = model('Menu','model');
		$model = model('AuthRule','model');
		$nodes = $menu->returnNodes(false);
		$model->updaterules($nodes);
		
	}

	public function authaccessup()
	{
		if($this->request->isAjax()){
			$post = $this->request->post();
			if ($this->request->has('rules','post')) {
				sort($post['rules']);
				$post['rules'] = implode(',', array_unique($post['rules']));
			}

			$post['module'] = 'admin';
			$post['type'] = 1;//Common\Model\AuthGroupModel::TYPE_ADMIN;
			$AuthGroup = Db::name('AuthGroup');
			if(empty($post)){
				$this->error('请选择权限!');
			}

			if(empty($post['id']))
			{
				$r = $AuthGroup->insert($post);
			}else{
				$r = $AuthGroup->update($post);
			}

			if($r) 
			{
				$this->success('操作成功!');
			}else {
				$this->error('操作失败');
			}
		}
	}

	public function authuser($group_id)
	{
		if (empty($group_id) || !is_numeric($group_id)) {
			$this->error('参数错误');
		}

		$auth_group = Db::name('AuthGroup')->where(array(
			'status' => array('egt', '0'),
			'module' => 'admin',
			'type'   => 1,
			))->column('id,id,title,rules');
		$list = model('User','model')->getAuthUserList($group_id);

		$this->assign('auth_group',$auth_group);
		$this->assign('this_group',$auth_group[(int) $group_id]);
		$this->assign('_list', $list);
		return $this->fetch('authuser');
	}

	public function authuseradd()
	{
		if($this->request->isAjax())
		{
			$uid =$this->request->post('uid');
			if(empty($uid)) $this->error('请输入管理员ID!');
			if(!check($uid,'d')){
				$user = Db::name('admin')->where('username',$uid)->find();
				if(!$user){
					$user = Db::name('admin')->where('nickname', $uid)->find();
				}
				if(!$user){
					$user = Db::name('admin')->where('mobile', $uid)->find();
				}
				if(!$user){
					$this->error('用户不存在');
				}
				$uid = $user['id'];
			}
			$gid = $this->request->param('group_id');
			$model = model('User','logic');
			$rs = $model->authUserAdd($uid, $gid);
			//dump($rs);die;
			if($rs['status'])
			{
				$this->success($rs['msg']);
			}else{
				$this->error($rs['msg']);
			}
		}
	}

	public function authuserremove(){
		if($this->request->isAjax()) 
		{
			$data = $this->request->param();
			$uid = $data['uid'];
			$gid = $data['group_id'];

			if ($uid == session('admin_id')) {
				$this->error('不允许解除自身授权');
			}

			if (empty($uid) || empty($gid)) {
				$this->error('参数有误');
			}

			$AuthGroup = model('AuthGroup','model');

			if (!$AuthGroup->find($gid)) {
				$this->error('用户组不存在');
			}

			if ($AuthGroup->removeFromGroup($uid, $gid)) {
				$this->success('操作成功');
			}
			else {
				$this->error('操作失败');
			}
		}
	}
}