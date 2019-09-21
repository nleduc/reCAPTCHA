<?php
/**
 * @category  ParkAndFinch
 * @package   ParkAndFinch_ReCaptcha
 * @author    Nicholas Leduc <nicholas@parkandfinch.com>
 * @copyright 2019 Park and Finch
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.parkandfinch.com/
 */

class ParkAndFinch_ReCaptcha_Block_Recaptcha extends Mage_Core_Block_Template
{
    /**
     * Returns whether the reCAPTCHA is enabled or not
     *
     * @return bool
     */
    public function isReCaptchaEnabled()
    {
        return $this->_getHelper()->isReCaptchaEnabled();
    }

    /**
     * Returns the site key defined in the reCAPTCHA config
     *
     * @return string|null
     */
    public function getSiteKey()
    {
        return $this->_getHelper()->getSiteKey();
    }

    /**
     * Returns the class added to the empty div that the reCAPTCHA will be injected into
     *
     * @return string
     */
    public function getReCaptchaClass()
    {
        return $this->_getHelper()->getReCaptchaClass();
    }

    /**
     * Get the reCAPTCHA helper
     *
     * @return ParkAndFinch_Recaptcha_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('recaptcha');
    }
}