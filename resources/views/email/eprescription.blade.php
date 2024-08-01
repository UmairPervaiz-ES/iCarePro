<!DOCTYPE html>
<html lang="en">
​
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-prescription</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            padding: 0px;
            margin: 0px;
            font-family: 'Lato', sans-serif;
            /* letter-spacing: 1px;  */
        }
    </style>
</head>
​
<body>
    <div class="spacer" style="width: 100%; height: 4px; background: #1c4c9a;">
    </div>
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 35px; background: #F9F9FB">
        <div class="" style="max-width: 150px;">
          
           
            <img src="{{asset('images/SeekPng.png')}}" alt="" width="100%">
        </div>
        <div style="text-align: right">
            <p style="font-size: 10.5px; line-height: 22px; font-weight: 600;">Prescription ID: 26378</p>
            <p style="font-size: 10px; line-height: 10px; font-weight: 400;">5 October 2022, Wed</p>
        </div>
    </div>
    <div style="display: flex; justify-content: space-between; padding: 23px 35px 15px;">
        <div class="">
            <p style="font-size: 14px; line-height: 22px; font-weight: 700;">Elizabeth Bennet, 40y/M</p>
            <p style="font-size: 12px; line-height: 22px; font-weight: 400;">Patient ID: 99790</p>
            <p style="color: #505050; line-height: 22px; font-size: 11px; font-weight: 400;">
                <img    src="{{asset('images/EnvelopeSimple.svg')}}"  width="10px" alt="">&nbsp;&nbsp;elizabeth.bennett@icareprop.com
            </p>
            <p style="color: #505050; line-height: 22px; font-size: 11px; font-weight: 400;"><img    src="{{asset('images/Phone2.svg')}}" width="10px" alt="">&nbsp;&nbsp;001-202-555-0114</p>
        </div>
        <div style="text-align: right">
            <p style="font-size: 14px; line-height: 22px; font-weight: 700;">Dr. Gilbert Joseph Richardson</p>
            <p style="font-size: 12px; line-height: 22px; font-weight: 400;">Doctor ID: 98790</p>
            <p style="color: #505050; line-height: 22px; font-size: 11px; font-weight: 400;">
                richard.joseph@icarepro.com&nbsp;&nbsp;<img  src="{{asset('images/EnvelopeSimple.svg')}}" width="10px" alt="">
            </p>
            <p style="color: #505050; line-height: 22px; font-size: 11px; font-weight: 400;">
                001-202-555-0114&nbsp;&nbsp;<img src="{{asset('images/Phone2.svg')}}"  width="10px" alt="">
            </p>
        </div>
    </div>
    <div style="display: flex; justify-content: space-between; padding: 0px 35px 23px;">
        <div class="">
            <p style="font-size: 12px; line-height: 22px; font-weight: 600;">Current Diagnosis:</p>
            <p style="color: #505050; font-size: 12px; line-height: 22px; font-weight: 400;">Diabetes mellitus with complication and Schizophrenia spectrum and other psychotic disorders.</p>
        </div>
    </div>
    <div class="seperator" style="height: 1px; background: #F2F2F4;">
    </div>
    <div style=" padding: 23px 35px 23px;">
        <p style="font-size: 12px; line-height: 22px; font-weight: 700;">Prescribed Drugs</p>
        <div style="margin-top: 7px;margin-bottom: 4px;background: #F9F9FB;padding: 12px 15px; display: flex; justify-content: space-between; border-radius: 4px;">
            <div class="">
                <p style="color: #505050; font-size: 11px; line-height: 13.2px; font-weight: 700;">Diabetes mellitus with complication and Schizophrenia spectrum and other psychotic disorders.</p>
                <p style="color: #505050; font-size: 10px; line-height: 15px; font-weight: 400; margin-top: 4px;">Take 1 tablet every day by oral route before meals for 12 days. | no refills</p>
                <p style="color: #505050; font-size: 10.5px; line-height: 12.6px; font-weight: 400; margin-top: 9px;"><strong>Note to Patient:</strong> Avoid oily and spicy food and take complete bed rest</p>
                <p style="color: #505050; font-size: 10.5px; line-height: 12.6px; font-weight: 400; margin-top: 2px;"><strong>Note to Patient:</strong> Do not refill more than once</p>
            </div>
            <div style="text-align: right">
                <p style="color: #505050; font-size: 11px; line-height: 13.2px; font-weight: 700;">قرص أسبرين 325 ملغ</p>
                <p style="color: #505050; font-size: 10px; line-height: 15px; font-weight: 400; margin-top: 4px;">خذ قرصًا واحدًا كل يوم عن طريق الفم قبل وجبات الطعام لمدة 12 يومًا. | لا عبوات</p>
            </div>
        </div>
        <div style="margin-top: 7px;margin-bottom: 4px;background: #F9F9FB;padding: 12px 15px; display: flex; justify-content: space-between; border-radius: 4px;">
            <div class="">
                <p style="color: #505050; font-size: 11px; line-height: 13.2px; font-weight: 700;">Diabetes mellitus with complication and Schizophrenia spectrum and other psychotic disorders.</p>
                <p style="color: #505050; font-size: 10px; line-height: 15px; font-weight: 400; margin-top: 4px;">Take 1 tablet every day by oral route before meals for 12 days. | no refills</p>
                <p style="color: #505050; font-size: 10.5px; line-height: 12.6px; font-weight: 400; margin-top: 9px;"><strong>Note to Patient:</strong> Avoid oily and spicy food and take complete bed rest</p>
                <p style="color: #505050; font-size: 10.5px; line-height: 12.6px; font-weight: 400; margin-top: 2px;"><strong>Note to Patient:</strong> Do not refill more than once</p>
            </div>
            <div style="text-align: right">
                <p style="color: #505050; font-size: 11px; line-height: 13.2px; font-weight: 700;">قرص أسبرين 325 ملغ</p>
                <p style="color: #505050; font-size: 10px; line-height: 15px; font-weight: 400; margin-top: 4px;">خذ قرصًا واحدًا كل يوم عن طريق الفم قبل وجبات الطعام لمدة 12 يومًا. | لا عبوات</p>
            </div>
        </div>
        <div style="margin-top: 7px;margin-bottom: 4px;background: #F9F9FB;padding: 12px 15px; display: flex; justify-content: space-between; border-radius: 4px;">
            <div class="">
                <p style="color: #505050; font-size: 11px; line-height: 13.2px; font-weight: 700;">Diabetes mellitus with complication and Schizophrenia spectrum and other psychotic disorders.</p>
                <p style="color: #505050; font-size: 10px; line-height: 15px; font-weight: 400; margin-top: 4px;">Take 1 tablet every day by oral route before meals for 12 days. | no refills</p>
                <p style="color: #505050; font-size: 10.5px; line-height: 12.6px; font-weight: 400; margin-top: 9px;"><strong>Note to Patient:</strong> Avoid oily and spicy food and take complete bed rest</p>
                <p style="color: #505050; font-size: 10.5px; line-height: 12.6px; font-weight: 400; margin-top: 2px;"><strong>Note to Patient:</strong> Do not refill more than once</p>
            </div>
            <div style="text-align: right">
                <p style="color: #505050; font-size: 11px; line-height: 13.2px; font-weight: 700;">قرص أسبرين 325 ملغ</p>
                <p style="color: #505050; font-size: 10px; line-height: 15px; font-weight: 400; margin-top: 4px;">خذ قرصًا واحدًا كل يوم عن طريق الفم قبل وجبات الطعام لمدة 12 يومًا. | لا عبوات</p>
            </div>
        </div>
    </div>
    <footer>
        <div style="display: flex; justify-content: center; gap: 16px; padding: 8px 35px; align-items: center">
            <p style="font-size: 9px; line-height: 15px; font-weight: 400"><img  src="{{asset('images/location.svg')}}"  alt="" width="9px">&nbsp;&nbsp;3195 Brown Mountain Beach Rd Morganton, North Carolina, 28655</p>
            <p style="font-size: 13px; color: rgba(192, 195, 206, 0.5)">|</p>
            <p style="font-size: 9px; line-height: 15px; font-weight: 400"><img  src="{{asset('images/email.svg')}}" alt="" width="9px">&nbsp;&nbsp;practice@icarepro.com</p>
            <p style="font-size: 13px; color: rgba(192, 195, 206, 0.5)">|</p>
            <p style="font-size: 9px; line-height: 15px; font-weight: 400"><img   src="{{asset('images/phone.svg')}}" alt="" width="9px">&nbsp;&nbsp;001-202-555-0114</p>
        </div>
        <div class="spacer" style="width: 100%; height: 4px; background: #1c4c9a;">
        </div>
        <div style="padding: 9px 35px;">
            <p style="font-size: 9px; line-height: 12px; font-weight: 400; text-align: center; color: #7D8693">Disclaimer: Lorem ipsum dolor sit amet consectetur adipiscing elit sed eiusmod tempor incididunt labore et dolore magna aliqua. Utenim ad minim veniam, quis nostrud exercitation ullamco. Laboris nisi ut aliquip commodo consequat excepteur sint.</p>
        </div>
    </footer>
</body>
​
</html>