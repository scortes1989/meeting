<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MeetingResource;
use App\Models\Meeting;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function index()
    {
        $meetings = Meeting::query()->get();

        return MeetingResource::collection($meetings);
    }

    public function store(Request $request)
    {
        $meeting = Meeting::create($request->only(['name', 'description']));

        return new MeetingResource($meeting);
    }
}
