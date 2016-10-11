<?php namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\NetworkDevice;
use App\Models\NetworkScan;
use App\Models\SpeedtestResult;
use Illuminate\Http\Request;
use Input;
use Log;
use InfluxDB;
use InfluxDB\Point;
use InfluxDB\Database;
class DataCollector extends Controller {

    public function receiveSpeedTestData(Request $request) {
        Log::info($request);
        $obj = new SpeedtestResult();
        $obj->download = $request->download;
        $obj->upload = $request->upload;
        $obj->ping = $request->ping;
        $obj->hostname = $request->hostname;
        $obj->save();

        $client = new InfluxDB\Client(env('INFLUXDB_HOST'), env('INFLUXDB_PORT'));
        $database = $client->selectDB('test');
        $points = array(
            new Point(
                'upload_speed', // name of the measurement
                $obj->upload, // the measurement value
                ['host' => $request->hostname]// optional tags
            ),
            new Point(
                'download_speed', // name of the measurement
                $obj->download, // the measurement value
                ['host' => $request->hostname]// optional tags
            ),
            new Point(
                'ping', // name of the measurement
                $obj->ping, // the measurement value
                ['host' => $request->hostname]// optional tags
            )
        );
        $result = $database->writePoints($points, Database::PRECISION_SECONDS);


        return 'ok';
    }
    public function receiveDashButtonClick(Request $request) {
        Log::info("dashbutton clicked: ".$request->name);
    }
    public function receiveNetworkScan(Request $request) {

        Log::info($request);
        $lastGroupNum = 0;
        $last = NetworkScan::orderBy('created_at', 'desc')->first();
        if($last)
            $lastGroupNum = $last->group;
        $groupum = $lastGroupNum+1;

        $data = $request->toArray();
        foreach($data as $eachScan) {
            $a = new NetworkScan();
            $a->mac = $eachScan['mac'];
            $a->group = $groupum;
            $a->save();

            $device = NetworkDevice::firstOrNew(['mac'=>$a->mac]);
            if(!$device->exists)
            {
                $device->nickname = "unnamed ".$a->mac;
                $device->save();
            }

        }

        $numDevices = sizeof($data)+1;//add 'myself'

       // return 'ok';
        $client = new InfluxDB\Client(env('INFLUXDB_HOST'), env('INFLUXDB_PORT'));
        $database = $client->selectDB('test');
        $points = array(
            new Point(
                'network_devices', // name of the measurement
                $numDevices, // the measurement value
                ['host' => 'ns.local']// optional tags
            )
        );
        $result = $database->writePoints($points, Database::PRECISION_SECONDS);

        return 'ok';
    }

}