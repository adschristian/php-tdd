<?php

declare(strict_types=1);

namespace Alura\Test\Model;

use Alura\Model\Auction;
use Alura\Model\Bid;
use Alura\Model\User;
use PHPUnit\Framework\TestCase;

class AuctionTest extends TestCase
{
    /**
     * @dataProvider generateBids
     */
    public function testAuctionMustReceiveBids(int $bidsQuantity, Auction $auction, array $values)
    {
        static::assertCount($bidsQuantity, $auction->getBids());

        foreach ($values as $key => $value) {
            static::assertEquals($value, $auction->getBids()[$key]->getValue());
        }
    }

    public function testAuctionShouldNotReceiveBidsInSequenceFromTheSameUser()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('User cannot take two bids in sequence.');

        $auction = new Auction('corsa');
        $anne = new User('anne');

        $auction->addBid(new Bid($anne, 1000));
        $auction->addBid(new Bid($anne, 1500));
    }

    public function testAuctionShouldNotReceiveMoreThanFiveBidsFromSameUser()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('User cannot take five or more bids.');

        $auction = new Auction('gol quadrado show');
        $johnDoe = new User('john doe');
        $janeDoe = new User('jane doe');

        $auction->addBid(new Bid($johnDoe, 5000));
        $auction->addBid(new Bid($janeDoe, 5100));
        $auction->addBid(new Bid($johnDoe, 5200));
        $auction->addBid(new Bid($janeDoe, 5300));
        $auction->addBid(new Bid($johnDoe, 5350));
        $auction->addBid(new Bid($janeDoe, 5500));
        $auction->addBid(new Bid($johnDoe, 6000));
        $auction->addBid(new Bid($janeDoe, 6100));
        $auction->addBid(new Bid($johnDoe, 6150));
        $auction->addBid(new Bid($janeDoe, 6175));
        $auction->addBid(new Bid($johnDoe, 6200));
    }

    public function generateBids()
    {
        $paloma = new User('paloma');
        $lorena = new User('lorena');

        $auctionWithTwoBids = new Auction('fiat 147 0Km');
        $auctionWithTwoBids->addBid(new Bid($paloma, 1000));
        $auctionWithTwoBids->addBid(new Bid($lorena, 2000));

        $auctionWithOneBid = new Auction('fiat 147 0Km');
        $auctionWithOneBid->addBid(new Bid($lorena, 1000));

        return [
            'auction with 2 bids' => [2, $auctionWithTwoBids, [1000, 2000]],
            'auction with 1 bids' => [1, $auctionWithOneBid, [1000]],
        ];
    }
}
