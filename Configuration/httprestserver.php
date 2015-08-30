<?php

class HttpRestServer {
   
    private  $requestHandler=null;
    
    
    function __construct() 
    {   
       
       $this->requestHandler= new HttpResquestHandler();
      
    }
    
    //The method handle the httprequest and the display Json contents
    public function Handle()
    {
     $this->requestHandler->run();
     $response = new HttpResponseHandler($this->requestHandler);
     $viewer=  $response->getResponseView();
     
     if($viewer==null){
        $viewer= new HtmlViewer(); 
     }
     $viewer->displayContent();
     
    }
}
