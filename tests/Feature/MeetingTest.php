<?php

namespace Tests\Feature;

use App\Models\Meeting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MeetingTest extends TestCase
{
    use RefreshDatabase;

    function test_list_meetings()
    {
        $meeting = Meeting::factory()->create();

        $this->get('api/v1/meetings')
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    0   => [
                        'id'    => $meeting->id,
                    ],
                ]
            ]);
    }

    // show
    function test_show_a_meeting()
    {
        $this->withoutExceptionHandling();
        $meeting = Meeting::factory()->create(['id' => $this->id]);

        $this->get('api/v1/meetings'.$meeting->id)
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    0   => [
                        'id'    => $meeting->id,
                    ],
                ]
            ]);
    }

    function test_create_a_meeting()
    {
        $data = Meeting::factory()->make();

        $parameters = [
            'name'          => $data->name,
            'description'   => $data->description,
        ];

        $this->post('api/v1/meetings', $parameters)
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    'name'  => $data->name,
                ]
            ]);

        $this->assertDatabaseHas('meetings', [
            'name'          => $data->name,
            'description'   => $data->description,
        ]);
    }

    // update

    // destroy
    function test_destroy_a_meeting()
    {
        $this->withoutExceptionHandling();
        $meeting = Meeting::factory()->create();
        $this->delete('api/v1/meetings'.$meeting->id)
            ->assertStatus(204);

        $this->assertSoftDeleted('meetings',[
            'id' => $meeting->id
        ]);
    }
}
