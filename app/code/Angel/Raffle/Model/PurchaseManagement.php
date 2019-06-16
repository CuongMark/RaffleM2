<?php


namespace Angel\Raffle\Model;

use Angel\Raffle\Model\Product\Attribute\Source\RaffleStatus;
use Angel\Raffle\Model\Ticket\Status;
use Angel\Raffle\Api\TicketRepositoryInterface;
use Angel\Raffle\Model\Data\TicketFactory as TicketDataFactory;
use Angel\Raffle\Model\EmailManagementFactory;
use Angel\Raffle\Service\Files;
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
    /**
     * @var EmailManagementFactory
     */
    private $emailManagementFactory;
    private $files;

    public function __construct(
        TicketDataFactory $ticketDataFactory,
        TicketRepositoryInterface $ticketRepository,
        ProductRepository $productRepository,
        Raffle $raffle,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        TicketFactory $ticketFactory,
        EmailManagementFactory $emailManagementFactory,
        Files $files
    ){
        $this->ticketDataFactory = $ticketDataFactory;
        $this->ticketRepository = $ticketRepository;
        $this->productRepository = $productRepository;
        $this->raffle = $raffle;
        $this->_eventManager = $eventManager;
        $this->messageManager = $messageManager;
        $this->ticketFactory = $ticketFactory;
        $this->emailManagementFactory = $emailManagementFactory;
        $this->files = $files;
    }

    /**
     * {@inheritdoc}
     */
    public function postPurchase($product_id, $qty, $customerId)
    {
        $ticketObject = $this->ticketFactory->create();
        $_db = $ticketObject->getResource();
        try {

            $product = $this->productRepository->getById($product_id);
            $totalTicket = $product->getData('total_tickets');
            $lastTicketNumber = $this->raffle->getLastTicketNumber($product);
            $qty = min($totalTicket - $lastTicketNumber, $qty);
            if ($qty<=0){
                throw new \Exception('Qty is not available');
            }
            /** @var \Angel\Raffle\Model\Data\Ticket $ticket */
            $ticket = $ticketObject->getDataModel();

            $ticket->setStart($lastTicketNumber + 1)
                ->setEnd($lastTicketNumber + $qty)
                ->setPrice($product->getPrice() * $qty)
                ->setCustomerId($customerId)
                ->setProductId($product_id)
                ->setStatus(Status::STATUS_PENDING);

            $_db->beginTransaction();
//            $ticket = $this->ticketRepository->save($ticket);
            /** create credit transaction */
            $this->_eventManager->dispatch('angel_raffle_create_new_ticket', ['ticket' => $ticket, 'product' => $product]);

            /** check ticket and generate winning numbers */
            if (!in_array($ticket->getStatus(),[Status::STATUS_PENDING, Status::STATUS_CANCELED, Status::STATUS_WINNING, Status::STATUS_LOSE])){
                $this->raffle->generateWinningNumber($product, $ticket);
                if ($ticket->getStatus() == Status::STATUS_WINNING) {
                    /** create pay out credit transaction */
                    $this->_eventManager->dispatch('angel_raffle_winning_ticket_ticket', ['ticket' => $ticket, 'product' => $product]);
                }
            } else {
                throw new \Exception('unable to draw winning number');
            }
            $ticket = $this->ticketRepository->save($ticket);
            /** update Raffle status */
            if ($ticket->getEnd() >= $totalTicket){
                $this->productRepository->save($product->setRaffleStatus(RaffleStatus::FINISHED));
            }
            $_db->commit();
            $this->messageManager->addSuccessMessage(__('You purchased %1 %2 tickets successfully.', $qty, $product->getName()));

            $this->emailManagementFactory->create()->sendNewTicketEmail($product, $ticket);

            $this->files->createFile(['last_ticket' => $ticket->getEnd(), 'status' => $product->getRaffleStatus()], $product->getId());
            return $ticket;
        } catch (\Exception $e){
            $_db->rollBack();
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $ticketObject->getDataModel();
    }
}
