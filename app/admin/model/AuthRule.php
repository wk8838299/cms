<?php
namespace app\admin\model;
use think\Model;
use think\Db;

class AuthRule extends Model
{
	const RULE_URL = 1;
	const RULE_MAIN = 2;

	public function updateRules($nodes){
		$AuthRule = Db::name('AuthRule');
		$map = array(
			'module' => 'admin',
			'type'   => array('in', '1,2')
			);
		$rules = $AuthRule->where($map)->order('name')->select();
		$data = array();

		foreach ($nodes as $value) {
			$temp['name'] = $value['url'];
			$temp['title'] = $value['title'];
			$temp['module'] = 'admin';

			if (0 < $value['pid']) {
				$temp['type'] = self::RULE_URL;
			}
			else {
				$temp['type'] = self::RULE_MAIN;
			}

			$temp['status'] = 1;
			$data[strtolower($temp['name'] . $temp['module'] . $temp['type'])] = $temp;
		}

		$update = array();
		$ids = array();
		foreach ($rules as $index => $rule) {
			$key = strtolower($rule['name'] . $rule['module'] . $rule['type']);
			if (isset($data[$key])) {
				$data[$key]['id'] = $rule['id'];
				$update[] = $data[$key];
				unset($data[$key]);
				unset($rules[$index]);
				unset($rule['condition']);
				$diff[$rule['id']] = $rule;
			}
			else if ($rule['status'] == 1) {
				$ids[] = $rule['id'];
			}
		}
	
		if (count($update)) {
			foreach ($update as $k => $row) {
				if ($row != $diff[$row['id']]) {
					$AuthRule->where(array('id' => $row['id']))->update($row);
				}
			}
		}

		if (count($ids)) {
			$AuthRule->where(array(
				'id' => array('IN', implode(',', $ids))
				))->update(array('status' => -1));
		}

		if (count($data)) {
			$AuthRule->insertAll(array_values($data));
		}
	}
}