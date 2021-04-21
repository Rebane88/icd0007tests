<?php

namespace stf;

use stf\browser\Url;
use stf\browser\page\Page;

class Globals {

    const MAX_REDIRECT_COUNT = 3;

    public Url $baseUrl;
    public Url $currentUrl;
    public Page $page;
    public int $responseCode;

    public bool $logRequests = false;
    public bool $logPostParameters = false;
    public bool $printStackTrace = false;
    public bool $printPageSourceOnError = false;

    public int $maxRedirectCount = self::MAX_REDIRECT_COUNT;

    public function reset() : void {
        $this->maxRedirectCount = self::MAX_REDIRECT_COUNT;
    }

}

