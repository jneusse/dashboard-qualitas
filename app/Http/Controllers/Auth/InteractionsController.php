<?php

namespace App\Http\Controllers\Auth;

use App\Events\AlertInteraction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Interaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Message;
use App\Exports\ConversationExport;
use Maatwebsite\Excel\Excel;

class InteractionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('session.expire');
    }

    public function getTotalInteractions(Request $request){

        $startDate = $request->startDate;
        $endDate = $request->endDate;
        // Consultar conversaciones en el año y mes seleccionado
        $interactions = Interaction::whereBetween('createdAt', [$startDate, $endDate])->get();
        $eventosClave = Interaction::where('flag_alert_indicator', true)->get();

        $res = new \stdClass();
        $res->total = count($interactions);
        $res->eventosClave = count($eventosClave);
        $res->sinEventosClave = $res->total - $res->eventosClave;

        $firstDay = Carbon::parse($startDate);
        $lastDay = Carbon::parse($endDate);
        $totalDias = $lastDay->diffInDays($firstDay)+1;

        $res->firstDay = $firstDay->format('Y-m-d');
        $res->lastDay = $lastDay->format('Y-m-d');
        $res->totalDias = $totalDias;

        $days = [];
        $interactionByGroup = DB::select('call getInteractionsByDaysBetween(?, ?)', [$res->firstDay, $res->lastDay]);

        for($d = 0; $d < $totalDias; $d++){

            $keyDay = $firstDay->format('Y-m-d');
            $days[$keyDay] = [0, 0];
            $firstDay->addDays();
        }

        foreach($interactionByGroup as $int){
            $days[$int->createdAt] = [$int->total-$int->flag, $int->flag];
        }

        $res->days = $days;

        return Response::json($res, 200);
    }
    public function getInteractions(Request $request){

        $startDate = $request->startDate;
        $endDate = $request->endDate;
        // Consultar conversaciones en el año y mes seleccionado
        if(isset($request->search)){
          $interactions = Interaction::whereBetween('createdAt', [$startDate, $endDate])
                ->where([['messageText', 'like', $request->search."%"]])
                ->orWhere([['id', $request->search."%"]])
                ->paginate(15);
        }else{
          $interactions = Interaction::whereBetween('createdAt', [$startDate, $endDate])
          ->paginate(15);
        }

        return Response::json($interactions, 200);
    }
    public function export(Request $request)
    {
        // $startDate = $request->startDate;
        // $endDate = $request->endDate;
        // $conversations = Conversation::whereBetween('conversation_start', [$startDate, $endDate])->get();

        // return (new ConversationExport)->getConversations($conversations)->download('data.xlsx');
    }
    public function getDetailInteraction(Request $request)
    {
        $interaction = Interaction::find($request->id);
        return Response::json($interaction, 200);
    }
}

