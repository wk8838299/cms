<?php
namespace app\admin\logic;
use think\Db;
use think\Config;
use think\Request;
use app\admin\model\Menu as MenuModel;
use app\admin\model\AuthRule;
use app\admin\model\Tree;

class Menu
{
	public function getOne($id){
		return Db::name('menu')->field(true)->where('id',$id)->limit(1)->find();
	}

	public function getAll(){
		$menus = Db::name('menu')->field(true)->select();
		$tree = new Tree();
		$menus = $tree->toFormatTree($menus);
		$menus = array_merge([
					['id' => 0, 'title_show' => '顶级菜单']
				], $menus);
		return $menus;
	}

	public function getMenuList(){
		$request = Request::instance();
		$pid = $request->param('pid',0);
		$map['pid'] = $pid;
		if($request->has('title','param')) $map['title'] = ['like','%' . $request->get('title') . '%'];
		$all_menu = Db::name('menu')->column('id,title');
		$list = collection(MenuModel::getLists($map))->toArray();
		int_to_string($list, array(
			'hide'   => array(1 => '是', 0 => '否'),
			'is_dev' => array(1 => '是', 0 => '否')
			)
		);

		if($list){
			foreach ($list as &$key) {
				if ($key['pid']) {
					$key['up_title'] = $all_menu[$key['pid']];
				}
			}

		}
		return $list;
	}

	public function getMenus($controller = '')
	{
		if(empty($menus)){
			$where = ['pid' => 0, 'hide' => 0];
			if(!(Config::get('DEVELOP_MODE'))) $where['is_dev'] =0;
			$request = Request::instance();
			$controller = (!empty($controller))? $controller:$controller=$request->controller();
			$action = $request->action();
			$module = $request->module();
			$menus['main'] = Db::name('menu')->where($where)->order('sort asc')->select();
			$menus['child'] = [];
			$current = Db::name('menu')->where('url like \'' . $controller . '/' .$action .'%\'')->field('id')->find();
			if(!$current){
				$current = Db::name('menu')->where('url like \'' . $controller . '/%\'')->field('id')->find();
			}

			if($current) {
				$nav = $this->getPath($current['id']);
				$nav_first_title= $nav[0]['title'];
				foreach($menus['main'] as $key => $item) {
					if(!is_array($item) || empty($item['title']) || empty($item['url']))
					{
						return false;
					}
					if(stripos($item['url'], $module) !== 0) {
						$item['url'] = $module . '/' . $item['url'];
					}
					// var_dump($item);
					if(!IS_ROOT && !$this->checkRule($item['url'], AuthRule::RULE_MAIN,null)) {
						unset($menus['main'][$key]);
						continue;
					}
					if($item['title'] == $nav_first_title) {
						$menus['main'][$key]['class'] = 'current';
						$groups = Db::name('menu')->where('pid', $item['id'])->distinct(true)->field('`group`')->select();
						if ($groups) {
							$groups = array_column($groups, 'group');
						}else{
							$groups = array();
						}
						$where = [];
						$where['pid'] = $item['id'];
						$where['hide'] = 0;
						if (!(Config::get('DEVELOP_MODE'))) {
	                        $where['is_dev'] = 0;
	                    }
	                    $second_urls = Db::name('menu')->where($where)->column('id,url');

	                    if (!IS_ROOT) {
	                    	$to_check_urls = [];
	                    	foreach ($second_urls as $key => $to_check_url) {
	                    		if (stripos($to_check_url, $module) !== 0) {
	                    			$rule = $module . '/' . $to_check_url;
	                    		} else {
	                    			$rule = $to_check_url;
	                    		}

	                    		if ($this->checkRule($rule, AuthRule::RULE_URL, null)) {
	                    			$to_check_urls[] = $to_check_url;
	                    		}
	                    	}
	                    }

	                    foreach($groups as $g){
	                    	$map = ['group' => $g];
	                    	if (isset($to_check_urls)) {
	                    	 	if (empty($to_check_urls)) {
	                    	 		continue;
	                    	 	}else{
	                    	 		$map['url'] = array('in', $to_check_urls);
	                    	 	}
	                    	}
	                    	$map['pid'] = $item['id'];
	                        $map['hide'] = 0;
	                        if(!(Config::get('DEVELOP_MODE'))) {
	                        	$map['is_dev'] = 0;
	                        }

	                        $menuList = Db::name('menu')->where($map)->field('id,pid,title,url,tip,ico_name')->order('sort asc')->select();
	                        $menus['child'][$g] = list_to_tree($menuList, 'id', 'pid', 'operater', $item['id']);
	                    }
	                    if ($menus['child'] === array()) {}
					}else{
						$menus['main'][$key]['class'] = '';
					}
				}
			}
		}
		return $menus;
	}

	public function getPath($id) 
	{
		$path = [];
		$nav = Db::name('menu')->where('id',$id)->field('id,pid,title')->find();
		$path[] = $nav;
		if(0 < $nav['pid']){
			$path = array_merge($this->getPath($nav['pid']), $path);
		}
		return $path;
	}

	public function checkRule($rule, $type = AuthRule::RULE_URL, $mode = 'url')
    {
        if (IS_ROOT) {
            return true;
        }

        static $Auth;

        if (!$Auth) {
            $Auth = new \Auth();
        }

        if (!$Auth->check($rule, UID, $type, $mode)) {
            return false;
        }

        return true;
    }
}