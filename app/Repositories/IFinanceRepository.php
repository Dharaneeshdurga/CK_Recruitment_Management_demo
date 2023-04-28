<?php

namespace App\Repositories;

interface IFinanceRepository {

    public function get_pending_po_request( $input_details );
    public function update_finance_status_cd($input_details_cd);
    public function get_approved_offers( $input_details );
    
}
?>