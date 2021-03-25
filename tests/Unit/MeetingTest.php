<?php

namespace Tests\Unit;

use App\Models\Meeting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MeetingTest extends TestCase
{
    use RefreshDatabase;

    function test_number_by_meeting()
    {
        $meeting = Meeting::factory()->create();

        $this->assertEquals('R'.$meeting->id, $meeting->getNumber());
    }
}