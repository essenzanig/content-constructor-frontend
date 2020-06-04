<?php

namespace MageSuite\ContentConstructorFrontend\DataProviders;

class DailyDealTeaserDataProvider implements \MageSuite\ContentConstructor\Components\DailyDealTeaser\DataProvider
{
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \MageSuite\ContentConstructor\Components\ProductCarousel\DataProvider
     */
    protected $dataProvider;

    /**
     * @var \MageSuite\BrandManagement\Api\BrandsRepositoryInterface
     */
    protected $brandsRepository;

    /**
     * @var \MageSuite\ContentConstructor\Service\MediaResolver
     */
    protected $mediaResolver;

    /**
     * @var \MageSuite\Frontend\Helper\Product
     */
    protected $productHelper;

    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \MageSuite\ContentConstructor\Components\ProductCarousel\DataProvider $dataProvider,
        \MageSuite\BrandManagement\Api\BrandsRepositoryInterface $brandsRepository,
        \MageSuite\ContentConstructor\Service\MediaResolver $mediaResolver,
        \MageSuite\Frontend\Helper\Product $productHelper
    ) {
        $this->productRepository = $productRepository;
        $this->dataProvider = $dataProvider;
        $this->brandsRepository = $brandsRepository;
        $this->mediaResolver = $mediaResolver;
        $this->productHelper = $productHelper;
    }

    public function getProduct($configuration)
    {
        $products = $this->dataProvider->getProducts($configuration);

        $product = array_shift($products);

        if (!$product) {
            return null;
        }

        $product['brandName'] = '';
        $productObject = $this->productRepository->get($product['sku']);
        $product['productObject'] = $productObject;

        if ($brandId = $productObject->getBrand()) {
            $brand = $this->brandsRepository->getById($brandId);

            $product['brandName'] = $brand->getBrandName();
        }

        $product['image'] = [
            'src' => $this->mediaResolver->resolve($product['image']),
            'srcSet' => $this->mediaResolver->resolveSrcSet($product['image'])
        ];

        if (isset($product['dailyDealOffer']['price']) && $product['dailyDealOffer']['price']) {
            $product['dailyDealOffer']['discountPercentage'] = $this->productHelper->getSalePercentage($productObject, $product['dailyDealOffer']['price']);
        }

        if (isset($product['dailyDealOffer']['dailyDiscount']) && $product['dailyDealOffer']['dailyDiscount']) {
            $product['dailyDealOffer']['discountPercentage'] = $product['dailyDealOffer']['dailyDiscount'];
        }

        return $product;
    }
}
