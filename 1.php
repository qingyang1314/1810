PHP发送HTTP请求的6种方法
复制代码
    方法1: 用 file_get_contents 以get方式获取内容：


<?php
$url = 'https://wenda.shukaiming.com/';
echo file_get_contents($url);
?>


    1
    2
    3
    4


    方法2: 用fopen打开url, 以get方式获取内容：


<?php
//r标识read，即标识只读
$fp = fopen($url, 'r');
stream_get_meta_data($fp);
while (!feof($fp)) {
    $body.= fgets($fp, 1024);
}
echo $body;
fclose($fp);
?>


    方法3：用 file_get_contents函数,以post方式获取url


<?php
$data = array(‘foo' => ‘bar');
$data = http_build_query($data);
$opts = array('http' => array('method' => 'POST', 'header' => 'Content-type: application/x-www-form-urlencodedrn' . 'Content-Length: ' . strlen($data) . '\r\n', 'content' => $data));
$context = stream_context_create($opts);
$html = file_get_contents('https://wenda.shukaming.com', false, $context);
echo $html;
?>




    方法4：用fsockopen函数打开url，以get方式获取完整的数据，包括header和body,fsockopen需要 PHP.ini 中 allow_url_fopen 选项开启


<?php
function get_url($url, $cookie = false) {
    $url = parse_url($url);
    $query = $url[path] . ” ? ” . $url[query];
    echo $query;
    $fp = fsockopen($url[host], $url[port] ? $url[port] : 80, $errno, $errstr, 30);
    if (!$fp) {
        return false;
    } else {
        $request = "GET $query HTTP/1.1\r\n";
        $request.= "Host: $url[host]\r\n";
        $request.= "Connection: Close\r\n";
        if ($cookie) $request.= "Cookie:  $cookien";
        $request.= "\r\n";
        fwrite($fp, $request);
        while (!@feof($fp)) {
            $result.= @fgets($fp, 1024);
        }
        fclose($fp);
        return $result;
    }
}
//获取url的html部分，去掉header
function GetUrlHTML($url, $cookie = false) {
    $rowdata = get_url($url, $cookie);
    if ($rowdata) {
        $body = stristr($rowdata, ”rnrn”);
        $body = substr($body, 4, strlen($body));
        return $body;
    }
    return false;
}
?>





    方法5：用fsockopen函数打开url，以POST方式获取完整的数据，包括header和body


<?php
function HTTP_Post($URL, $data, $cookie, $referrer = "") {
    // parsing the given URL
    $URL_Info = parse_url($URL);
    // Building referrer
    if ($referrer == "") // if not given use this script as referrer
    $referrer = "111";
    // making string from $data
    foreach ($data as $key => $value) $values[] = "$key=" . urlencode($value);
    $data_string = implode("&", $values);
    // Find out which port is needed – if not given use standard (=80)
    if (!isset($URL_Info["port"])) $URL_Info["port"] = 80;
    // building POST-request:
    $request.= "POST " . $URL_Info["path"] . " HTTP/1.1\n";
    $request.= "Host: " . $URL_Info["host"] . "\n";
    $request.= "Referer: $referer\n";
    $request.= "Content-type: application/x-www-form-urlencodedn";
    $request.= "Content-length: " . strlen($data_string) . "\n";
    $request.= "Connection: closen";
    $request.= "Cookie:  $cookien";
    $request.= "\n";
    $request.= $data_string . "\n";
    $fp = fsockopen($URL_Info["host"], $URL_Info["port"]);
    fputs($fp, $request);
    while (!feof($fp)) {
        $result.= fgets($fp, 1024);
    }
    fclose($fp);
    return $result;
}
?>






    方法6:使用curl库，使用curl库之前，可能需要查看一下php.ini是否已经打开了curl扩展


<?php
$ch = curl_init();
$timeout = 5;
curl_setopt($ch, CURLOPT_URL, 'http://wenda.shukaiming.com');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$file_contents = curl_exec($ch);
curl_close($ch);
echo $file_contents;
?>