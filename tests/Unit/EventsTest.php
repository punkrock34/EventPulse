<?php

namespace Tests\Unit;

use App\Constants\EventStatus;
use DateTime;
use PHPUnit\Framework\TestCase;

interface RepositoryInterface
{
    public function all(): array;
}

class FakeEvent
{
    public $id;

    public $title;

    public $description;

    public $status;

    public $created_at;

    public $updated_at;

    public $owner_id;

    public function __construct(
        $id = null,
        $title = null,
        $description = null,
        $status = null,
        $owner_id = null
    ) {
        $this->id = $id ?? rand(1, 1000);
        $this->title = $title ?? 'Fake Event '.$this->id;
        $this->description = $description ?? 'Description for fake event '.$this->id;
        $this->status = $status ?? EventStatus::UPCOMING->value;
        $this->created_at = new DateTime;
        $this->updated_at = new DateTime;
        $this->owner_id = $owner_id ?? 1;
    }

    protected function user()
    {
        return new class
        {
            public function id()
            {
                return 1;
            }
        };
    }
}

class FakeEventRepository implements RepositoryInterface
{
    protected $events;

    public function __construct($events)
    {
        $this->events = $events;
    }

    public function all(): array
    {
        return $this->events;
    }
}

class FakeEventService
{
    protected $eventRepository;

    public function __construct($eventRepository = null)
    {
        $this->eventRepository = $eventRepository;
    }

    public function getAllEvents(): array
    {
        return $this->eventRepository->all();
    }
}

function fakeEvents($eventNumber)
{
    $events = [];

    if ($eventNumber <= 0) {
        return $events;
    }

    for ($i = 0; $i < $eventNumber; $i++) {
        $events[] = new FakeEvent;
    }

    return $events;
}

function fakeEventService($eventsNumber = 0)
{
    $events = fakeEvents($eventsNumber);

    return new FakeEventService(new FakeEventRepository($events));
}

// TODO: update tests to only test for events linked to the user
class EventsTest extends TestCase
{
    public function test_get_all_returns_empty_when_there_are_no_events(): void
    {
        $eventService = fakeEventService();
        $events = $eventService->getAllEvents();

        $this->assertEmpty($events);
        $this->assertCount(0, $events);
    }

    public function test_get_all_returns_single_event_when_one_events_exists(): void
    {
        $eventService = fakeEventService(1);
        $events = $eventService->getAllEvents();

        $this->assertNotEmpty($events);
        $this->assertCount(1, $events);
    }

    public function test_get_all_returns_multiple_events_when_multiple_events_exists(): void
    {
        $eventService = fakeEventService(5);
        $events = $eventService->getAllEvents();

        $this->assertNotEmpty($events);
        $this->assertCount(5, $events);
    }
}
