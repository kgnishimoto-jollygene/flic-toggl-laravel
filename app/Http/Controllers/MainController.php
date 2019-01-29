<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use AJT\Toggl\TogglClient;

class MainController extends Controller
{
    public function index(Request $request)
    {
        $bodyContent = $request->getContent();

        $togglToken = $bodyContent;
        $togglClient = TogglClient::factory(array('api_key' => $togglToken));

        $timeEntries = $togglClient->getTimeEntries();
        $runnigTimeEntries = [];
        foreach ($timeEntries as $timeEntry) {
            if (isset($timeEntry['stop'])) {
                continue;
            }
            $runnigTimeEntries[] = $timeEntry;
        }
        if (count($runnigTimeEntries) === 0) {
            $togglClient->startTimeEntry([
                "time_entry" => [
                    "created_with" => "Flic",
                ],
            ]);
        } else {
            $togglClient->stopTimeEntry([
                "id" => $runnigTimeEntries[0]['id'],
            ]);
        }
        return;
    }
}
