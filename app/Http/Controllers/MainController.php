<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use AJT\Toggl\TogglClient;

class MainController extends Controller
{
    public function index(Request $request)
    {
        $bodyContent = $request->getContent();

        $toggl_token = $bodyContent; // Fill in your token here
        $toggl_client = TogglClient::factory(array('api_key' => $toggl_token, 'debug' => true));

        $toggl_client = TogglClient::factory(array('api_key' => $toggl_token));

        $timeEntries = $toggl_client->getTimeEntries();
        $runnigTimeEntries = [];
        foreach ($timeEntries as $timeEntry) {
            if (isset($timeEntry['stop'])) {
                continue;
            }
            $runnigTimeEntries[] = $timeEntry;
        }
        if (count($runnigTimeEntries) === 0) {
            $toggl_client->startTimeEntry([
                "time_entry" => [
                    "created_with" => "Flic",
                ],
            ]);
        } else {
            $toggl_client->stopTimeEntry([
                "id" => $runnigTimeEntries[0]['id'],
            ]);
        }
        return;
    }
}
