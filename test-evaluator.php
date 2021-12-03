<?php

use Alura\Model\Auction;
use Alura\Model\Bid;
use Alura\Model\User;
use Alura\Service\Evaluator;

require __DIR__ . '/vendor/autoload.php';

// arrange|given
$auction = new Auction('fiat 147 0km');
$christian = new User('christian');
$paloma = new User('paloma');

$auction->addBid(new Bid($christian, 2000));
$auction->addBid(new Bid($paloma, 2500));

$evaluator = new Evaluator();

// act|when
$evaluator->evaluate($auction);

$maxValue = $evaluator->getMaxValue();

// assert|then
$expectedValue = 2500;
if ($maxValue === $expectedValue) {
    echo 'teste ok';
} else {
    echo 'teste falhou';
}
