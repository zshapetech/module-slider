<?php
/**
 * @package ZShapeTech_Slider
 * @author ZShapeTech <zshapetech@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version $Id$
 */
namespace ZShapeTech\Slider\Model;

use ZShapeTech\Slider\Api\Data;
use ZShapeTech\Slider\Api\SlideRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use ZShapeTech\Slider\Model\ResourceModel\Slide as ResourceSlide;
use ZShapeTech\Slider\Model\ResourceModel\Slide\CollectionFactory as SlideCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class SlideRepository
 */
class SlideRepository implements SlideRepositoryInterface
{
    /**
     * @var ResourceSlide
     */
    protected $resource;

    /**
     * @var SlideFactory
     */
    protected $slideFactory;

    /**
     * @var SlideCollectionFactory
     */
    protected $slideCollectionFactory;

    /**
     * @var Data\SlideSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var \ZShapeTech\Slider\Api\Data\SlideInterfaceFactory
     */
    protected $dataSlideFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @param ResourceSlide $resource
     * @param SlideFactory $slideFactory
     * @param Data\SlideInterfaceFactory $dataSlideFactory
     * @param SlideCollectionFactory $slideCollectionFactory
     * @param Data\SlideSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceSlide $resource,
        SlideFactory $slideFactory,
        Data\SlideInterfaceFactory $dataSlideFactory,
        SlideCollectionFactory $slideCollectionFactory,
        Data\SlideSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor = null
    ) {
        $this->resource = $resource;
        $this->slideFactory = $slideFactory;
        $this->slideCollectionFactory = $slideCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataSlideFactory = $dataSlideFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();
    }

    /**
     * Save Slide data
     *
     * @param \ZShapeTech\Slider\Api\Data\SlideInterface $slide
     * @return Slide
     * @throws CouldNotSaveException
     */
    public function save(\ZShapeTech\Slider\Api\Data\SlideInterface $slide)
    {
        if (empty($slide->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $slide->setStoreId($storeId);
        }
        try {
            $this->resource->save($slide);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the slide: %1', $exception->getMessage()),
                $exception
            );
        }
        return $slide;
    }

    /**
     * Load Slide data by given Slide Identity
     *
     * @param string $slideId
     * @return Slide
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($slideId)
    {
        $slide = $this->slideFactory->create();
        $slide->load($slideId);
        if (!$slide->getId()) {
            throw new NoSuchEntityException(__('Slide with id "%1" does not exist.', $slideId));
        }
        return $slide;
    }

    /**
     * Load Slide data collection by given search criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \ZShapeTech\Slider\Api\Data\SlideSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        /** @var \ZShapeTech\Slider\Model\ResourceModel\Slide\Collection $collection */
        $collection = $this->slideCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var Data\SlideSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Delete Slide
     *
     * @param \ZShapeTech\Slider\Api\Data\SlideInterface $slide
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(\ZShapeTech\Slider\Api\Data\SlideInterface $slide)
    {
        try {
            $this->resource->delete($slide);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the slide: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * Delete Slide by given Slide Identity
     *
     * @param string $slideId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($slideId)
    {
        return $this->delete($this->getById($slideId));
    }

    /**
     * Retrieve collection processor
     *
     * @return CollectionProcessorInterface
     */
    private function getCollectionProcessor()
    {
        if (!$this->collectionProcessor) {
            $this->collectionProcessor = \Magento\Framework\App\ObjectManager::getInstance()->get(
                'ZShapeTech\Slider\Model\Api\SearchCriteria\SlideCollectionProcessor'
            );
        }
        return $this->collectionProcessor;
    }
}
