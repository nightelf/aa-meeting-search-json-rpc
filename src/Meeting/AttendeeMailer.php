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

        // @todo clean headers up
        $headers = 'From: no-reply@example.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion() . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-Type: text/html; charset=utf-9' . "\r\n";

        foreach ($attendeeCollection as $attendee) {

            $filteredMeetingCollection = $meetingCollection->filterByDay($attendee->getPreferredDay());
            $filteredMeetingCollection->sortByDistance($attendee->getAddress());
            $html = $this->renderTemplate($attendee, $filteredMeetingCollection);

            // Why php mail? Because it just works without configuration.
            mail($attendee->getEmail(), sprintf(self::EMAIL_SUBJECT, ucwords($meetingCollection->getCity())), $html, $headers);
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