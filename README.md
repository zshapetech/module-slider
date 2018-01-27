# Magento 2 image slider extension
This extension allows you to create image slider in your Magento 2 store.

## Getting started
The following instructions describe installation and configuration aspects of extension.

### Prerequisites
Magento Open Source 2.x installed

### Getting the extension
- Using `Composer`

    If `Composer` is installed on your server add in command line the following command:

    `composer require zshapetech/module-slider`

- Simply download repository as `.zip` file or clone with the following command:
    
    `git clone https://github.com/zshapetech/module-slider.git`
    
    Create directory `app/code/ZShapeTech/Slider` in Magento 2 installation and copy downloaded files.
    
    Install [zshapetech/module-core](https://github.com/zshapetech/module-core)
    
### Installing
Run in command line the following commands:

  * `php bin/magento setup:upgrade`
  
  * `php bin/magento setup:di:compile`

### Configurating
Open Magento 2 admin and navigate to section **ZshapeTech > Settings**. Here you can change frontend slider settings.

To add a new slide navigate to section **ZShapeTech > Manage slides** and click **Add new slide**. Complete the form and click **Save slide**.

By default slider is appearing on home page. You can change its placement by moving the following xml block:

`<block class="ZShapeTech\Slider\Block\Slide" name="zshape.slider" template="ZShapeTech_Slider::slider.phtml" />`

## License
This magento 2 extension is licensed under the MIT license.
