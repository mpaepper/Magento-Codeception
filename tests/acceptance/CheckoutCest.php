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

    function _before(AT $I) {
        //$this->cleanOutput();
        $this->cleanData();
        // will be executed at the beginning of each test
        $this->pageCatalog = new Page\Catalog($I);
        $this->pageCheckout = new Page\Checkout($I);
    }

    protected function cleanOutput() {
        $path = 'tests/_output';
        if (is_dir($path) === true) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($files as $file) {
                if (in_array($file->getBasename(), array('.', '..')) !== true) {
                    if ($file->isDir() === true) {
                        rmdir($file->getPathName());
                    } else if (($file->isFile() === true) || ($file->isLink() === true)) {
                        unlink($file->getPathname());
                    }
                }
            }

            return rmdir($path);
        } else if ((is_file($path) === true) || (is_link($path) === true)) {
            return unlink($path);
        }

        return false;
    }

    protected function cleanData() {
        
        define('MAGENTO_ROOT', '../magento');
        $mageFilename = MAGENTO_ROOT . '/app/Mage.php';
        require_once $mageFilename;
        umask(0);
        Mage::app();
        
        if (!Mage::registry('isSecureArea')){
            Mage::register('isSecureArea', true);
        }

        $cart = Mage::getModel('checkout/cart');                
        $cart->truncate(); // remove all active items in cart page

        $collection = Mage::getModel('sales/order')->getCollection();
        foreach ($collection as $data) {
            $id = $data['increment_id'];
             try{
                Mage::getModel('sales/order')->loadByIncrementId($id)->delete();
                echo "order #".$id." is removed".PHP_EOL;
            }catch(Exception $e){
                echo "order #".$id." could not be remvoved: ".$e->getMessage().PHP_EOL;
            }
        }

        $customer = Mage::getModel("customer/customer");
        $customer->setWebsiteId(1);
        
        $customer->loadByEmail('newuser@lemundo.de');
        $customer->setIsDeleteable(true);
        $id = $customer->getId();
        $customer->delete();
        Mage::unregister('isSecureArea');
        
        Mage::log('Customer '.$id.' is deleted.<br/>', null, "customer-delete.log");
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

        if ($this->getActor() == 'new') {
            $I->fillField($this->pageCheckout->newUserPass, $this->getConfig('new_password'));
            $I->fillField($this->pageCheckout->newUserConfirmPass, $this->getConfig('new_confirm_password'));
        }

        $I->click($this->pageCheckout->billingAddressContainer);
    }

    protected function gotoOnePageCheckout(AT $I) {
        $I->wantTo('use One Page Checkout');
        $I->lookForwardTo('experience flawless checkout');
        $I->amGoingTo('place an order as a '. $this->getActor());
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
