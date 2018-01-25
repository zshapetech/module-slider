<?php
/**
 * @package ZShapeTech_Slider
 * @author ZShapeTech <zshapetech@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version $Id$
 */
namespace ZShapeTech\Slider\Model\Slide;

use ZShapeTech\Slider\Model\ResourceModel\Slide\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \ZShapeTech\Slider\Model\ResourceModel\Slide\Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $slideCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $slideCollectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $slideCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
    }

    /**
     * Prepares Meta
     *
     * @param array $meta
     * @return array
     */
    public function prepareMeta(array $meta)
    {
        return $meta;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var $page \ZShapeTech\Slider\Model\Slide */
        foreach ($items as $slide) {
			$slideItemData = $slide->getData();
			$slideSrc = $slideItemData['slide_src'];
			if ($slideSrc) {
				$slideItemData['slide_src'] = array(
					array(
						'name' => $slide->getTitle(),
						'url' => $slideSrc,
						'type' => $slide->getSlideType(),
						'size' => $slide->getSlideSize()
					)
				);
			}
            $this->loadedData[$slide->getId()] = $slideItemData;
        }

        $data = $this->dataPersistor->get('zshape_slide');
        if (!empty($data)) {
            $slide = $this->collection->getNewEmptyItem();
            $slide->setData($data);
            $this->loadedData[$slide->getId()] = $slide->getData();
            $this->dataPersistor->clear('zshape_slide');
        }

        return $this->loadedData;
    }
}
