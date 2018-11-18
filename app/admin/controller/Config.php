<?php
namespace app\admin\controller;
use think\Db;
use think\Request;

class Config extends Admin 
{
	public function index()
	{

		$data = Db::name('config')->where('id', 1)->find();
		$this->assign('data',$data);
		return $this->fetch('index');
	}

	public function edit()
	{
		$data = filter_login(Request::instance()->post());

		if (Db::name('config')->where('id',1)->update($data)) {
			$this->success('修改成功！');
		}
		else {
			$this->error('修改失败');
		} 
	}

	public function image()
	{
		// $upload = new \Think\Upload();
		// $upload->maxSize = 3145728;
		// $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
		// $upload->rootPath = './Upload/public/';
		// $upload->autoSub = false;
		// $info = $upload->upload();

		// foreach ($info as $k => $v) {
		// 	$path = $v['savepath'] . $v['savename'];
		// 	echo $path;
		// 	exit();
		// }
		// 获取表单上传文件 例如上传了001.jpg
	    $file = request()->file('upload_file0');
	    //var_dump($file);die;
	    // 移动到框架应用根目录/public/upload/ 目录下
	    if($file){
	         $info = $file->validate(['size'=>3145728,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'static' . DS . 'upload' . DS . 'common');
	        if($info){
	            // 成功上传后 获取上传信息
	            // 输出 jpg
	            //echo $info->getExtension();
	            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
	            echo $info->getSaveName();exit();
	            // 输出 42a79759f284b767dfcb2a0197904287.jpg
	            //echo $info->getFilename(); 
	        }else{
	            // 上传失败获取错误信息
	            echo $file->getError();
	        }
	    }
	}
}