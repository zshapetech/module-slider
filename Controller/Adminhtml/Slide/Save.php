<?php
/** 
 * @package ZShapeTech_Slider
 * @author ZShapeTech <zshapetech@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version $Id$
 */
namespace ZShapeTech\Slider\Controller\Adminhtml\Slide;

use Magento\Backend\App\Action;
use ZShapeTech\Slider\Model\Slide;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\UrlInterface;
use ZShapeTech\Slider\Model\FileUploader\FileProcessor;

class Save extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'ZShapeTech_Slider::save';

    /**
     * @var PostDataProcessor
     */
    protected $dataProcessor;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \ZShapeTech\Slider\Model\SlideFactory
     */
    private $slideFactory;

    /**
     * @var \ZShapeTech\Slider\Api\SlideRepositoryInterface
     */
    private $slideRepository;
    
    /**
     *  @var \ZShapeTech\Slider\Model\FileUploader\Upload
     */
    private $slideUploader;

    /**
     * @param Action\Context $context
     * @param PostDataProcessor $dataProcessor
     * @param DataPersistorInterface $dataPersistor
     * @param \ZShapeTech\Slider\Model\SlideFactory $slideFactory
     * @param \ZShapeTech\Slider\Api\SlideRepositoryInterface $slideRepository
     *
     */
    public function __construct(
        Action\Context $context,
        PostDataProcessor $dataProcessor,
        DataPersistorInterface $dataPersistor,
        \ZShapeTech\Slider\Model\SlideFactory $slideFactory = null,
        \ZShapeTech\Slider\Api\SlideRepositoryInterface $slideRepository = null,
        \ZShapeTech\Slider\Model\FileUploader\Upload $slideUploader
    ) {
        $this->dataProcessor = $dataProcessor;
        $this->dataPersistor = $dataPersistor;
        $this->slideFactory = $slideFactory
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(\ZShapeTech\Slider\Model\SlideFactory::class);
        $this->slideRepository = $slideRepository
            ?: \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\ZShapeTech\Slider\Api\SlideRepositoryInterface::class);
        $this->slideUploader = $slideUploader;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $data = $this->dataProcessor->filter($data);
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = Slide::STATUS_ENABLED;
            }
            if (empty($data['slide_id'])) {
                $data['slide_id'] = null;
            }

            /** @var \ZShapeTech\Slider\Model\Slide $model */
            $model = $this->slideFactory->create();

            $id = $this->getRequest()->getParam('slide_id');
            $data['identifier'] = 'zshape_slide_ident';
            if ($id) {
                $model->load($id);
                if (!$model->getId()) {
                    $this->messageManager->addErrorMessage(__('This slide no longer exists.'));
                    /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                    $resultRedirect = $this->resultRedirectFactory->create();
                    return $resultRedirect->setPath('*/*/');
                }
            }
            
            $slideSrc = $this->getRequest()->getParam('slide_src');
            
            if($slideSrc) {
				$slideType = $slideSrc[0]['type'];
				$slideSize = $slideSrc[0]['size'];
				unset($data['slide_src']);
			}
            
            if($slideSrc && isset($slideSrc[0]['file'])) {
				$slideFile = $slideSrc[0]['file'];
				$this->slideUploader->uploadSlide($slideFile);
				$distroBaseUrl = $this->getRequest()->getDistroBaseUrl();
				$slidePath = $distroBaseUrl . 'pub' . '/' . UrlInterface::URL_TYPE_MEDIA . '/' . FileProcessor::FILE_DIR. '/' . $slideFile;				
			}

            $model->setData($data);
            
            if($slideSrc) {
				$model->setSlideType($slideType);
				$model->setSlideSize($slideSize);
			} else {
				$model->setSlideType(null);
				$model->setSlideSize(null);
			}
            
            if($slideSrc && isset($slideSrc[0]['file'])) {
				$model->setSlideSrc($slidePath);
			}
			
			if($slideSrc && ! isset($slideSrc[0]['file'])) {
				$model->setSlideSrc($slideSrc[0]['url']);
			}

            $this->_eventManager->dispatch(
                'zshape_slide_prepare_save',
                ['slide' => $model, 'request' => $this->getRequest()]
            );

            try {
                $this->slideRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the slide.'));
                $this->dataPersistor->clear('zshape_slide');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['slide_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?:$e);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the slide.'));
            }

            $this->dataPersistor->set('zshape_slide', $data);
            return $resultRedirect->setPath('*/*/edit', ['slide_id' => $this->getRequest()->getParam('slide_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
