<?php


namespace Angel\Raffle\Model;

use Angel\Raffle\Model\Ticket\Status;
use Angel\Raffle\Model\ResourceModel\Ticket\Collection as TicketCollection;
use Angel\Raffle\Model\ResourceModel\Prize\Collection as PrizeCollection;
use Angel\Raffle\Model\ResourceModel\Number\Collection as NumberCollection;
use Angel\Raffle\Model\ResourceModel\Ticket\CollectionFactory as TicketCollectionFactory;
use Angel\Raffle\Model\ResourceModel\Prize\CollectionFactory as PrizeCollectionFactory;
use Angel\Raffle\Model\ResourceModel\Number\CollectionFactory as NumberCollectionFactory;
use Magento\Catalog\Model\Product;

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

    public function __construct(
        TicketCollectionFactory $ticketCollectionFactory,
        NumberCollectionFactory $numberCollectionFactory,
        PrizeCollectionFactory $prizeCollectionFactory,
        \Angel\Raffle\Model\Data\NumberFactory $numberFactory,
        NumberRepository $numberRepository

    ){
        $this->ticketCollectionFactory = $ticketCollectionFactory;
        $this->numberCollectionFactory = $numberCollectionFactory;
        $this->prizeCollectionFactory = $prizeCollectionFactory;
        $this->numberFactory = $numberFactory;
        $this->numberRepository = $numberRepository;
    }

    /**
     * @param Product $product
     * @return Collection
     */
    public function getTickets($product){
        /** @var Collection $collection */
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
     * @param Product $product
     */
    public function getPrizes($product){
        return $prizesCollection = $this->prizeCollectionFactory->create()
            ->addFieldToFilter('product_id', $product->getId());
    }

    /**
     * @param Product $product
     * @param \Angel\Raffle\Model\Data\Ticket $ticket
     * @return \Angel\Raffle\Model\Data\Ticket
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function generateWinningNumber($product, $ticket){
        /** @var PrizeCollection $prizeCollection */
        $prizeCollection = $this->prizeCollectionFactory->create();
        $prizeCollection->addFieldToFilter('product_id', $product->getid());
        $this->joinTotalWinningNumbersToPrizeCollection($prizeCollection);
        $totalTickets = (int)$product->getTotalTickets();

        $existed = [];
        $winningNumbers = [];
        $winningPrize = 0;
        /** @var \Angel\Raffle\Model\Data\Prize $prize */
        foreach ($prizeCollection as $prize){
            for ($i=0; $i<$prize->getTotalPrizeLeft(); $i++){
                $number = $this->getRandomNumber($ticket->getStart(), $totalTickets, $existed);
                if ($number >= $ticket->getStart() && $number <= $ticket->getEnd()){
                    /** @var \Angel\Raffle\Model\Data\Number $winningNumberObject */
                    $winningNumberObject = $this->numberFactory->create();
                    $winningNumberObject->setNumber($number)
                        ->setPrizeId($prize->getPrizeId());
                    $this->numberRepository->save($winningNumberObject);
                    $winningNumbers[] = $number;
                    $winningPrize += $prize->getPrize();
                }
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

        $collection->getSelect()->joinLeft(
            ['number' => $collection->getTable('angel_raffle_number')],
            'main_table.start <= number.number AND main_table.end >= number.number',
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
     * @param ResourceModel\Ticket\Collection $collection
     * @return ResourceModel\Ticket\Collection
     */
    public function joinProductNameToTicketsCollection($collection){
        $productNameAttributeId = \Magento\Framework\App\ObjectManager::getInstance()->create('Magento\Eav\Model\Config')
            ->getAttribute(\Magento\Catalog\Model\Product::ENTITY, \Magento\Catalog\Api\Data\ProductInterface::NAME)
            ->getAttributeId();
        $collection->getSelect()->joinLeft(['product_varchar' => $collection->getTable('catalog_product_entity_varchar')],
            "product_varchar.entity_id = main_table.product_id AND product_varchar.attribute_id = $productNameAttributeId", ['product_name' => 'product_varchar.value']
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
            'total_prizes' => 'SUM(total)',
            'total_prizes_price' => 'SUM(total * prize)'
        ])->group('product_id');
        $collection->getSelect()->joinLeft(
            ['prize' => new \Zend_Db_Expr('('.$prizeCollection->getSelect()->__toString().')')],
            'prize.product_id = e.entity_id',
            ['total_prizes' => 'prize.total_prizes', 'total_prizes_price' => 'prize.total_prizes_price']
        );
        $collection->getSelect()->joinLeft(
            ['number' => $collection->getTable('angel_raffle_number')],
            'prize.prize_id = number.prize_id',
            ['numbers_generated' => 'COUNT(number.number)']
        )->group('e.entity_id');
        return $collection;
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function joinTotalPrizeWonToProductCollection($collection){
        $ticketCollection = $this->ticketCollectionFactory->create();
        $ticketCollection->getSelect()->columns(['total_price' => 'SUM(price)', 'total_prize_won' => 'SUM(prize)', 'total_ticket_sold'=> 'MAX(end)'])->group('product_id');
        $collection->getSelect()->joinLeft(
            ['ticket' => new \Zend_Db_Expr('('.$ticketCollection->getSelect()->__toString().')')],
            'ticket.product_id = e.entity_id',
            ['total_price' => 'ticket.total_price', 'total_prize_won' => 'ticket.total_prize_won', 'total_ticket_sold' => 'ticket.total_ticket_sold']
        );
        return $collection;
    }

    /**
     * @param int $start
     * @param int $end
     * @param array $existed
     * @return int
     */
    private function getRandomNumber($start, $end, &$existed){
        $number = mt_rand($start, $end);
        while (in_array($number, $existed)){
            $number = mt_rand($start, $end);
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
