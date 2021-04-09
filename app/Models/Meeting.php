<?php

namespace App\Models;

use App\Notifications\MeetingNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Notification;

class Meeting extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'meetings';
    protected $guarded = [];

    // relations

    public function participants()
    {
        return $this->belongsToMany(
            User::class, 'meeting_participants', 'meeting_id', 'participant_id'
        );
    }

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    // methods

    public function getNumber()
    {
        return 'R'.$this->id;
    }

    public function addParticipants($request)
    {
        $this->participants()->attach($request->participants);

        return $this;
    }

    public function storeFile($request)
    {
        $path = $request->file('file')->store('meetings');

        $this->file()->create([
            'name'  => $request->file('file')->getClientOriginalName(),
            'path'  => $path,
        ]);

        return $this;
    }

    public function sendNotification()
    {
        Notification::send($this->participants()->select('id', 'email')->get(), new MeetingNotification());

        return $this;
    }
}
