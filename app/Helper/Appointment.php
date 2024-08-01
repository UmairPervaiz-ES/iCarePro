<?php

namespace App\Helper;

use App\Models\Appointment\Appointment as ModelsAppointment;
use App\Models\Practice\Practice;
use Google\Service\Calendar\Event;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage as FacadesStorage;



class Appointment
{
    /**
     * Create Appointment
     *
     * @param mixed $request
     * @return ModelsAppointment
     */
    public static function createAppointment($request): ModelsAppointment
    {
        $appointmentKey = Appointment::setAppointmentKey($request);
        return  ModelsAppointment::create([
            'appointment_key' => $appointmentKey,
            'practice_id' => $request['practice_id'],
            'doctor_id' => $request['doctor_id'],
            'patient_id' => $request['patient_id'],
            //  medical problem ids is covert to string
            'medical_problem_id' => implode(',', $request['medical_problem_id']),
            'doctor_slot_id' => $request['doctor_slot_id'],
            'date' => $request['date'],
            'status' => 'Confirmed',
            'start_time' =>  $request['start_time'],
            'end_time' =>  $request['end_time'],
            'utc_date' =>  $request['date'],
            'utc_start_time' =>  $request['start_time'],
            'utc_end_time' =>  $request['end_time'],
            'instructions' => $request['instructions'],
            'created_by' => $request['created_by'],
            'appointment_type' => $request['appointment_type'],
            'previous_id' => isset($request['id']) ? $request['id'] : null,
        ]);
    }

    static function getEventsICalObject($icareProEventDetails): string
    {

        $start = Carbon::parse($icareProEventDetails['date'] . ' ' . $icareProEventDetails['start_time'])->format('Ymd\THis\Z');
        $end = Carbon::parse($icareProEventDetails['date'] . ' ' . $icareProEventDetails['end_time'])->format('Ymd\THis\Z');

        $summary = "Apponitment is confirmed ";
        $icalObject = "BEGIN:VCALENDAR
VERSION:2.0
METHOD:PUBLISH
PRODID:-//Charles Oduk//Tech Events//EN\n";

        $icalObject .=
            "BEGIN:VEVENT
DTSTART:" . $start . "
DTEND:" . $end . "
DTSTAMP:" . $start . "
SUMMARY:$summary
DESCRIPTION:$icareProEventDetails->content
UID:$icareProEventDetails->uuid
STATUS:" . 'CONFIRMED' . "
LAST-MODIFIED:" . $start . "
LOCATION:
END:VEVENT\n";

        $icalObject .= "END:VCALENDAR";
        $fName = storage_path('app/public/ical/' . $icareProEventDetails['id'] . 'icarepro-event.ics');
        $fp = fopen($fName, "wb");
        fwrite($fp, $icalObject);
        fclose($fp);
        return $fName;
    }

    /**
     * Get Initials From Name
     * @return string
     */
    public static function getInitials($string = null)
    {
        return array_reduce(
            explode(' ', $string),
            function ($initials, $word) {
                return strtoupper(sprintf('%s%s', $initials, substr($word, 0, 1)));
            },
            ''
        );
    }

    /**
     * Description: Set Appointment Key
     *  1) Get Practice
     *  2) Get Initials of Practice
     *  3) Set Date, Time, Year
     *  4) Check Appointment Key Exist
     *  5) Add/ Create Appointment key
     *
     * @param mixed $request
     * @return String
     */
    public static function setAppointmentKey($request = null)
    {
        $practice = Practice::with('initialPractice')->where('id', $request['practice_id'])->first();
        $name = Appointment::getInitials($practice['initialPractice']['practice_name']);

        $month = date('m', strtotime($request['date']));
        $date = date('d', strtotime($request['date']));
        $year = date('y', strtotime($request['date']));

        $checkAppointmentKey = ModelsAppointment::where('appointment_key', 'like', $name . '-' . $month . '%')
            ->latest('appointment_key')->first();
        if ($checkAppointmentKey) {
            $appointmentKey = $checkAppointmentKey['appointment_key'];
            $keys = explode('-', $appointmentKey);
            $key = $keys[2];
            $key = str_pad(intval($key) + 1, strlen($key), '0', STR_PAD_LEFT);
            $appointmentKey = $name . '-' . $keys[1] . '-' . $key;
        } else {
            $appointmentKey = $name . '-' . $month . $date . $year . '-00001';
        }
        return $appointmentKey;
    }
}
