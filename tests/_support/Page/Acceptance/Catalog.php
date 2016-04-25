<?php
namespace Page\Acceptance;

class Catalog extends Elements
{
    public $CATEGORY_URL;
    public $CATEGORY_URL_17;

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    public  $categoryFirstProduct;
    public  $productBody;
    public  $addToCartForm;
    public  $successMessage;

    /**
     * @var \AcceptanceTester;
     */
    protected $acceptanceTester;

    public function __construct(\AcceptanceTester $I)
    {   
        parent::__construct($I);
    
        $this->acceptanceTester = $I;
        
        $this->configs = $this->getElementsConfig();

        $this->CATEGORY_URL =  $this->configs['common_test_settings']['test_category_url'];
        $this->CATEGORY_URL_17 = '/furniture.html';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

        $this->categoryFirstProduct = $this->configs['common_test_settings']['first_product_html_element'];
        $this->productBody = '.catalog-product-view';
        $this->addToCartForm = '#product_addtocart_form';
        $this->successMessage = 'li.success-msg';        
    }
    
}
