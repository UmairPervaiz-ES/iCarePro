<?php

use App\Models\ZoomAppointmentDetail;

 $zoomDetail = ZoomAppointmentDetail::where('appointment_id', $appointment['id'])->first();

$new_date = date('l,d F Y', strtotime($appointment['date']));
?>
<!DOCTYPE html>
<html>
<head>

  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  {{-- <title>Email Confirmation</title> --}}
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,900;1,100;1,400&display=swap" rel="stylesheet">

  <style type="text/css">
     .body {
      background: #F9F9FB !important;
      padding: 20px !important;
    }
    .header img {
      height: 28px !important; 
      width: 108px !important;
    }
    .section {
      padding: 30px !important;
      background-color: white !important;
    }
    .section .top {
      display: flex !important;
    }
    .section .top img {
      height: 24px !important;
     width: 26px !important;
     margin-top: 4px !important;
    }
    

    .section .top .heading {
      font-family: 'Lato' , sans-serif !important;
      font-style: normal !important;
      font-weight: 700 !important;
      font-size: 22px !important;
      line-height: 32px !important;
      color: #0D0C22 !important;
      margin-left: 12px !important;
    }
    .inner-section {
      margin-top: 10px !important;
    }
    .inner-section .description {
      margin-top: 10px !important;
      margin-bottom: 22px !important;
    }
    .inner-section .row{
      display: flex !important;
      font-family: 'Lato' , sans-serif  !important;
      margin-bottom: 5px !important;
      font-style: normal !important;
      font-weight: 500 !important;
      font-size: 14px !important;
      line-height: 22px !important;
      color: #0D0C22 !important;
    }
    .inner-section .row .value{
      margin-left: 4px !important;
    }

    .action-heading {
      font-family: 'Lato' , sans-serif !important;
      font-style: normal !important;
      font-weight: 400 !important;
      font-size: 14px !important;
      line-height: 22px !important;
      color: #505050 !important;
      margin-top: 10px !important;
      margin-bottom: 21px !important;
    }
    .zoom-btn {
    background: #0e72ed !important;
    border-radius: 5px !important;
    height: 36px !important;
    width: 200px !important;
    color: white !important;
    font-family: 'Lato' , sans-serif !important;
    font-style: normal !important;
    font-weight: 500 !important;
    font-size: 14px !important;
    line-height: 35px !important;
    text-align: center !important;
    text-decoration: none !important;
    }
    .action-btn {
    background: #2A6049;
    border-radius: 5px !important;
    height: 36px !important;
    width: 200px !important;
    color: white !important;
    font-family: 'Lato' , sans-serif  !important;
    font-style: normal !important;
    font-weight: 500 !important;
    font-size: 14px !important;
    line-height: 35px !important;
    text-align: center !important;
    text-decoration: none !important;
    }
    .best-regards{
      /* display: flex !important;
      flex-direction: column !important; */
    }
    .best-regards .best{
      font-family: 'Lato' , sans-serif !important;
      font-size: 14px !important;
      font-weight: 400 !important;
      line-height: 22px !important;
      letter-spacing: 0em !important;
      text-align: left !important;
      margin-top: 32px !important;
    }
    
    .best-regards .regards{
      font-family: 'Lato' , sans-serif  !important;
      font-size: 14px !important;
      font-weight: 700 !important;
      line-height: 22px !important;
      letter-spacing: 0em !important;
      text-align: left !important;
      margin-bottom: 50px !important;
    }
    .footer-above {
      font-family: 'Lato' , sans-serif !important;
      font-style: normal !important;
      font-weight: 400 !important;
      font-size: 13px !important;
      line-height: 20px !important;
      color: #7D8693 !important;
    }
    .footer {
      font-family: 'Lato' , sans-serif !important;
      font-style: normal !important;
      font-weight: 400 !important;
      font-size: 12px !important;
      line-height: 21px !important;
      text-align: center !important;
      color: #7D8693 !important;
      margin: 10px 0px 10px 0px !important;
    }
    .key {
      margin-left: 10px !important;
      margin-top: -4px !important;
    }
    .value{
      margin-top: -4px !important;

    }
    .cancel_reason {
    margin-left: -9px !important;
    width: 10px !important;
    }
  </style>
</head>
<body  class="body">
  
  <div class="header">
     <img src="{{$message->embed(public_path().'/images/Logo.png')}}" alt="Logo">
  </div>

  <div class="section">
    <div class="top">
      @if($appointment['previous_id'])    
      <img  src="{{$message->embed(public_path().'/images/reschdule.png')}}" alt="">
      @elseif ($appointment['reason'])  
      <img  src="{{$message->embed(public_path().'/images/cancel.png')}}" alt="">
      @else
      <img  src="{{$message->embed(public_path().'/images/CheckCircle.png')}}" alt="">
      @endif
     <div class="heading">Your appointment has been 
          
        @if($appointment['previous_id'])    
        rescheduled   
        @elseif ($appointment['reason'])  
        cancelled
        @else
        confirmed
        
        @endif
          !
      </div>
    </div>
    <div class="inner-section">
      <div class="title">Hi <strong>{{ucfirst($patient['first_name'])}} {{ucfirst($patient['middle_name'])}} {{ucfirst($patient['last_name'])}}</strong>,</div>
      <div class="description">Your appointment has been 
        @if($appointment['previous_id'])    
        rescheduled   
        @elseif ($appointment['reason'])  
        cancelled
        @else
        confirmed
        @endif
        with Dr. {{$doctor['first_name']}} {{$doctor['middle_name']}} {{$doctor['last_name']}}  on {{$new_date}} . Please find the details below:</div>
      <div class="row">
        <img  src="{{$message->embed(public_path().'/images/Newspaper.png')}}"  alt="" width="15px" height="14px"
         style = "margin-top: 4px"><div class="col-md-2 key"><strong>Appointment ID:</strong></div>
        <div class="col-md-10 value">{{$appointment['appointment_key']}}</div>
      </div>
      <div class="row">
        <img src="{{$message->embed(public_path().'/images/CalendarPlus.png')}}"   style = "margin-top: 4px" alt="" width="15px" height="14px"><div class="col-md-2 key"><strong>Date:</strong></div>
        <div class="col-md-10 value">{{$new_date}}</div>
      </div>
      <div class="row">
        <img  src="{{$message->embed(public_path().'/images/Clock.png')}}"   style = "margin-top: 4px" alt="" width="15px" height="14px">
        <div class="col-md-2 key"><strong>Time:</strong></div>
        <div class="col-md-10 value">{{$appointment['start_time']}} - {{$appointment['end_time']}}</div>
      </div>
      <div class="row">
        <img src="{{$message->embed(public_path().'/images/User.png')}}"  style = "margin-top: 4px" alt="" width="15px" height="14px">
        <div class="col-md-2 key"><strong>Doctor:</strong></div>
        <div class="col-md-10 value">{{$doctor['first_name']}} {{$doctor['middle_name']}} {{$doctor['last_name']}}</div>
      </div>
      <div class="row">
        <img  src="{{$message->embed(public_path().'/images//MapPin.png')}}"   style = "margin-top: 4px"  alt="" width="15px" height="14px">
        <div class="col-md-2 key"><strong>Practice Address:</strong></div>
        <div class="col-md-10 value">{{$practice[0]->address_line_1}} {{$practice[0]->address_line_2}} </div>
      </div>

      @if ($doctor['zoom_status'] && isset($zoomDetail['join_url']))
      <div class="row">
        <a  href = "{{$zoomDetail['join_url']}}" target="_blank"  class="zoom-btn">Join Zoom Meeting</a>
      </div>
      @endif

      @if ($appointment['reason'])
      <div class="row">
        {{-- <img src={{$message->embed(public_path().'/images/comment.svg')}}" alt="" class="cancel_reason"> --}}
        <img src="{{$message->embed(public_path().'/images/email.png')}}" alt=""  style = "margin-top: 4px" width="15px" height="14px" class="appointment-images">
        <div class="col-md-2 key"><strong>Reason:</strong></div>
        <div class="col-md-10 value">{{$appointment['reason']}}</div>
      </div>
       @endif

      <div class="row">
        <div class="action-heading">Need to make changes on this appointment? Click on the button below</div>
      </div>

      <div class="row">
        <a  href="{{$practiceLogin}}" target="_blank"  class="action-btn">Manage appointments</a>

      </div>
      <div class="best-regards">
        <p style="margin-bottom: 0px;">Best Regards, </p>
        <p style="margin-top: 0px;"><strong>{{$practice[0]->practice_name}}</strong></p>
      </div>
  

    </div>
    @extends('email.layout.footer')


</body>
</html>