<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Resources;
use app\tools\VodTools;
use yii\console\Controller;

class ResController extends Controller
{
    /**
     *
     */
    public function actionUp()
    {
        $list = Resources::find()->where('convert_status=:convert_status', array(':convert_status' => Resources::CONVERT_STATUS_CONVERTING))->offset(0)->limit(100)->all();
        if (empty($list) == false) {
            foreach ($list as $model) {
                $vodtools = new VodTools();
                try {
                    print("third_resource_id:" . $model->third_resource_id . " at " . date("Y-m-d H:i:s") . "\n");
                    $info = $vodtools->get_play_info($model->third_resource_id);
                    $data = array();
                    if ("Normal" == $info['VideoBase']['Status']) {
                        foreach ($info['PlayInfoList']['PlayInfo'] as $rec) {
                            if ($rec['Format'] == 'mp4') {
                                $data['duration'] = $rec['Duration'];
                                $data['url'] = $rec['PlayURL'];
                                $data['size'] = $rec['Size'];
                                $data['thumb'] = $info['VideoBase']['CoverURL'];
                                $data['convert_status'] = Resources::CONVERT_STATUS_FISHING;
                                $data['suffix'] = $rec['Format'];
                                Resources::updateAll($data, 'id=:id', array(':id' => $id));
                            }
                        }
                    }
                } catch (\Exception $e) {
                    //还在转码中
                }
            }
        }
    }
}
