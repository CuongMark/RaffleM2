<?php


namespace Angel\Raffle\Model;

use Angel\Raffle\Model\Product\Attribute\Source\RaffleStatus;
use Angel\Raffle\Model\Ticket\Status;
use Angel\Raffle\Api\TicketRepositoryInterface;
use Angel\Raffle\Model\Data\TicketFactory as TicketDataFactory;
use Magento\Catalog\Model\ProductRepository;

class PurchaseManagement implements \Angel\Raffle\Api\PurchaseManagementInterface
{
    /**
     * @var TicketDataFactory
     */
    private $ticketDataFactory;
    /**
     * @var TicketRepositoryInterface
     */
    private $ticketRepository;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var \Angel\Raffle\Model\Raffle
     */
    private $raffle;

    /**
     * Core event manager proxy
     *
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $_eventManager;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;
    /**
     * @var TicketFactory
     */
    private $ticketFactory;

    public function __construct(
        TicketDataFactory $ticketDataFactory,
        TicketRepositoryInterface $ticketRepository,
        ProductRepository $productRepository,
        Raffle $raffle,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        TicketFactory $ticketFactory
    ){
        $this->ticketDataFactory = $ticketDataFactory;
        $this->ticketRepository = $ticketRepository;
        $this->productRepository = $productRepository;
        $this->raffle = $raffle;
        $this->_eventManager = $eventManager;
        $this->messageManager = $messageManager;
        $this->ticketFactory = $ticketFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function postPurchase($product_id, $qty, $customerId)
    {
        $ticketObject = $this->ticketFactory->create();
        $_db = $ticketObject->getResource();
        try {
            $_db->beginTransaction();

            $product = $this->productRepository->getById($product_id);
            $totalTicket = $product->getData('total_tickets');
            $qty = min($totalTicket, $qty);
            if ($qty<=0){
                throw new \Exception('Qty is not available');
            }
            /** @var \Angel\Raffle\Model\Data\Ticket $ticket */
            $ticket = $ticketObject->getDataModel();
            $lastTicketNumber = $this->raffle->getLastTicketNumber($product);
            $ticket->setStart($lastTicketNumber + 1)
                ->setEnd($lastTicketNumber + $qty)
                ->setPrice($product->getPrice() * $qty)
                ->setCustomerId($customerId)
                ->setProductId($product_id)
                ->setStatus(Status::STATUS_PENDING);

            /** create credit transaction */
            $this->_eventManager->dispatch('angel_raffle_create_new_ticket', ['ticket' => $ticket, 'product' => $product]);

            /** check ticket and generate winning numbers */
            if (!in_array($ticket->getStatus(),[Status::STATUS_CANCELED, Status::STATUS_WINNING, Status::STATUS_LOSE])){
                $this->raffle->generateWinningNumber($product, $ticket);
                if ($ticket->getStatus() == Status::STATUS_WINNING) {
                    /** create pay out credit transaction */
                    $this->_eventManager->dispatch('angel_raffle_winning_ticket_ticket', ['ticket' => $ticket, 'product' => $product]);
                }
            }

            $ticket = $this->ticketRepository->save($ticket);
            /** update Raffle status */
            if ($ticket->getEnd() >= $totalTicket){
                $this->productRepository->save($product->setRaffleStatus(RaffleStatus::FINISHED));
            }
            $this->messageManager->addSuccessMessage(__('You purchased %1 %2 tickets successfully.', $qty, $product->getName()));

            $_db->commit();
            return $ticket;
        } catch (\Exception $e){
            $this->messageManager->addErrorMessage($e->getMessage());
            $_db->rollBack();
        }
        return $ticketObject->getDataModel();
    }
}
