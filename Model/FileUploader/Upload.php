<?php
/**
 * @package ZShapeTech_Slider
 * @author ZShapeTech <zshapetech@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version $Id$
 */
namespace ZShapeTech\Slider\Model\FileUploader;

use Magento\Config\Model\Config\Backend\File;

class Upload extends File
{
	/**
	 * Upload slide to media storage
	 * 
	 * @param string $filename
	 * @return void
	 */
	public function uploadSlide($filename)
	{
        $result = $this->_mediaDirectory->copyFile(
            $this->tempUploadPath($filename),
            $this->uploadPath() . '/' . $filename
        );
        if ($result) {
            $this->_mediaDirectory->delete($this->tempUploadPath($filename));
        }
	}
	
	/**
	 * Retrieve temporary slide upload path
	 * 
	 * @param string @filename
	 * @return string 
	 */
	protected function tempUploadPath($filename) {
		return 'tmp/' . $this->uploadPath() . '/' . $filename;
	}
	
	/**
	 * Retrieve slide upload directory
	 * 
	 * @return string
	 */
	protected function uploadPath()
	{
		return FileProcessor::FILE_DIR;
	}		
}
