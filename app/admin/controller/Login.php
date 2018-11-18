<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Session;
use think\Cache;
use think\Cookie;

class Login extends Controller
{
	/**
	 * 登录页首页
	 * @param  string $username 用户名
	 * @param  string $password 密码
	 * @param  string $verify   验证码
	 * @return void             json
	 */
	public function index($username = NULL, $password = NULL, $verify = NULL){
		if(Request::instance()->isPost()){
			if(Session::get('refuse_login') > (time() -60)){
				$this->error('输入密码次数超过5次，请'.(60-time()+Session::get('refuse_login')).'秒后再尝试！');
			}
			$username = filter_login($username);
			//校验
			$data = ['username' =>$username,'password'=> $password];
			$validate =  \think\Loader::validate('Login');
			if(!$validate->check($data)){
    			$this->error($validate->getError());
			}

			$admin = Db::name('admin')->where('username',$username)->find();
			(!$admin['status']) && $this->error('该用户已冻结!');
			if($admin['password'] != passwords($password)){
				Cache::inc('login_errs');
				if(Cache::get('login_errs') >5){
					Cache::set('login_errs',0);
					Session::set('refuse_login',time());
				}
				$this->error('用户名或密码错误!');
			}else{
				$data = ['last_login_time'=>time()];
				Db::name('admin')->where('id', $admin['id'])->update($data);
				Cache::rm('login_errs');
				Session::delete('refuse_login');
				Session::set('admin_id', $admin['id']);
				Session::set('admin_username', $admin['username']);
				Session::set('admin_password', $admin['password']);
				$this->success('登录成功!', url('index/index'));
			}
			
		}else{
			(Session::get('admin_id')) && $this->redirect('index/index');

			return $this->fetch('index');
		}
	}

	public function logout()
	{
		Session::clear();
		$this->redirect('login/index');
	}

	public function lockScreen(){
		if(!(Request::instance()->isPost())){
			return $this->fetch('lockscreen');
		}else{
			if($pass = Request::instance()->post('pass')){
				Session::set('LockScreen', $pass);
				Session::set('LockScreenTime', 3);
				$this->success('锁屏成功，正在跳转中...');
			}else{
				$this->error('请输入一个锁屏密码!');
			}
		}
	}

	public function unlock()
	{
		$request = Request::instance();
		if($request->isAjax()){
			$pass = $request->post('pass');
			if(!Session::get('admin_id')){
				Session::clear();
				$this->error('登录已经失效，请重新登录...', url('login/index'));
			}
			if(Session::get('LockScreenTime') < 0) {
				Session::clear();
				$this->error('密码错误次数过多，请重新登录...', url('login/index'));
			}
			if($pass == Session::get('LockScreen')){
				Session::delete('LockScreen');
				$this->success('解锁成功', url('index/index'));
			}
			$admin = Db::name('admin')->where('id' ,Session::get('admin_id'))->find();
			if($admin['password'] == passwords($pass)){
				Session::delete('LockScreen');
				$this->success('解锁成功', url('index/index'));
			}
			Session::set('LockScreenTime', Session::get('LockScreenTime')-1);
			$this->error('用户名或密码错误!');
		}
	}
}