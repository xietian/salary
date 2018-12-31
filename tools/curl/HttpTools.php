<?php
namespace app\tools\curl;

use CURLFile;
use Yii;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/10/10
 * Time: 20:25
 */
class HttpTools
{
    static function http_get($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if (stripos($url, 'https://') !== FALSE) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_SSLVERSION, 1);
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    static function get_http_headers($url, $opt = 0)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        if (stripos($url, 'https://') !== FALSE) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_SSLVERSION, 1);
        }
        curl_exec($ch);
        if ($opt) {
            $res = curl_getinfo($ch, $opt);
        } else {
            $res = curl_getinfo($ch);
        }
        curl_close($ch);
        return $res;

    }

    static function http_post($url, $data, $isJson = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if (stripos($url, 'https://') !== FALSE) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_SSLVERSION, 1);
        }
        if (empty($data) == false) {
            if (is_array($data)) {
                if ($isJson) {
                    $data = json_encode($data);
                } else {
                    foreach ($data as $key => $val) {
                        if (stripos($val, '@') === 0) {
                            if (version_compare(PHP_VERSION, "5.5", ">")) {
                                $data[$key] = new CURLFile(trim($val, "@"));
                            }
                        }
                    }
                }
            }
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if ($isJson) { // 发送JSON数据
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json'
            ));
        }
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    static function https_post($url, $data)
    {
        return self::http_post($url, $data);
    }

    static function http_copy($url, $file = "", $header, $timeout = 60)
    {
        $file = empty($file) ? pathinfo($url, PATHINFO_BASENAME) : $file;
        $dir = pathinfo($file, PATHINFO_DIRNAME);
        !is_dir($dir) && @mkdir($dir, 0755, true);
        $url = str_replace(" ", "%20", $url);

        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $temp = curl_exec($ch);
            if (@file_put_contents($file, $temp) && !curl_error($ch)) {
                return $file;
            } else {
                return false;
            }
        } else {
            $opts = array(
                "http" => array(
                    "method" => "GET",
                    "header" => $header . join("\r\n"),
                    "timeout" => $timeout)
            );
            $context = stream_context_create($opts);
            if (@copy($url, $file, $context)) {
                //$http_response_header
                return $file;
            } else {
                return false;
            }
        }
    }

    /**
     *  post 文件内容
     *
     * */
    public static function postImageContent($url, $assoc = array(), $files = array(), $data_type = "json")
    {

        $cl = curl_init();
        $optionItems = array();
        if (stripos($url, 'https://') !== FALSE) {
            $optionItems[CURLOPT_SSL_VERIFYPEER] = FALSE;
            $optionItems[CURLOPT_SSL_VERIFYHOST] = FALSE;
            $optionItems[CURLOPT_SSLVERSION] = 1;
        }
        $optionItems[CURLOPT_URL] = $url;
        $optionItems[CURLOPT_RETURNTRANSFER] = 1;
        //general post file
        static $disallow = array("\0", "\"", "\r", "\n");
        foreach ($assoc as $k => $v) {
            $k = str_replace($disallow, "_", $k);
            $body[] = implode("\r\n", array(
                "Content-Disposition: form-data; name=\"{$k}\"",
                "",
                filter_var($v),
            ));
        }
        foreach ($files as $k => $v) {
            $k = str_replace($disallow, "_", $k);
            $tmpFileName = md5($v) . ".jpg";
            $body[] = implode("\r\n", array(
                "Content-Disposition: form-data; name=\"{$k}\"; filename=\"{$tmpFileName}\"",
                "Content-Type: image/jpeg",
                "",
                $v,
            ));
        }
        do {
            $boundary = "---------------------" . md5(mt_rand() . microtime());
        } while (preg_grep("/{$boundary}/", $body));
        array_walk($body, function (&$part) use ($boundary) {
            $part = "--{$boundary}\r\n{$part}";
        });
        $body[] = "--{$boundary}--";
        $body[] = "";
        $optionItems[CURLOPT_POST] = true;
        $optionItems[CURLOPT_POSTFIELDS] = implode("\r\n", $body);

        $optionItems[CURLOPT_HTTPHEADER] = array(

            "Content-Type: multipart/form-data; boundary={$boundary}",
        );
        curl_setopt_array($cl, $optionItems);
        $content = curl_exec($cl);
        $status = curl_getinfo($cl);
        curl_close($cl);
        if (isset($status['http_code']) && $status['http_code'] == 200) {
            if ($data_type == 'json') {
                $content = json_decode($content, true);
            }
            return $content;
        }
        return false;
    }

    /**
     * 模拟POST请求
     *
     * @param string $url
     * @param array $fields
     * @param string $data_type
     *
     * @return mixed
     *
     * Examples:
     * ```
     * HttpCurl::post('http://api.example.com/?a=123', array('abc'=>'123', 'efg'=>'567'), 'json');
     * HttpCurl::post('http://api.example.com/', '这是post原始内容', 'json');
     * 文件post上传
     * HttpCurl::post('http://api.example.com/', array('abc'=>'123', 'file1'=>'@/data/1.jpg'), 'json');
     * ```
     */
    static public function post($url, $fields, $data_type = 'text')
    {
        $cl = curl_init();
        if (stripos($url, 'https://') !== FALSE) {
            curl_setopt($cl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($cl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($cl, CURLOPT_SSLVERSION, 1);
        }
        curl_setopt($cl, CURLOPT_URL, $url);
        curl_setopt($cl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cl, CURLOPT_POST, true);
        curl_setopt($cl, CURLOPT_POSTFIELDS, $fields);
        $content = curl_exec($cl);
        $status = curl_getinfo($cl);
        curl_close($cl);
        if (isset($status['http_code']) && $status['http_code'] == 200) {
            if ($data_type == 'json') {
                $content = json_decode($content, true);
            }
            return $content;
        }
        return false;
    }
}