<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;

class ArchiveAppointmentController extends Controller
{
    //

    public function index(){
        return view('administrator.archive-appointment');
    }

    public function getArchiveAppointments(Request $req){
        $sort = explode('.', $req->sort_by);

        $date = $req->appdate;

       // $ndate = date('Y-m-d',strtotime($date)); //convert to format time UNIX

        if($req->appdate){
            $ndate = date("Y-m-d", strtotime($req->appdate));
        }else{
            $ndate = '';
        }

        $data = Appointment::with(['user', 'schedule', 'patients'])
            ->where('appointment_date', 'like',  $ndate . '%')
            ->where('is_archived', 1)
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }

    public function create(){
        return view('administrator.archive-appointment-create');
    }


    public function store(Request $req){

        $appointment = Appointment::whereBetween('created_at', [$req->start_date, $req->end_date])
            ->update([
                'is_archived' => 1
            ]);

        return response()->json([
            'status' => 'archived'
        ], 200);
    }

    public function restoreArchived(Request $req){

        $appointment = Appointment::whereBetween('created_at', [$req->start_date, $req->end_date])
            ->update([
                'is_archived' => 0
            ]);

        return response()->json([
            'status' => 'restored'
        ], 200);
    }

}
