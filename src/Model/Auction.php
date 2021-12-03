<?php

namespace Alura\Model;

use DomainException;

class Auction
{
    /** @var Bid[] */
    private array $bids;
    private string $description;
    private ?User $lastUser;
    private float $finished;

    public function __construct(string $description)
    {
        $this->description = $description;
        $this->bids = [];
        $this->lastUser = null;
        $this->finished = false;
    }

    public function addBid(Bid $bid)
    {
        $user = $bid->getUser();
        if ($user === $this->lastUser) {
            throw new DomainException('User cannot take two bids in sequence.');
        }

        if ($this->getTotalBidsByUser($user) >= 5) {
            throw new DomainException('User cannot take five or more bids.');
        }

        $this->bids[] = $bid;
        $this->lastUser = $user;
    }

    public function finish(): void
    {
        $this->finished = true;
    }

    private function getTotalBidsByUser(User $user): int
    {
        $callback = function (int $carry, Bid $bid) use ($user) {
            if ($bid->getUser() === $user) {
                return $carry + 1;
            }

            return $carry;
        };
        $result = array_reduce($this->bids, $callback, 0);

        return $result;
    }

    /**
     * @return Bid[]
     */
    public function getBids(): array
    {
        return $this->bids;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get the value of finished
     */ 
    public function isFinished()
    {
        return $this->finished;
    }
}
