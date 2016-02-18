<?php
namespace Page\Acceptance;

class Catalog
{
    public static $CATEGORY_URL = '/haarpflege/elektrogeraete.html';
    public static $CATEGORY_URL_17 = '/furniture.html';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    public static $categoryFirstProduct = 'div.row-fluid:nth-child(6) > ul:nth-child(1) > li:nth-child(1) > div:nth-child(1) > div:nth-child(1) > a:nth-child(1)';
    public static $productBody = '.catalog-product-view';
    public static $addToCartForm = '#product_addtocart_form';
    public static $successMessage = 'li.success-msg';

    /**
     * @var \AcceptanceTester;
     */
    protected $acceptanceTester;

    public function __construct(\AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
    }

}
