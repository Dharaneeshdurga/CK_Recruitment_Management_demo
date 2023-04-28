<!DOCTYPE html>
<html>

<head>
    <title>Po Letter</title>
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

            padding: 5px 5px 5px 5px;
            /* padding: 4px 4px 4px 4px; */
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

    

    <main>
<h3 style="text-align:center">PURCHASE ORDER COMPONENT</h3>
        <table border="1" cellspacing="0" width="100%" >
            <tbody>
                
                <tr>
                    <td width="10%" style="text-align:center;"><strong>#</strong></td>
                    <td width="40%" style="text-align:center;"><strong>Details</strong></td>
                    <td width="28%" style="text-align:center;"><strong>Description</strong></td>
                    <td width="22%" style="text-align:center;"><strong>Amount (Indian Rupees)/Annum</strong></td>
                    <td width="22%" style="text-align:center;"><strong>Amount (Indian Rupees)/Month</strong></td>
                    <td width="22%"  style="text-align:center;"><strong>Remarks</strong></td>
                </tr>
            
                @foreach($json as $pdv)
            
                    <tr>
                        @if($pdv['po_details'] !='One Time Recruitment Fee') 
                        <td style="text-align:center;">{{$pdv['sno']}}</td>
                        @else
                        <td style="text-align:center;"></td>
                        @endif
                        @if($pdv['po_details'] !='Total Purchase Order Value (Rs)') 
                                <td>{{$pdv['po_details']}}</td>
                            @else
                                <td style="font-weight:bold">  {{$pdv['po_details']}}  </td>
                         @endif
                       
                         <td> 
                            @if($pdv['po_description'] != "null")
                            {{$pdv['po_description']}}
                            @endif
                         </td>
                           
                        <td style="text-align:right;">
                            @if($pdv['po_amount'] !='no_val' && $pdv['po_amount'] !="NaN")
                            {{$pdv['po_amount'].'.00'}}
                        @endif
                        </td>
                        <td style="text-align:right;">
                            @if($pdv['po_amount_month'] !='' && $pdv['po_amount_month'] !="NaN" && ($pdv['po_details'] !='One Time Recruitment Fee') )
                            {{$pdv['po_amount_month'].'.00'}}
                        @endif
                        </td>

                        <td style="text-align:right;">
                            @if($pdv['remark'] !='null')
                            {{$pdv['remark']}}
                        @endif
                        </td>
                    </tr>
                @endforeach

                
            </tbody>
        </table>
{{-- <p>One Time Recruitment Fee :   {{$pdv['po_reg_fee']}}</p> --}}
    </main>
</body>

</html>