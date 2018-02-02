<?php

require_once "./vendor/autoload.php";

use Dotenv\Dotenv;
use Meeting\Address;
use Meeting\MeetingClient;

$dotenv = new Dotenv(__DIR__);
$dotenv->load();
$user = getenv('MEETINGS_API_USER');
$pass = getenv('MEETINGS_API_PASSWORD');

if (empty($user)) {
    exit("Must define MEETINGS_API_USER in .env file");
} else if (empty($user)) {
    exit("Must define MEETINGS_API_PASSWORD in .env file");
}

$resource = 'tools.referralsolutionsgroup.com/meetings-api/v1/';
$scheme = 'http://';
$url = $scheme . $user . ':' . $pass . '@' . $resource;

// Create the client
$meetingClient = MeetingClient::build($user, $pass);
$meetingCollection = $meetingClient->search();

$referenceAddress = new Address();
$referenceAddress->setLat('32.7107335');
$referenceAddress->setLng('-117.1629242');
$filteredMeetingCollection = $meetingCollection->filterByDay('Monday');
$filteredMeetingCollection->sortByDistance($referenceAddress);

print_r($filteredMeetingCollection);
// Send the request


