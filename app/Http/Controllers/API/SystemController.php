<?php namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\NetworkScan;
use App\Models\NetworkDevice;
use App\Models\SpeedtestResult;
use Carbon\Carbon;
class SystemController extends Controller {
    public function networkDevices() {
        $data = [
            'total_scans' => NetworkScan::count(),
            'latest_scan' => (string) NetworkScan::orderBy('created_at','DESC')->first()->created_at
        ];
        foreach (NetworkDevice::with('user')->get() as $device) {
            $q=NetworkScan::where('mac',$device->mac)->orderBy('created_at','DESC');
            $data['devices'][$device->nickname]['count'] = $q->count();
            $data['devices'][$device->nickname]['device'] = $device;
            if($q->first())
                $data['devices'][$device->nickname]['latest'] = (string) $q->first()->created_at->diffForHumans();
        }
        return($data);
    }
    public function networkSpeed() {

        $lastScan = SpeedtestResult::orderBy('created_at','DESC')->first();
        if(!$lastScan)
            return [];
        $lastScan['when'] = $lastScan->created_at->diffForHumans();


        $thisWeek = SpeedtestResult::where('created_at', '>', Carbon::now()->subDays(7))->get();
        $runningTotalUpload = [];
        $runningTotalDownload = [];
        $runningTotalPing = [];
        foreach ($thisWeek as $eachScan) {
            $runningTotalDownload[]=$eachScan->download;
            $runningTotalUpload[]=$eachScan->upload;
            $runningTotalPing[]=$eachScan->ping;
        }
        $stats = [
            'upload' => ['min' => min($runningTotalUpload),'max' => max($runningTotalUpload)],
            'download' => ['min' => min($runningTotalDownload),'max' => max($runningTotalDownload)],
            'ping' => ['min' => min($runningTotalPing),'max' => max($runningTotalPing)]
        ];
        return [
            'stats'=>$stats,
            'latest'=>$lastScan
        ];
    }
}
