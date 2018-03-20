<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/17
 * Time: 16:36
 */
//function getRealIp()
//{
//    $ip=false;
//    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
//        $ip = $_SERVER["HTTP_CLIENT_IP"];
//    }
//    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
//        $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
//        if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
//        for ($i = 0; $i < count($ips); $i++) {
//            if (!eregi ("^(10│172.16│192.168).", $ips[$i])) {
//                $ip = $ips[$i];
//                break;
//            }
//        }
//    }
//    return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
//}
//echo getRealIp();

/**
获取网卡的MAC地址原码；目前支持WIN/LINUX系统
获取机器网卡的物理（MAC）地址
 **/

//class GetMacAddr{
//
//    var $return_array = array(); // 返回带有MAC地址的字串数组
//    var $mac_addr;
//
//    function GetMacAddr($os_type){
//        switch ( strtolower($os_type) ){
//            case "linux":
//                $this->forLinux();
//                break;
//            case "solaris":
//                break;
//            case "unix":
//                break;
//            case "aix":
//                break;
//            default:
//                $this->forWindows();
//                break;
//
//        }
//
//        $temp_array = array();
//        foreach ( $this->return_array as $value ){
//
//            if (
//            preg_match("/[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f][:-]"."[0-9a-f][0-9a-f]/i",$value,
//                $temp_array ) ){
//                $this->mac_addr = $temp_array[0];
//                break;
//            }
//
//        }
//        unset($temp_array);
//        return $this->mac_addr;
//    }
//
//    function forWindows(){
//        @exec("ipconfig /all", $this->return_array);
//        if ( $this->return_array )
//            return $this->return_array;
//        else{
//            $ipconfig = $_SERVER["WINDIR"]."\system32\ipconfig.exe";
//            if ( is_file($ipconfig) )
//                @exec($ipconfig." /all", $this->return_array);
//            else
//                @exec($_SERVER["WINDIR"]."\system\ipconfig.exe /all", $this->return_array);
//            return $this->return_array;
//        }
//    }
//
//    function forLinux(){
//        @exec("ifconfig -a", $this->return_array);
//        return $this->return_array;
//    }
//
//}
////方法使用
//$mac = new GetMacAddr(PHP_OS);
//echo $mac->mac_addr; //这里是机器的真实MAC地址，请注释掉

 $mac_addr = array();
    switch ($os_type) {
        case 'windows':
            @exec("ipconfig /all", $mac_addr);
            break;
        case 'linux':
            @exec("ifconfig -a", $mac_addr);
            break;
        default:
            # code...
            break;
    }
    echo "<pre>";
    echo  $mac_addr;


?>