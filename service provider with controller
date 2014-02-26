<?php

/**
 * Controller for generate the image
 *
 * @author nan
 */

namespace xx\xx;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ServiceProviderInterface;
use \Exception;
use \Imagick;

class ComposeImageController implements ControllerProviderInterface, ServiceProviderInterface  {
    
    public function register(Application $app) {
        $app['imagecomposer'] = $this;
    }
    
    public function boot(Application $app) {

    }
    
    public function getImageUrl($photoid,$x,$y){
        $resultImageName = md5($photoid."_".$x."_".$y).".jpg";
        $resultImagePath = IMAGE_OUTPUT_DIR . $resultImageName;

        if (file_exists($resultImagePath)) {
            //return $app->sendFile($resultImagePath);
            return IMAGE_OUTPUT_URL . $resultImageName;
        }else{
            if($this->generateImage($photoid,$x,$y))
               return IMAGE_OUTPUT_URL . $resultImageName;
        }
        return "";
    }
    
    protected function generateImage($photoid,$x,$y){
        
        if(is_null($photo)){ 
            throw new Exception('Can not find the image'); 
        }

        // read the images. 
        $bk = new Imagick();
        if (FALSE === $bk->readImageBlob($photo->getImageData())) {
            throw new Exception();
        }

        $ball = new Imagick(); 
        if (FALSE === $ball->readImage(BALL_IMAGE_PATH)) { 
            throw new Exception(); 
        } 

        // resize 
        /*if (FALSE === $bk->resizeImage(200, 200, Imagick::FILTER_LANCZOS, 1)) { 
            throw new Exception(); 
        }*/

        // put the face in hole :)
        $bk->compositeImage($ball, imagick::COMPOSITE_OVER, $x, $y);

        // merge all layers (it is not mandatory). 
        $bk->flattenImages();

        $bk->setImageFileName($resultImagePath);

        // Let's write the image. 
        if  (FALSE == $bk->writeImage()) { 
            throw new Exception('Unable to write result image.'); 
            return false;
        } 
        return true;
                
    }
    public function connect(Application $app) {

        $controllers = $app['controllers_factory'];

        
        $controllers->match('/{photoid}/{x}/{y}', function(Application $app, $photoid, $x,$y) {
            
                $resultImageName = md5($photoid."_".$x."_".$y).".jpg";
                $resultImagePath = IMAGE_OUTPUT_DIR . $resultImageName;
                
                if (file_exists($resultImagePath)) {
                    return $app->sendFile($resultImagePath);
                   // return IMAGE_OUTPUT_URL . $resultImageName;; 
                }
            
                         
                $this->generateImage($photoid,$x,$y);
              
                $resultImageURL = IMAGE_OUTPUT_URL . $resultImageName;
              
                
                //return $resultImageURL;
                
                return $app->sendFile($resultImagePath);
               
           
        }, 'GET|POST')
        ->bind("composeimage")
        ->assert('x','\d+')
        ->assert('y','\d+')
        ->assert('photo','\d+');
        
        return $controllers;
    }
    
}

