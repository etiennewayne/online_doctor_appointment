<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use App\Models\AppSetting;


class AppointmentController extends Controller
{
    //

    public function index(){
        return view('administrator.appointment');
    }

    public function getAppointments(Request $req){
        $sort = explode('.', $req->sort_by);
        
        $date = $req->appdate;

       // $ndate = date('Y-m-d',strtotime($date)); //convert to format time UNIX

        if($req->appdate){
            $ndate = date("Y-m-d", strtotime($req->appdate));
        }else{
            $ndate = '';
        }

        $data = Appointment::with(['user', 'schedule', 'patients'])
            //->where('appointment_date', 'like',  $ndate . '%')
            ->whereBetween('appointment_date', [$req->start, $req->end])
            ->whereHas('user', function($q) use ($req){
                $q->where('lname', 'like', '%'. $req->name . '%');
            })
            ->where('is_archived', 0)
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }

    public function getDailyAppointments(Request $req){
        $sort = explode('.', $req->sort_by);
        
        $date = $req->appdate;
        $todayDate = date('Y-m-d');
       // $ndate = date('Y-m-d',strtotime($date)); //convert to format time UNIX

        $data = Appointment::with(['user', 'schedule', 'patients'])
            ->where('appointment_date', $todayDate)
            ->whereHas('user', function($q) use ($req){
                $q->where('lname', 'like', '%'. $req->name . '%');
            })
            ->where('is_archived', 0)
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }

    

    public function create(){
        return view('administrator.appointment-create')
            ->with('id', 0);
    }


    public function store(Request $req){

        $appdate = date("Y-m-d", strtotime($req->appointment_date));

        //get Max no per schedule
        $schedule = Schedule::find($req->schedule_id);
        $max_no = $schedule->max_no;


        //para sure isa lang ka schedule per day.
        $existSched = Appointment::where('user_id', $req->user_id)
            ->where('appointment_date', $appdate)
            ->where('is_archived', 0)
            ->exists();
        if($existSched){
            return response()->json([
                'errors' => [
                    'exists' => ['Sorry. User already have a reservation.']
                ],
                'message' => "Thie given data was invalid"
            ], 422);
        }
        

        //get the max no of the schedule
        $appMax = Appointment::where('schedule_id', $req->schedule_id)
            ->where('appointment_date', $appdate)
            ->where('is_archived', 0)
            ->count();

        //para sure dili mulapas sa na set up nga max sa schedule
        if($appMax >= $max_no){

            return response()->json([
                'errors' => [
                    'max' => ['Sorry. The schedule reach the maximum number of reservation.']
                ],
                'message' => "The given data was invalid"
            ], 422);
        }

        //insert into database
        Appointment::create([
            'user_id' => $req->user_id,
            'schedule_id' => $req->schedule_id,
            'appointment_date' => $appdate,
        ]);


        return response()->json([
            'status' => 'saved'
        ], 200);

    } //store

    public function edit($id){
        $appointment = Appointment::with(['user'])->find($id);

        return view('administrator.appointment-create')
            ->with('appointment', $appointment)
            ->with('id', $id);
    }

    public function update(Request $req, $id){
        $appdate = date("Y-m-d", strtotime($req->appointment_date));

        //get Max no per schedule
        $schedule = Schedule::find($req->schedule_id);
        $max_no = $schedule->max_no;


        //para sure isa lang ka schedule per day.
        $existSched = Appointment::where('user_id', $req->user_id)
            ->where('appointment_date', $appdate)
            ->where('appointment_id', '!=', $id)
            ->where('is_archived', 0)
            ->exists();

        if($existSched){
            return response()->json([
                'errors' => [
                    'exists' => ['Sorry. User already have a reservation.']
                ],
                'message' => "Thie given data was invalid"
            ], 422);
        }

        $app = Appointment::with(['user'])->where('appointment_id', $id);
        $user = $app->first()->user;

        $app->update([
            'user_id' => $req->user_id,
            'schedule_id' => $req->schedule_id,
            'appointment_date' => $appdate,
        ]);

        $newSchedule = Appointment::with('schedule')
            ->where('appointment_id', $id)
            ->first();

        //return $newSchedule;

        $is_sms_enable = AppSetting::where('dword', 'ENABLE_SMS')->first(); //get ip sms setting
        $proxy = AppSetting::where('dword', 'PROXY_SMS')->first(); //get proxy gateway setting

        if($is_sms_enable->value > 0){
            $timeStart = date('h:i A', strtotime($newSchedule->schedule->time_from));
            $timeEnd = date('h:i A', strtotime($newSchedule->schedule->time_end));

            $msg = 'Reschedule Notice: '. ' Your appointment with Dr. Tilao has been moved to '. date('M-d-Y', strtotime($appdate)) .', ' . $timeStart. '.';
            

            try{
                Http::withHeaders([
                    'Content-Type' => 'text/plain'
                ])->get($proxy->value . '/'.$msg.'/'. $user->contact_no);
            }catch(\Exception $e){} //just hide the error
            
        }

        return response()->json([
            'status' => 'updated'
        ], 200);
    }



    
    public function destroy(){

    }




    //appoved appointment
    public function approveAppointment($id){
        //return $req;

        $data = Appointment::find($id);
        $data->status = 1;
        $data->save();

        $user = User::where('user_id', $data->user_id)->first();
        $schedule = Schedule::where('schedule_id', $data->schedule_id)->first();

        //$nameTitle = $user->sex == 'MALE' ? 'Mr. ' : 'Ms./Mrs. ';

        $is_sms_enable = AppSetting::where('dword', 'ENABLE_SMS')->first(); //get ip sms setting
        $proxy = AppSetting::where('dword', 'PROXY_SMS')->first(); //get proxy gateway setting


        if($is_sms_enable->value > 0){
            $timeStart = date('h:i A', strtotime($schedule->time_from));
            $timeEnd = date('h:i A', strtotime($schedule->time_end));

            $msg = 'Appointment confirmation: '. $user->lname . ', ' . $user->fname . ', your appointment with Dr. Tilao on '. date('M-d-Y', strtotime($data->appointment_date)) .', ' . $timeStart. ') has been approved.';
            
            try{
                Http::withHeaders([
                    'Content-Type' => 'text/plain'
                ])->get($proxy->value . '/'.$msg.'/'. $user->contact_no);
            }catch(\Exception $e){} //just hide the error

            // try{
            //     Http::withHeaders([
            //         'Content-Type' => 'text/plain'
            //     ])->post('http://'. env('IP_SMS_GATEWAY') .'/services/api/messaging?Message='.$msg.'&To='.$user->contact_no.'&Slot=1', []);
            // }catch(\Exception $e){} //just hide the error
        }

        return response()->json([
            'status' => 'saved'
        ], 200);
    }


    public function cancelAppointment($id){
        $data = Appointment::find($id);
        $data->status = 2;
        $data->save();

        $user = User::where('user_id', $data->user_id)->first();
        $schedule = Schedule::where('schedule_id', $data->schedule_id)->first();
        //$nameTitle = $user->sex == 'MALE' ? 'Mr. ' : 'Ms./Mrs. ';
        /*
            Appointment confirmation:
            Ms/Mr/Miss/Mrs Ong, Lensey your appointment with Dr. Tilao on May 15 at 3 pm has been confirmed/Approved.
        */

        $is_sms_enable = AppSetting::where('dword', 'ENABLE_SMS')->first(); //get ip sms setting
        $proxy = AppSetting::where('dword', 'PROXY_SMS')->first(); //get proxy gateway setting

        if($is_sms_enable->value > 0){
            $timeStart = date('h:i A', strtotime($schedule->time_from));
            $timeEnd = date('h:i A', strtotime($schedule->time_end));

            $msg = 'Cancellation notice: '. $user->lname . ', ' . $user->fname . ', your appointment with Dr. Tilao on '. date('M-d-Y', strtotime($data->appointment_date)) .', ' . $timeStart. ') has been cancelled.';
            
            try{
                Http::withHeaders([
                    'Content-Type' => 'text/plain'
                ])->get($proxy->value . '/'.$msg.'/'. $user->contact_no);
            }catch(\Exception $e){} //just hide the error
        }

        return response()->json([
            'status' => 'saved'
        ], 200);
    }

    public function pendingAppointment($id){
        $data = Appointment::find($id);
        $data->status = 0;
        $data->save();

        return response()->json([
            'status' => 'saved'
        ], 200);
    }

    public function archiveAppointment($id){
        $data = Appointment::find($id);
        $data->is_archived = 1;
        $data->save();

        return response()->json([
            'status' => 'archived'
        ], 200);
    }


    public function setArrived($id){

        $dateTimeNow = date('Y-m-d H:i:s');
        $data = Appointment::with(['schedule'])->find($id);

        //return $data;

        $appDateFrom = date('Y-m-d H:i:s', strtotime($data->appointment_date . ' ' .$data->schedule->time_from));
        $appDateEnd = date('Y-m-d H:i:s', strtotime($data->appointment_date . ' ' .$data->schedule->time_end));

        //e contnue pa ugma.
        if($dateTimeNow <= $appDateFrom || $dateTimeNow > $appDateEnd){ //continue tomorrow
            return response()->json([
                'errors' => [
                    'early' => ['Sorry, it\'s not on the schedule yet.']
                ]
            ], 422);
        }

        $data->is_arrived = 1;
        $data->arrival_datetime = date('Y-m-d H:s:i');
        $data->save();

        return response()->json([
            'status' => 'arrived'
        ], 200);
    }

    public function setServed($id){
        $data = Appointment::find($id);
        $data->is_served = 1;
        $data->served_datetime = date('Y-m-d H:s:i');

        $data->is_arrived = 1;
        $data->arrival_datetime = date('Y-m-d H:s:i');

        $data->save();

        return response()->json([
            'status' => 'arrived'
        ], 200);
    }


    public function setClose(){
        $dateNow = date('Y-m-d');

        $app = Appointment::where('appointment_date', $dateNow)
            ->where('is_arrived', 0)
            ->update([
                'is_noshow' => 1,
                'no_show_datetime' => date('Y-m-d H:i:s')
            ]);

        return response()->json([
            'status' => 'closed'
        ], 200);
    }

}
