<?php

require_once "./vendor/autoload.php";

use Dotenv\Dotenv;
use Meeting\AttendeeParser;
use Meeting\MeetingClient;
use Meeting\AttendeeMailer;
use Geocoder\Exception\QuotaExceeded;

// Parse args. Could not find a good arg parser package
$argMap = [
    1 => [ 'error' => 'Argument 1, the path of the attendee csv, is required' ],
    2 => [ 'error' => 'Argument 2 the search city, is required' ],
    3 => [ 'error' => 'Argument 3 the search state code (eg. \'CA\'), is required' ],
];

foreach ($argMap as $key => $arg) {
    if (!isset($argv[$key])) {
        exit("\n" . $arg['error'] . "\n");
    }
}

if (count($argv) > 4) {
    exit("\n" . "Too many arguments. Don't forget to double quote args with spaces." . "\n");
}

$attendeeCsvPath = $argv[1];
if (!is_file($attendeeCsvPath)) {
    exit("\n Path  '{$attendeeCsvPath}' is not a file.\n");
}
$searchCity = $argv[2];
$searchState = $argv[3];

// do dotenv
$dotenv = new Dotenv(__DIR__);
$dotenv->load();
$user = getenv('MEETINGS_API_USER');
$pass = getenv('MEETINGS_API_PASSWORD');
$templatePath = __DIR__ . '/views/templates/';
$templateCachePath = $templatePath . 'cache/';

if (empty($user)) {
    exit("\n" ."Must define MEETINGS_API_USER in .env file" . "\n");
} else if (empty($user)) {
    exit("\n" ."Must define MEETINGS_API_PASSWORD in .env file" . "\n");
}

// parse the attendees
$attendeeParser = AttendeeParser::build();
try {
    $attendeeCollection = $attendeeParser->parseCsvFile($attendeeCsvPath);
} catch (QuotaExceeded $e) {
    die("Google geocoding daily limit reached. Sorry folks. No attendees were emailed meeting results. Try again later.");
}



// Create the client, get results and mail
$meetingClient = MeetingClient::build($user, $pass);
$meetingCollection = $meetingClient->search($searchCity, $searchState);
$attendeeMailer = AttendeeMailer::build($templatePath, [
    'cache' => $templateCachePath,
]);

$attendeeMailStatuses = $attendeeMailer->emailAttendees($attendeeCollection, $meetingCollection);
$attendeeMailer->printAttendeeEmailStatusesToConsole($attendeeMailStatuses);


