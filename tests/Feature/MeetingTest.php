<?php

namespace Tests\Feature;

use App\Models\Meeting;
use App\Models\User;
use App\Notifications\MeetingNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
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

    function test_show_a_meeting()
    {
        $meeting = Meeting::factory()->create();

        $this->get('api/v1/meetings/'.$meeting->id)
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    'id'    => $meeting->id,
                ]
            ]);
    }

    function test_create_a_meeting()
    {
        $this->withoutExceptionHandling();

        Storage::fake();
        Notification::fake();

        $data = Meeting::factory()->make();

        $participant = User::factory()->create();
        $participant2 = User::factory()->create();

        $parameters = [
            'name'          => $data->name,
            'description'   => $data->description,
            'date'          => $data->date,
            'participants'  => [$participant->id, $participant2->id],
            'file'          => UploadedFile::fake()->create('test.pdf'),
        ];

        $this->post('api/v1/meetings', $parameters)
            ->assertStatus(201)
            ->assertJson([
                'data'  => [
                    'name'  => $data->name,
                ]
            ]);

        $this->assertDatabaseHas('meetings', [
            'name'          => $data->name,
            'description'   => $data->description,
            'date'          => $data->date,
        ]);

        $this->assertDatabaseHas('meeting_participants', [
            'meeting_id'        => Meeting::max('id'),
            'participant_id'    => $participant->id,
        ]);

        $this->assertDatabaseHas('meeting_participants', [
            'meeting_id'        => Meeting::max('id'),
            'participant_id'    => $participant2->id,
        ]);

        $this->assertDatabaseHas('files', [
            'fileable_id'       => Meeting::max('id'), // 1
            'fileable_type'     => Meeting::class, // App\\Models\\Meeting
            'name'              => 'test.pdf'
        ]);

        Notification::assertSentTo($participant, MeetingNotification::class);
    }

    // update
    function test_update_a_meeting(){

        $this->withoutExceptionHandling();

        $meeting = Meeting::factory()->create();
        $data = Meeting::factory()->make();

        $parameters = [
            'name'          => $data->name,
            'description'   => $data->description,
            'date'          => $data->date,
        ];

        $this->put('api/v1/meetings/'.$meeting->id, $parameters)
            ->assertStatus(200)
            ->assertJson([
                'data'  => [
                    'name'  => $data->name,
                ]
            ]);

        $this->assertDatabaseHas('meetings', [
            'name'          => $data->name,
            'description'   => $data->description,
            'date'          => $data->date,
        ]);

    }

    function test_destroy_a_meeting()
    {
        $meeting = Meeting::factory()->create();

        $this->delete('api/v1/meetings/'.$meeting->id)
            ->assertStatus(204);

        $this->assertSoftDeleted('meetings',[
            'id' => $meeting->id
        ]);
    }
}
