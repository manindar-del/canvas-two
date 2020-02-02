@extends('layouts.agent')

@section('content')

@php
    //dd($booking_data->BookingId);
    $transfer_date =$booking_data->form->form->transfer_date ;
    $transfer_date_new = date("d M Y", strtotime($transfer_date));
    $adult = $booking_data->form->form->adult;
    $child = $booking_data->form->form->child;
    $title = $booking_data->transfer->title;
    $type = $booking_data->transfer->type;
    $adult_price = $booking_data->transfer->adult_price;
    $child_price = $booking_data->transfer->child_price;
    $total_transfer_rate = ($adult * $adult_price) +  ($child * $child_price);
@endphp

<div class="_page">
    <div class="container">

        <a href="{{ route('booking.transferinvoicepdf', [$booking->id, $booking_data->BookingId]) }}" class="btn ticker-btn ">Download PDF</a>

        <br>

        <div class="pdf">

            <table width="100%" border="0" cellspacing="5" cellpadding="5" style="color: #000; font-family: Georgia, serif; font-size: 18px;">
                <tr>
                    <td width="200" align="left" valign="middle">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="" style="width: 160px;" />
                    </td>
                    <td valign="top">
                        <strong>CANVAS VACATIONS GLOBAL CO.,LTD.</strong> <br>
                        <strong>Address :</strong> 165/156 MOO 5,Tambon Srisoonthorn Amphur, <br>
                        Thalang District, Phuket 83100, Thailand <br>
                        <strong>Email &nbsp;</strong>:online@canvasvacations.net<br>
                        <strong>Tel &nbsp;</strong>:+66-634538668 <br>
                        <strong>Global Support &nbsp;</strong>: +91-8101064166 <br>

                    </td>
                </tr>
            </table>

            <div style="margin: 15px 0; background-color:#87CEFA; color:#FFF; font-family: Gerogia, serif; font-size:32px; font-weight:bold; text-align:center;">
                <strong style="color: #fff;">PROFORMA INVOICE - TRANSFERS</strong>
            </div>

            @if (!empty($booking->user))
                <div style="margin: 30px 0;">
                    <p><strong>To:</strong> {{ $booking->user->name }}</p>
                    <p><strong>Tel:</strong> {{ $booking->user->tel }}</p>
                    <p><strong>Email:</strong> {{ $booking->user->email }}</p>
                </div>
            @endif

            <table width="100%" class="tbl_1">
                <thead>
                    <tr>
                        <th style="text-align: left;">INVOICE DATE</th>
                        <th style="text-align: left;">INVOICE NO.</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $booking->created_at }}</td>
                        <td>{{ $booking_data->BookingId }}</td>
                    </tr>
                </tbody>
            </table>

            <div style="margin: 30px 0;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="200" align="left">
                            <p><strong>Leading Guest</strong></p>
                        </td>
                        <td>
                            @foreach ($booking_data->members as $_guest_info)
                                <p>
                                    @foreach ($_guest_info as $_members)
                                        {{ $_members->first_name }}/
                                        {{ $_members->last_name }}
                                        {{ $_members->salutation }}.
                                    @endforeach
                                </p>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td align="left"><strong>Transfer Date</strong> </td>
                        <td>: {{$transfer_date_new}}</td>
                    </tr>
                </table>
            </div>

            <table width="100%" class="tbl_1">
                <thead>
                    <tr>
                        <th align="center">Description</th>
                        <th>Type</th>
                        <th>Price </th>
                        <th width="200">Total Price</th>
                    </tr>
                </thead>
                <tr>
                    <td valign="top" align="left">
                        <p><strong>{{ $title }} </strong> </p>
                        <p>
                            @php
                                if($adult) echo $adult. " Adult(s), ";
                                if($child) echo $child. " Child(ren), ";
                            @endphp
                        </p>
                    </td>
                    <td valign="top" align="center">{{$type}}</td>
                    <td valign="top" align="center">
                        <p>
                            @if(!empty($adult_price))
                                Adult : {{$adult_price}} X {{$adult}} Adult(s) = {{ $adult_price * $adult }}
                            @endif
                        </p>
                        <p>
                            @if(!empty($child_price))
                                Adult : {{$child_price}} X {{$child}} Child(ren) = {{ $child_price * $child }}
                            @endif
                        </p>
                    </td>
                    <td valign="top" align="right">
                        @php
                            $total_price_padded = ceil($total_transfer_rate);
                            $total_price = sprintf('%0.2f', $total_price_padded);
                            echo $total_price;
                            $payment_received =  $total_price;
                            $outstanding_balance = 0.0;
                        @endphp
                    </td>
                </tr>
                <tr>
                    <td colspan="3" align="left" valign="top">INR :
                        @php
                            echo strtoupper(\App\Helpers\CurrencyHelper::numberToWords($total_price_padded));
                        @endphp
                    </td>
                    <td><strong>TOTAL INR</strong> <span style="float:right;">{{ $total_price }}</span></td>
                </tr>
            </table>

            <table width="100%" class="tbl_1" style="margin: 30px 0;">
                <tbody>
                    <tr>
                        <th style="width: 70%; background-color: transparent; text-align: left;">SERVICE CHARGE</th>
                        <td style="text-align: right;">100</td>
                    </tr>
                    <tr>
                        <th style="width: 70%; background-color: transparent; text-align: left;">GST ON SERVICE CHARGES (18%)</th>
                        <td style="text-align: right;">18</td>
                    </tr>
                </tbody>
            </table>

            <p style="margin: 30px 0;">
                The total billing amount is inclusive of Service Charge and Applicable GST.
                For detailed Invoice kindly contact accounts@canvasvacations.net
            </p>

            <div class="row" style="margin: 30px 0;">
                <div class="col-md-6 col-md-offset-6" align="right">
                    <table width="100%" border="0" cellspacing="5" cellpadding="5" class="tbl">
                        <tr>
                            <td><strong>Payment Received </strong></td>
                            <td><strong>{{$payment_received}}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Outstanding Balance</strong></td>
                            <td><strong> 0.0</strong></td>
                        </tr>
                        <tr>
                            <td><strong>TOTAL INR</strong></td>
                            <td><strong>{{ $total_price }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div style="color: #444;">
                <p>
                    Please note the ROE on the date of booking will be applicable.
                </p>
                <p>
                    Electronically generated. Does not require a physical signature. Bill payable immediately or subject to interest @24% per annum.
                </p>

                <p>Bank Details:</p>

                <ul style="padding-left: 30px; list-style-type: circle;">
                    <li>BENEFICIARY NAME : CANVAS VACATIONS GLOBAL CO., LTD.</li>
                    <li>BENEFICIARY ADDRESS : 165/156 MOO.5, SRISOONTHORN SUB-DISTRICT, THALANG DISTRICT, PHUKET 83110,  THAILAND</li>
                    <li>BENEFICIARY ACCOUNT NO : 045-3-66780-4</li>
                    <li>BANK NAME : KASIKORNBANK PUBLIC COMPANY LIMITED</li>
                    <li>Bank Address : 24/52 MOO.5NONG PRUE BANGLAMUNG CHON BURI 20120 THAILAND</li>
                    <li>BRANCH : NOEN PLAP WAN CHONBURI</li>
                    <li>SWIFT CODE : KASITHBK</li>
                </ul>
                <p>India Bank Details :</p>
                <ul style="padding-left: 30px; list-style-type: circle;">
                    <li>ACCOUNT NAME : CANVAS VACATIONS PRIVATE LIMITED.</li>
                    <li>BANK NAME : KOTAK MAHINDRA BANK LIMITED</li>
                    <li>ACCOUNT TYPE : CURRENT</li>
                    <li>ACCOUNT NUMBER :2513072291</li>
                    <li>IFSC CODE : KKBK0006576</li>
                    <li>BRANCH NAME : SALT LAKE KOLKATA</li>
                </ul>

                <p>
                    PLEASE ENSURE THAT CANVAS VACATIONS GETS FULL PAYMENT AND REMITTANCE CHARGES ARE BORNE BY REMITTER.ANY SHORT PAYMENT NEEDS TO BE PAID BEFORE CHECK IN
                </p>

                <p>
                    CANVAS VACATIONS PVT.LTD IS OPERATING AS COLLECTION AGENT FOR PAYMENTS IN INR. WITH SERVICE CHARGES .
                </p>
            </div>

            {{-- <p style="text-decoration: underline;">Remark</p>

            <ol class="ol_1">
                <li> ADD USD 25 or Equivalent amount (in case of other currency) in each remittance for covering banking Charge.</li>
                <li> In Remittance 71A Detail of Charges should be OUR.</li>
                <li> Please pay the amount before the payment due date to avoid auto release of booking. Bank details are mentioned above.</li>
                <li> Payment/Remittance to be made in the currency as stated in the invoice.</li>
                <li> To ensure prompt credit, please send payment details along with remittance advice at prepay@travelbullz.com.</li>
                <li> Interest @ 18% will be charged on all overdue.</li>
                <li> This document is not a voucher. Voucher will be issued after the receipt of full monies &amp; necessary documents.</li>
            </ol>

            <p style="text-decoration: underline;">Payable to</p>

            <table width="100%" border="0" cellspacing="5" cellpadding="5" class="tbl">
                <tr>
                    <td width="200" align="left"><strong>Bank</strong> </td>
                    <td>: HSBC BANK </td>
                </tr>
                <tr>
                    <td><strong>Branch</strong> </td>
                    <td> :1 Queen􀂶s Road Central, Hong Kong</td>
                </tr>
                <tr>
                    <td><strong>A/C No.</strong> </td>
                    <td> :801-148032-838 (USD/SGD/GBP/EUR/HKD/CNY/AUD/NZD/</td>
                </tr>
                <tr>
                    <td><strong>Swift</strong> </td>
                    <td> :HSBCHKHHHKH</td>
                </tr>
                <tr>
                    <td><strong>Address</strong> </td>
                    <td>
                        :RDB Boulevard, Plot K-1, Sector V, Block EP & GP, Salt Lake City, 8th Floor, Kolkata, West Bengal 700091 WEST BENGAL
                    </td>
                </tr>
                <tr>
                    <td><strong>Beneficiary Name </strong></td>
                    <td> :HSBCHKHHHKH</td>
                </tr>
            </table> --}}

        </div>

    </div>
</div>

@endsection

@push('header-bottom')
    <style>
        .tbl {
            border-collapse: collapse;
            width: 80%;
        }

        .tbl td,
        th {
            padding: 5px;
            color: #000;
        }

        .tbl_1 td,
        th {
            padding: 8px;
            margin: 10px;
            color: #000;
            border: 2px solid #000;
        }

        .tbl_1 th {
            background-color: #CCC;
            text-align: center;
            padding: 8px;
            margin: 10px;
        }

        .padleft20 {
            padding-left: 20px;
        }

        .ab_bgcolor {
            background-color: #87CEFA;
        }

        .header_bgcolor {
            background-color: #929292;
            color: #FFF;
            padding: 8px;
            margin-bottom: 15px;
        }

        .header_bgcolor h4 {
            color: #FFF;
            font-weight: bold;
        }

        strong {
            color: #000;
        }

        .black {
            color: #000;
            font-size: 16px;
        }

        .ol_1 li {
            list-style: decimal;
            color: #000;
        }

        .ol_1 {
            margin: 30px;
        }

        .ol_1 li {
            padding-left: 2em;
            text-indent: -2em;
        }

        .pdf b {
            color: #6E6E6E;
        }
    </style>
@endpush