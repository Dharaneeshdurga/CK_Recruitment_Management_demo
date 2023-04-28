<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use App\Models\ExternalCandidateDatabase;
use App\Repositories\IExternalRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ExternalCandidate extends Controller
{
    public function __construct(IExternalRepository $corepo)
    {
        $this->corepo = $corepo;

        $this->middleware('backend_coordinator');
    }

    public function get_external_candidate_database(Request $request)
    {
        if ($request->ajax()) {

            $af_from_date = (!empty($_POST["af_from_date"])) ? ($_POST["af_from_date"]) : ('');
            $af_to_date = (!empty($_POST["af_to_date"])) ? ($_POST["af_to_date"]) : ('');
            $af_position_title = (!empty($_POST["af_position_title"])) ? ($_POST["af_position_title"]) : ('');

            if( $af_from_date || $af_to_date  || $af_position_title)
            {
                // get all data
                $advanced_filter = array(
                    'af_from_date'=>$af_from_date,
                    'af_to_date'=>$af_to_date,
                    'af_position_title'=>$af_position_title,
                );
                $get_external_candidate_database_result = $this->corepo->get_external_candidate_database_af( $advanced_filter );

            }
            else{

                $get_external_candidate_database_result = $this->corepo->get_external_candidate_database_data( );
             }
        return DataTables::of($get_external_candidate_database_result)
        ->addIndexColumn()

        ->addColumn('cv_upload', function($row) {

            $extension = pathinfo(storage_path('/uploads/'.$row->cv_upload.''), PATHINFO_EXTENSION);
            // dd($extension);

            // print_r($row);
            // print_r($row->cv_upload);
            // die;
            // echo "<pre>";print_r($row->cv_upload_path);die;
        if($row->cv_upload != ""){

            if($row->cv_upload_path == '0')
            {
                if($extension == 'doc' || $extension == 'docx')
                {
                $btn = '<a href="http://hub1.cavinkare.in/CareersHemas/public/uploads/'.$row->cv_upload.'" class="badge bg-info" download><i class="bi bi-eye"></i></a>';
                }else{
                $btn = '<a href="http://hub1.cavinkare.in/CareersHemas/public/uploads/'.$row->cv_upload.'" class="badge bg-info" target="_blank"><i class="bi bi-eye"></i></a>';
                }
            }

            elseif($row->cv_upload_path == '1')
            {
                if($extension == 'doc' || $extension == 'docx')
                {
                $btn = '<a href="../external_candidate/'.$row->cv_upload.'" class="badge bg-info" download><i class="bi bi-eye"></i></a>';
                }else{
                $btn = '<a href="../external_candidate/'.$row->cv_upload.'" class="badge bg-info" target="_blank"><i class="bi bi-eye"></i></a>';
                }
            }

        }else{
            $btn = '<a class="btn btn-primary" onclick="show_cv_upload_div('."'".$row->id."'".');" style="width: 32px; height: 28px;"><i class="fa fa-upload" aria-hidden="true" style="width: 14px; margin-left: -3px; margin-bottom: 5px;"></i></a>';
        }
            return $btn;
        })

        ->addColumn('created_at', function($created) {

            $result = date('d-m-Y', strtotime($created->created_at));
            // print_r($result);
            // die;
            return $result;
        })
        ->rawColumns(['cv_upload', 'created_at'])
        ->make(true);
        }
        return view('external_candidate_database');
    }

    public function get_position_apply_title_af(){

        $get_position_apply_title_af_result = $this->corepo->get_position_apply_title_af(  );

        return $get_position_apply_title_af_result;
    }

    public function import()
    {
        Excel::import(new UsersImport,request()->file('select_file'));

        // return back();
        return back()->with('success', 'Excel Data Imported successfully.');
    }

    public function cv_upload_save(Request $req){

       $get_hidden_id =  $req->input('hiddenid');
       //    print_r($result);
       //    die;

        // $name = $req->file('file')->getClientOriginalName();
        $fileName = rand().time().'.'.$req->file->extension();
        $req->file->move(public_path('external_candidate'), $fileName);

        $form_credentials = array(
            'cv_upload' => $fileName,
            'cv_upload_path' => "1"
        );

       $result=ExternalCandidateDatabase::where('id',$get_hidden_id)->update($form_credentials);
        $response = 'success';
       if($result)
       {
        $response = 'success';

       }

        return response()->json( ['response' => $response] );
    }

}
