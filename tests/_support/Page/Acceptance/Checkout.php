<?php
namespace Page\Acceptance;

class Checkout
{
    public static $URL = '/checkout/onepage/';
    public static $CART_URL = '/hagel/magento/checkout/cart/';
    public static $SUCCESS_URL = '/hagel/magento/checkout/onepage/success';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    public static $radioTypeGuest = '#login:guest';
    public static $continueButton = 'button#onepage-guest-register-button';

    public static $billingFirstname = '#billing:firstname';
    public static $billingLastname = '#billing:lastname';
    public static $billingEmail = '#billing:email';
    public static $billingEmailCopy = 'billing[confirm_email]';
    public static $billingCountryId = '#billing:country_id';
    public static $billingStreet1 = '#billing:street1';
    public static $billingPostcode = '#billing:postcode';
    public static $billingCity = '#billing:city';
    public static $billingTelephone = '#billing:telephone';
    public static $billingAddressContainer = '#billing-buttons-container .btn-next';

    public static $shippingButtonsContainer = '#shipping-method-buttons-container .btn-next';
    public static $paymentButtonsContainer = '#payment-buttons-container .btn-next';
    public static $checkoutReviewContainer = '#checkout-review-submit .btn-success';

    public static $shippingMethodInput = 'form input[name=shipping_method]';
    public static $shippingMethod = 'DHL';
    public static $paymentMethod = '#p_method_banktransfer';

    public static $checkbox1 = '#agreement-1';
    public static $checkbox2 = '#agreement-2';

    /**
     * @var \AcceptanceTester;
     */
    protected $acceptanceTester;

    public function __construct(\AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
    }

}
