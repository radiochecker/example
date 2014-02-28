<?php
namespace xxx;

require_once('/usr/share/php/Services/oEmbed.php');

use Services_oEmbed;
use Services_oEmbed_Object_Video;
    
class OEmbed {
	 
        protected $videoUrl;
        protected $oEmbedUrl;
        protected $options;

        function __construct($videoUrl, $oEmbedUrl,$options=array()){
            $this->videoUrl = $videoUrl;
            $this->oEmbedUrl = $oEmbedUrl;
        }

		public function getEmbed($width, $height){
            //make a key that is not ugly.
            $key = new \stdClass();
            $key->width = $width;
            $key->height = $height;
            $key->host = SITE_DOMAIN;
            $key->videoUrl = $this->videoUrl;

            $key = json_encode($key);

            /*$embed = null;
            try{
                $embed = \apc_fetch($key);
            }catch(\Exception $e){
                
            }
            if ($embed) {
                return $embed;
            }*/

            $options = array();

            $oEmbed = new \Services_oEmbed(
                $this->videoUrl,
                array_merge(
                    array(\Services_oEmbed::OPTION_API=> $this->oEmbedUrl, \Services_oEmbed::OPTION_TIMEOUT=>15),
                    $options
                )
            );

            if (!$oEmbed){
                return null;
            }

            $embed = $oEmbed->getObject(array('width'=>$width, 'height'=>$height ,'maxwidth'=>$width, 'maxheight'=>$height));
            
           // apc_store($key, $embed, 60);// add ttl// here or cache for ever?
			
			return $embed;

        }
	
}
 
