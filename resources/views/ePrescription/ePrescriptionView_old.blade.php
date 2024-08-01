<!DOCTYPE html>
<html>
<head>
    <title>E-prescription</title>
</head>
<style>
    /* .card{
        background-color: #a0abb74d;
    } */
 
    @page {
        margin: 60px 0px;
    }
body{
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
}

    header {
        position: fixed;
        top: -60px;
        height: 35px;
        width: 100%;
        background-color: #296049;
        color: white;
        text-align: center;
        line-height: 35px;
    }

    footer {
        position: fixed;
        bottom: -60px;
        height: 35px;
        width:100%;
        background-color: #296049;
        color: white;
        text-align: center;
        line-height: 35px;
    }

    main{
        margin:0px 30px;
    }

    table{
        font-size: 14px;
    }
    .table th , .table td{
        text-align: left;
        width: 33.3%;
    }

    .table td{
        padding-bottom: 25px;
    }

    .capitalCase{
        text-transform: capitalize;
    }

    hr{
        outline: none;
        border:0.2px solid #a7a7a7;
    }
    p{
        line-height: 25px;
        font-size: 14px;
    }
    b{
        line-height: 15px;
    }
    .logo{
        height: 40px;
        width: auto;
        margin-bottom: 25px;
    }
</style>
<body>
    @if(isset($data))
        <header></header>

        <footer></footer>

        <main>
            <table width="100%">
                <tr>
                    <td width="50%"><img class="logo" src="data:image/png;base64,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" alt="logo"></td>
                    <td align="right" style="color:#5a5a5a;">{{$data['appointment']['appointment_key']}}</td>
                </tr>
            </table>
                <table width="100%" class="table">
            <tr>
                <th>Practice Name</th>
                <th>Practice Email</th>
                <th>Practice Phone</th>
            </tr>
            <tr>
                <td>{{$data['practice']['initial_practice']['practice_name']}}</td>
                <td>{{$data['practice']['email']}}</td>
                <td>{{$data['practice']['initial_practice']['phone_number']}}</td>
            </tr>
            <tr>
                <th>Doctor Name</th>
                <th>Doctor Email</th>
                <th>Doctor Phone</th>
            </tr>
            <tr>
                <td>{{$data['doctor']['first_name'].' '.$data['doctor']['last_name']}}</td>
                <td>{{$data['doctor']['primary_email']}}</td>
                <td>{{$data['doctor']['country_code_primary_phone_number'].' '.$data['doctor']['primary_phone_number']}}</td>
            </tr>
        </table>
        <table width="100%">
            <tr  align="left">
                <th>Patient Name</th>
                <th>Patient Email</th>
                <th>Patient Phone</th>
                <th>Patient Gender</th>
            </tr>
            <tr>
                <td>{{$data['patient']['first_name'].' '.$data['patient']['last_name']}}</td>
                <td>{{$data['patient']['email']}}</td>
                <td>{{$data['patient']['country_code'].' '.$data['patient']['phone_number']}}</td>
                <td>{{$data['patient']['gender']}}</td>
            </tr>
        </table>
        <hr/>
        <div class="body">
            @if($data['prescribed_drugs'])
                <h3>Prescribed Drugs</h3>
                @foreach($data['prescribed_drugs'] as $drug)
                <div class="card">
                    <b>{{$drug['drug_name']}}</b>
                    <p>Take <span class="capitalCase">{{$drug['type']}}</span> ({{$drug['quantity']}} {{$drug['mg_tab']}}) <span class="capitalCase">{{$drug['repetition']}}</span> by <span class="capitalCase">{{$drug['route']}}</span> Route - <span class="capitalCase">{{$drug['when']}}</span> - {{$drug['quantity_unit']}} {{$drug['for_days']}} <br>
                    @if($drug['note_to_patient'])<b>Note to Patient:</b> {{$drug['note_to_patient']}}@endif
                    @if($drug['note_to_pharmacy'])<b>Note to Pharmacy:</b> {{$drug['note_to_pharmacy']}}@endif</p>
                </div>
                <hr/>
                @endforeach
            @endif
            @if($data['prescribed_lab_tests'])
                <h3>Prescribed Lab Test</h3>
                @foreach($data['prescribed_lab_tests'] as $labTest)
                @endforeach
            @endif
            @if($data['prescribed_procedures'])
                <h3>Prescribed Procedure</h3>
                @foreach($data['prescribed_procedures'] as $procedure)
                @endforeach
            @endif
        </div>
        </main>
    @endif
</body>
</html>