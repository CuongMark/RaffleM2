<?php


namespace Angel\Raffle\Block\Adminhtml\Ticket;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Angel\Raffle\Api\TicketRepositoryInterface;
use Angel\Raffle\Model\Data\Ticket;
use Angel\Raffle\Model\Raffle;
use Angel\Raffle\Model\ResourceModel\Ticket\Collection;
use Angel\Raffle\Model\ResourceModel\Ticket\CollectionFactory;
use Angel\Raffle\Model\Ticket\Status;
use Magento\Catalog\Model\ProductRepository;
use Magento\Customer\Model\Session;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class View extends \Magento\Backend\Block\Template
{
    /**
     * @var CollectionFactory
     */
    protected $ticketCollectionFactory;

    /**
     * @var Session
     */
    protected $_customerSession;

    /**
     * @var Collection
     */
    protected $ticketCollection;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    protected $ticketManagement;
    /**
     * @var Raffle
     */
    private $raffle;
    private $prizeCollectionFactory;
    private $ticketRepository;

    /**
     * @var Ticket
     */
    protected $ticket;
    private $productRepository;
    protected $product;

    /**
     * Index constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param CollectionFactory $ticketCollectionFactory
     * @param Session $customerSession
     * @param PriceCurrencyInterface $priceCurrency
     * @param Raffle $raffle
     * @param \Angel\Raffle\Model\ResourceModel\Prize\CollectionFactory $prizeCollectionFactory
     * @param TicketRepositoryInterface $ticketRepository
     * @param ProductRepository $productRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        CollectionFactory $ticketCollectionFactory,
        Session $customerSession,
        PriceCurrencyInterface $priceCurrency,
        Raffle $raffle,
        \Angel\Raffle\Model\ResourceModel\Prize\CollectionFactory $prizeCollectionFactory,
        TicketRepositoryInterface $ticketRepository,
        ProductRepository $productRepository,
        array $data = []
    ){
        parent::__construct($context, $data);
        $this->ticketCollectionFactory = $ticketCollectionFactory;
        $this->_customerSession = $customerSession;
        $this->priceCurrency = $priceCurrency;
        $this->raffle = $raffle;
        $this->prizeCollectionFactory = $prizeCollectionFactory;
        $this->ticketRepository = $ticketRepository;
        $this->productRepository = $productRepository;
    }


    /**
     * @return \Angel\Raffle\Api\Data\TicketInterface|Ticket
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getTicket()
    {
        if (!$this->ticket) {
            $this->ticket = $this->ticketRepository->getById($this->getRequest()->getParam('id'));
        }
        return $this->ticket;
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getPrizes()) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'sales.tickets.pager'
            )->setCollection(
                $this->getPrizes()
            );
            $this->setChild('pager', $pager);
            $this->getPrizes()->load();
        }
        return $this;
    }

    public function getProduct(){
        if (!$this->product) {
            $this->product = $this->productRepository->getById($this->getTicket()->getProductId());
        }
        return $this->product;
    }

    public function isOwnerTicket(){
        return $this->_customerSession->getCustomerId() == $this->getTicket()->getCustomerId();
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @return \Angel\Raffle\Model\ResourceModel\Prize\Collection|array
     */
    public function getPrizes(){
        try {
            $collection = $this->prizeCollectionFactory->create();
            $collection->addFieldToFilter('product_id', $this->getTicket()->getProductId());
            $this->raffle->joinTotalWinningNumbersToPrizeCollection($collection, $this->getTicket());
            $collection->getSelect()->where('number.winning_numbers IS NOT NULL');
            return $collection;
        } catch (\Exception $e){
            return [];
        }
    }

    /**
     * Retrieve formated price
     *
     * @param float $value
     * @return string
     */
    public function formatPrice($value, $isHtml = true)
    {
        return $this->priceCurrency->format(
            $value,
            $isHtml,
            PriceCurrencyInterface::DEFAULT_PRECISION,
            1 //Todo getStore
        );
    }

    public function getStatusLabel($ticket){
        $options = Status::getOptionArray();
        return $options[$ticket->getStatus()];
    }
}
