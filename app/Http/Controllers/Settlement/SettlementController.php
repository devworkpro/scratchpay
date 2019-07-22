<?php
namespace App\Http\Controllers\Settlement;


use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;
use Response;
use App\Http\Controllers\Controller;
class SettlementController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $holiday_Count =0;
    private $holidayOfYears = [
        '2019-01-01',
        '2019-01-21',
        '2019-02-18',
        '2019-05-27',
        '2019-07-04',
        '2019-09-02',
        '2019-10-14',
        '2019-11-11',
        '2019-11-28',
        '2019-12-25',
        '2019-12-26',
        '2019-12-27',
        '2019-12-28',
        '2019-12-29',
        '2019-12-30',
        '2019-12-31',
        '2020-01-01',
        '2020-01-17',
    ];
    public function __construct()
    {
		// any middleware for authentication
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
	
     
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'initialDate' => 'required|date',
            'delay' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'ok'=>false,
                'initialQuery'=>$request->all(),
                'results'=> ['error'=>$validator->Errors()]]
            );
        }
        $initialDate = $request->get('initialDate');
        $initialDate2 =$initialDate = Carbon::parse($initialDate)->format('Y-m-d');
        $delay = $request->get('delay');
        

         $businessDate =$this->getWorkingDays ($initialDate, $delay);
         $future_date = Carbon::parse($businessDate);
         $start_date = Carbon::parse($initialDate);

         $totalDays = $future_date->diffInDays($start_date);
         $weekendDays = 0;
         for($day = 1; $day<= $totalDays; $day++ ){
             if(Carbon::parse($initialDate2)->isWeekend()){
                $weekendDays++;
             }
             $initialDate2 = Carbon::parse($initialDate2)->addDay(1);
         }

        return response()->json([
            'ok'=>true,
            'initialQuery'=>$request->all(),
            'results'=> [
                "businessDate"=>$businessDate ,
                "totalDays" => $totalDays,
                "holidayDays"=> $this->holiday_Count,
                "weekendDays"=> $weekendDays,
                ]
            ]);
    }


    function getWorkingDays($startDate,$wDays) {

        // using + weekdays excludes weekends
        $new_date = date('Y-m-d', strtotime("{$startDate} +{$wDays} weekdays"));
        
        foreach($this->holidayOfYears as $holiday):
            $holiday_ts = strtotime($holiday);
        
        // if holiday falls between start date and new date, then account for it
            if ($holiday_ts >= strtotime($startDate) && $holiday_ts <= strtotime($new_date)) {
        
                // check if the holiday falls on a working day
                $h = date('w', $holiday_ts);
                    if ($h != 0 && $h != 6 ) {
                    $this->holiday_Count ++;    
                    // holiday falls on a working day, add an extra working day
                    $new_date = date('Y-m-d', strtotime("{$new_date} + 1 weekdays"));
                }
            }
        endforeach;
    
        return $new_date;
    }
	
}
