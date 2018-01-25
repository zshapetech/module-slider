<?php
/**
 * @package ZShapeTech_Slider
 * @author ZShapeTech <zshapetech@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version $Id$
 */
namespace ZShapeTech\Slider\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

/**
 * @api
 * @since 100.0.2
 */
class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column
{
    const NAME = 'thumbnail';

    const ALT_FIELD = 'name';

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            //\Zend_Debug::dump($this->imageHelper->getDefaultPlaceholderUrl());
            //\Zend_Debug::dump($fieldName);
            //\Zend_Debug::dump($dataSource['data']['items']);
            
            foreach ($dataSource['data']['items'] as & $item) {
				if (isset($item['slide_src'])) {
					$product = new \Magento\Framework\DataObject($item);
					//\Zend_Debug::dump(get_class($product)); die(__FILE__);
					//$imageHelper = $this->imageHelper->init($product, 'product_listing_thumbnail');
					$item[$fieldName . '_src'] = $item['slide_src'];
					$item[$fieldName . '_alt'] = $item['title'];
					$item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
						'zshape/slide/edit',
						['slide_id' => $product->getEntityId(), 'store' => $this->context->getRequestParam('store')]
					);
					//$origImageHelper = $this->imageHelper->init($product, 'product_listing_thumbnail_preview');
					$item[$fieldName . '_orig_src'] = $item['slide_src'];
				}
            }
        }

        return $dataSource;
    }
}
