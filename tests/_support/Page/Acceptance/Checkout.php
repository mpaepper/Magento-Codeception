<?php
namespace Page\Acceptance;

class Checkout extends Elements
{
    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    
    public $CHECKOUT_URL;
    public $CART_URL;
    public $SUCCESS_URL;

    public $loginEmail = '#login-email';
    public $loginPassword = '#login-password';
    public $loginButton = '//*[@id="checkout-step-login"]/div/div[1]/div/div[2]/button';
    
    public $radioTypeGuest = '#login:guest';
    public $radioTypeRegister;
    public $continueButton = 'button#onepage-guest-register-button';

    public $billingFirstname;
    public $billingLastname;
    public $billingEmail;
    public $billingEmailCopy;
    public $billingCountryId;
    public $billingStreet1;
    public $billingPostcode;
    public $billingCity;
    public $billingTelephone;
    public $billingAddressContainer;

    public $newUserPass;
    public $newUserConfirmPass;
    
    public $shippingButtonsContainer = '#shipping-method-buttons-container .btn-next';
    public $paymentButtonsContainer = '#payment-buttons-container .btn-next';
    public $checkoutReviewContainer = '#checkout-review-submit .btn-success';

    public $shippingMethodInput = 'form input[name=shipping_method]';
    public $shippingMethod = 'DHL';
    public $paymentMethod = '#p_method_banktransfer';

    public $checkbox1 = '#agreement-1';
    public $checkbox2 = '#agreement-2';
    public $afterLoginContinueButton = '//*[@id="billing-buttons-container"]/button[2]';
    /**
     * @var \AcceptanceTester;
     */
    protected $acceptanceTester;

    public function __construct(\AcceptanceTester $I)
    {
        parent::__construct($I);

        $this->acceptanceTester = $I;
        $this->configs = $this->getElementsConfig();
        
        //$mainConfig = \Codeception\Configuration::suiteSettings("acceptance", \Codeception\Configuration::config());
        //$url = $mainConfig['modules']['enabled'][0]['WebDriver']['url'];
        $this->CHECKOUT_URL =  $this->configs['common_test_settings']['checkout_url'];
        $this->CART_URL  = $this->configs['common_test_settings']['cart_url'];
        $this->SUCCESS_URL = $this->configs['common_test_settings']['success_url'];
        
        $this->loginEmail = $this->configs['existed_user']['existed_user_email_element']; //'#login-email';
        $this->loginPassword = $this->configs['existed_user']['existed_user_password_element']; //'#login-password';
        $this->loginButton = '//*[@id="checkout-step-login"]/div/div[1]/div/div[2]/button';

        
        $this->radioTypeGuest = '#login:guest';
        $this->radioTypeRegister = '#login:register';
        $this->continueButton = 'button#onepage-guest-register-button';
        $this->afterLoginContinueButton = '//*[@id="billing-buttons-container"]/button[2]';

    
        $this->billingFirstname = $this->configs['register_new_form']['form_firstname_element']; //'#billing:firstname';
        $this->billingLastname = $this->configs['register_new_form']['form_lastname_element']; //'#billing:lastname';
        $this->billingEmail = $this->configs['register_new_form']['form_email_element']; //'#billing:email';
        $this->billingEmailCopy = $this->configs['register_new_form']['form_email_confirm_element']; //'billing[confirm_email]';
        $this->billingCountryId = $this->configs['register_new_form']['form_country_element']; //'#billing:country_id';
        $this->billingStreet1 = $this->configs['register_new_form']['guest_street_element']; //'#billing:street1';
        $this->billingPostcode = $this->configs['register_new_form']['form_postcode_element']; //'#billing:postcode';
        $this->billingCity = $this->configs['register_new_form']['form_city_element']; //'#billing:city';
        $this->billingTelephone = $this->configs['register_new_form']['form_tel_element']; //'#billing:telephone';
        $this->billingAddressContainer = '#billing-buttons-container .btn-next';

        $this->newUserPass = $this->configs['new_user']['new_user_password_element'];
        $this->newUserConfirmPass = $this->configs['new_user']['new_user_password_confirm_element'];
        
        $this->shippingButtonsContainer = '#shipping-method-buttons-container .btn-next';
        $this->paymentButtonsContainer = '#payment-buttons-container .btn-next';
        $this->checkoutReviewContainer = '#checkout-review-submit .btn-success';

        $this->shippingMethodInput = 'form input[name=shipping_method]';
        $this->shippingMethod = 'DHL';
        $this->paymentMethod = $this->getActivePaymentElement();

        $this->checkbox1 = '#agreement-1';
        $this->checkbox2 = '#agreement-2';
    }

}
