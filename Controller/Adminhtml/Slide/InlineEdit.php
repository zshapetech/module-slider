<?php
/**
 * @package ZShapeTech_Slider
 * @author ZShapeTech <zshapetech@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version $Id$
 */
namespace ZShapeTech\Slider\Controller\Adminhtml\Slide;

use Magento\Backend\App\Action\Context;
use ZShapeTech\Slider\Api\SlideRepositoryInterface as SlideRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use ZShapeTech\Slider\Api\Data\SlideInterface;

/**
 * Slides grid inline edit controller
 */
class InlineEdit extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'ZShapeTech_Slider::save';

    /**
     * @var \ZShapeTech\Slider\Controller\Adminhtml\Slide\PostDataProcessor
     */
    protected $dataProcessor;

    /**
     * @var \ZShapeTech\Slider\Api\SlideRepositoryInterface
     */
    protected $slideRepository;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param PostDataProcessor $dataProcessor
     * @param SlideRepository $slideRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        PostDataProcessor $dataProcessor,
        SlideRepository $slideRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->dataProcessor = $dataProcessor;
        $this->slideRepository = $slideRepository;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach (array_keys($postItems) as $slideId) {
            /** @var \ZShapeTech\Slider\Model\Slide $slide */
            $slide = $this->slideRepository->getById($slideId);
            try {
                $slideData = $this->filterPost($postItems[$slideId]);
                $this->validatePost($slideData, $slide, $error, $messages);
                $extendedSlideData = $slide->getData();
                $this->setZShapeSlideData($slide, $extendedSlideData, $slideData);
                $this->slideRepository->save($slide);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithSlideId($slide, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithSlideId($slide, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithSlideId(
                    $slide,
                    __('Something went wrong while saving the slide.')
                );
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Filtering posted data.
     *
     * @param array $postData
     * @return array
     */
    protected function filterPost($postData = [])
    {
        $slideData = $this->dataProcessor->filter($postData);
        
        return $slideData;
    }

    /**
     * Validate post data
     *
     * @param array $slideData
     * @param \ZShapeTech\Slider\Model\Slide $slide
     * @param bool $error
     * @param array $messages
     * @return void
     */
    protected function validatePost(array $slideData, \ZShapeTech\Slider\Model\Slide $slide, &$error, array &$messages)
    {
        if (!$this->dataProcessor->validateRequireEntry($slideData)) {
            $error = true;
            foreach ($this->messageManager->getMessages(true)->getItems() as $error) {
                $messages[] = $this->getErrorWithSlideId($slide, $error->getText());
            }
        }
    }

    /**
     * Add slide title to error message
     *
     * @param SlideInterface $slide
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithSlideId(SlideInterface $slide, $errorText)
    {
        return '[Slide ID: ' . $slide->getId() . '] ' . $errorText;
    }

    /**
     * Set slide data
     *
     * @param \ZShapeTech\Slider\Model\Slide $slide
     * @param array $extendedSlideData
     * @param array $slideData
     * @return $this
     */
    public function setZShapeSlideData(\ZShapeTech\Slider\Model\Slide $slide, array $extendedSlideData, array $slideData)
    {
        $slide->setData(array_merge($slide->getData(), $extendedSlideData, $slideData));
        return $this;
    }
}
