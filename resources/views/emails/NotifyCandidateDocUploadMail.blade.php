<!DOCTYPE html>
<html>
<head>
    <title>CAREERS@HEPL:  DOCUMENT UPLOAD LINK</title>
</head>
<style>
    span{
        margin:-9px auto;
        line-height: 0.8em !important;
    }
</style>
<body>

    <p><strong>Dear Mr/Ms {{ $details['candidate_name'] }},</strong></p>
    <p>You have successfully cleared the panel assessments for Careers@HEPL. Well done.</p>
    <p>The next step in the hiring process is to upload essential documents for verification and offer confirmation.  Please click on the link below to upload documents.</p>
    <p>Use This Login credentials to sign in to upload the documents:<br>
        Login id: {{ $details['candidate_id'] }} <br>
        Password: {{ $details['password'] }}
    </p>
    <a href="{{$details['doc_upload_link']}}">Click here to upload Documents</a>

    <br><br>
    <p><strong>For Hema's Enterprises Private Limited<strong></p>

    <p><strong>Team Member - Talent Acquisition<strong></p>
    <span style="font-size:10pt;margin:-9px auto;">(This is an electronically generated document and does not require a signature) </span>
    <!-- <img src="http://hub1.cavinkare.in/CK_recruitment_management_new/public/assets/images/logo/logo.jpg" alt="" style="width:100px;"> -->

</body>
</html>
