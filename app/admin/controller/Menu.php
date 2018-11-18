<?php
namespace app\admin\controller;
use think\Request;
use app\admin\model\Menu as menuModel;

class Menu extends Admin 
{
	//public $request;

	public function _initialize(){
		parent::_initialize();
		Request::instance()->filter(['strip_tags','trim']);
		$this->request = Request::instance();
	}

	public function index()
	{
		$model = model('Menu','logic');
		$list = $model->getMenuList();
		// dump($list);die;
		$this->assign('list', $list);
		return $this->fetch('index');
	}

	public function add()
	{
		if($this->request->isPost()) 
		{
			$data = $this->request->param();
			// 调用当前模型对应的User验证器类进行数据验证
			$validate =  \think\Loader::validate('Menu');
			if(!$validate->check($data)){
    			$this->error($validate->getError());
			}
			$model = model('Menu','model');
			if ($model->insertGetId($data)) {
				$this->success('添加成功', Cookie('__forward__'));
			}else {
					$this->error('添加失败');
			}
		}else{
			$logic = model('Menu','logic');
			$menus =$logic->getAll();

			$this->assign('info', ['pid' => input('pid'),'hide'=>0,'is_dev'=>0]);
			$this->assign('menus', $menus);
			return $this->fetch('edit');
		}
		
	}

	public function edit($id =0)
	{
		
		if ($this->request->isPost()) {
			$data = $this->request->param();
			// 调用当前模型对应的User验证器类进行数据验证
			$validate =  \think\Loader::validate('Menu');
			if(!$validate->check($data)){
    			$this->error($validate->getError());
			}
			$model = model('Menu','model');
			if ($model->save($data,['id'=>$id]) !== false) {
				$this->success('更新成功', Cookie('__forward__'));
			}else {
					$this->error('更新失败');
			}
		}
		else {
			$info = [];
			$logic = model('Menu','logic');
			$info = $logic->getOne($id);
			$menus =$logic->getAll();

			$this->assign('menus', $menus);
			if (false === $info) {
				$this->error('获取后台菜单信息错误');
			}
			$this->assign('info', $info);
			$this->meta_title = '编辑后台菜单';
			return $this->fetch('edit'); 
		}
	}

	public function import($pid =0){
		if($this->request->isPost()) {
			$tree = input('post.tree');
			$lists = explode(PHP_EOL, $tree);
			$menuModel = new menuModel;

			if ($lists == array()) {
				$this->error('请按格式填写批量导入的菜单，至少一个菜单');
			}
			else {
				$pid = input('post.pid');

				foreach ($lists as $key => $value) {
					$record = explode('|', $value);

					if (count($record) == 4) {
						$menuModel->save(array('title' => $record[0], 'url' => $record[1], 'pid' => $pid, 'sort' => 1, 'hide' => 0, 'tip' => '', 'is_dev' => 0, 'group' => $record[2],'ico_name'=>$record[3]));
					}
				}

				$this->success('导入成功', url('menu/index',['pid'=> $pid]));
			}
		}else{
			$data = menuModel::where('id',$pid)->field(true)->find();
			$this->assign('data', $data);
			$this->assign('pid', $pid);
			return $this->fetch('import');
		}
		
	}
}