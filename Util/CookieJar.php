<?php

namespace Avtonom\WebGateBundle\Util;

use \Buzz\Util\Cookie;

class CookieJar extends \Buzz\Util\CookieJar
{
    /**
     * Adds a cookie to the current cookie jar.
     *
     * @param Cookie $cookie A cookie object
     */
    public function addCookie(Cookie $cookie)
    {
        foreach ($this->cookies as $key => $_cookie) {
            if ($_cookie->getName() == $cookie->getName()) {
                $this->cookies[$key] = $cookie;
                return;
            }
        }
        $this->cookies[] = $cookie;
    }
}