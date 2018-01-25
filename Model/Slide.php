<?php
/**
 * @package ZShapeTech_Slider
 * @author ZShapeTech <zshapetech@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version $Id$
 */
namespace ZShapeTech\Slider\Model;

use ZShapeTech\Slider\Api\Data\SlideInterface;
use ZShapeTech\Slider\Model\ResourceModel\Slide as ResourceSlide;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;

/**
 * Slide Model
 *
 * @api
 * @method Page setStoreId(array $storeId)
 * @method array getStoreId()
 */
class Slide extends AbstractModel implements SlideInterface, IdentityInterface
{

    /**#@+
     * Slide's Statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    /**#@-*/

    /**
     * Slider cache tag
     */
    const CACHE_TAG = 'zshape_s';

    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'zshape_slide';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\ZShapeTech\Slider\Model\ResourceModel\Slide::class);
    }

    /**
     * Receive slide store ids
     *
     * @return int[]
     */
    public function getStores()
    {
        return $this->hasData('stores') ? $this->getData('stores') : (array)$this->getData('store_id');
    }

    /**
     * Prepare slide's statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId()
    {
        return parent::getData(self::SLIDE_ID);
    }
	
	/**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }
    
    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->getData(self::IDENTIFIER);
    }
    
    /**
     * Get image url
     *
     * @return string
     */
    public function getImage()
    {
        return $this->getData(self::IMAGE);
    }
    
    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->getData(self::LINK);
    }

    /**
     * Is active
     *
     * @return bool
     */
    public function isActive()
    {
        return (bool)$this->getData(self::IS_ACTIVE);
    }
    
    /**
     * Get sort order
     *
     * @return string
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return \ZShapeTech\Slider\Api\Data\SlideInterface
     */
    public function setId($id)
    {
        return $this->setData(self::SLIDE_ID, $id);
    }

    /**
     * Set title
     *
     * @param string $title
     * @return \ZShapeTech\Slider\Api\Data\SlideInterface
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }
    
    /**
     * Set identifier
     *
     * @param string $identifier
     * @return \ZShapeTech\Slider\Api\Data\SlideInterface
     */
    public function setIdentifier($identifier)
    {
        return $this->setData(self::IDENTIFIER, $identifier);
    }
    
    /**
     * Set image url
     *
     * @param string $image
     * @return \ZShapeTech\Slider\Api\Data\SlideInterface
     */
    public function setImage($image)
    {
        return $this->setData(self::IMAGE, $image);
    }
    
    /**
     * Set link
     *
     * @param string $link
     * @return \ZShapeTech\Slider\Api\Data\SlideInterface
     */
    public function setLink($link)
    {
        return $this->setData(self::LINK, $link);
    }

    /**
     * Set sort order
     *
     * @param string $sortOrder
     * @return \ZShapeTech\Slider\Api\Data\SlideInterface
     */
    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

    /**
     * Set is active
     *
     * @param int|bool $isActive
     * @return \ZShapeTech\Slider\Api\Data\SlideInterface
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * @return ScopeConfigInterface
     */
    private function getScopeConfig()
    {
        if (null === $this->scopeConfig) {
            $this->scopeConfig = \Magento\Framework\App\ObjectManager::getInstance()->get(ScopeConfigInterface::class);
        }

        return $this->scopeConfig;
    }
}
