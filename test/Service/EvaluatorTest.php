<?php

namespace Alura\Test\Service;

use Alura\Model\{Bid, User, Auction};
use Alura\Service\Evaluator;
use PHPUnit\Framework\TestCase;

class EvaluatorTest extends TestCase
{
    const expectedMinValue = 1345;
    const expectedMaxValue = 7856;
    const expected2NdValue = 7802;
    const expected3rdValue = 7011;

    private Evaluator $evaluator;

    protected function setUp(): void
    {
        $this->evaluator = new Evaluator();
    }

    /**
     * @dataProvider auctionInAscendingOrder
     * @dataProvider auctionInDescendingOrder
     * @dataProvider auctionInRandomOrder
     */
    public function testEvaluatorMustFindTheMaxValueOfBids(Auction $auction)
    {
        // act|when
        $this->evaluator->evaluate($auction);

        $maxValue = $this->evaluator->getMaxValue();

        // assert|then
        static::assertEquals(self::expectedMaxValue, $maxValue);
    }

    /**
     * @dataProvider auctionInAscendingOrder
     * @dataProvider auctionInDescendingOrder
     * @dataProvider auctionInRandomOrder
     */
    public function testEvaluatorMustFindTheMinValueOfBids(Auction $auction)
    {
        // act|when
        $this->evaluator->evaluate($auction);

        $minValue = $this->evaluator->getMinValue();

        // assert|then
        static::assertEquals(self::expectedMinValue, $minValue);
    }

    /**
     * @dataProvider auctionInAscendingOrder
     * @dataProvider auctionInDescendingOrder
     * @dataProvider auctionInRandomOrder
     */
    public function testEvaluatorShouldFindTheThreeMajorBidsValues(Auction $auction)
    {
        $this->evaluator->evaluate($auction);

        $maxBids = $this->evaluator->getMaxBids();

        static::assertCount(3, $maxBids);
        static::assertEquals(self::expectedMaxValue, $maxBids[0]->getValue());
        static::assertEquals(self::expected2NdValue, $maxBids[1]->getValue());
        static::assertEquals(self::expected3rdValue, $maxBids[2]->getValue());
    }

    public function testEmptyAuctionCannotBeEvaluated()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('It is not possible to evaluate an empty auction.');

        $auction = new Auction('moto da dança da motinha');
        $this->evaluator->evaluate($auction);
    }

    public function testFinishedAuctionCannotBeEvaluated()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Auction already finished.');

        $auction = new Auction('fusca amarelo');
        $auction->addBid(
            new Bid(
                new User('test'),
                5000
            )
        );

        $auction->finish();

        $this->evaluator->evaluate($auction);
    }

    public function auctionInAscendingOrder()
    {
        $auction = new Auction('fiat 147 0km');

        $christian = new User('christian');
        $paloma = new User('paloma');
        $lorena = new User('lorena');
        $caio = new User('caio');
        $leonardo = new User('leonardo');
        $selma = new User('selma');
        $maria = new User('maria');

        $auction->addBid(new Bid($christian, 1345));
        $auction->addBid(new Bid($paloma, 2427));
        $auction->addBid(new Bid($maria, 2970));
        $auction->addBid(new Bid($lorena, 3350));
        $auction->addBid(new Bid($christian, 3683));
        $auction->addBid(new Bid($paloma, 3752));
        $auction->addBid(new Bid($caio, 4009));
        $auction->addBid(new Bid($selma, 4317));
        $auction->addBid(new Bid($lorena, 4095));
        $auction->addBid(new Bid($selma, 5657));
        $auction->addBid(new Bid($caio, 5667));
        $auction->addBid(new Bid($maria, 6764));
        $auction->addBid(new Bid($leonardo, 7011));
        $auction->addBid(new Bid($lorena, 7802));
        $auction->addBid(new Bid($leonardo, 7856));

        return [
            'ascending order' => [$auction]
        ];
    }

    public function auctionInDescendingOrder()
    {
        $auction = new Auction('fiat 147 0km');

        $christian = new User('christian');
        $paloma = new User('paloma');
        $lorena = new User('lorena');
        $caio = new User('caio');
        $leonardo = new User('leonardo');
        $selma = new User('selma');
        $maria = new User('maria');

        $auction->addBid(new Bid($leonardo, 7856));
        $auction->addBid(new Bid($lorena, 7802));
        $auction->addBid(new Bid($leonardo, 7011));
        $auction->addBid(new Bid($maria, 6764));
        $auction->addBid(new Bid($caio, 5667));
        $auction->addBid(new Bid($selma, 5657));
        $auction->addBid(new Bid($lorena, 4317));
        $auction->addBid(new Bid($selma, 4095));
        $auction->addBid(new Bid($caio, 4009));
        $auction->addBid(new Bid($paloma, 3752));
        $auction->addBid(new Bid($christian, 3683));
        $auction->addBid(new Bid($lorena, 3350));
        $auction->addBid(new Bid($maria, 2970));
        $auction->addBid(new Bid($paloma, 2427));
        $auction->addBid(new Bid($christian, 1345));

        return [
            'descending order' => [$auction]
        ];
    }

    public function auctionInRandomOrder()
    {
        $auction = new Auction('fiat 147 0km');
        $christian = new User('christian');
        $paloma = new User('paloma');
        $lorena = new User('lorena');
        $caio = new User('caio');
        $leonardo = new User('leonardo');
        $selma = new User('selma');
        $maria = new User('maria');

        $auction->addBid(new Bid($paloma, 3752));
        $auction->addBid(new Bid($christian, 1345));
        $auction->addBid(new Bid($lorena, 7802));
        $auction->addBid(new Bid($maria, 2970));
        $auction->addBid(new Bid($leonardo, 7856));
        $auction->addBid(new Bid($caio, 5667));
        $auction->addBid(new Bid($selma, 4317));
        $auction->addBid(new Bid($leonardo, 7011));
        $auction->addBid(new Bid($caio, 4009));
        $auction->addBid(new Bid($christian, 3683));
        $auction->addBid(new Bid($selma, 4095));
        $auction->addBid(new Bid($paloma, 2427));
        $auction->addBid(new Bid($lorena, 5657));
        $auction->addBid(new Bid($maria, 6764));
        $auction->addBid(new Bid($lorena, 3350));

        return [
            'random order' => [$auction]
        ];
    }
}
