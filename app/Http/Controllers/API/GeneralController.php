<?php namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\NetworkScan;
use App\NetworkDevice;
class GeneralController extends Controller {
    public function networkdevicestest() {
        $data = [
            'total_scans' => NetworkScan::count(),
            'latest_scan' => (string) NetworkScan::orderBy('created_at','DESC')->first()->created_at
        ];
        foreach (NetworkDevice::all() as $device) {
            $q=NetworkScan::where('mac',$device->mac)->orderBy('created_at','DESC');
            $data['devices'][$device->nickname]['count'] = $q->count();
            if($q->first())
                $data['devices'][$device->nickname]['latest'] = (string) $q->first()->created_at;
        }
        return($data);
    }
}
