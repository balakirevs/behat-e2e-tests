<?php

namespace Page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class Browser extends Page
{
    /*
     * Get browser name
     */
    public function getBrowserName()
    {
        $driver = $this->getSession()->getDriver();
        $userAgent = $driver->evaluateScript('return navigator.userAgent');
        $provider = $driver->evaluateScript('return navigator.vendor');
        $browser = null;
        if (preg_match('/google/i', $provider)) {
            $browser = 'Chrome';
        } elseif (preg_match('/firefox/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif ((strpos('Safari', $userAgent) !== false)) {
            $browser = 'Safari';
        } elseif (preg_match('/MSIE/i', $userAgent)) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/phantomjs/i', $userAgent)) {
            $browser = 'PhantomJS';
        } else {
            print 'Unknown Browser ' . $userAgent . $provider;
        }
        return $browser;
    }

    /*
     * set browser size
     * @param string $browser
     * @param int $width
     * @param int $height
     */
    public function setBrowserToSize($browser, $width, $height)
    {
        if ($this->getBrowserName() == $browser) {
            $this->getSession()->resizeWindow($width, $height);
        } else {
            $this->getSession()->maximizeWindow();
        }
    }
}