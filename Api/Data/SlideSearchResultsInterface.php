<?php
/**
 * @package ZShapeTech_Slider
 * @author ZShapeTech <zshapetech@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version $Id$
 */
namespace ZShapeTech\Slider\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for slide search results.
 * @api
 */
interface SlideSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get slides list.
     *
     * @return \ZShapeTech\Slider\Api\Data\SlideInterface[]
     */
    public function getItems();

    /**
     * Set slides list.
     *
     * @param \ZShapeTech\Slider\Api\Data\SlideInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
