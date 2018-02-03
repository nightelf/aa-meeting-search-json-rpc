<?php

require_once "./vendor/autoload.php";

use Dotenv\Dotenv;
use Meeting\AttendeeParser;
use Meeting\MeetingClient;
use Meeting\AttendeeMailer;

$dotenv = new Dotenv(__DIR__);
$dotenv->load();
$user = getenv('MEETINGS_API_USER');
$pass = getenv('MEETINGS_API_PASSWORD');
$templatePath = __DIR__ . '/views/templates/';
$templateCachePath = $templatePath . 'cache/';

if (empty($user)) {
    exit("Must define MEETINGS_API_USER in .env file");
} else if (empty($user)) {
    exit("Must define MEETINGS_API_PASSWORD in .env file");
}


$attendeeParser = AttendeeParser::build();
$attendeeCollection = $attendeeParser->parseCsvFile($argv[1]);

// Create the client
$meetingClient = MeetingClient::build($user, $pass);
$meetingCollection = $meetingClient->search('San Diego', 'CA');
$attendeeMailer = AttendeeMailer::build($templatePath, [
    'cache' => $templateCachePath,
]);

$attendeeMailer->emailAttendees($attendeeCollection, $meetingCollection);



