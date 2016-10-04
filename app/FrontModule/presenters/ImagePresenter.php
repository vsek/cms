<?php

namespace App\FrontModule\Presenters;

use Nette\Utils\Image;

/**
 * Description of ImagePresenetr
 *
 * @author Vsek
 */
class ImagePresenter extends BasePresenter{
    
    /**
     * vykresli obrazek
     * @param type $image nazev obrazku
     * @param type $width sirka
     * @param type $height vyska
     * @param type $sharpen zaostreni
     * @param type $exact presna velikost (oreze zbytek)
     */
    public function renderPreview($image, $width = null, $height = null, $sharpen = false, $exact = true){
        /*$this->getHttpResponse()->setExpiration(strtotime('+ 1 year', time()));
        $this->getHttpResponse()->setHeader('Pragma', 'cache');*/
        
        //nazev preview
        $previewName = explode('.', $image);
        $postfix = $previewName[count($previewName) - 1];
        unset($previewName[count($previewName) - 1]);
        $previewName = implode('.', $previewName) . '_' . $width . '_' . $height;
        if($sharpen){
            $previewName .= '_sharpen';
        }
        if($exact){
            $previewName .= '_exact';
        }
        $previewName .= '.' . $postfix;
        
        //slouzim routu do normalniho tvaru
        $prefixDir = substr($image, 0, 4);
        if(!is_dir($this->context->parameters['wwwDir'] . '/images/preview/' . $prefixDir)){
            mkdir($this->context->parameters['wwwDir'] . '/images/preview/' . $prefixDir);
            chmod($this->context->parameters['wwwDir'] . '/images/preview/' . $prefixDir, 0777);
        }
        
        if(file_exists($this->context->parameters['wwwDir'] . '/images/preview/' . $prefixDir . '/' . $previewName)){
            $this->getHttpResponse()->setHeader('Content-Type', 'image/png');
            echo(file_get_contents($this->context->parameters['wwwDir'] . '/images/preview/' . $prefixDir . '/' . $previewName));
            exit;
        }else{
            $image = Image::fromFile($this->context->parameters['wwwDir'] . '/images/upload/' . $prefixDir . '/' . $image);
            if($exact){
                $image->resize($width, $height, Image::EXACT);
            }else{
                $image->resize($width, $height, Image::SHRINK_ONLY);
            }
            if($sharpen){
                $image->sharpen();
            }
            $image->save($this->context->parameters['wwwDir'] . '/images/preview/' . $prefixDir . '/' . $previewName, 100, Image::JPEG);
            $image->send(Image::JPEG);
        }
        $this->terminate();
    }
}
