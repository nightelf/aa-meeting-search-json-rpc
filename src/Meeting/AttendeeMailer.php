<?php

namespace Meeting;

use Twig_Loader_Filesystem;
use Twig_Environment;

class AttendeeMailer {

    /**
     * @var string
     */
    const TEMPLATE_NAME = 'near_aa_meetings_email.html';

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * AttendeeMailer constructor.
     * @param $twig
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function emailAttendees(AttendeeCollection $attendeeCollection, MeetingCollection $meetingCollection) {

        foreach ($attendeeCollection as $attendee) {

            $filteredMeetingCollection = $meetingCollection->filterByDay($attendee->getPreferredDay());
            $filteredMeetingCollection->sortByDistance($attendee->getAddress());
            $html = $this->renderTemplate($attendee, $filteredMeetingCollection);
            $headers = 'From: no-reply@example.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion() . "\r\n" .
                'MIME-Version: 1.0' . "\r\n" .
                'Content-Type: text/html; charset=utf-9' . "\r\n";

            $result = mail($attendee->getEmail(), "Tester", $html, $headers);
            var_dump($html);
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
        return new self($twig);
    }
}