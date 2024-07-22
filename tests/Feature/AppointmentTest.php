<?php

namespace Tests\Feature;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        // dd(Carbon::createFromTimestamp(1721628000)->toDateTimeString());
        dd(Appointment::where('start_at', Carbon::createFromTimestamp(1721628000)->toDateTimeString())->where('appointable_id', 1)->first());

        $response->assertStatus(200);
    }
}
