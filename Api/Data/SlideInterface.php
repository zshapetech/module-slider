<?php
/**
 * @package ZShapeTech_Slider
 * @author ZShapeTech <zshapetech@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version $Id$
 */
namespace ZShapeTech\Slider\Api\Data;

/**
 * Slider interface.
 * @api
 */
interface SlideInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const SLIDE_ID = 'slide_id';
    const IS_ACTIVE = 'is_active';
    const TITLE = 'title';
    const IDENTIFIER = 'identifier';
    const IMAGE = 'slide_src';
    const LINK = 'link';
    const SORT_ORDER = 'sort_order';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();
    
    /**
     * Is active
     *
     * @return bool|null
     */
    public function isActive();

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle();
    
    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier();
    
    /**
     * Get image
     * 
     * @return string|null 
     */
    public function getImage();
    
    /**
     * Get link 
     * 
     * @return string|null
     */
    public function getLink();
    
    /**
     * Get sort order
     *
     * @return string|null
     */
    public function getSortOrder();

    /**
     * Set ID
     *
     * @param int $id
     * @return \ZShapeTech\Slider\Api\Data\SlideInterface
     */
    public function setId($id);
    
    /**
     * Set is active
     *
     * @param int|bool $isActive
     * @return \ZShapeTech\Slider\Api\Data\SlideInterface
     */
    public function setIsActive($isActive);

    /**
     * Set title
     *
     * @param string $title
     * @return \ZShapeTech\Slider\Api\Data\SlideInterface
     */
    public function setTitle($title);
    
    /**
     * Set identifier
     *
     * @param string $identifier
     * @return \ZShapeTech\Slider\Api\Data\SlideInterface
     */
    public function setIdentifier($identifier);
    
    /**
     * Set image
     * 
     * @param string $image
     * @return \ZShapeTech\Slider\Api\Data\SlideInterface
     */
    public function setImage($image);
    
    /**
     * Set link
     * 
     * @param string $link
     * @return \ZShapeTech\Slider\Api\Data\SlideInterface
     */
    public function setLink($link);
    
    /**
     * Set sort order
     *
     * @param string $sortOrder
     * @return \ZShapeTech\Slider\Api\Data\SlideInterface
     */
    public function setSortOrder($sortOrder);
}
