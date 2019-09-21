<?php
/**
 * @category  ParkAndFinch
 * @package   ParkAndFinch_ReCaptcha
 * @author    Nicholas Leduc <nicholas@parkandfinch.com>
 * @copyright 2019 Park and Finch
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.parkandfinch.com/
 */

class ParkAndFinch_Recaptcha_Helper_Data extends Mage_Core_Helper_Abstract
{
    const MODULE_NAME = 'ParkAndFinch_ReCaptcha';

    /**
     * Configuration paths
     */
    const RECAPTCHA_ENABLED = 'recaptcha_options/settings/enable_recaptcha';
    const RECAPTCHA_SITE_KEY = 'recaptcha_options/settings/google_recaptcha_site_key';
    const RECAPTCHA_SECRET_KEY = 'recaptcha_options/settings/google_recaptcha_secret_key';

    const RECAPTCHA_POST_PARAM = 'g-recaptcha-response';
    const RECAPTCHA_CLASS = 'g-recaptcha';
    const RECAPTCHA_VERIFCATION_URL = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * Returns whether reCAPTCHA is enabled or not
     *
     * @return int
     */
    public function isReCaptchaEnabled()
    {
        return $this->isModuleActive() && Mage::getStoreConfig(self::RECAPTCHA_ENABLED);
    }

    /**
     * Returns the reCAPTCHA site key set in the admin
     *
     * @return string|null
     */
    public function getSiteKey()
    {
        return Mage::getStoreConfig(self::RECAPTCHA_SITE_KEY);
    }

    /**
     * Returns the reCAPTCHA secret key set in the admin
     *
     * @return string|null
     */
    public function getSecretKey()
    {
        return Mage::getStoreConfig(self::RECAPTCHA_SECRET_KEY);
    }

    /**
     * Checks to see if the user successfully passed the invisible Google reCAPTCHA v2
     *
     * @return bool
     */
    public function isReCaptchaSuccessful()
    {
        $postFields = [
            'secret' => $this->getSecretKey(),
            'response' => $this->_getRequest()->getPost(self::RECAPTCHA_POST_PARAM),
            'remoteIp' => $this->_getRequest()->getClientIp(true)
        ];

        try {
            $curlHandler = curl_init();
            curl_setopt($curlHandler, CURLOPT_URL, self::RECAPTCHA_VERIFCATION_URL);
            curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curlHandler, CURLOPT_POST, 1);
            curl_setopt($curlHandler, CURLOPT_POSTFIELDS, $postFields);
            curl_setopt($curlHandler, CURLOPT_TIMEOUT, 2);
            $recaptchaVerificationResponseJson = curl_exec($curlHandler);
            curl_close($curlHandler);
            $recaptchaVerificationResponseArray = json_decode($recaptchaVerificationResponseJson, true);
        } catch (Exception $e) {
            Mage::logException($e);
        }

        if (isset($recaptchaVerificationResponseArray['success']) && $recaptchaVerificationResponseArray['success'] == true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns the reCAPTCHA CSS class
     *
     * @return string
     */
    public function getReCaptchaClass()
    {
        return self::RECAPTCHA_CLASS;
    }

    /**
     * Is the module active
     *
     * @return bool
     */
    public function isModuleActive()
    {
        return Mage::getConfig()
            ->getModuleConfig(self::MODULE_NAME)
            ->is('active', 'true');
    }
}