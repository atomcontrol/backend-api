<?php namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\NetworkScan;
use App\Models\SpeedtestResult;
use Illuminate\Http\Request;
use Input;
use Log;

class DataCollector extends Controller {

    public function receiveSpeedTestData(Request $request) {
        Log::info($request);
        $obj = new SpeedtestResult();
        $obj->download = $request->download;
        $obj->upload = $request->upload;
        $obj->ping = $request->ping;
        $obj->hostname = $request->hostname;
        $obj->save();
        return 'ok';
    }
    public function receiveDashButtonClick(Request $request) {
        Log::info("dashbutton clicked: ".$request->name);
    }
    public function receiveNetworkScan(Request $request) {

        $lastGroupNum = 0;
        $last = NetworkScan::orderBy('created_at', 'desc')->first();
        if($last)
            $lastGroupNum = $last->group;
        $groupum = $lastGroupNum+1;

        foreach($request->toArray() as $eachScan) {
            $a = new NetworkScan();
            $a->hostname = $eachScan['hostname'];
            $a->mac = $eachScan['mac'];
            $a->group = $groupum;
            $a->save();
        }
        return 'ok';
    }

}