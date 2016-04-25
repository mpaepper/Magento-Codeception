<?php
namespace Page\Acceptance;

class Elements{
    
    /**
     * @var \AcceptanceTester;
     */
    protected $acceptanceTester;
    protected $elementsConfig;
    
    public function __construct(\AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
        
        $config = \Codeception\Configuration::config();
        $this->elementsConfig = \Codeception\Configuration::suiteSettings('acceptance_elements', $config);
    }
   
    public function getElementsConfig(){
        return $this->elementsConfig;
    }
    
    public function getActivePaymentElement(){
        $activePayment = $this->elementsConfig['paymentmathod']['paymentmethod_active'];
        
        foreach ($this->elementsConfig['paymentmathod']['paymentmethod_elements'] as $key=> $element){
           if ($element['payment_method']==$activePayment){
               return $element['html_style'];
           }
        }
    }
}
?>