<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MeetingResource;
use App\Models\File;
use App\Models\Meeting;
use App\Notifications\MeetingNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class MeetingController extends Controller
{
    public function index()
    {
        $meetings = Meeting::query()->get();

        return MeetingResource::collection($meetings);
    }

    public function store(Request $request)
    {
        $meeting = Meeting::create($request->only(['name', 'description', 'date']));

        $meeting->addParticipants($request)->storeFile($request)->sendNotification();

        return new MeetingResource($meeting);
    }

    public function show($meetingId)
    {
        $meeting = Meeting::query()->find($meetingId);

        return new MeetingResource($meeting);
    }

    public function destroy($meetingId)
    {
        Meeting::query()->where('id', $meetingId)->delete();

        return response([],204);
    }
}
