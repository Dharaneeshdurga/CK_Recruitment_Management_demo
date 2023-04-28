<?php
$sc_special_allowance_pa =  ($get_cs_pa - $sc_basic_pa - $sc_medical_allowance_pa - $sc_conveyance_expence_pa - $emp_esi_cont_pa - $emp_pf_cont_pa - $gratity_pa -$bonus_pa);
//$sc_special_allowance_pm =  round($sc_special_allowance_pa / 12);

// if ($abc_ctc_pa != $get_cs_pa)
// {
//     $get_sa_dif = $get_cs_pa - $abc_ctc_pa;
//     $sc_special_allowance_pa = $get_sa_dif;
     $sc_monthly_gross_pa_a = round($sc_basic_pa + $sc_hra_pa + $sc_conveyance_expence_pa + $sc_special_allowance_pa + $sc_medical_allowance_pa);

    $abc_ctc_pa = round($sc_monthly_gross_pa_a + $sub_total_b_pa + $sub_total_c_pa);
    $sc_special_allowance_pm = round($sc_special_allowance_pa / 12);

    $get_pf_eligible_amt = $sc_basic_pm + $sc_conveyance_expence_pm + $sc_medical_allowance_pm + $sc_special_allowance_pm;


    if ($get_pf_eligible_amt <= 15000)
    {
        $emp_pf_cont_pa = round($get_pf_eligible_amt * 12 * 0.12);
    }
    else
    {
        $emp_pf_cont_pa = round(15000 * 12 * 0.12);
    }

    $emp_pf_cont_pm = round($emp_pf_cont_pa / 12);

    $sc_monthly_gross_pm_a = round($sc_basic_pm + $sc_hra_pm + $sc_conveyance_expence_pm + $sc_special_allowance_pm + $sc_medical_allowance_pm);

    // repeat the process
    if ($sc_monthly_gross_pm_a <= 21001)
    {
        $emp_esi_cont_pm = round($sc_monthly_gross_pm_a * 0.0325);
        $emp_esi_cont_pa = round($get_emp_esi_cont_pm * 12);

        $sub_total_b_pm = $emp_pf_cont_pm + $emp_esi_cont_pm;
        $sub_total_b_pa = $get_sub_total_b_pa;

    }
    else
    {
        $emp_esi_cont_pm = 0;
        $emp_esi_cont_pa = 0;

        $sub_total_b_pm = $emp_pf_cont_pm;
        $sub_total_b_pa = $emp_pf_cont_pa;

    }

    $abc_ctc_pm = round($sc_monthly_gross_pm_a + $sub_total_b_pm + $sub_total_c_pm);
    $abc_ctc_pa = round($sc_monthly_gross_pa_a + $sub_total_b_pa + $sub_total_c_pa);

  //  $sc_special_allowance_pa =  ($get_cs_pa - $sc_basic_pa - $sc_medical_allowance_pa - $sc_conveyance_expence_pa - $emp_esi_cont_pa - $emp_pf_cont_pa - $gratity_pa -$bonus_pa);
 //   $sc_special_allowance_pm =  round($sc_special_allowance_pa / 12);
   // $test = $abc_ctc_pa - $sc_special_allowance_pa;
    //echo $test;
// }
$i=0;
while ($abc_ctc_pa > $get_cs_pa)
{
    // $get_sa_dif = $get_cs_pa - $abc_ctc_pa;
    //  //$sc_special_allowance_pa = $get_sa_dif;
    // $sc_special_allowance_pa = $sc_special_allowance_pa + ($get_sa_dif) ;
     $sc_special_allowance_pa =  ($get_cs_pa - $sc_basic_pa - $sc_medical_allowance_pa - $sc_conveyance_expence_pa - $emp_esi_cont_pa - $emp_pf_cont_pa - $gratity_pa -$bonus_pa);


    $sc_monthly_gross_pa_a = round($sc_basic_pa + $sc_hra_pa + $sc_conveyance_expence_pa + $sc_special_allowance_pa + $sc_medical_allowance_pa);

    $abc_ctc_pa = round($sc_monthly_gross_pa_a + $sub_total_b_pa + $sub_total_c_pa);
    $sc_special_allowance_pm = round($sc_special_allowance_pa / 12);

    $get_pf_eligible_amt = $sc_basic_pm + $sc_conveyance_expence_pm + $sc_medical_allowance_pm + $sc_special_allowance_pm;


    if ($get_pf_eligible_amt <= 15000)
    {
        $emp_pf_cont_pa = round($get_pf_eligible_amt * 12 * 0.12);
    }
    else
    {
        $emp_pf_cont_pa = round(15000 * 12 * 0.12);
    }

    $emp_pf_cont_pm = round($emp_pf_cont_pa / 12);

    $sc_monthly_gross_pm_a = round($sc_basic_pm + $sc_hra_pm + $sc_conveyance_expence_pm + $sc_special_allowance_pm + $sc_medical_allowance_pm);

    // repeat the process
    if ($sc_monthly_gross_pm_a <= 21000)
    {
        $emp_esi_cont_pm = round($sc_monthly_gross_pm_a * 0.0325);
        $emp_esi_cont_pa = round($get_emp_esi_cont_pm * 12);

        $sub_total_b_pm = $emp_pf_cont_pm + $emp_esi_cont_pm;
        $sub_total_b_pa = $get_sub_total_b_pa;

    }
    else
    {
        $emp_esi_cont_pm = 0;
        $emp_esi_cont_pa = 0;

        $sub_total_b_pm = $emp_pf_cont_pm;
        $sub_total_b_pa = $emp_pf_cont_pa;

    }

    $abc_ctc_pm = round($sc_monthly_gross_pm_a + $sub_total_b_pm + $sub_total_c_pm);
    $abc_ctc_pa = round($sc_monthly_gross_pa_a + $sub_total_b_pa + $sub_total_c_pa);

    // $sc_special_allowance_pa =  ($get_cs_pa - $sc_basic_pa - $sc_medical_allowance_pa - $sc_conveyance_expence_pa - $emp_esi_cont_pa - $emp_pf_cont_pa - $gratity_pa -$bonus_pa);
   //  $sc_special_allowance_pm =  round($sc_special_allowance_pa / 12);
      }

//
$i++;

$i=0;
// while ($abc_ctc_pa < $get_cs_pa)
// {
//     //echo $i;
//      $get_sa_dif = $get_cs_pa - $abc_ctc_pa;
//       //$sc_special_allowance_pa = $get_sa_dif;
//     $sc_special_allowance_pa = $get_sa_dif - $sc_special_allowance_pa;
//     //$sc_special_allowance_pa =  ($get_cs_pa - $sc_basic_pa - $sc_medical_allowance_pa - $sc_conveyance_expence_pa - $emp_esi_cont_pa - $emp_pf_cont_pa - $gratity_pa -$bonus_pa);


//     $sc_monthly_gross_pa_a = round($sc_basic_pa + $sc_hra_pa + $sc_conveyance_expence_pa + $sc_special_allowance_pa + $sc_medical_allowance_pa);

//     $abc_ctc_pa = round($sc_monthly_gross_pa_a + $sub_total_b_pa + $sub_total_c_pa);
//     $sc_special_allowance_pm = round($sc_special_allowance_pa / 12);

//     $get_pf_eligible_amt = $sc_basic_pm + $sc_conveyance_expence_pm + $sc_medical_allowance_pm + $sc_special_allowance_pm;


//     if ($get_pf_eligible_amt <= 15000)
//     {
//         $emp_pf_cont_pa = round($get_pf_eligible_amt * 12 * 0.12);
//     }
//     else
//     {
//         $emp_pf_cont_pa = round(15000 * 12 * 0.12);
//     }

//     $emp_pf_cont_pm = round($emp_pf_cont_pa / 12);

//     $sc_monthly_gross_pm_a = round($sc_basic_pm + $sc_hra_pm + $sc_conveyance_expence_pm + $sc_special_allowance_pm + $sc_medical_allowance_pm);

//     // repeat the process
//     if ($sc_monthly_gross_pm_a <= 21001)
//     {
//         $emp_esi_cont_pm = round($sc_monthly_gross_pm_a * 0.0325);
//         $emp_esi_cont_pa = round($get_emp_esi_cont_pm * 12);

//         // $sub_total_b_pm = $emp_pf_cont_pm + $emp_esi_cont_pm;
//         // $sub_total_b_pa = $get_sub_total_b_pa;

//     }
//     else
//     {
//         $emp_esi_cont_pm = 0;
//         $emp_esi_cont_pa = 0;

//         // $sub_total_b_pm = $emp_pf_cont_pm;
//         // $sub_total_b_pa = $emp_pf_cont_pa;

//     }
//     $sub_total_b_pm = $emp_pf_cont_pm + $emp_esi_cont_pm;
//     $sub_total_b_pa = $emp_pf_cont_pa + $emp_esi_cont_pa;
//     $get_sub_total_b_pa = $emp_pf_cont_pa + $emp_esi_cont_pa;

//     $abc_ctc_pm = round($sc_monthly_gross_pm_a + $sub_total_b_pm + $sub_total_c_pm);
//     $abc_ctc_pa = round($sc_monthly_gross_pa_a + $sub_total_b_pa + $sub_total_c_pa);

//     // $sc_special_allowance_pa =  ($get_cs_pa - $sc_basic_pa - $sc_medical_allowance_pa - $sc_conveyance_expence_pa - $emp_esi_cont_pa - $emp_pf_cont_pa - $gratity_pa -$bonus_pa);
//    //  $sc_special_allowance_pm =  round($sc_special_allowance_pa / 12);
//       }

// //
// $i++;
