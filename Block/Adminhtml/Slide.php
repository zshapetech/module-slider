<?php
/**
 * @package ZShapeTech_Slider
 * @author ZShapeTech <zshapetech@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version $Id$
 */
namespace ZShapeTech\Slider\Block\Adminhtml;

/**
 * Adminhtml slides block
 */
class Slide extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Block constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_page';
        $this->_blockGroup = 'ZShapeTech_Slider';
        $this->_headerText = __('Manage Slides');

        parent::_construct();

        if ($this->_isAllowedAction('ZShapeTech_Slider::save')) {
            $this->buttonList->update('add', 'label', __('Add New Slide'));
        } else {
            $this->buttonList->remove('add');
        }
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
