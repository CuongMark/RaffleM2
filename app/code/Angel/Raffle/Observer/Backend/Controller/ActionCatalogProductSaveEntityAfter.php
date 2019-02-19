<?php


namespace Angel\Raffle\Observer\Backend\Controller;

use Angel\Raffle\Model\Data\PrizeFactory;
use Angel\Raffle\Api\PrizeRepositoryInterface;
use Angel\Raffle\Model\Product\Attribute\Source\RaffleStatus;
use Angel\Raffle\Model\Product\Type\Raffle;
use Angel\Raffle\Model\ResourceModel\Prize\Collection;
use Angel\Raffle\Model\ResourceModel\Prize\CollectionFactory;

class ActionCatalogProductSaveEntityAfter implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var PrizeFactory
     */
    private $prizeFactory;
    /**
     * @var PrizeRepositoryInterface
     */
    private $prizeRepository;
    /**
     * @var CollectionFactory
     */
    private $prizeCollectionFactory;

    public function __construct(
        PrizeFactory $prizeFactory,
        PrizeRepositoryInterface $prizeRepository,
        CollectionFactory $prizeCollectionFactory
    ){
        $this->prizeFactory = $prizeFactory;
        $this->prizeRepository = $prizeRepository;
        $this->prizeCollectionFactory = $prizeCollectionFactory;
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $observer->getEvent()->getProduct();
        if (!in_array($product->getTypeId(), [Raffle::TYPE_ID])){
            return ;
        }

        $prizes = $product->getData('prizes');
        $existPrize = [];
        if (is_array($prizes)) {
            foreach ($prizes as $_prize) {
                if (isset($_prize['prize_id']) && is_numeric($_prize['prize_id'])) {
                    $prize = $this->prizeRepository->getById($_prize['prize_id']);
                } else {
                    /** @var \Angel\Raffle\Model\Data\Prize $prize */
                    $prize = $this->prizeFactory->create();
                }
                $prize->setProductId($product->getId())
                    ->setName($_prize['name'])
                    ->setPrize($_prize['prize'])
                    ->setTotal($_prize['total']);
                $prize = $this->prizeRepository->save($prize);
                $existPrize[] = $prize->getPrizeId();
            }
        }
        /** @var Collection $prizesCollection */

        $prizesCollection = $this->prizeCollectionFactory->create();
        $prizesCollection->addFieldToFilter('product_id',$product->getId())
            ->addFieldToFilter('prize_id', ['nin' => $existPrize]);
        foreach ($prizesCollection as $prize) {
            $this->prizeRepository->delete($prize->getDataModel());
        }

        $prizesCollection = $this->prizeCollectionFactory->create();
        $prizesCollection->addFieldToFilter('product_id',$product->getId());
        if ($product->getRaffleStatus() != RaffleStatus::PENDING && !$prizesCollection->getSize()){
            throw new \Exception(__('Please set prizes before start Raffle'));
        }
    }
}