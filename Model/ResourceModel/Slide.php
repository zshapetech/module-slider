<?php
/**
 * @package ZShapeTech_Slider
 * @author ZShapeTech <zshapetech@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version $Id$
 */
namespace ZShapeTech\Slider\Model\ResourceModel;

use ZShapeTech\Slider\Model\Slide as ZShapeSlide;
use Magento\Framework\DB\Select;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\EntityManager\EntityManager;
use ZShapeTech\Slider\Api\Data\SlideInterface;

/**
 * Slide mysql resource
 */
class Slide extends AbstractDb
{
    /**
     * Store model
     *
     * @var null|Store
     */
    protected $_store = null;

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var MetadataPool
     */
    protected $metadataPool;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param EntityManager $entityManager
     * @param MetadataPool $metadataPool
     * @param string $connectionName
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        EntityManager $entityManager,
        MetadataPool $metadataPool,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->_storeManager = $storeManager;
        $this->entityManager = $entityManager;
        $this->metadataPool = $metadataPool;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('zshape_slide', 'slide_id');
    }

    /**
     * @inheritDoc
     */
    public function getConnection()
    {
        return $this->metadataPool->getMetadata(SlideInterface::class)->getEntityConnection();
    }

    /**
     * @param AbstractModel $object
     * @param string $value
     * @param string|null $field
     * @return bool|int|string
     * @throws LocalizedException
     * @throws \Exception
     */
    private function getSlideId(AbstractModel $object, $value, $field = null)
    {
        $entityMetadata = $this->metadataPool->getMetadata(SlideInterface::class);

        if (!is_numeric($value) && $field === null) {
            $field = 'identifier';
        } elseif (!$field) {
            $field = $entityMetadata->getIdentifierField();
        }

        $slideId = $value;
        if ($field != $entityMetadata->getIdentifierField() || $object->getStoreId()) {
            $select = $this->_getLoadSelect($field, $value, $object);
            $select->reset(Select::COLUMNS)
                ->columns($this->getMainTable() . '.' . $entityMetadata->getIdentifierField())
                ->limit(1);
            $result = $this->getConnection()->fetchCol($select);
            $slideId = count($result) ? $result[0] : false;
        }
        return $slideId;
    }

    /**
     * Load an object
     *
     * @param ZShapeSlide|AbstractModel $object
     * @param mixed $value
     * @param string $field field to load by (defaults to model id)
     * @return $this
     */
    public function load(AbstractModel $object, $value, $field = null)
    {
        $slideId = $this->getSlideId($object, $value, $field);
        if ($slideId) {
            $this->entityManager->load($object, $slideId);
        }
        return $this;
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param ZShapeSlide|AbstractModel $object
     * @return Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $entityMetadata = $this->metadataPool->getMetadata(SlideInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $storeIds = [
                Store::DEFAULT_STORE_ID,
                (int)$object->getStoreId(),
            ];
            $select->join(
                ['zshape_slide_store' => $this->getTable('zshape_slide_store')],
                $this->getMainTable() . '.' . $linkField . ' = zshape_slide_store.' . $linkField,
                []
            )
                ->where('is_active = ?', 1)
                ->where('zshape_slide_store.store_id IN (?)', $storeIds)
                ->order('zshape_slide_store.store_id DESC')
                ->limit(1);
        }

        return $select;
    }

    /**
     * Retrieves slide title from DB by passed id.
     *
     * @param string $id
     * @return string|false
     */
    public function getZShapeSlideTitleById($id)
    {
        $connection = $this->getConnection();
        $entityMetadata = $this->metadataPool->getMetadata(SlideInterface::class);

        $select = $connection->select()
            ->from($this->getMainTable(), 'title')
            ->where($entityMetadata->getIdentifierField() . ' = :slide_id');

        return $connection->fetchOne($select, ['slide_id' => (int)$id]);
    }


    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $slideId
     * @return array
     */
    public function lookupStoreIds($slideId)
    {
        $connection = $this->getConnection();

        $entityMetadata = $this->metadataPool->getMetadata(SlideInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = $connection->select()
            ->from(['zss' => $this->getTable('zshape_slide_store')], 'store_id')
            ->join(
                ['zs' => $this->getMainTable()],
                'zss.' . $linkField . ' = zs.' . $linkField,
                []
            )
            ->where('zs.' . $entityMetadata->getIdentifierField() . ' = :slide_id');

        return $connection->fetchCol($select, ['slide_id' => (int)$slideId]);
    }

    /**
     * Set store model
     *
     * @param Store $store
     * @return $this
     */
    public function setStore($store)
    {
        $this->_store = $store;
        return $this;
    }

    /**
     * Retrieve store model
     *
     * @return Store
     */
    public function getStore()
    {
        return $this->_storeManager->getStore($this->_store);
    }

    /**
     * @inheritDoc
     */
    public function save(AbstractModel $object)
    {
        $this->entityManager->save($object);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function delete(AbstractModel $object)
    {
        $this->entityManager->delete($object);
        return $this;
    }
}
