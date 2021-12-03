<?php

declare(strict_types=1);

namespace Alura\Service;

use Alura\Model\Auction;
use Alura\Model\Bid;
use DomainException;

class Evaluator
{
    private float $maxValue = -INF;
    private float $minValue = INF;

    /**
     * @var Bid[]
     */
    private array $maxBids;

    public function evaluate(Auction $auction): void
    {
        $bids = $auction->getBids();

        if ($auction->isFinished()) {
            throw new DomainException('Auction already finished.');
        }

        if (empty($bids)) {
            throw new DomainException("It is not possible to evaluate an empty auction.");
        }

        usort($bids, function (Bid $one, Bid $two) {
            return $two->getValue() - $one->getValue();
        });

        $this->maxValue = $bids[0]->getValue();
        $this->minValue = end($bids)->getValue();
        $this->maxBids = array_slice($bids, 0, 3);
    }

    public function getMaxValue(): float
    {
        return $this->maxValue;
    }

    public function getMinValue(): float
    {
        return $this->minValue;
    }

    /**
     * Get the value of maxBids
     *
     * @return Bid[]
     */
    public function getMaxBids(): array
    {
        return $this->maxBids;
    }
}
