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
        <h4>Dear  {{ ucfirst($candidate_name) }},</h4>


        <h3 class="center_align">OFFER OF APPRENTICESHIP UNDER NAPS</h3>
        <!-- <p>{{ $date }}</p> -->
        <p class="p_lh justify_align">This has reference to your application for Apprenticeship Training with <strong>Hema’s Enterprises Private Limited</strong> (hereinafter referred to as HEPL).</p>
        <p class="p_lh justify_align">Subsequent to our discussions and assessments, we are pleased to confirm the Offer of Apprenticeship Training as <strong>Trainee - {{ $position_title }}</strong> in <strong>{{ $department }}</strong> Faculty/Specialisation under <strong>NAPS (National Apprenticeship Promotion Scheme)</strong> on the following terms and conditions: </p>

        <ol class="justify_align">
            <li>You shall be eligible for an all-inclusive Stipend of <strong>Rs {{ $closed_salary }} /- ({{$amount_in_words}}
                only)</strong>  per month during the period of Training.  Your Training will commence on <strong>{{ $join_date }}</strong>  </li>
            <li>This offer of Apprenticeship shall stand automatically cancelled in the event of you failing to report for Training on the said date.</li>
            <li><strong>Notice Period</strong> for withdrawal from Apprenticeship shall be 45 days. The Company may at its discretion, discontinue the Apprenticeship engagement on account of non-performance of expectations as per the terms and conditions of Apprenticeship by the Apprentice. In such cases, the Company may extend a notice to the apprentice,15 days in advance of the date of discontinuance of Apprenticeship.</li>
            <li><strong>Alternate Engagement prohibited:</strong> During the course of your apprenticeship training, you are not permitted to engage yourself in any full time or part time employment or Training or Apprenticeship or any such services that may directly or indirectly violate the rights of HEPL. In such circumstances of proven engagement, your apprenticeship engagement with HEPL shall be liable to be terminated at the discretion of HEPL.</li>
            <li><strong>Policies:</strong> During the course of engagement as Apprentice, you shall be governed by the people policies of HEPL and shall be duty bound to adhere to and abide by the provisions of the HEPL Code of Business Conduct and  Policies related to Privacy, Confidentiality, Intellectual Property and Non Disclosure. </li>
            <li><strong>Nature of Engagement:</strong> The Engagement is purely Learning in nature and defined by the NAPS Framework. At no point during the engagement shall this relationship be construed as employer-employee relationship, The relationship as Apprentice shall cease to exist close of work hours of last day of engagement as Apprentice and no lien shall . </li>
        </ol>


        <p class="p_lh justify_align">You may reach out to the appropriate Talent Acquisition Specialist / HR Business Partner for any clarifications about the offer of Apprenticeship Training. Please confirm your interest in the Offer of Apprenticeship by a reply mail to the Sender of this note, at the earliest. </p>


        <p class="p_lh justify_align">We wish you the very best in your Learning Journey with HEPL.</p>


        <p><strong>For Hema’s Enterprises Limited</strong></p>

        <!-- <img src="http://localhost/CK_Recruitment_Management_demo/public/assets/images/logo/prasanna.png" alt=""> -->
        <!-- <p><strong>R PRASANNA </strong></p> -->
        <p><strong>Talent Acquisition Specialist</strong></p>
        <span style="margin-bottom:-55px;font-size:12px;">This is an electronically generated document and does not require a signature</span>

        <main>
</body>

</html>
