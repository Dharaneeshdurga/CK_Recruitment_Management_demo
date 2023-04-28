<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ExternalRepository implements IExternalRepository
{
    public function get_external_candidate_database_data(){
        $candidaterecruitment = DB::table('external_candidate_details')
        ->get();

        return $candidaterecruitment;
    }

    public function get_external_candidate_database_af($advanced_filter){
        $candidaterecruitmentaf = DB::table('external_candidate_details');

        if($advanced_filter['af_from_date'] !=''  && $advanced_filter['af_to_date'] !=''){

            $candidaterecruitmentaf = $candidaterecruitmentaf->whereDate( 'created_at', '>=', $advanced_filter['af_from_date'] );
            $candidaterecruitmentaf = $candidaterecruitmentaf->whereDate( 'created_at', '<=', $advanced_filter['af_to_date']);
        }

        if($advanced_filter['af_from_date'] !=''  && $advanced_filter['af_to_date'] ==''){

            $candidaterecruitmentaf = $candidaterecruitmentaf->whereDate( 'created_at', '=', $advanced_filter['af_from_date'] );
        }
        if($advanced_filter['af_from_date'] ==''  && $advanced_filter['af_to_date'] !=''){

            $candidaterecruitmentaf = $candidaterecruitmentaf->whereDate( 'created_at', '=', $advanced_filter['af_to_date'] );
        }
        if ($advanced_filter['af_position_title'] != '') {
            $candidaterecruitmentaf = $candidaterecruitmentaf->where( 'position_applying_to',  $advanced_filter['af_position_title']);
        }


        return $candidaterecruitmentaf->get();
    }

    public function get_position_apply_title_af(){
        $get_position_apply_title_details = DB::table('external_candidate_details')
        ->select('position_applying_to')
        ->orderBy('position_applying_to', 'asc')
        ->groupBy('position_applying_to')
        ->get();

        return $get_position_apply_title_details;
    }

}
