<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{

    public $slot_length;

    public function __construct()
    {
        $this->slot_length = 0.5 * 60; //half of hour: 30 minutes
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'appointable_id' => 'required',
            'day' => 'required|integer'
        ]);

        return response($this->calculateSlotDays($request->day, $request->appointable_id));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'appointable_id' =>  'required|integer',
            'start_at' => 'required',
        ]);

        $user_id = auth()->id();
        $appointable_id = $request->appointable_id ?? auth()->id();
        $appointable_type = User::class;
        $start_at = $request->start_at;
        $end_at = $start_at + $this->slot_length * 60;

        $check_appointment = Appointment::where('start_at', Carbon::createFromTimestamp($start_at)->toDateTimeString())->where('appointable_id', $appointable_id)->first();

        if ($check_appointment == null) {

            Appointment::create(compact(
                'user_id',
                'appointable_id',
                'appointable_type',
                'start_at',
                'end_at',
            ));

            return response('apointed successfuly!');
        } else {

            return response('appointment slot is not free', 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $mode)
    {
        /**
         * modes:
         *      yours
         *      customers
         */

        switch ($mode) {
            case 'yours':
                return response(Appointment::where('user_id', auth()->id())->where('appointable_type', User::class)->orderBy('start_at', 'DESC')->get());
                break;

            case 'customers':
                return response(Appointment::where('appointable_id', auth()->id())->where('appointable_type', User::class)->orderBy('start_at', 'DESC')->get());
                break;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $appointment = Appointment::where('id', $id)->where('appointable_type', User::class)->first();

        if($appointment !== null) {
            if($appointment->user_id == auth()->id() || $appointment->appointable_id == auth()->id())
            {
                $appointment->delete();
                return response('deleted successfuly');
            } else {
                return response('not found', 404);
            }
        } else {
            return response('not found', 404);
        }
    }

    public function calculateSlotUnit($i, $date)
    {

        $starting_slot = $i / 60;
        $starting_slot_rounded = $starting_slot % 60;
        $remained = ($starting_slot - $starting_slot_rounded) * 60;
        if ($remained == 0) {
            $remained = '00';
        }
        if ($starting_slot_rounded < 10) {
            $starting_slot_rounded = "0$starting_slot_rounded";
        }
        $date->setTime($starting_slot_rounded, $remained);
        return $date->timestamp;
    }

    public function calculateSlotDays($day, $appointable_id)
    {
        $date = Carbon::now();

        $start_hour = 9 * 60;
        $day_hours = 11 * 60 + $start_hour;
        $slot_length = $this->slot_length;

        $addDayFromStartofDay = $date->startOfDay()->addDays($day);
        $current_loop_day = $addDayFromStartofDay->timestamp;

        $slots = [];
        for ($j = $start_hour; $j <= $day_hours - $slot_length; $j += $slot_length) {

            $enable = true;

            $start_at = $this->calculateSlotUnit($j, $addDayFromStartofDay);
            $end_at = $this->calculateSlotUnit($j + $slot_length, $addDayFromStartofDay);

            if (Appointment::where('start_at', Carbon::createFromTimestamp($start_at)->toDateTimeString())->where('appointable_id', $appointable_id)->first() !== null || $start_at < Carbon::now()->timestamp) {
                $enable = false;
            }

            $current_slot = compact('start_at', 'end_at', 'enable');

            array_push($slots, $current_slot);
        }

        return $slots;
    }
}
