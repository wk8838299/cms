<?php
namespace app\admin\controller;
use think\Controller;
use think\Session;
use think\Url;
use think\Request;
use think\Config;

class Admin extends Controller
{
	public function _initialize()
	{
		parent::_initialize();
		define('UID', session('admin_id'));
		if(!Session::get('admin_id')) $this->redirect(Url::build('login/index'));
		if (Session::get('admin_id') == 1){define('IS_ROOT', 1);}else{define('IS_ROOT', 0);}
		//锁屏
		Session::get('LockScreen') && $this->redirect('login/lockscreen');
		$this->checkaccess();
		$menu = model('Menu','logic')->getMenus();
		//dump($menu);die;
		(!$menu) && $this->error('控制器基类menus属性元素配置有误');
		$this->assign('versionUp', 0);
		$this->assign('__MENU__', $menu);
	}

	protected function checkaccess(){
		$access = $this->accessControl();
		if($access === false) {
			$this->error('403:禁止访问');
		}else if($access == null) {
			$dynamic = $this->checkDynamic();
			$module  = $request->module();
			$control = $request->controller();
			$action  =$request->action();
			$menus = model('Menu', 'logic');
			if ($dynamic === null) {
                $rule = strtolower(request()->module() . '/' . request()->control() . '/' . request()->action());
                if (!$menus->checkRule($rule, array('in', '1,2'))) {
                    $this->error('未授权访问!');
                }
            } else if ($dynamic === false) {
                $this->error('未授权访问!');
            }
		}
	}

	final protected function accessControl()
    {
        if (IS_ROOT) {
            return true;
        }
        $request = Request::instance();
        $allow = Config::get('ALLOW_VISIT');
        $deny = Config::get('DENY_VISIT');
        $check = strtolower($request->controller() . '/' . $request->action());

        if (!empty($deny) && in_array_case($check, $deny)) {
            return false;
        }

        if (!empty($allow) && in_array_case($check, $allow)) {
            return true;
        }

        return null;
    }

    protected function checkDynamic()
    {
        if (IS_ROOT) {
            return true;
        }

        return null;
    }
}