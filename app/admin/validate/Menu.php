<?php
namespace app\admin\validate;
use think\Validate;

class Menu extends Validate {

	protected $rule = [
        'title'  =>  'require|max:25',
        'sort' =>  'number',
        'url'	=>	'require|max:60',
    ];
    
    protected $message = [
        'title.require'  =>  '名称必须填写',
        'title.max'  =>  '名称不能超过25个字节',
        'sort.number' =>  '排序必须是数字',
        'url.require'  =>  '链接必须填写',
        'url.max'  =>  '链接长度不能超过60',
    ];
   
}