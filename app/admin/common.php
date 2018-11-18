<?php
use think\Config;

function is_administrator($uid = NULL)
{
	$uid = (is_null($uid) ? session('admin_id') : $uid);
	return $uid && (intval($uid) === Config::get('USER_ADMINISTRATOR'));
}

function check($data, $rule = NULL, $ext = NULL)
{
	$data = trim(str_replace(PHP_EOL, '', $data));

	if (empty($data)) {
		return false;
	}

	$validate['require'] = '/.+/';
	$validate['url'] = '/^http(s?):\\/\\/(?:[A-za-z0-9-]+\\.)+[A-za-z]{2,4}(?:[\\/\\?#][\\/=\\?%\\-&~`@[\\]\':+!\\.#\\w]*)?$/';
	$validate['currency'] = '/^\\d+(\\.\\d+)?$/';
	$validate['number'] = '/^\\d+$/';
	$validate['zip'] = '/^\\d{6}$/';
	$validate['cny'] = '/^(([1-9]{1}\\d*)|([0]{1}))(\\.(\\d){1,2})?$/';
	$validate['integer'] = '/^[\\+]?\\d+$/';
	$validate['double'] = '/^[\\+]?\\d+(\\.\\d+)?$/';
	$validate['english'] = '/^[A-Za-z]+$/';
	$validate['idcard'] = '/^([0-9]{15}|[0-9]{17}[0-9a-zA-Z])$/';
	$validate['truename'] = '/^[\\x{4e00}-\\x{9fa5}]{2,4}$/u';
	$validate['username'] = '/^[a-zA-Z]{1}[0-9a-zA-Z_]{4,15}$/';
	$validate['email'] = '/^\\w+([-+.]\\w+)*@\\w+([-.]\\w+)*\\.\\w+([-.]\\w+)*$/';
	$validate['mobile'] = '/^(((1[0-9][0-9]{1})|159|153)+\\d{8})$/';
	$validate['password'] = '/^[a-zA-Z0-9_\\@\\#\\$\\%\\^\\&\\*\\(\\)\\!\\,\\.\\?\\-\\+\\|\\=]{6,16}$/';
	$validate['xnb'] = '/^[a-zA-Z]$/';

	if (isset($validate[strtolower($rule)])) {
		$rule = $validate[strtolower($rule)];
		return preg_match($rule, $data);
	}

	$Ap = '\\x{4e00}-\\x{9fff}' . '0-9a-zA-Z\\@\\#\\$\\%\\^\\&\\*\\(\\)\\!\\,\\.\\?\\-\\+\\|\\=';
	$Cp = '\\x{4e00}-\\x{9fff}';
	$Dp = '0-9';
	$Wp = 'a-zA-Z';
	$Np = 'a-z';
	$Tp = '@#$%^&*()-+=';
	$_p = '_';
	$pattern = '/^[';
	$OArr = str_split(strtolower($rule));
	in_array('a', $OArr) && ($pattern .= $Ap);
	in_array('c', $OArr) && ($pattern .= $Cp);
	in_array('d', $OArr) && ($pattern .= $Dp);
	in_array('w', $OArr) && ($pattern .= $Wp);
	in_array('n', $OArr) && ($pattern .= $Np);
	in_array('t', $OArr) && ($pattern .= $Tp);
	in_array('_', $OArr) && ($pattern .= $_p);
	isset($ext) && ($pattern .= $ext);
	$pattern .= ']+$/u';
	// var_dump($data);die;
	return preg_match($pattern, $data);
}

/* 登录过滤 */
function filter_login($data){
	if(empty($data)) return $data;
	
	if(is_array($data)) return array_map('filter_params',$data);
	
	return filter_params($data);
}

function filter_params($data){
	$filter = ['/\'/','/;/','/and/','/or/',
	'/update/','/insert/','/select/','/delete/','/&/',
	'/sql/','/SQL/','/UPDATE/','/INSERT/','/SELECT/','/AND/',
	];
	$data = trim(strip_tags(preg_replace($filter, '', $data)));
	return $data;
}

function passwords($password, $salt ='yocms_'){
	return md5(md5($password).md5($salt));
}

function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
{
	$tree = array();

	if (is_array($list)) {
		$refer = array();

		foreach ($list as $key => $data) {
			$refer[$data[$pk]] = &$list[$key];
		}

		foreach ($list as $key => $data) {
			$parentId = $data[$pid];

			if ($root == $parentId) {
				$tree[] = &$list[$key];
			}
			else if (isset($refer[$parentId])) {
				$parent = &$refer[$parentId];
				$parent[$child][] = &$list[$key];
			}
		}
	}

	return $tree;
}

function tree_to_list($tree, $child = '_child', $order = 'id', &$list = array())
{
	if (is_array($tree)) {
		$refer = array();

		foreach ($tree as $key => $value) {
			$reffer = $value;

			if (isset($reffer[$child])) {
				unset($reffer[$child]);
				tree_to_list($value[$child], $child, $order, $list);
			}

			$list[] = $reffer;
		}

		$list = list_sort_by($list, $order, $sortby = 'asc');
	}

	return $list;
}

function int_to_string(&$data, $map = array(
	'status' => array(1 => '正常', -1 => '删除', 0 => '禁用', 2 => '未审核', 3 => '草稿')
	))
{
	if (($data === false) || ($data === NULL)) {
		return $data;
	}

	$data = (array) $data;
	
	foreach ($data as $key => $row) {
		foreach ($map as $col => $pair) {
			if (isset($row[$col]) && isset($pair[$row[$col]])) {
				$data[$key][$col . '_text'] = $pair[$row[$col]];
			}
		}
	}

	return $data;
}

function model_error($code, $msg){
	return ['code' => $code, 'msg' => $msg];
}