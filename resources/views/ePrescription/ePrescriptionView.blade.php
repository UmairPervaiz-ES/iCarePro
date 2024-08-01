<!DOCTYPE html>
<html lang="en">
<?php  //echo "<pre>";print_r($data);exit();
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-prescription</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <style>
        * {
            box-sizing: border-box;
            padding: 0px;
            margin: 0px;
            font-family: 'Lato', sans-serif;
        }

        .vitals-tbl {
            border-collapse: collapse;
        }

        .vitals-tbl th,
        .vitals-tbl tr,
        .vitals-tbl td {
            border: 1px solid #F2F2F4;
            border-collapse: collapse;
            text-align: left;
            padding: 5px 10px;
            line-height: 16px;
            color: #0D0C22;
            font-weight: 400 !important;
            font-family: 'Lato', sans-serif !important;
        }

        .vitals-tbl th {
            font-size: 12px !important;
            color: #0D0C22;
            line-height: 16px !important;
            font-weight: 600 !important;
            font-family: 'Lato', sans-serif !important;
        }

        .vitals-tbl td {
            font-size: 12px;
        }
    </style>
</head>
<?php
$colorScheme = '#1c4c9a';
$logo = asset('images/Logo.png');
if (isset($templateData['color_scheme'])) {
    $colorScheme = $templateData['color_scheme'];
}
if (isset($templateData['logo'])) {
    $logo = asset($templateData['logo']);
}
$birthDate = new DateTime($data['patient']['dob']);
$today   = new DateTime('today');
$age = $birthDate->diff($today);
$year = $birthDate->diff($today)->y;
$month = $birthDate->diff($today)->m;
$days = $birthDate->diff($today)->d;
$patientAge = $year . 'y';
if ($year == 0) {
    $patientAge = $month . 'm';
    if ($month == 0) {
        $patientAge = $days . 'd';
    }
}
?>

<body>
    <div class="spacer" style="width: 100%; height: 4px; background: {{$colorScheme}};">
    </div>
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 35px; background: #F9F9FB">
        <table style="width: 100%;">
            <tr>
                <td>
                    <div class="" style="max-width: 150px;">
                        <img src="{{$logo}}" alt="" style="height: 38.6px; width:auto;">
                    </div>
                </td>
                <td>
                    <div style="text-align: right">
                        <p style="color: #0D0C22;font-size: 10.5px; line-height: 22px; font-weight: 600;">Appointment ID: {{$data['appointment']['appointment_key']}}</p>
                        <p style="color: #0D0C22;font-size: 10px; line-height: 22px; font-weight: 400;">{{date('d M Y, D',strtotime($data['created_at']))}}</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div style="padding: 23px 35px 15px;">
        <table style="width: 100%;">
            <tr>
                <td>
                    <p style="color: #0D0C22;font-size: 12px; line-height: 22px; font-weight: 700;">{{$data['patient']['first_name'].' '.$data['patient']['last_name']}}, {{$patientAge}}/{{$data['patient']['gender']}}</p>
                </td>
                <td>
                    <p style="color: #0D0C22;font-size: 12px; line-height: 22px; font-weight: 700;text-align: right">Dr. {{$data['doctor']['first_name'].' '.$data['doctor']['last_name']}}</p>
                </td>
            </tr>
            <tr>
            </tr>
            <tr>
                <td>
                    <p style="color: #0D0C22; line-height: 22px; font-size: 10px; font-weight: 400;">Appointment Time:{{date('h:m a',strtotime($data['appointment']['start_time']))}}</p>
                </td>
                <td>
                    <p style="color: #0D0C22; line-height: 22px; font-size: 10px; font-weight: 400;text-align: right;word-wrap: break-word;">@foreach($data['doctor']['doctor_specializations'] as $specializations){{$specializations['specializations'][0]['name']}} @endforeach </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p style="color: #0D0C22; line-height: 22px; font-size: 10px; font-weight: 400;">
                        <img src="{{asset('images/EnvelopeSimple.png')}}" width="10px" alt="">&nbsp;&nbsp;{{$data['patient']['email']}}
                    </p>
                </td>

                <td>
                    <p style="color: #0D0C22; line-height: 22px; font-size: 10px; font-weight: 400;text-align: right">
                        {{$data['doctor']['primary_email']}}&nbsp;&nbsp;<img src="{{asset('images/EnvelopeSimple.png')}}" width="10px" alt="">
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <p style="color: #0D0C22; line-height: 22px; font-size: 10px; font-weight: 400;"><img src="{{asset('images/Phone.png')}}" width="10px" alt="">&nbsp;&nbsp;{{$data['patient']['country_code'].' '.$data['patient']['phone_number']}}</p>
                </td>
                <td>
                    <p style="color: #0D0C22; line-height: 22px; font-size: 10px; font-weight: 400;text-align: right">
                        {{$data['doctor']['country_code_primary_phone_number'].' '.$data['doctor']['primary_phone_number']}}&nbsp;&nbsp;<img src="{{asset('images/Phone.png')}}" width="10px" alt="">
                    </p>
                </td>
            </tr>
        </table>
    </div>
    <div class="seperator" style="height: 1px; background: #F2F2F4;">
    </div>
    @if($data['medical_problems'])
    <div style="display: flex; justify-content: space-between; padding: 23px 35px 23px;">
        <div class="">
            <p style="font-size: 11px; line-height: 22px; font-weight: 700;color:0D0C22;">Current Diagnosis:</p>
            <p style="color: #0D0C22; font-size: 10px; line-height: 16px; font-weight: 400;word-wrap: break-word;">@foreach($data['medical_problems'] as $medical_problems){{$medical_problems['name']}} @endforeach</p>
        </div>
    </div>
    @endif
    <div class="seperator" style="height: 1px; background: #F2F2F4;">
    </div>
    <div style="display: flex;padding: 0px 35px 0px;flex-wrap: wrap;">
        <table width="100%" style="border-collapse:collapse;">
            <tr>
                @if($data['prescribed_drugs'])
                <td width="75%" style="border-right:1px solid #F2F2F4;vertical-align: top;">
                    <div style="padding-top:23px;padding-bottom:23px;padding-right:35px;">
                        <p style="font-size: 12px; line-height: 22px; font-weight: 700;color:#0D0C22;">Prescribed Drugs</p>
                        @foreach($data['prescribed_drugs'] as $drug)
                        <div style="margin-top: 7px;margin-bottom: 4px;background: #F9F9FB;padding: 12px 15px; display: flex; justify-content: space-between; border-radius: 4px;opacity: 0.9;">
                            <table style="width: 100%;">
                                <tr>
                                    <td>{{$drug['drug_name']}}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="color: #0D0C22; font-size: 10px; line-height: 15px; font-weight: 400; margin-top: 4px;">Take <span class="capitalCase">{{$drug['type']}}</span> ({{$drug['strength_value']}} {{$drug['drug']['unit']}}) <span class="capitalCase">{{$drug['repetition']}}</span>@if($drug['drug']['intake']) by <span class="capitalCase">{{$drug['drug']['intake']}}</span> Route @endif @if($drug['when'])- <span class="capitalCase">{{$drug['when']}}</span>@endif @if ($drug['for_days']) - for {{$drug['for_days']}} days @endif<br>
                                    </td>
                                </tr>
                                @if($drug['note_to_patient'])
                                <tr>
                                    <td>
                                        <p style="color: #0D0C22; font-size: 10.5px; line-height: 12.6px; font-weight: 400; margin-top: 9px;word-wrap: break-word;"><strong style="color: #000000">Note to Patient:</strong> {{$drug['note_to_patient']}}</p>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        @endforeach
                    </div>
                </td>
                @endif
                @if(sizeof($data['vitals']) > 0)
                <td width="15%" style="border-left:1px solid #F2F2F4;padding-top:23px;padding-left:35px;padding-bottom:23px; vertical-align: top;">
                    <div>
                        <p style="font-size: 12px; line-height: 22px; font-weight: 700;color:#0D0C22;">Vitals</p>
                        <table width="100%" style="margin-top: 7px;" class="vitals-tbl">
                            @if($data['vitals']['blood_pressure_vital'])
                            @foreach($data['vitals']['blood_pressure_vital'] as $blood_pressure_vital)
                            <tr>
                                <th>BP:</th>
                                @if((!empty($blood_pressure_vital['systole']) || !empty($blood_pressure_vital['diastole'])) && $blood_pressure_vital['not_performed'] === false)
                                <td>{{$blood_pressure_vital['systole'].'/'.$blood_pressure_vital['diastole']}}mm</td>
                                @elseif($blood_pressure_vital['not_performed'] === true)
                                <td>{{$blood_pressure_vital['reason']}}</td>
                                @endif
                            </tr>
                            @endforeach
                            @endif

                            @if($data['vitals']['height_vital'])
                            @foreach($data['vitals']['height_vital'] as $height_vital)
                            <tr>
                                <th>Ht:</th>
                                @if(!empty($height_vital['height_inches']) && $height_vital['not_performed'] === false)
                                <td>{{$height_vital['height_inches']}}in</td>
                                @elseif($height_vital['not_performed'] === true)
                                <td>{{$height_vital['reason']}}</td>
                                @endif
                            </tr>
                            @endforeach
                            @endif

                            @if($data['vitals']['weight_vital'])
                            @foreach($data['vitals']['weight_vital'] as $weight_vital)
                            <tr>
                                <th>Wt:</th>
                                @if(!empty($weight_vital['weight_lbs']) && $weight_vital['not_performed'] === false)
                                <td>{{$weight_vital['weight_lbs']}}lbs</td>
                                @elseif($weight_vital['not_performed'] === true)
                                <td>{{$weight_vital['reason']}}</td>
                                @endif
                            </tr>
                            @endforeach
                            @endif

                            @if($data['vitals']['heart_rate_vital'])
                            @foreach($data['vitals']['heart_rate_vital'] as $heart_rate_vital)
                            <tr>
                                <th>HR:</th>
                                @if(!empty($heart_rate_vital['rate']) && $heart_rate_vital['not_performed'] === false)
                                <td>{{$heart_rate_vital['rate']}}bpm</td>
                                @elseif($heart_rate_vital['not_performed'] === true)
                                <td>{{$heart_rate_vital['reason']}}</td>
                                @endif
                            </tr>
                            @endforeach
                            @endif

                            @if($data['vitals']['pulse_vital'])
                            @foreach($data['vitals']['pulse_vital'] as $pulse_vital)
                            <tr>
                                <th>P:</th>
                                @if(!empty($pulse_vital['rate']) && $pulse_vital['not_performed'] === false)
                                <td>{{$pulse_vital['rate']}}bpm</td>
                                @elseif($pulse_vital['not_performed'] === true)
                                <td>{{$pulse_vital['reason']}}</td>
                                @endif
                            </tr>
                            @endforeach
                            @endif

                            @if($data['vitals']['respiratory_rate_vital'])
                            @foreach($data['vitals']['respiratory_rate_vital'] as $respiratory_rate_vital)
                            <tr>
                                <th>RR:</th>
                                @if(!empty($respiratory_rate_vital['rate']) && $respiratory_rate_vital['not_performed'] === false)
                                <td>{{$respiratory_rate_vital['rate']}}bpm</td>
                                @elseif($respiratory_rate_vital['not_performed'] === true)
                                <td>{{$respiratory_rate_vital['reason']}}</td>
                                @endif
                            </tr>
                            @endforeach
                            @endif

                            @if($data['vitals']['temperature_vital'])
                            @foreach($data['vitals']['temperature_vital'] as $temperature_vital)
                            <tr>
                                <th>T:</th>
                                @if(!empty($temperature_vital['temperature_f']) && $temperature_vital['not_performed'] === false)
                                <td>{{$temperature_vital['temperature_f']}}F</td>
                                @elseif($temperature_vital['not_performed'] === true)
                                <td>{{$temperature_vital['reason']}}</td>
                                @endif
                            </tr>
                            @endforeach
                            @endif



                            @if($data['vitals']['inhaled_o2_vital'])
                            @foreach($data['vitals']['inhaled_o2_vital'] as $inhaled_o2_vital)
                            <tr>
                                <th>SpO2:</th>
                                <td>{{$inhaled_o2_vital['inhaled_o2_concentration_vital']}}%</td>
                            </tr>
                            @endforeach
                            @endif


                            @if($data['vitals']['wc_vital'])
                            @foreach($data['vitals']['wc_vital'] as $wc_vital)
                            <tr>
                                <th>WC:</th>
                                <td>{{$wc_vital['wc_vital_inches']}}in</td>
                            </tr>
                            @endforeach
                            @endif

                            @if($data['vitals']['bmi_vital'])
                            @foreach($data['vitals']['bmi_vital'] as $bmi_vital)
                            <tr>
                                <th>BMI:</th>
                                <td>{{$bmi_vital['bmi_vital']}} </td>
                            </tr>
                            @endforeach
                            @endif


                            @if($data['vitals']['pain_scale_vital'])

                            @foreach($data['vitals']['pain_scale_vital'] as $pain_scale_vital)
                            <tr>
                                <th>NRS:</th>
                                <td>{{$pain_scale_vital['pain_scale_vital']}} </td>
                            </tr>
                            @endforeach
                            @endif
                        </table>
                    </div>
                </td>
                @endif
            </tr>
        </table>


    </div>
    <div class="seperator" style="height: 1px; background: #F2F2F4;"></div>
    <!-- class="seperator" style="height: 1px; background: #F2F2F4;" -->
    <div>
        <table width="100%" style="border-collapse:collapse;">
            <tr>
                @if($data['prescribed_lab_tests'])
                <td width="50%" style="border-right:1px solid #F2F2F4;padding-top:23px;padding-right:35px;padding-left:35px;padding-bottom:23px;vertical-align: top;">
                    <div>
                        <p style="margin-bottom: 7px;font-size: 12px; line-height: 22px; font-weight: 700;color:#0D0C22;">Prescribed Lab Tests</p>
                        <table width="100%">
                            <?php foreach (array_chunk($data['prescribed_lab_tests'], 2) as $row) { ?>
                                <tr style="padding-top:5px;padding-bottom:5px;">
                                    <?php foreach ($row as $value) { ?>
                                        <td width="50%" style=" font-weight: 500;font-size: 11px;line-height: 13px;text-transform: capitalize;color: #0D0C22;word-wrap: break-word;">&#x2022; {{$value['lab_test_name']}}</td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </table>

                    </div>
                </td>
                @endif
                @if($data['prescribed_procedures'])
                <td width="50%" style="border-left:1px solid #F2F2F4;padding-top:23px;padding-left:35px;padding-right:35px;padding-bottom:23px;vertical-align: top;">
                    <div>
                        <p style="margin-bottom: 7px;font-size: 12px; line-height: 22px; font-weight: 700;color:#0D0C22;">Prescribed Procedures</p>
                        @foreach($data['prescribed_procedures'] as $procedure)
                        <table style="width: 100%;">
                            <tr>
                                <td style="font-weight: 500;font-size: 11px;line-height: 13px;text-transform: capitalize;color: #0D0C22;word-wrap: break-word;">{{$procedure['procedure_name']}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <p style="font-weight: 400;font-size: 9px;line-height: 14px;text-transform: capitalize;color: #0D0C22;word-wrap: break-word;">{{$procedure['procedure']['description']}}</p>
                                </td>
                            </tr>
                        </table>
                        @endforeach
                    </div>
                </td>
                @endif
            </tr>
        </table>
    </div>
    <div class="seperator" style="height: 1px; background: #F2F2F4;"></div>
    <footer style="position: fixed; bottom: 0;left: 0; right: 0;width: 100%;">

        <div style="align-items: center">
            <table style="margin: auto;">
                <tr>
                    @if(isset($templateData['address']))
                    <td style="border-right:1px solid #F2F2F4;padding-right:20px;padding-left:20px;">
                        <table style="margin: auto;">
                            <tr>
                                <td><img src="{{asset('images/MapPin.png')}}" alt="" width="10.14px"></td>
                                <td>
                                    <p style="font-size: 9px; line-height: 15px; font-weight: 400;">&nbsp;&nbsp;{{$templateData['address']}}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    @endif
                    @if(isset($templateData['email']))
                    <!-- <td>
                        <p style="font-size: 13px; color: rgba(192, 195, 206, 0.5); padding: 0px 10px;">|</p>
                    </td> -->
                    <td style="border-right:1px solid #F2F2F4;padding-right:20px;padding-left:20px;">
                        <table style="margin: auto;">
                            <tr>
                                <td><img src="{{asset('images/EnvelopeSimple.png')}}" alt="" width="10.56px"></td>
                                <td>
                                    <p style="font-size: 9px; line-height: 15px; font-weight: 400;">&nbsp;&nbsp;{{$templateData['email']}}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    @endif
                    @if(isset($templateData['phone']))
                    <!-- <td>
                        <p style="font-size: 13px; color: rgba(192, 195, 206, 0.5); padding: 0px 10px;">|</p>
                    </td> -->
                    <td style="padding-right:20px;padding-left:20px;">
                        <table style="margin: auto;">
                            <tr>
                                <td><img src="{{asset('images/PhoneDense.png')}}" alt="" width="10.14px"></td>
                                <td>
                                    <p style="font-size: 9px; line-height: 15px; font-weight: 400;">&nbsp;&nbsp;{{$templateData['country_code'].' '.$templateData['phone']}}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    @endif
                </tr>
            </table>
        </div>
        @if(isset($templateData['disclaimer']))
        <div class="spacer" style="width: 100%; height: 4px; background: {{$colorScheme}};">
        </div>
        <div style="padding: 9px 35px;">
            <table style="width: 100%;background: #F9F9FB;">
                <tr>
                    <td>
                        <p style="font-size: 9px; line-height: 12px; font-weight: 400; text-align: center; color: #7D8693;word-wrap: break-word;">Disclaimer: {{$templateData['disclaimer']}}
                        </p>
                    </td>
                </tr>
            </table>
        </div>
        @endif
    </footer>
</body>

</html>