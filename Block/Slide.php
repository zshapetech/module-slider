<?php
/**
 * @package ZShapeTech_Slider
 * @author ZShapeTech <zshapetech@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version $Id$
 */
namespace ZShapeTech\Slider\Block;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;
use ZShapeTech\Slider\Model\ResourceModel\Slide\Collection;

class Slide extends Template {
	
	/**
	 * Config paths to slider settings
	 */ 
	 
	const SLIDER_ENABLE = 'zshape_slider/options/enable';
	
	const SLIDER_ADAPTIVE_HEIGHT = 'zshape_slider/options/adaptive_height';
	
	const SLIDER_AUTOPLAY = 'zshape_slider/options/autoplay';
	
	const SLIDER_AUTOPLAY_SPEED = 'zshape_slider/options/autoplay_speed';
	
	const SLIDER_SHOW_ARROWS = 'zshape_slider/options/arrows';
	
	const SLIDER_SHOW_DOTS = 'zshape_slider/options/dots';
	
	/**
	 * @var \Magento\Framework\View\Element\Template
	 */
	protected $context;
	
	/**
	 * @var Magento\Store\Model\StoreManagerInterface
	 */ 
	protected $storeManager;
	
	/**
	 * @var ZShapeTech\Slider\Model\ResourceModel\Slide\Collection
	 */ 
	protected $slideCollection;
	
	/**
	 * Constructor
	 * 
	 * @param \ZShapeTech\Slider\Model\ResourceModel\Slide\Collection $slideCollection
	 * @param \Magento\Store\Model\StoreManagerInterface $storeManager
	 * @param \Magento\Framework\View\Element\Template\Context $context
	 * @param array $data
	 */ 
	public function __construct(
		Collection $slideCollection,
		StoreManagerInterface $storeManager,
		Template\Context $context, 
		array $data = []
	)
	{
		parent::__construct($context, $data);
		$this->slideCollection = $slideCollection;
		$this->storeManager = $storeManager;
	}
	
	/**
	 * Retrieve slides collection
	 * 
	 * @return \ZShapeTech\Slider\Model\ResourceModel\Slide\Collection
	 */ 
	public function getSlideCollection()
	{
		$store = $this->storeManager->getStore();
		return $this->slideCollection->addStoreFilter($store, true)->addFieldToFilter('is_active', 1)->setOrder('sort_order', 'ASC');
	}
	
	/**
	 * @return boolean
	 */ 
	public function getSliderEnable()
	{
		return (bool) $this->_scopeConfig->getValue(self::SLIDER_ENABLE);
	}
	
	/**
	 * @return boolean
	 */ 
	public function getAdaptiveHeight()
	{
		return (bool) $this->_scopeConfig->getValue(self::SLIDER_ADAPTIVE_HEIGHT);
	}
	
	/**
	 * @return boolean
	 */ 
	public function getSliderAutoplay()
	{
		return (bool) $this->_scopeConfig->getValue(self::SLIDER_AUTOPLAY);
	}
	
	/**
	 * @return string
	 */ 
	public function getSliderAutoplaySpeed()
	{
		return $this->_scopeConfig->getValue(self::SLIDER_AUTOPLAY_SPEED);
	}
	
	/**
	 * @return boolean
	 */ 
	public function getShowArrows()
	{
		return (bool) $this->_scopeConfig->getValue(self::SLIDER_SHOW_ARROWS); 
	}

	/**
	 * @return boolean
	 */ 
	public function getShowDots()
	{
		return (bool) $this->_scopeConfig->getValue(self::SLIDER_SHOW_DOTS); 
	}
	
}
