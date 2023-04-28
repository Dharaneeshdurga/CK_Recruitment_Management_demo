<!DOCTYPE html>
<html>

<head>
    <title>Hi</title>
    <!-- <link rel="stylesheet" type="text/css" href="{{ base_path().'/assets/css/bootstrap.css' }}"> -->



    <style>
    @media screen,
    print {

        table {
            width: 100%;
            /* border-spacing: 5px 0px 5px 0px; */
            font-size:10pt;
            font-family: "Arial Narrow", Arial, sans-serif;

        }

        th,
        td {
            text-align: left;
            vertical-align: top;

            /* margin: 5px 5px 5px 5px; */
            padding: 4px 4px 4px 4px;
        }
    }

    .right_align {
        text-align: right;
    }

    .center_align {
        text-align: center;
    }
    .justify_align{
        text-align: justify;
    }
    li {
        margin-bottom: 5px;
    }

    .red {
        color: red;
    }

    footer {
        position: fixed;
        bottom: -35px;
        left: 0px;
        right: 0px;
        /* background-color: lightblue; */
        height: 100px;
    }

    .p_lh {
        line-height: 1.5;
    }
    /* span {
        page-break-after: always;
    }

    span:last-child {
        page-break-after: never;
    } */
    body{
        font-family: "Arial Narrow", Arial, sans-serif;
        font-style: normal;
        font-size:11pt;

    }
    
    </style>
</head>

<body>

    <footer>
        <p class="center_align" style="margin-bottom:-15px;"><strong>Hema's Enterprises Pvt Ltd.</strong></p>
        <p class="center_align" style="margin-bottom:-15px;"><small><strong>Regional Office: No 3, SIDCO Industrial Estate, Semmandalam, Cuddalore - 1</small></strong></p>
        <p class="center_align" style="margin-bottom:-15px;"><small>CIN: U74999TN2019PTC132662</small></p>
        <p class="center_align" style="margin-bottom:-15px;"><small><strong>(Confidential Document. Meant For Intended Recipient Only. Do Not Share Or Circulate.)</small></strong></p>
        <!-- <p class="center_align" style="margin-bottom:-15px;font-size:12px;">This document is computer generated and does not require signature or
the Company’s stamp in order to be considered valid</p> -->
    </footer>

    <main>
        <img src="{{ $logo_path }}" alt="logo" style="margin-left:80%;width:20%;">
        <!-- <img src="{{ url('/assets/images/logo/logo_bk.jpg') }}http://localhost/CK_Recruitment_Management_demo/public/assets/images/logo/logo_bk.jpg" alt="logo" style="margin-left:80%;width:20%;"> -->

        <br>
        <h5 style="float:right;">Date: {{ $date }} <br>RFH No: {{$rfh_no}}</h5>
        <br>
        <br>
        <?php $cn = strtolower($candidate_name);?>
        <h4>Dear {{ ucfirst($cn) }},</h4>


        <h3 class="center_align">CAREER OFFER</h3>
        <!-- <p>{{ $date }}</p> -->
        <p><strong>CONGRATULATIONS.</strong></p>


        <p class="p_lh justify_align">With reference to your application for a Career at HEMA’S ENTERPRISES PVT LTD and our subsequent
            discussions, we are delighted to offer you the full-time position of <strong>{{ $position_title }}</strong>,
            with the anticipated start date of <strong>{{ $join_date }}</strong>, commencing with Onboarding at
            09:30 hours at HEMA’S ENTERPRISES PVT LTD, S3, SIDCO INDUSTRIAL ESTATE, SEMMANDALAM, CUDDALORE,
            TAMIL NADU 607 001 INDIA.</p>

        <p class="p_lh justify_align">Your Starting Annual Compensation (ACTC) shall be <strong>Rs {{ $closed_salary }} /- ({{$amount_in_words}}
                only)</strong>
            As an employee of HEPL, you will have access to our Comprehensive Benefits Program, details of which are
            attached along with Salary break up for your reference in the Annexure. This offer is contingent upon your
            successful completion of Pre-Onboarding processes including antecedent check and document verification.
        </p>


        <p class="p_lh justify_align">Should you choose to accept this offer, please reply to this email with your acknowledgement and
            acceptance by <strong>{{$accept_end_date}}</strong> and share the required documents as listed in the Annexure through
            the links provided for upload at the earliest.</p>


        <p class="p_lh justify_align"><strong>{{ $or_recruiter_name }}</strong>, (Email: <strong>{{ $or_recruiter_email }}</strong>) from our
            Recruiting Team shall be your primary Point of Contact. Your Onboarding Buddy shall be <strong>{{ $or_buddy_name }}</strong>, (Email: <strong>{{ $or_buddy_email }}</strong>) and you may
            reach out to him/her on the Day of Joining for commencing Onboarding actions.</p>

        <p class="p_lh justify_align">We look forward to hearing from you and hope to see you onboard soon!</p>


        <p><strong>For Hema’s Enterprises Limited</strong></p>

    
        <!-- <img src="http://localhost/CK_Recruitment_Management_demo/public/assets/images/logo/prasanna.png" alt=""> -->
        <p><strong>R PRASANNA </strong></p>
        <p><strong>MANAGER HUMAN RESOURCES</strong></p>
        <span style="margin-bottom:-55px;font-size:12px;">This is an electronically generated document and does not require a signature</span>
        <br><br>
        <br>
        <br>
        <br><br>
        <br><br>
        <br><br>
        <br><br>
        <br><br><br><br>
        <h3 class="center_align">ANNEXURE 1</h3>
        
        <h3 class="center_align">LIST OF DOCUMENTS TO BE SHARED WITH ONBOARDING TEAM</h3>
        <p class="center_align">(Prior to Anticipated Start Date)</p>

        <p class="p_lh justify_align">You are required to furnish copies of the following documents at the earliest through the links
            provided for upload. Documents in PDF and JPEG/JPG/PNG alone are accepted formats.
            Kindly ignore if you have already made the submission of all documents listed below.</p>

        <ol class="p_lh justify_align">
            <li><strong>PROOF OF IDENTITY:</strong> Govt issued AADHAR Card | PAN Card | VOTER ID Card | PASSPORT etc
                <span class="red">*</span></li>
            <li><strong>PROOF OF ADDRESS:</strong> AADHAR CARD | VOTER ID CARD | BANK STATEMENT | PASSPORT bearing
                Current Full Address of Residence <span class="red">*</span></li>
            <li><strong>EDUCATIONAL QUALIFICATION:</strong> In Chronological Order, beginning with the oldest and ending
                with the Latest qualification (Marksheets and Final Certificates included <span class="red">*</span>
            </li>
            <li><strong>PROOF OF WORK EXPERIENCE:</strong> Appointment Letters of Previous/Last Worked/Current Working
                Organisation (This is not applicable for Freshers) <span class="red">*</span></li>
            <li><strong>PROOF OF COMPENSATION & BENEFITS RECEIVED:</strong> Latest 3 Months PAYSLIPS | Form 16 of
                Previous Years | Bank Statement disclosing Credits , in PDF format (This is not applicable for Freshers)
                <span class="red">*</span></li>
            <li><strong>TAX ENTITY PROOF:</strong> PAN CARD bearing Name, PAN Number details</li>
            <li><strong>PROOF OF DATE OF BIRTH:</strong> Govt issued BIRTH CERTIFICATE| SSLC MARKSHEET | PAN CARD etc
            </li>
            <li><strong>PROOF OF RELIEVING:</strong> Relieving Letter/such documentation from Current/Latest Employer.
                (This is not applicable for Freshers) </li>
            <li><strong>PROOF OF BANK ACCOUNT:</strong> Cancelled CHECK LEAF | BANK STATEMENT bearing details of Bank
                Account | Bank Passbook Photo Identity Page with Account Details</li>
            <li><strong>PROOF OF VACCINATION:</strong> Final Vaccination Certificate as downloaded from Govt Portal</li>
            <li><strong>PROOF OF BLOOD GROUP:</strong> Medical certificate or Blood Donation Card bearing details </li>
        </ol>

        <p><strong>Please bring the Originals of the documents on the Day of Joining for Document Verification.</strong>
        </p>
       <span></span>
        <br><br>
        <br><br>
        <br><br>
        <br><br>
        <br><br><br><br>
        <br><br>
        <br><br>
        <h3 class="center_align">ANNEXURE 2</h3>
        <h3 class="center_align">DETAILS OF OFFER COMPENSATION & BENEFITS</h3>



        <table border="1" cellspacing="0" width="100%" >
            <tbody>
                
                <tr>
                    <td width="20%">
                        <strong>Name :</strong>
                    </td>
                    <td width="20%">{{ $candidate_name }} </td>
                    <td colspan="2" width="15%">
                        <strong>Band :</strong>
                    </td>
                    <td width="15%">{{ $band_title }} </td>
                    <td width="30%"></td>
                </tr>

                <tr>
                    <td width="20%">
                        <strong>Department :</strong>
                    </td>
                    <td width="20%">{{ $department }} </td>
                    <td colspan="2" width="15%">
                        <strong>Designation :</strong>
                    </td>
                    <td width="15%">{{$position_title}} </td>
                    <td width="30%"></td>

                </tr>

                <tr>
                    <td width="20%">
                        <strong>BU :</strong>
                    </td>
                    <td width="20%">{{ $business }} </td>
                    <td colspan="2" width="15%">
                        <strong>Date of Joining :</strong>
                    </td>
                    <td width="15%">24-01-2022 </td>
                    <td width="30%"></td>

                </tr>

                <tr>
                    <td width="20%">
                        <strong>Location :</strong>
                    </td>
                    <td width="20%">{{ $location }} </td>
                    <td colspan="2" width="15%">
                        <strong>CTC:</strong>
                    </td>
                    <td width="15%">{{ $closed_salary }} </td>
                    <td width="30%"><strong>Remark</strong></td>

                </tr>

                <tr>
                    <td colspan="2">
                        <strong>COMPONENTS</strong>
                    </td>
                    <td colspan="2">
                        <strong>PM</strong>
                    </td>
                    <td>
                        <strong>PA</strong>
                    </td>
                    <td><strong>Description</strong></td>

                </tr>

                <!-- <tr>
                <td colspan="6">
                    <strong>MONTHLY (A)</strong>
                </td>

            </tr> -->

                <tr>
                    <td colspan="2">
                        Base Pay (Basic + DA)
                    </td>
                    <td colspan="2" class="right_align">{{ $sc_basic_pm }} </td>
                    <td class="right_align">{{ $sc_basic_pa }} </td>
                    <td>40% of Monthly Cost to Company</td>

                </tr>

                <tr>
                    <td colspan="2">
                        House Rent Allowance (HRA)
                    </td>
                    <td colspan="2" class="right_align">{{ $sc_hra_pm }} </td>
                    <td class="right_align">{{ $sc_hra_pa }} </td>
                    <td>50% of Base Pay</td>

                </tr>
                <tr>
                    <td colspan="2">
                        Medical Allowance
                    </td>
                    <td colspan="2" class="right_align">{{ $sc_medical_allowance_pm }} </td>
                    <td class="right_align">{{ $sc_medical_allowance_pa }} </td>
                    <td>One Month Basic (or) Max. of Rs.15,000/-</td>

                </tr>

                <tr>
                    <td colspan="2">
                        Conveyance
                    </td>
                    <td colspan="2" class="right_align">{{ $sc_conveyance_expence_pm }} </td>
                    <td class="right_align">{{ $sc_conveyance_expence_pa }} </td>
                    <td>Max. Rs.1,600/- p.m (as per IT Act)</td>

                </tr>

                <tr>
                    <td colspan="2">
                        Special Allowance

                    </td>
                    <td colspan="2" class="right_align">{{ $sc_special_allowance_pm }} </td>
                    <td class="right_align">{{ $sc_special_allowance_pa }} </td>
                    <td>Residual Amount</td>

                </tr>

                <!-- <tr>
                    <td colspan="2">
                        LTA
                    </td>
                    <td colspan="2"> </td>
                    <td> </td>
                    <td></td>

                </tr> -->


                <tr>
                    <td colspan="2">
                        <strong>Monthly Components [A]</strong>
                    </td>
                    <td colspan="2" class="right_align">{{ $sc_monthly_gross_pm }} </td>
                    <td class="right_align">{{ $sc_monthly_gross_pa }} </td>
                    <td>Total Monthly Gross</td>

                </tr>

                <tr>
                    <td colspan="6">
                        <strong>Employer Contribution (DIRECT)</strong>
                    </td>

                </tr>

                <tr>
                    <td colspan="2">
                        Employee Contribution PF
                    </td>
                    <td colspan="2" class="right_align">{{ $emp_pf_cont_pm }} </td>
                    <td class="right_align">{{ $emp_pf_cont_pa }} </td>
                    <td>12% on PF salary.</td>

                </tr>

                <tr>
                    <td colspan="2">
                        Employer Contribution ESI
                    </td>
                    <td colspan="2" class="right_align">{{ $emp_esi_cont_pm }}</td>
                    <td class="right_align">{{ $emp_esi_cont_pa }} </td>
                    <td>3.25% on monthly Gross salary.</td>

                </tr>

                <tr>
                    <td colspan="2">
                        <strong>SUB TOTAL [B]</strong>
                    </td>
                    <td colspan="2" class="right_align">{{ $sub_total_b_pm }} </td>
                    <td class="right_align">{{ $sub_total_b_pa }} </td>
                    <td></td>

                </tr>

                <tr>
                    <td colspan="6">
                        <strong>Annual Benefits (INDIRECT)</strong>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        Gratuity
                    </td>
                    <td colspan="2" class="right_align">{{ $gratity_pm }} </td>
                    <td class="right_align">{{ $gratity_pa }} </td>
                    <td>As per Payment of Gratuity Act, 1972</td>

                </tr>

                <tr>
                    <td colspan="2">
                        Statutory Bonus
                    </td>
                    <td colspan="2" class="right_align">{{ $bonus_pm }} </td>
                    <td class="right_align">{{ $bonus_pa }} </td>
                    <td>Minimum Bonus @ 8.33% of Minimum Wage</td>

                </tr>

                <tr>
                    <td colspan="2">
                        SUB TOTAL [C]
                    </td>
                    <td colspan="2" class="right_align">{{ $sub_total_c_pm }} </td>
                    <td class="right_align">{{ $sub_total_c_pa }} </td>
                    <td></td>

                </tr>

                <tr>
                    <td colspan="2">
                        [A] + [B] + [C]
                    </td>
                    <td colspan="2" class="right_align">{{ $abc_ctc_pm }} </td>
                    <td class="right_align">{{ $abc_ctc_pa }} </td>
                    <td>Cost to Company </td>

                </tr>

                <tr>
                    <td colspan="2">
                        NET PAY [In Rs PM]
                    </td>
                    <td colspan="3" class="right_align">{{ $netpay }} </td>
                    <td>Subject to deduction as per IT Act</td>

                </tr>

                <tr>
                    <td colspan="2" valign="bottom">
                        Group Mediclaim for Self and Family(if ESI not Covered
                    </td>
                    <td colspan="3" class="right_align">
                        2,00,000
                    </td>
                    <td>Per Annum</td>

                </tr>

                <tr>
                    <td colspan="2" valign="bottom">
                        Personal Accident Policy
                    </td>
                    <td colspan="3" valign="bottom" class="right_align">
                        5,00,000
                    </td>
                    <td>Per Annum</td>

                </tr>

                <tr>
                    <td colspan="2" valign="bottom">
                        Term Insurance
                    </td>
                    <td colspan="3" class="right_align" valign="bottom">
                        5,00,000
                    </td>
                    <td>Per Annum</td>

                </tr>
                <tr>
                    <td colspan="5" valign="bottom">
                        PARTICULARS
                        <br><br> <br><br>
                        <br><br> 
                    </td>
                    <td></td>

                </tr>
            </tbody>
        </table>

        <main>
</body>

</html>