
<?php

class ArchiveQueue
{
    private array $queue = [];

    // Add an item to the back of the queue
    public function enqueue(array $item): void
    {
        $this->queue[] = $item;
    }

    // Remove and return the first item
    public function dequeue(): ?array
    {
        if ($this->isEmpty()) {
            return null;
        }

        return array_shift($this->queue);
    }

    // Check if the queue is empty
    public function isEmpty(): bool
    {
        return count($this->queue) === 0;
    }

    // Get the number of queued items
    public function size(): int
    {
        return count($this->queue);
    }
}
