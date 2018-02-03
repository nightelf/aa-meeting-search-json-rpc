<?php

namespace Meeting;

use Twig_Loader_Filesystem;
use Twig_Environment;
use Meeting\TwigFunction\GetDistance;

class AttendeeMailer {

    /**
     * @var string
     */
    const TEMPLATE_NAME = 'near_aa_meetings_email.html';

    /**
     * @var string
     */
    const EMAIL_SUBJECT = 'Some Alcoholics Anonymous meetings nearby';

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
     */
    public function emailAttendees(AttendeeCollection $attendeeCollection, MeetingCollection $meetingCollection) {

        $headers = 'From: no-reply@example.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion() . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-Type: text/html; charset=utf-9' . "\r\n";

        foreach ($attendeeCollection as $attendee) {

            $filteredMeetingCollection = $meetingCollection->filterByDay($attendee->getPreferredDay());
            $filteredMeetingCollection->sortByDistance($attendee->getAddress());
            $html = $this->renderTemplate($attendee, $filteredMeetingCollection);
            mail($attendee->getEmail(), self::EMAIL_SUBJECT, $html, $headers);
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
        return new self($twig);
    }
}