<?php

use \AcceptanceTester as AT;
use Page\Acceptance as Page;

// @codingStandardsIgnoreFile

class CheckoutCest {

    /**
     * Parent configuration node for custom values 
     */
    const CONFIG_NODE = 'checkout';

    private $actor;

    function _before(AT $I)
    {
        // will be executed at the beginning of each test
        $this->pageCatalog = new Page\Catalog($I);
        $this->pageCheckout = new Page\Checkout($I);
    }
    
    protected function getActor() {
        return $this->actor;
    }

    protected function setActor($actor) {
        $this->actor = $actor;
    }

    protected function testAddProductToCart(AT $I) {
        $I->wantTo('Add product to cart');

        $I->amGoingTo('open category page');
        $I->amOnPage($this->pageCatalog->CATEGORY_URL); 
        $I->expectTo('see category page');

        $I->amGoingTo('open product page');
        $I->click($this->pageCatalog->categoryFirstProduct);
        $I->expectTo('see product page');
        $I->seeElement($this->pageCatalog->productBody);

        $I->amGoingTo('submit the form');
        $I->submitForm($this->pageCatalog->addToCartForm, array());

        $I->expectTo('see the cart page');
        $I->seeCurrentUrlEquals($this->pageCheckout->CART_URL);
        $I->expectTo('see a success message');
        $I->seeElement($this->pageCatalog->successMessage);
    }

    protected function fillForm(AT $I) {
        $I->amGoingTo('fill my address');
        $I->fillField($this->pageCheckout->billingFirstname, $this->getConfig('firstname'));
        $I->fillField($this->pageCheckout->billingLastname, $this->getConfig('lastname'));
        $I->fillField($this->pageCheckout->billingEmail, $this->getConfig('email'));
        $I->fillField($this->pageCheckout->billingEmailCopy, $this->getConfig('email'));
        $I->selectOption($this->pageCheckout->billingCountryId, $this->getConfig('country_id'));
        $I->fillField($this->pageCheckout->billingStreet1, $this->getConfig('street'));
        $I->fillField($this->pageCheckout->billingPostcode, $this->getConfig('postcode'));
        $I->fillField($this->pageCheckout->billingCity, $this->getConfig('city'));
        $I->fillField($this->pageCheckout->billingTelephone, $this->getConfig('phone'));
        
        if ($this->getActor()=='new'){
            $I->fillField($this->pageCheckout->newUserPass, $this->getConfig('new_password'));
            $I->fillField($this->pageCheckout->newUserConfirmPass, $this->getConfig('new_confirm_password'));
        }
        
        $I->click($this->pageCheckout->billingAddressContainer);
        
    }

    protected function gotoOnePageCheckout(AT $I) {
        $I->wantTo('use One Page Checkout');
        $I->lookForwardTo('experience flawless checkout');
        $I->amGoingTo('place an order as a guest');
        $I->amOnPage($this->pageCheckout->CHECKOUT_URL);

        $I->amGoingTo('select the checkout type');
    }


    protected function testOnePageCheckout(AT $I) {

        $I->amGoingTo('select shipping method');
        $I->waitForElementVisible($this->pageCheckout->shippingButtonsContainer);
        $I->selectOption($this->pageCheckout->shippingMethodInput, $this->pageCheckout->shippingMethod);
        $I->click($this->pageCheckout->shippingButtonsContainer);

        $I->waitForElementVisible($this->pageCheckout->paymentButtonsContainer);
        $I->amGoingTo('select payment method');
        $I->click($this->pageCheckout->paymentMethod);
        $I->click($this->pageCheckout->paymentButtonsContainer);

        $I->waitForElementVisible($this->pageCheckout->checkoutReviewContainer);
        $I->amGoingTo('review and finish my order');
        $I->checkOption($this->pageCheckout->checkbox1);
        $I->checkOption($this->pageCheckout->checkbox2);
        $I->click($this->pageCheckout->checkoutReviewContainer);
        $I->wait($this->getConfig('timeout'));
        
        $I->wantTo('Observe the order success page');
        $I->expectTo('see order success page');
        $I->seeInCurrentUrl($this->pageCheckout->SUCCESS_URL);
        $I->expectTo('be a happy customer');
    }

    /**
     * Returns a configuration value for selected key
     * 
     * @param $configKey string
     */
    protected function getConfig($configKey) {
        $config = \Codeception\Configuration::config();
        $suiteSettings = \Codeception\Configuration::suiteSettings('acceptance', $config);
        return $suiteSettings['data'][self::CONFIG_NODE][$configKey];
    }
    
    protected function login(AT $I) {
        $I->fillField($this->pageCheckout->loginEmail, $this->getConfig('login_email'));
        $I->fillField($this->pageCheckout->loginPassword, $this->getConfig('login_password'));
        $I->click($this->pageCheckout->loginButton);
        $I->click($this->pageCheckout->afterLoginContinueButton);
    }

    
    /**
     * Tests Checkout Success page
     *
     * @group checkout
     *
     * @param $I AcceptanceTester
     *
     */
    public function testGuest(AT $I) {
        
        $this->setActor('guest');
        $this->testAddProductToCart($I);
        $I->am('Cuest');
        $this->gotoOnePageCheckout($I);
        $I->selectOption($this->pageCheckout->radioTypeGuest, 'guest');
        $I->click($this->pageCheckout->continueButton);
        $this->fillForm($I);
        $this->testOnePageCheckout($I);
    }

    /**
     * Tests Checkout Success page
     *
     * @group checkout
     *
     * @param $I AcceptanceTester
     *
     */    
    public function testRegistered(AT $I) {
        $this->setActor('registered');
        $this->testAddProductToCart($I);
        $I->am('Registered User');
        $this->gotoOnePageCheckout($I);
        $this->login($I);
        $this->testOnePageCheckout($I);
    }

    /**
     * Tests Checkout Success page
     *
     * @group checkout
     *
     * @param $I AcceptanceTester
     *
     */    
    public function testNew(AT $I) {
        $this->setActor('new');
        $this->testAddProductToCart($I);
        $I->am('New User');
        $this->gotoOnePageCheckout($I);
        $I->selectOption($this->pageCheckout->radioTypeRegister, 'register');
        $I->click($this->pageCheckout->continueButton);
        $this->fillForm($I);
        $this->testOnePageCheckout($I);
    }

}