<?php

namespace App\Repositories;

interface ILeaderRepository {

    public function get_candidate_profile_ld( $input_details );
    public function update_approval_process($input_details_cd);
    // public function get_approved_offers( $input_details );
    
}
?>