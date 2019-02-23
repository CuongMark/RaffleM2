<?php


namespace Angel\Raffle\Block\Raffle;

use Magento\Catalog\Api\ProductRepositoryInterface;

class View extends \Magento\Catalog\Block\Product\View
{
    private $raffle;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Angel\Raffle\Model\Raffle $raffle,
        array $data = []
    ){
        parent::__construct($context, $urlEncoder, $jsonEncoder, $string, $productHelper, $productTypeConfig, $localeFormat, $customerSession, $productRepository, $priceCurrency, $data);
        $this->raffle = $raffle;
    }

    /**
     * @return bool
     */
    public function isLoggedIn(){
        return $this->customerSession->isLoggedIn();
    }

    public function getPrizes(){
        $prizes = $this->raffle->getPrizes($this->getProduct());
        return $this->raffle->joinTotalWinningNumbersToPrizeCollection($prizes);
    }

    public function formatPrice($price){
        return $this->priceCurrency->format($price);
    }
}
