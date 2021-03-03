<?php

namespace stf;

class Globals {

    public Url $baseUrl;
    public Url $currentUrl;
    public Page $page;
    public int $responseCode;

    public bool $logRequests = false;
    public bool $logPostParameters = false;
    public bool $printStackTrace = false;
    public bool $printPageSourceOnParseError = false;

}

