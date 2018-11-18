<?php
namespace app\index\controller;
use think\captcha\Captcha;
use think\Controller;
class Index extends Controller
{
    public function index()
    {
       dump(config());
    }
}
