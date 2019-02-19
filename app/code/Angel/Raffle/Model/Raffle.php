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
     * @return mixed
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
     * @return mixed
     */
    public function joinTotalWinningNumbersToPrizeCollection($collection){
        /** @var NumberCollection $collection */
        $numberCollection = $this->numberCollectionFactory->create();
        $numberCollection->getSelect()->columns([
            'winning_numbers' => 'GROUP_CONCAT(number,\', \')',
            'total_winning_numbers' => 'COUNT(number)'
        ])->group('prize_id');
        $collection->getSelect()->joinLeft(
            ['number' => new \Zend_Db_Expr('('.$numberCollection->getSelect()->__toString().')')],
            'main_table.prize_id = number.prize_id',
            [
                'winning_numbers' => 'number.winning_numbers',
                'total_winning_numbers' => 'number.total_winning_numbers',
                'total_winning_price' => '(prize * number.total_winning_numbers)',
                'total_prize_left' => '(main_table.total - number.total_winning_numbers)'
            ]
        );
        return $collection;
    }
}
