<?php
/**
 * Created by Dong.
 * User: Dong(mailto:techdong@hotmail.com)
 * Date: 2015-08-01
 * Time: 23:51
 */

define('DB_HOST', "localhost");
define('DB_PORT', 3306);
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_DATABASE', 'test');

$config = array(
    'appId' => "wxb51b697eeeed61d7",
    'secret' => "9e197a361b2e68ead1926dfd42b0a620",
    'redirect' => "http://reinchat.com:8002/wx/cb.php",
);


function get_from_url($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $res = curl_exec($ch);
    curl_close($ch);
    return $res;
}

function S($k, $v = null, $expire = 100000)
{
    //echo 'get it', $k, $v;
    $f = __DIR__ . '/' . $k . '.cache';
    if ($v != null) {
        $c = serialize(array('k' => $k, 'v' => $v, 'e' => $expire, 'b' => time()));
        file_put_contents($f, $c);
    }
    if ($v == null && file_exists($f)) {
        $c = unserialize(file_get_contents($f));
        return time() - $c['b'] < $c['e'] ? $c['v'] : null;
    }
    return null;
}

function logErr($msg)
{
    $f = __DIR__ . '/log.txt';
    file_put_contents($f, $msg . "\r\n", FILE_APPEND);
}