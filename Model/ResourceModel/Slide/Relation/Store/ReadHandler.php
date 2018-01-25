<?php
/**
 * @package ZShapeTech_Slider
 * @author ZShapeTech <zshapetech@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version $Id$
 */
namespace ZShapeTech\Slider\Model\ResourceModel\Slide\Relation\Store;

use ZShapeTech\Slider\Model\ResourceModel\Slide;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;

/**
 * Class ReadHandler
 */
class ReadHandler implements ExtensionInterface
{
    /**
     * @var MetadataPool
     */
    protected $metadataPool;

    /**
     * @var Slide
     */
    protected $resourceSlide;

    /**
     * @param MetadataPool $metadataPool
     * @param Slide $resourceSlide
     */
    public function __construct(
        MetadataPool $metadataPool,
        Slide $resourceSlide
    ) {
        $this->metadataPool = $metadataPool;
        $this->resourceSlide = $resourceSlide;
    }

    /**
     * @param object $entity
     * @param array $arguments
     * @return object
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($entity, $arguments = [])
    {
        if ($entity->getId()) {
            $stores = $this->resourceSlide->lookupStoreIds((int)$entity->getId());
            $entity->setData('store_id', $stores);
        }
        return $entity;
    }
}
