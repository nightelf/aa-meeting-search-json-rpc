<?php

namespace Meeting;

use Meeting\TwigFunction\UcWords;
use Twig_Loader_Filesystem;
use Twig_Environment;
use Meeting\TwigFunction\GetDistance;

/**
 * Class AttendeeMailer
 * @package Meeting
 */
class AttendeeMailer {

    /**
     * @var string
     */
    const TEMPLATE_NAME = 'near_aa_meetings_email.html';

    /**
     * @var string
     */
    const EMAIL_SUBJECT = 'Some Alcoholics Anonymous meetings in the %s area';

    /**
     * @var integer
     */
    const STATUS_EMAIL_SENT_SUCCESS = 1;

    /**
     * @var integer
     */
    const STATUS_EMAIL_NOT_SENT_NO_MEETINGS_CITY = 2;

    /**
     * @var integer
     */
    const STATUS_EMAIL_NOT_SENT_NO_MEETINGS_DAY = 3;

    /**
     * @var integer
     */
    const STATUS_EMAIL_NOT_SENT_MAIL_FAILURE = 4;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * AttendeeMailer constructor.
     * @param $twig
     */
    public function __construct(Twig_Environment $twig) {

        $this->twig = $twig;
    }

    /**
     * Email attendess
     * @param AttendeeCollection $attendeeCollection
     * @param MeetingCollection $meetingCollection
     * @return array [[ Attendee, integer:status ]]
     */
    public function emailAttendees(AttendeeCollection $attendeeCollection, MeetingCollection $meetingCollection) {

        $attendeeMailStatus = [];

        // @todo clean headers up
        $headers = 'From: no-reply@example.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion() . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-Type: text/html; charset=utf-9' . "\r\n";

        foreach ($attendeeCollection as $attendee) {

            if (!$meetingCollection->count()) {
                $attendeeMailStatus[] = [ $attendee, self::STATUS_EMAIL_NOT_SENT_NO_MEETINGS_CITY ];
                continue;
            }

            $filteredMeetingCollection = $meetingCollection->filterByDay($attendee->getPreferredDay());
            if (!$filteredMeetingCollection->count()) {
                $attendeeMailStatus[] = [ $attendee, self::STATUS_EMAIL_NOT_SENT_NO_MEETINGS_DAY ];
                continue;
            }

            $filteredMeetingCollection->sortByDistance($attendee->getAddress());
            $html = $this->renderTemplate($attendee, $filteredMeetingCollection);

            // Why php mail? Because it just works without configuration.
            $result = mail($attendee->getEmail(), sprintf(self::EMAIL_SUBJECT, ucwords($meetingCollection->getCity())), $html, $headers);

            if ($result) {
                $attendeeMailStatus[] = [ $attendee, self::STATUS_EMAIL_SENT_SUCCESS ];
            } else {
                $attendeeMailStatus[] = [ $attendee, self::STATUS_EMAIL_NOT_SENT_MAIL_FAILURE ];
            }
        }
        return $attendeeMailStatus;
    }

    /**
     * @param array $attendeeMailStatuses [[ Attendee, integer ]]
     */
    public function printAttendeeEmailStatusesToConsole(array $attendeeMailStatuses) {

        $success = [];
        $failNoResultsCity = [];
        $failNoResultsDay = [];
        $failMailError = [];
        $unknown = [];

        foreach ($attendeeMailStatuses as $attendeeMailStatus) {

            list($attendee, $status) = $attendeeMailStatus;
            switch ($status) {
                case AttendeeMailer::STATUS_EMAIL_SENT_SUCCESS:
                    $success[] = [ $attendee->getName(), $attendee->getEmail() ];
                    break;
                case AttendeeMailer::STATUS_EMAIL_NOT_SENT_NO_MEETINGS_CITY:
                    $failNoResultsCity[] = [ $attendee->getName(), $attendee->getEmail() ];
                    break;
                case AttendeeMailer::STATUS_EMAIL_NOT_SENT_NO_MEETINGS_DAY:
                    $failNoResultsDay[] = [ $attendee->getName(), $attendee->getEmail() ];
                    break;
                case AttendeeMailer::STATUS_EMAIL_NOT_SENT_MAIL_FAILURE:
                    $failMailError[] = [ $attendee->getName(), $attendee->getEmail() ];
                    break;
                default:
                    $unknown[] = [ $attendee->getName(), $attendee->getEmail() ];
                    break;
            }
        }

        echo PHP_EOL . "Printing email status results:" . PHP_EOL . PHP_EOL;
        $this->printAttendeeEmailStatusesToConsoleForGroup("Email successfully sent to:", $success);
        $this->printAttendeeEmailStatusesToConsoleForGroup("No city search results. Email not sent to:", $failNoResultsCity);
        $this->printAttendeeEmailStatusesToConsoleForGroup("No day search results. Email not sent to:", $failNoResultsDay);
        $this->printAttendeeEmailStatusesToConsoleForGroup("Email Error. Email not sent to:", $failMailError);
        $this->printAttendeeEmailStatusesToConsoleForGroup("Email status not known for:", $unknown);
    }

    /**
     * @param string $message
     * @param array $persons [[ string:name, string:email ]]
     */
    private function printAttendeeEmailStatusesToConsoleForGroup(string $message, array $persons) {

        if($persons) {
            echo $message . PHP_EOL;
            foreach ($persons as $person) {
                list($name, $email) = $person;
                echo "$name, email: $email" . PHP_EOL;
            }
            echo PHP_EOL;
        }
    }

    /**
     * Render template
     * @param Attendee $attendee
     * @return string
     */
    public function renderTemplate(Attendee $attendee, MeetingCollection $meetingCollection) {

        return $this->twig->render(self::TEMPLATE_NAME, [
            'attendee' => $attendee,
            'meeting_collection' => $meetingCollection,
        ]);
    }

    /**
     * @param string $templatePath
     * @param array options
     * @return AttendeeMailer
     */
    public static function build(string $templatePath, $options) {

        $loader = new Twig_Loader_Filesystem($templatePath);
        $twig = new Twig_Environment($loader, $options);
        $twig->addFunction((new GetDistance)->getFunction());
        $twig->addFilter((new UcWords())->getFilter());
        return new self($twig);
    }
}