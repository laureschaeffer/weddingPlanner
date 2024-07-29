<?php

namespace App\EventSubscriber;

use CalendarBundle\Entity\Event;
use CalendarBundle\Event\SetDataEvent;
use App\Repository\AppointmentRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly AppointmentRepository $appointmentRepository,
        private readonly UrlGeneratorInterface $router
    ) {}

    public static function getSubscribedEvents()
    {
        return [
            SetDataEvent::class => 'onCalendarSetData',
        ];
    }

    // public function onCalendarSetData(SetDataEvent $setDataEvent)
    // {
    //     $start = $setDataEvent->getStart();
    //     $end = $setDataEvent->getEnd();
    //     $filters = $setDataEvent->getFilters();

    //     // You may want to make a custom query from your database to fill the calendar

    //     $setDataEvent->addEvent(new Event(
    //         'Event 1',
    //         new \DateTime('Tuesday this week'),
    //         new \DateTime('Wednesdays this week')
    //     ));

    //     // If the end date is null or not defined, it creates a all day event
    //     $setDataEvent->addEvent(new Event(
    //         'All day event',
    //         new \DateTime('Friday this week')
    //     ));
    // }

    public function onCalendarSetData(SetDataEvent $setDataEvent)
    {
        $start = $setDataEvent->getStart();
        $end = $setDataEvent->getEnd();
        $filters = $setDataEvent->getFilters();

        // Modify the query to fit to your entity and needs
        // Change booking.beginAt by your start date property
        $appointment = $this->appointmentRepository
            ->createQueryBuilder('a')
            // ->where('a.beginAt BETWEEN :start and :end OR a.endAt BETWEEN :start and :end')
            // ->setParameter('start', $start->format('Y-m-d H:i:s'))
            // ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult()
        ;

        foreach ($appointment as $appointment) {
            // this create the events with your data (here booking data) to fill calendar
            $bookingEvent = new Event(
                $appointment->getDateStart(),
                $appointment->getDateEnd() // If the end date is null or not defined, a all day event is created.
            );

            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             */
            $bookingEvent->setOptions([
                'backgroundColor' => 'red',
                'borderColor' => 'red',
            ]);
            $bookingEvent->addOption(
                'url',
                $this->router->generate('app_booking_show', [
                    'id' => $appointment->getId(),
                ])
            );

            // finally, add the event to the CalendarEvent to fill the calendar
            $setDataEvent->addEvent($bookingEvent);
        }
    }
}