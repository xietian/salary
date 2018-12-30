<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2018/11/22
 * Time: 14:20
 */

namespace app\tools;


use core\DefaultAcsClient;
use core\Profile\DefaultProfile;
use vod\Request\V20170321\CreateUploadVideoRequest;
use vod\Request\V20170321\GetPlayInfoRequest;
use vod\Request\V20170321\GetVideoInfoRequest;
use vod\Request\V20170321\RefreshUploadVideoRequest;

class VodTools
{
    public $regionId;
    public $client;

    public function __construct()
    {
        $this->regionId = \Yii::$app->params['vod']['region_id'];
        $access_key_id = \Yii::$app->params['vod']['access_key_id'];
        $access_key_secret = \Yii::$app->params['vod']['access_key_secret'];
        $profile = DefaultProfile::getProfile($this->regionId, $access_key_id, $access_key_secret);
        $this->client = new DefaultAcsClient($profile);
    }

    /**
     * 上传接口
     * @param $title
     * @param $fileName
     * @param int $fileSize
     * @param string $description
     * @param string $coverUrl
     * @param string $ip
     * @param string $tags
     * @param int $cateId
     * @return mixed|\SimpleXMLElement
     */
    function create_upload_video($title, $fileName, $fileSize = 0, $description = "", $coverUrl = "", $ip = "127.0.0.1", $tags = "", $cateId = 0)
    {
        $request = new CreateUploadVideoRequest();
        //视频源文件标题(必选)
        $request->setTitle($title);
        //视频源文件名称，必须包含扩展名(必选)
        $request->setFileName($fileName);
        //视频源文件字节数(可选)
        $request->setFileSize($fileSize);
        //视频源文件描述(可选)
        $request->setDescription($description);
        //自定义视频封面URL地址(可选)
        $request->setCoverURL($coverUrl);
        //上传所在区域IP地址(可选)
        $request->setIP($ip);
        //视频标签，多个用逗号分隔(可选)
        $request->setTags($tags);
        //视频分类ID(可选)
        $request->setCateId($cateId);
        $response = $this->client->getAcsResponse($request);
//        $obj = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);

        $responseStr = json_encode($response);
        $jsonArray = json_decode($responseStr, true);
        return $jsonArray;
    }

    /**
     * 刷新上传文件
     * @param $videoId
     * @return mixed
     */
    function refresh_upload_video($videoId)
    {
        $request = new RefreshUploadVideoRequest();
        //视频ID(必选)
        $request->setVideoId($videoId);
        $response = $this->client->getAcsResponse($request);
//        $obj = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
        $responseStr = json_encode($response);
        $jsonArray = json_decode($responseStr, true);
        return $jsonArray;
    }

    /**
     * 获取视频信息
     * @param $videoId
     * @return mixed
     */
    public function get_video_info($videoId)
    {
        $request = new GetVideoInfoRequest();
        //视频ID(必选)
        $request->setVideoId($videoId);
        $response = $this->client->getAcsResponse($request);
//        $obj = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
        $responseStr = json_encode($response);
        $jsonArray = json_decode($responseStr, true);
        return $jsonArray;
    }

    public function get_play_info($videoId)
    {
        $request = new GetPlayInfoRequest();
        //视频ID(必选)
        $request->setVideoId($videoId);
        $response = $this->client->getAcsResponse($request);
//        $obj = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
        $responseStr = json_encode($response);
        $jsonArray = json_decode($responseStr, true);
        return $jsonArray;
    }
}