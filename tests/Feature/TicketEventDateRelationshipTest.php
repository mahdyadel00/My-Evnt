<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventDate;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketEventDateRelationshipTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that ticket can be created with event_date_id
     */
    public function test_ticket_can_be_created_with_event_date(): void
    {
        // Arrange
        $event = Event::factory()->create();
        $eventDate = EventDate::factory()->create(['event_id' => $event->id]);

        // Act
        $ticket = Ticket::create([
            'event_id' => $event->id,
            'event_date_id' => $eventDate->id,
            'ticket_type' => 'VIP',
            'price' => 150.00,
            'quantity' => 100,
        ]);

        // Assert
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'event_date_id' => $eventDate->id,
        ]);
    }

    /**
     * Test ticket belongsTo eventDate relationship
     */
    public function test_ticket_belongs_to_event_date(): void
    {
        // Arrange
        $event = Event::factory()->create();
        $eventDate = EventDate::factory()->create(['event_id' => $event->id]);
        $ticket = Ticket::factory()->create([
            'event_id' => $event->id,
            'event_date_id' => $eventDate->id,
        ]);

        // Act
        $relatedEventDate = $ticket->eventDate;

        // Assert
        $this->assertInstanceOf(EventDate::class, $relatedEventDate);
        $this->assertEquals($eventDate->id, $relatedEventDate->id);
    }

    /**
     * Test eventDate hasMany tickets relationship
     */
    public function test_event_date_has_many_tickets(): void
    {
        // Arrange
        $event = Event::factory()->create();
        $eventDate = EventDate::factory()->create(['event_id' => $event->id]);
        
        $ticket1 = Ticket::factory()->create([
            'event_id' => $event->id,
            'event_date_id' => $eventDate->id,
        ]);
        
        $ticket2 = Ticket::factory()->create([
            'event_id' => $event->id,
            'event_date_id' => $eventDate->id,
        ]);

        // Act
        $tickets = $eventDate->tickets;

        // Assert
        $this->assertCount(2, $tickets);
        $this->assertTrue($tickets->contains($ticket1));
        $this->assertTrue($tickets->contains($ticket2));
    }

    /**
     * Test ticket can be created without event_date_id (nullable)
     */
    public function test_ticket_can_be_created_without_event_date(): void
    {
        // Arrange
        $event = Event::factory()->create();

        // Act
        $ticket = Ticket::create([
            'event_id' => $event->id,
            'event_date_id' => null,
            'ticket_type' => 'General',
            'price' => 50.00,
            'quantity' => 200,
        ]);

        // Assert
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'event_date_id' => null,
        ]);
        
        $this->assertNull($ticket->eventDate);
    }

    /**
     * Test cascade delete: when event_date is deleted, tickets are deleted
     */
    public function test_deleting_event_date_cascades_to_tickets(): void
    {
        // Arrange
        $event = Event::factory()->create();
        $eventDate = EventDate::factory()->create(['event_id' => $event->id]);
        $ticket = Ticket::factory()->create([
            'event_id' => $event->id,
            'event_date_id' => $eventDate->id,
        ]);

        // Act
        $eventDate->delete();

        // Assert
        $this->assertDatabaseMissing('tickets', [
            'id' => $ticket->id,
        ]);
    }

    /**
     * Test eager loading eventDate with tickets
     */
    public function test_eager_loading_event_date_with_tickets(): void
    {
        // Arrange
        $event = Event::factory()->create();
        $eventDate = EventDate::factory()->create(['event_id' => $event->id]);
        Ticket::factory()->count(3)->create([
            'event_id' => $event->id,
            'event_date_id' => $eventDate->id,
        ]);

        // Act
        $tickets = Ticket::with('eventDate')->get();

        // Assert
        $this->assertCount(3, $tickets);
        $tickets->each(function ($ticket) {
            $this->assertTrue($ticket->relationLoaded('eventDate'));
        });
    }
}

