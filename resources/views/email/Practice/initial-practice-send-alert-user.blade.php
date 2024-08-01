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
      background: #F9F9FB;
      padding: 20px;
    }
    .header img {
      height: 28px;
      width: 108px;
    }
    .section {
      padding: 30px;
      background-color: white;
    }
    .section .top {
      display: flex;
    }
    .section .top img {
      height: 24px;
     width: 26px;
     margin-top: 4px;
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
      margin-top: 10px;
    }
    .inner-section .description {
      margin-top: 10px;
      margin-bottom: 22px;
    }
    .inner-section .row{
      display: flex;
      font-family: 'Lato', sans-serif !important;
      margin-bottom: 5px;
      font-style: normal !important;
      font-weight: 500;
      font-size: 14px;
      line-height: 22px;
      color: #0D0C22 !important;
    }
    .inner-section .row .value{
      margin-left: 4px;
    }

    .action-heading {
      font-family: 'Lato', sans-serif !important;
      font-style: normal;
      font-weight: 400;
      font-size: 14px;
      line-height: 22px;
      margin-top: 10px;
      margin-bottom: 21px;
    }
    .action-btn {
    background: #2A6049 !important;
    border-radius: 5px;
    height: 36px;
    width: 200px;
    color: white !important;
    font-family: 'Lato' , sans-serif !important;
    font-style: normal;
    font-weight: 500;
    font-size: 14px;
    line-height: 35px;
    text-align: center;
    text-decoration: none;
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
      font-family: 'Lato' , sans-serif !important;
      font-size: 14px !important;
      font-weight: 700 !important;
      line-height: 22px !important;
      letter-spacing: 0em !important;
      text-align: left !important;
      margin-bottom: 50px !important;
      margin-top: 55px !important;
      margin-left: -89px !important;
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
      font-style: normal;
      font-weight: 400;
      font-size: 12px;
      line-height: 21px;
      text-align: center;
      color: #7D8693;
      margin: 10px 0px 10px 0px;
    }
    .key {
      margin-left: 10px;
    }
    .cancel_reason {
    margin-left: -9px;
    width: 10px;
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
     <div class="heading">Initial practice registration request sent successfully!
      </div>
    </div>
    <div class="inner-section">
      <div class="title">Hi <strong> 
        {{ucfirst($initialPractice['first_name'])}}
         {{ucfirst($initialPractice['middle_name'])}} 
         {{ucfirst($initialPractice['last_name'])}}
        </strong>,</div>
      <div class="description">
        Thank you for reaching out. Your request has been received, our team will get back to you in the next 48 hours.
      </div>
    

      <div class="best-regards">
        <p style="margin-bottom: 0px;">Best Regards, </p>
        <p style="margin-top: 0px;"><strong>Team iCarePro</strong></p>
      </div>
  

    </div>
    @extends('email.layout.footer')


</body>
</html>