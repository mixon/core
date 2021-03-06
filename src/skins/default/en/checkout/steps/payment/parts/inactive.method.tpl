{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Checkout : payment step : inactive state : method(s)
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="checkout.payment.inactive", weight="20")
 *}
<div class="secondary">
  {if:cart.getPaymentMethod()}
    <div class="label">{t(#Payment method#)}:</div>
    {cart.paymentMethod.name}

  {else:}
    <h3>{t(#Payment methods#)}</h3>
    <widget template="checkout/steps/payment/methods.tpl" disabledSelector="true" />
  {end:}
</div>
