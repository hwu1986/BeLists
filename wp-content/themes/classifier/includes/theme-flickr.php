<?php
class FlickrImages {
    private $xml;
    
    public function __construct( $rss_url ) {
        $this->xml = simplexml_load_file( $rss_url );
    }
    
    public function getTitle() {
        return $this->xml->channel->title;
    }
    
    public function getProfileLink() {
        return $this->xml->channel->link;
    }
    
    public function getImages() {
        $images = array();
        $regx = "/<img(.+)\/>/";
        
        foreach( $this->xml->channel->item as $item ) {
                preg_match( $regx, $item->description, $matches );
                $imgthumb = $matches[ 0 ];								$imgthumb = str_replace('_m.jpg', '_s.jpg', $imgthumb);
                $images[] = array(
                                  'title' => $item->title,
                                  'link' => $item->link,
                                  'thumb' => $imgthumb
                                );
        }
        
        return $images;
    }
}
?>