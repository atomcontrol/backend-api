<?php namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\NetworkScan;
use App\Models\NetworkDevice;
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
}
