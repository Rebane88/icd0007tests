<?php

namespace stf\browser;

class Url {

    private string $host;
    private Path $path;
    private string $file;
    private array $queryParams = [];
    private string $fragment;

    public function __construct(string $url) {
        $this->extractHost($url);
        $this->extractPath($url);
        $this->extractQueryStringAndFragment($url);
    }

    private function extractHost($url) : void {
        $scheme = parse_url($url, PHP_URL_SCHEME) ?? '';
        $host = parse_url($url, PHP_URL_HOST) ?? '';
        $port = parse_url($url, PHP_URL_PORT) ?? '';

        $this->host = $scheme
            . ($scheme ? '://' : '')
            . $host
            . ($port ? ':' : '')
            . $port;
    }

    private function extractPath($url) : void {
        $path = parse_url($url, PHP_URL_PATH) ?? '';
        if (preg_match('/\.$/', $path)) {
            $path .= '/';
        }

        $pathRegex = '/(.*\/)?(.*)/';
        preg_match($pathRegex, $path, $matches);

        $this->path = new Path($matches[1]);
        $this->file = $matches[2];
    }

    private function extractQueryStringAndFragment($url) : void {
        $queryString = parse_url($url, PHP_URL_QUERY) ?? '';
        $this->fragment = parse_url($url, PHP_URL_FRAGMENT) ?? '';

        parse_str($queryString, $this->queryParams);
    }

    public function asString() : string {
        if ($this->host
            && ($this->path->isEmpty() || $this->path->isRoot())
            && !$this->file
            && empty($this->queryParams)) {

            return $this->host;
        }

        return $this->host
            . ($this->host && !$this->path->isAbsolute() ? '/' : '')
            . $this->path->asString()
            . $this->file
            . (empty($this->queryParams) ? '' : '?') . $this->getQueryString()
            . ($this->fragment ? '#' : '') . $this->fragment;
    }

    private function isEmpty() : bool {
        return !$this->host
            && $this->path->isEmpty()
            && !$this->file
            && empty($this->queryParams)
            && !$this->fragment;
    }

    public function navigateTo(string $destination) : Url {
        $dest = new Url($destination);

        if ($dest->host) {
            return new Url($destination);
        } else if ($dest->isEmpty() && trim($destination) !== '?') {
            return $this;
        }

        $newUrl = new Url('');
        $newUrl->host = $this->host;
        $newUrl->path = $this->path->cd($dest->path);

        $newUrl->file = ($dest->file || !$dest->path->isEmpty())
            ? $dest->file : $this->file;

        $newUrl->queryParams = $dest->queryParams;
        $newUrl->fragment = $dest->fragment;

        return $newUrl;
    }

    public function getQueryString() : string {
        return http_build_query($this->queryParams);
    }

    public function addRequestParameter(string $key, string $value): void {
        $this->queryParams[$key] = $value;
    }
}
