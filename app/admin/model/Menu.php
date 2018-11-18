<?php
namespace app\admin\model;
use think\Model;
use think\Config;
use think\Request;
use think\Db;
use app\admin\model\AuthRule;

class Menu extends Model
{
	public static function getLists($condition){
		$data = self::where($condition)->field(true)->order('sort asc,id asc')->select();
		return $data;
	}

	public function returnNodes($tree = true)
    {
        static $tree_nodes = array();

        if ($tree && !empty($tree_nodes[(int)$tree])) {
            return $tree_nodes[$tree];
        }
        $model = Db::name('Menu');
        if ((int)$tree) {
            $list = $model->field('id,pid,title,url,tip,hide')->order('sort asc')->select();

            foreach ($list as $key => $value) {
                if (stripos($value['url'], Request::instance()->module()) !== 0) {
                    $list[$key]['url'] = Request::instance()->module() . '/' . $value['url'];
                }
            }

            $nodes = list_to_tree($list, $pk = 'id', $pid = 'pid', $child = 'operator', $root = 0);

            foreach ($nodes as $key => $value) {
                if (!empty($value['operator'])) {
                    $nodes[$key]['child'] = $value['operator'];
                    unset($nodes[$key]['operator']);
                }
            }
        } else {
            $nodes = $model->field('title,url,tip,pid')->order('sort asc')->select();

            foreach ($nodes as $key => $value) {
                if (stripos($value['url'], Request::instance()->module()) !== 0) {
                    $nodes[$key]['url'] = Request::instance()->module() . '/' . $value['url'];
                }
            }
        }

        $tree_nodes[(int)$tree] = $nodes;
        return $nodes;
    }
}