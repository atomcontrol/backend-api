<?php namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Input;
use Log;
use AWS;
use GuzzleHttp\Client;

class LightingController extends Controller {

    public function index() {
        $client = new Client();
        $list = json_decode($client->request('GET', env('LIGHTING_CONTROLLER_URL').'lights')->getBody());
        $stats = json_decode($client->request('GET', env('LIGHTING_CONTROLLER_URL').'stats')->getBody());
        return ['list'=>$list, 'stats'=>$stats];
    }
    public function test() {
        //dd(json_decode(Redis::hget('light-presets','test'),true));
        return self::queuePreset('test');
        //return Redis::hgetall('light-presets');
//        $a = '{ "actions": [
//                        {
//                            "light": 1,
//                            "colors": {
//                                "r": "255"
//                            },
//                            "timing": 200
//                        }
//                    ],
//                    "wait": 300
//                }';
//
//        $b =  json_decode($a,true);
//
//        return self::sendToQueue($b);
    }
    public static function queuePreset($presetName) {
        $preset =  Redis::hget('light-presets',$presetName);
        return self::sendToQueue(json_decode($preset,true));
    }
    public static function sendToQueue($cueArray) {
        $client = new Client();
        $r = $client->request('PUT', env('LIGHTING_CONTROLLER_URL').'q', [
            'json' => $cueArray
        ]);
        return $r;
    }
}