<?php
/**
 * @package ZShapeTech_Slider
 * @author ZShapeTech <zshapetech@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version $Id$
 */
namespace ZShapeTech\Slider\Model\Slide\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class IsActive implements OptionSourceInterface
{
    /**
     * @var \ZShapeTech\Slider\Model\Slide
     */
    protected $zshapeSlide;

    /**
     * Constructor
     *
     * @param \ZShapeTech\Slider\Model\Slide $zshapeSlide
     */
    public function __construct(\ZShapeTech\Slider\Model\Slide $zshapeSlide)
    {
        $this->zshapeSlide = $zshapeSlide;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->zshapeSlide->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
