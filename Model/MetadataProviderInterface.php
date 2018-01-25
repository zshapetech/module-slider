<?php
/**
 * @package ZShapeTech_Slider
 * @author ZShapeTech <zshapetech@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version $Id$
 */
namespace ZShapeTech\Slider\Model;

/**
 * Interface \ZShapeTech\Slider\Model\MetadataProviderInterface
 *
 */
interface MetadataProviderInterface
{
    /**
     * Return design config field metadata as an array
     * Each array item consists metadata for one field. The key is a field name in UI XML configuration
     * The value is an array with metadata:
     *  - 'backend_model' field backend model
     *  - other optional parameters
     *
     * @return array
     */
    public function get();
}
