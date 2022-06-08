<?php

namespace Pixel\ReviewBundle\Domain\Event;

use Pixel\ReviewBundle\Entity\Review;
use Sulu\Bundle\ActivityBundle\Domain\Event\DomainEvent;

class ReviewRestoredEvent extends DomainEvent
{
    private Review $review;
    private array $payload;

    public function __construct(Review $review, array $payload)
    {
        parent::__construct();
        $this->review = $review;
        $this->payload = $payload;
    }

    public function getReview(): Review
    {
        return $this->review;
    }

    public function getEventPayload(): ?array
    {
        return $this->payload;
    }

    public function getEventType(): string
    {
        return 'restored';
    }

    public function getResourceKey(): string
    {
        return Review::RESOURCE_KEY;
    }

    public function getResourceId(): string
    {
        return (string)$this->review->getId();
    }

    public function getResourceTitle(): ?string
    {
        return $this->review->getName();
    }

    public function getResourceSecurityContext(): ?string
    {
        return Review::SECURITY_CONTEXT;
    }
}
