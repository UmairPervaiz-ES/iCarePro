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
      font-family: 'Lato', sans-serif !important;
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
      font-family: 'Lato', sans-serif !important;
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
      font-family: 'Lato', sans-serif !important;
      font-style: normal !important;
      font-weight: 400 !important;
      font-size: 14px !important;
      line-height: 22px !important;
      color: #505050 !important;
      margin-top: 10px !important;
      margin-bottom: 21px !important;
    }
    .action-btn {
    background: #2A6049 !important;
    border-radius: 5px !important;
    height: 36px !important;
    width: 200px !important;
    color: white !important;
    font-family: 'Lato', sans-serif !important;
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
      font-family: 'Lato', sans-serif !important;
      font-size: 14px !important;
      font-weight: 400 !important;
      line-height: 22px !important;
      letter-spacing: 0em !important;
      text-align: left !important;
      margin-top: 32px !important;
    }
    
    .best-regards .regards{
      font-family: 'Lato', sans-serif !important;
      font-size: 14px !important;
      font-weight: 700 !important;
      line-height: 22px !important;
      letter-spacing: 0em !important;
      text-align: left !important;
      margin-bottom: 50px !important;
    }
    .footer-above {
      font-family: 'Lato', sans-serif !important;
      font-style: normal !important;
      font-weight: 400 !important;
      font-size: 13px !important;
      line-height: 20px !important;
      color: #7D8693 !important;
    }
    .footer {
      font-family: 'Lato', sans-serif !important;
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
      <img  src="{{$message->embed(public_path().'/images/CheckCircle.png')}}" alt="">
     <div class="heading">Doctor request rejected!
      </div>
    </div>
    <div class="inner-section">
      <div class="title">Hi <strong> {{ucfirst($doctor['first_name'])}} 
        {{ucfirst($doctor['middle_name'])}} {{ucfirst($doctor['last_name'])}}</strong>,</div>
      <div class="description">Your request has been reviewed by the registration team. We feel very sorry to let you know that your Practice registration request has been rejected.

      </div>

      <div class="row">
        <div class="action-heading">You can submit a new request by clicking on the below link
</div>
      </div>

      <div class="row">
        <a  href="{{$parcticeLogin}}" target="_blank"  class="action-btn">Visit website</a>

      </div>  


      <div class="best-regards">
        <p style="margin-bottom: 0px;">Best Regards, </p>
        <p style="margin-top: 0px;"><strong>Team iCarePro</strong></p>
      </div>

    </div>
    @extends('email.layout.footer')


</body>
</html>