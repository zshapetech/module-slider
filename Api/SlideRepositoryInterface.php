<?php
/**
 * @package ZShapeTech_Slider
 * @author ZShapeTech <zshapetech@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version $Id$
 */
namespace ZShapeTech\Slider\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Slider CRUD interface.
 * @api
 */
interface SlideRepositoryInterface
{
    /**
     * Save slide.
     *
     * @param \ZShapeTech\Slider\Api\Data\SlideInterface $slide
     * @return \ZShapeTech\Slider\Api\Data\SlideInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\ZShapeTech\Slider\Api\Data\SlideInterface $slide);

    /**
     * Retrieve slide.
     *
     * @param int $slideId
     * @return \ZShapeTech\Slider\Api\Data\SlideInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($slideId);

    /**
     * Retrieve slides matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \ZShapeTech\Slider\Api\Data\SlideSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete slide.
     *
     * @param \ZShapeTech\Slider\Api\Data\SlideInterface $slide
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\ZShapeTech\Slider\Api\Data\SlideInterface $slide);

    /**
     * Delete slide by ID.
     *
     * @param int $slideId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($slideId);
}
