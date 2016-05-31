<?php 
//set canonical url
if($appSettings->enable_seo){
 $document = JFactory::getDocument();
    foreach($document->_links as $key=> $value)
    {
        if(is_array($value))
        {
            if(array_key_exists('relation', $value))
            {
                if($value['relation'] == 'canonical')
                {                       
                    //the document link that contains the canonical url found and changed
                    $document->_links[$url] = $value;
                    unset($document->_links[$key]);
                    break;                      
                }
            }
        }
    }   
}
?>