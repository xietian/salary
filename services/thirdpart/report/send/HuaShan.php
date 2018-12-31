<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/11 0011
 * Time: 17:19
 */

namespace app\services\thirdpart\report\send;


use app\services\thirdpart\ApiService;
use Yii;

class HuaShan extends ThirdPlatBase {

//	private $huashanServerUrl = 'http://118.89.148.213:8080/snapecg/images/';
	private $huashanServerUrl = 'http://dongmom.com:8080/snapecg/images';

	/**
	 * 上传报告
	 *
	 * @param int $phone 手机号
	 * @param string $filePath 报告地址
	 * @param int $recordId 报告ID
	 * @param int $recordCreateTime 报告记录时间
	 *
	 * @return bool
	 */
	public function uploadReportWithRecordId($phone, $filePath, $recordId, $recordCreateTime){
		$param = [
			'idcard' => $phone,
			'report_id' => $recordId,
			'record_create_time' => date('c', $recordCreateTime)
		];
		$res = ApiService::post($this->huashanServerUrl, $param, true, true);
		if($res) {
			// CURLINFO_HTTP_CODE 为201时为成功
			if (isset($res['record_create_time']) && isset($res['idcard'])) {
				return true;
			}
		}
		Yii::error('调用华山医院接口出错：' . var_export($res, true), 'yii\error\sendHuashanReport');

		return false;
	}

	public function unbind( $phone, $doctorId ) {
	}

}
