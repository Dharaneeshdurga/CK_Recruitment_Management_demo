<?php
namespace App\Repositories;

interface IExternalRepository
{

    public function get_external_candidate_database_data();
    public function get_external_candidate_database_af($advanced_filter);
    public function get_position_apply_title_af();

}
