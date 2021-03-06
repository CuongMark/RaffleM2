<?php


namespace Angel\Raffle\Model;

use Angel\Core\Model\RandomNumberGenerate;
use Angel\Raffle\Model\Ticket\Status;
use Angel\Raffle\Model\ResourceModel\Ticket\Collection as TicketCollection;
use Angel\Raffle\Model\ResourceModel\Prize\Collection as PrizeCollection;
use Angel\Raffle\Model\ResourceModel\Number\Collection as NumberCollection;
use Angel\Raffle\Model\ResourceModel\Ticket\CollectionFactory as TicketCollectionFactory;
use Angel\Raffle\Model\ResourceModel\Prize\CollectionFactory as PrizeCollectionFactory;
use Angel\Raffle\Model\ResourceModel\Number\CollectionFactory as NumberCollectionFactory;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class Raffle
{
    /**
     * @var TicketCollectionFactory
     */
    private $ticketCollectionFactory;
    /**
     * @var TicketCollectionFactory
     */
    private $numberCollectionFactory;

    /**
     * @var Data\NumberFactory
     */
    private $numberFactory;

    /**
     * @var PrizeCollectionFactory
     */
    protected $prizeCollectionFactory;
    /**
     * @var NumberRepository
     */
    private $numberRepository;
    private $productCollectionFactory;

    public function __construct(
        TicketCollectionFactory $ticketCollectionFactory,
        NumberCollectionFactory $numberCollectionFactory,
        PrizeCollectionFactory $prizeCollectionFactory,
        \Angel\Raffle\Model\Data\NumberFactory $numberFactory,
        NumberRepository $numberRepository,
        CollectionFactory $productCollectionFactory

    ){
        $this->ticketCollectionFactory = $ticketCollectionFactory;
        $this->numberCollectionFactory = $numberCollectionFactory;
        $this->prizeCollectionFactory = $prizeCollectionFactory;
        $this->numberFactory = $numberFactory;
        $this->numberRepository = $numberRepository;
        $this->productCollectionFactory = $productCollectionFactory;
    }

    /**
     * @param Product $product
     * @return TicketCollection
     */
    public function getTickets($product){
        /** @var TicketCollection $collection */
        $collection = $this->ticketCollectionFactory->create();
        $collection->addFieldToFilter('product_id', $product->getId());
        return $collection;
    }

    /**
     * @param Product $product
     * @return int
     */
    public function getLastTicketNumber($product){
        $ticketNumber = $this->getTickets($product)->getLastItem()->getEnd();
        return $ticketNumber?$ticketNumber:0;
    }

    /**
     * @param $product
     * @return PrizeCollection
     */
    public function getPrizes($product){
        return $prizesCollection = $this->prizeCollectionFactory->create()
            ->addFieldToFilter('product_id', $product->getId());
    }

    public function getTotalPrizes($product){
        $prizes = $this->getPrizes($product);
        $totalPrizes = 0;
        /** @var \Angel\Raffle\Model\Data\Prize $prize */
        foreach ($prizes as $prize){
            $totalPrizes += (int)$prize->getTotal();
        }
        return $totalPrizes;
    }

    /**
     * @param Product $product
     * @param \Angel\Raffle\Model\Data\Ticket $ticket
     * @return \Angel\Raffle\Model\Data\Ticket
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Exception
     */
    public function generateWinningNumber($product, $ticket){
        /** @var PrizeCollection $prizeCollection */
        $prizeCollection = $this->prizeCollectionFactory->create();
        $prizeCollection->addFieldToFilter('product_id', $product->getid());
        $this->joinTotalWinningNumbersToPrizeCollection($prizeCollection);
        $prizes = $prizeCollection->getItems();
        shuffle($prizes);
        $totalTickets = (int)$product->getTotalTickets();

        $existed = [];
        $totalTicketNumber = $ticket->getEnd() - $ticket->getStart() + 1;
        $count = 0;
        $winningNumbers = [];
        $winningPrize = 0;
        /** @var \Angel\Raffle\Model\Data\Prize $prize */
        foreach ($prizes as $prize){
            $prizeLeft = (int)$prize->getTotalPrizeLeft();
            $prizeCount = $prize->getTotal() - $prizeLeft;
            for ($i=0; $i < $prizeLeft; $i++){

                $number = RandomNumberGenerate::getRandomNumber($ticket->getStart(), $totalTickets, $existed);

                if ($number >= $ticket->getStart() && $number <= $ticket->getEnd()){
                    $prizeCount ++;
                    /** @var \Angel\Raffle\Model\Data\Number $winningNumberObject */
                    $winningNumberObject = $this->numberFactory->create();
                    $winningNumberObject->setNumber($number)
                        ->setPrizeId($prize->getPrizeId())
                        ->setCount($prizeCount);
                    $this->numberRepository->save($winningNumberObject);
                    $winningNumbers[] = $number;
                    $winningPrize += $prize->getPrize();

                    /** break if win all of number */
                    $count++;
                    if ($totalTicketNumber <= $count){
                        break;
                    }
                }
            }

            /** break if win all of number */
            if ($totalTicketNumber <= $count){
                break;
            }
        }
        if (count($winningNumbers)){
            $ticket->setWinningNumbers(implode(', ', $winningNumbers))
                ->setPrize($winningPrize)
                ->setStatus(Status::STATUS_WINNING);
        } else {
            $ticket->setStatus(Status::STATUS_LOSE);
        }

        return $ticket;
    }

    /**
     * @param ResourceModel\Ticket\Collection $collection
     */
    public function joinTotalWinningNumbersToTicketsCollection($collection){

        $numberCollection = $this->numberCollectionFactory->create();
        $numberCollection->getSelect()->joinLeft(
            ['prize' => $collection->getTable('angel_raffle_prize')],
            'main_table.prize_id = prize.prize_id',
            ['product_id' => 'prize.product_id']
        );
        $collection->getSelect()->joinLeft(
            ['number' => new \Zend_Db_Expr('('.$numberCollection->getSelect()->__toString().')')],
            'main_table.start <= number.number AND main_table.end >= number.number AND number.product_id = main_table.product_id',
            [
                'winning_numbers' => 'GROUP_CONCAT(number.number, \' \')'
            ]
        )->group('main_table.ticket_id');
    }
    /**
     * @param ResourceModel\Ticket\Collection $collection
     */
    public function joinCustomerEmailToTicketsCollection($collection){

        $collection->getSelect()->joinLeft(
            ['customer' => $collection->getTable('customer_entity')],
            'main_table.customer_id = customer.entity_id',
            [
                'customer_email' => 'customer.email'
            ]
        );
    }

    /**
     * @param $collection
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function joinProductNameToTicketsCollection($collection){
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->joinAttribute('name', 'catalog_product/name', 'entity_id', null, 'inner');
        $collection->getSelect()->joinLeft(
            ['product' => new \Zend_Db_Expr('('.$productCollection->getSelect()->__toString().')')],
            'product.entity_id = main_table.product_id',
            [
                'product_name' => 'product.name'
            ]
        );
        return $collection;
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function joinTotalPrizeToProductCollection($collection){
        $prizeCollection = $this->prizeCollectionFactory->create();
        $prizeCollection->getSelect()->columns([
            'total_prizes' => 'SUM(main_table.total)',
            'total_prizes_price' => 'SUM(main_table.total * main_table.prize)'
        ])->group('main_table.product_id');

        $secondPrizeCollection = $this->prizeCollectionFactory->create();
        $secondPrizeCollection->getSelect()->joinLeft(
            ['number' => $secondPrizeCollection->getTable('angel_raffle_number')],
            'main_table.prize_id = number.prize_id',
            ['numbers_generated' => 'COUNT(number.number)']
        )->group('product_id');

        $prizeCollection->getSelect()->joinLeft(
            ['second_prize' => new \Zend_Db_Expr('('.$secondPrizeCollection->getSelect()->__toString().')')],
            'main_table.prize_id = second_prize.prize_id',
            ['numbers_generated' => 'second_prize.numbers_generated']
        );

        $collection->getSelect()->joinLeft(
            ['prize' => new \Zend_Db_Expr('('.$prizeCollection->getSelect()->__toString().')')],
            'prize.product_id = e.entity_id',
            ['total_prizes' => 'prize.total_prizes', 'total_prizes_price' => 'prize.total_prizes_price', 'numbers_generated' => 'prize.numbers_generated']
        );
        return $collection;
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function joinTotalPrizeWonToProductCollection($collection){
        $ticketCollection = $this->ticketCollectionFactory->create();
        $ticketCollection->getSelect()->columns(['total_price' => 'SUM(price)', 'total_prize_won' => 'SUM(prize)', 'total_tickets_sold'=> 'MAX(end)'])->group('product_id');
        $collection->getSelect()->joinLeft(
            ['ticket' => new \Zend_Db_Expr('('.$ticketCollection->getSelect()->__toString().')')],
            'ticket.product_id = e.entity_id',
            ['total_price' => 'ticket.total_price', 'total_prize_won' => 'ticket.total_prize_won', 'total_tickets_sold' => 'ticket.total_tickets_sold']
        );
        return $collection;
    }

    /**
     * @param int $start
     * @param int $end
     * @param array $existed
     * @return int
     * @throws \Exception
     */
    public function getRandomNumber($start, $end, &$existed){
        $number = random_int($start, $end);
        /** To make sure the winning numbers are not duplicated */
        while (in_array($number, $existed)){
            $number = random_int($start, $end);
        }
        $existed[] = $number;
        return $number;
    }

    /**
     * @param PrizeCollection $collection
     * @param \Angel\Raffle\Model\Data\Ticket $ticket
     * @return mixed
     */
    public function joinTotalWinningNumbersToPrizeCollection($collection, $ticket = null){
        /** @var NumberCollection $collection */
        $numberCollection = $this->numberCollectionFactory->create();
        $numberCollection->getSelect()->columns([
            'winning_numbers' => 'GROUP_CONCAT(number,\' \')',
            'total_winning_numbers' => 'COUNT(number)'
        ])->group('prize_id');

        if ($ticket){
            $numberCollection->addFieldToFilter('number', ['gteq' => $ticket->getStart()])
            ->addFieldToFilter('number', ['lteq' => $ticket->getEnd()]);
        }

        $collection->getSelect()->joinLeft(
            ['number' => new \Zend_Db_Expr('('.$numberCollection->getSelect()->__toString().')')],
            'main_table.prize_id = number.prize_id',
            [
                'winning_numbers' => 'number.winning_numbers',
                'total_winning_numbers' => 'IF(number.total_winning_numbers, number.total_winning_numbers, 0)',
                'total_winning_price' => 'IF(number.total_winning_numbers, (prize * number.total_winning_numbers), 0)',
                'total_prize_left' => '(main_table.total - IF(number.total_winning_numbers, number.total_winning_numbers, 0))'
            ]
        );
        return $collection;
    }
}
