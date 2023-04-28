<?php

namespace App\Repositories;

interface IPayrollRepository {

    public function get_candidate_profile_ps( $input_details );
    public function candidate_follow_up_history( $input_details );
    public function candidate_follow_up_history_pd( $input_details );
    public function get_ctc_calculation( $input_details );
    public function update_candidate_details($input_details_cd);
    public function process_orpop_status_ld($input_details);
    public function get_approved_offers( $input_details );
    public function update_po_finance_status($input_details_cd);
    public function get_offer_oat_date( $input_details_oat );
    public function get_offer_oat_offrat_date($credentials);
  //  public function update_po_bh_status($input_details_cd);
    public function get_po_details($input_details_cd);

    public function get_po_default_values();
    public function submit_po_process($data);
    public function check_po_details( $input_details_cd );
    public function update_po_process( $data );
    public function update_po_process_oat( $data );
    public function update_poletter_fn( $input_details_pf );
    public function check_offer_oat($credentials);
    public function update_client_type( $input_details );

}
?>
