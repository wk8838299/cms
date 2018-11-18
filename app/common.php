<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use think\Db;
use think\Request;
use think\Response;
use app\admin\controller\Auth;
use think\Lang;

/**
 * curl访问
 * @author rainfer <81818832@qq.com>
 * @param  string $url
 * @param string $type
 * @param boolean $data
 * @param string $err_msg
 * @param int $timeout
 * @param array $cert_info
 * @return string
 */
function go_curl($url, $type, $data = false, &$err_msg = null, $timeout = 20, $cert_info = array())
{
	$type = strtoupper($type);
    if ($type == 'GET' && is_array($data)) {
        $data = http_build_query($data);
    }
    $option = array();
    if ( $type == 'POST' ) {
        $option[CURLOPT_POST] = 1;
    }
    if ($data) {
        if ($type == 'POST') {
            $option[CURLOPT_POSTFIELDS] = $data;
        } elseif ($type == 'GET') {
            $url = strpos($url, '?') !== false ? $url.'&'.$data :  $url.'?'.$data;
        }
    }
    $option[CURLOPT_URL]            = $url;
    $option[CURLOPT_FOLLOWLOCATION] = TRUE;
    $option[CURLOPT_MAXREDIRS]      = 4;
    $option[CURLOPT_RETURNTRANSFER] = TRUE;
    $option[CURLOPT_TIMEOUT]        = $timeout;
    //设置证书信息
    if(!empty($cert_info) && !empty($cert_info['cert_file'])) {
        $option[CURLOPT_SSLCERT]       = $cert_info['cert_file'];
        $option[CURLOPT_SSLCERTPASSWD] = $cert_info['cert_pass'];
        $option[CURLOPT_SSLCERTTYPE]   = $cert_info['cert_type'];
    }
    //设置CA
    if(!empty($cert_info['ca_file'])) {
        // 对认证证书来源的检查，0表示阻止对证书的合法性的检查。1需要设置CURLOPT_CAINFO
        $option[CURLOPT_SSL_VERIFYPEER] = 1;
        $option[CURLOPT_CAINFO] = $cert_info['ca_file'];
    } else {
        // 对认证证书来源的检查，0表示阻止对证书的合法性的检查。1需要设置CURLOPT_CAINFO
        $option[CURLOPT_SSL_VERIFYPEER] = 0;
    }
    $ch = curl_init();
    curl_setopt_array($ch, $option);
    $response = curl_exec($ch);
    $curl_no  = curl_errno($ch);
    $curl_err = curl_error($ch);
    curl_close($ch);
    // error_log
    if($curl_no > 0) {
        if($err_msg !== null) {
            $err_msg = '('.$curl_no.')'.$curl_err;
        }
    }
    return $response;
}
/**
 * 设置全局配置到文件
 *
 * @param $key
 * @param $value
 * @return boolean
 */
function sys_config_setbykey($key, $value)
{
    $file = ROOT_PATH.'data/conf/config.php';
    $cfg = array();
    if (file_exists($file)) {
        $cfg = include $file;
    }
    $item = explode('.', $key);

    switch (count($item)) {
        case 1:
            $cfg[$item[0]] = $value;
            break;
        case 2:
            $cfg[$item[0]][$item[1]] = $value;
            break;
    }
    return file_put_contents($file, "<?php\nreturn " . var_export($cfg, true) . ";");
}
/**
 * 设置全局配置到文件
 *
 * @param array
 * @return boolean
 */
function sys_config_setbyarr($data)
{
    $file = ROOT_PATH.'data/conf/config.php';
    if(file_exists($file)){
        $configs=include $file;
    }else {
        $configs=array();
    }
    $configs=array_merge($configs,$data);
    return file_put_contents($file, "<?php\treturn " . var_export($configs, true) . ";");
}
/**
 * 获取全局配置
 *
 * @param $key
 * @return array|null
 */
function sys_config_get($key)
{
    $file = ROOT_PATH.'data/conf/config.php';
    $cfg = array();
    if (file_exists($file)) {
        $cfg = (include $file);
    }
    return isset($cfg[$key]) ? $cfg[$key] : null;
}
/**
 * 返回带协议的域名
 * @author rainfer <81818832@qq.com>
 */
function get_host()
{
    $host=$_SERVER["HTTP_HOST"];
    $protocol=Request::instance()->isSsl()?"https://":"http://";
    return $protocol.$host;
}
/**
 * ajax数据返回，规范格式
 * @param array $data   返回的数据，默认空数组
 * @param string $msg   信息，一般用于错误信息提示
 * @param int $code     错误码，0-未出现错误|其他出现错误
 * @param array $extend 扩展数据
 * @return string
 */
function ajax_return($data = [], $msg = "", $code = 0, $extend = [])
{
    $msg=empty($msg)?'失败':$msg;
    $ret = ["code" => $code, "msg" => $msg, "data" => $data];
    $ret = array_merge($ret, $extend);
    return Response::create($ret, 'json');
}

/**
 * 随机字符
 * @param int $length 长度
 * @param string $type 类型
 * @param int $convert 转换大小写 1大写 0小写
 * @return string
 */
function random($length=10, $type='letter', $convert=0)
{
    $config = array(
        'number'=>'1234567890',
        'letter'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'string'=>'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789',
        'all'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    );

    if(!isset($config[$type])) $type = 'letter';
    $string = $config[$type];

    $code = '';
    $strlen = strlen($string) -1;
    for($i = 0; $i < $length; $i++){
        $code .= $string{mt_rand(0, $strlen)};
    }
    if(!empty($convert)){
        $code = ($convert > 0)? strtoupper($code) : strtolower($code);
    }
    return $code;
}

/**
 * 是否存在控制器
 * @param string $module 模块
 * @param string $controller 待判定控制器名
 * @return boolean
 */
function has_controller($module,$controller)
{
	$arr=\ReadClass::readDir(APP_PATH . $module. DS .'controller');
    if((!empty($arr[$controller])) && $arr[$controller]['class_name']==$controller){
        return true;
    }else{
        return false;
    }
}

/**
 * 是否存在方法
 * @param string $module 模块
 * @param string $controller 待判定控制器名
 * @param string $action 待判定控制器名
 * @return number 方法结果，0不存在控制器 1存在控制器但是不存在方法 2存在控制和方法
 */
function has_action($module,$controller,$action)
{
	$arr=\ReadClass::readDir(APP_PATH . $module. DS .'controller');
    if((!empty($arr[$controller])) && $arr[$controller]['class_name']==$controller ){
		$method_name=array_map('array_shift',$arr[$controller]['method']);
        if(in_array($action, $method_name)){
           return 2;
        }else{
           return 1;
        }
    }else{
        return 0;
    }
}

/**
 * 返回不含前缀的数据库表数组
 *
 * @author rainfer <81818832@qq.com>
 * @param bool
 * @return array
 */
function db_get_tables($prefix=false)
{
    $db_prefix =config('database.prefix');
    $list  = Db::query('SHOW TABLE STATUS FROM '.config('database.database'));
    $list  = array_map('array_change_key_case', $list);
    $tables = array();
    foreach($list as $k=>$v){
        if(empty($prefix)){
            if(stripos($v['name'],strtolower(config('database.prefix')))===0){
                $tables [] = strtolower(substr($v['name'], strlen($db_prefix)));
            }
        }else{
            $tables [] = strtolower($v['name']);
        }

    }
    return $tables;
}

/**
 * 返回数据表的sql
 *
 * @author rainfer <81818832@qq.com>
 *
 * @param $table : 不含前缀的表名
 * @return string
 */
function db_get_insert_sqls($table)
{
    $db_prefix =config('database.prefix');
    $db_prefix_re = preg_quote($db_prefix);
    $db_prefix_holder = db_get_db_prefix_holder();
    $export_sqls = array();
    $export_sqls [] = "DROP TABLE IF EXISTS $db_prefix_holder$table";
    switch (config('database.type')) {
        case 'mysql' :
            if (!($d = Db::query("SHOW CREATE TABLE $db_prefix$table"))) {
                $this->error("'SHOW CREATE TABLE $table' Error!");
            }
            $table_create_sql = $d [0] ['Create Table'];
            $table_create_sql = preg_replace('/' . $db_prefix_re . '/', $db_prefix_holder, $table_create_sql);
            $export_sqls [] = $table_create_sql;
            $data_rows = Db::query("SELECT * FROM $db_prefix$table");
            $data_values = array();
            foreach ($data_rows as &$v) {
                foreach ($v as &$vv) {
                    //TODO mysql_real_escape_string替换方法
                    //$vv = "'" . @mysql_real_escape_string($vv) . "'";
					$vv = "'" . addslashes(str_replace(array("\r","\n"),array('\r','\n'),$vv)) . "'";
                }
                $data_values [] = '(' . join(',', $v) . ')';
            }
            if (count($data_values) > 0) {
                $export_sqls [] = "INSERT INTO `$db_prefix_holder$table` VALUES \n" . join(",\n", $data_values);
            }
            break;
    }
    return join(";\n", $export_sqls) . ";";
}

/**
 * 检测当前数据库中是否含指定表
 *
 * @author rainfer <81818832@qq.com>
 *
 * @param $table : 不含前缀的数据表名
 * @return bool
 */
function db_is_valid_table_name($table)
{
    return in_array($table, db_get_tables());
}

/**
 * 不检测表前缀,恢复数据库
 *
 * @author rainfer <81818832@qq.com>
 *
 * @param $file
 * @param $prefix
 */
function db_restore_file($file,$prefix='')
{
    $prefix=$prefix?:db_get_db_prefix_holder();
    $db_prefix=config('database.prefix');
    $sqls = file_get_contents($file);
    $sqls = str_replace($prefix, $db_prefix, $sqls);
    $sqlarr = explode(";\n", $sqls);
    foreach ($sqlarr as &$sql) {
        Db::execute($sql);
    }
}
/**
 * 返回表前缀替代符
 * @author rainfer <81818832@qq.com>
 *
 * @return string
 */
function db_get_db_prefix_holder()
{
    return '<--db-prefix-->';
}

/**
 * 强制下载
 * @author rainfer <81818832@qq.com>
 *
 * @param string $filename
 * @param string $content
 */
function force_download_content($filename, $content)
{
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Transfer-Encoding: binary");
    header("Content-Disposition: attachment; filename=$filename");
    echo $content;
    exit ();
}