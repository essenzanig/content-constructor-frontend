<?php

namespace MageSuite\ContentConstructorFrontend\Block\Component;

class ProductFinder extends \Magento\Framework\View\Element\Template
{
    protected $_template = 'MageSuite_ContentConstructorFrontend::component/product_finder.phtml';

    /**
     * @var \MageSuite\ContentConstructorFrontend\Service\ProductFinder\Redirect\EndpointUrlProvider
     */
    protected $endpointUrlProvider;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \MageSuite\ContentConstructorFrontend\Service\ProductFinder\Redirect\EndpointUrlProvider $endpointUrlProvider,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->endpointUrlProvider = $endpointUrlProvider;
    }

    public function getRedirectEndpointUrl() {
        return $this->endpointUrlProvider->getUrl();
    }
}