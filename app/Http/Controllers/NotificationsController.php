<?php

namespace App\Http\Controllers;

use App\Events\AlertInteraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use stdClass;

class NotificationsController extends Controller
{
    public function newInteraction(Request $request)
    {
        $interaction = new stdClass;

        $interaction->id = $request->id;
        $interaction->callId = $request->callId;
        $interaction->messageText = $request->messageText;


        event(new AlertInteraction($interaction));

        return Response::json($interaction, 200);
    }
}
