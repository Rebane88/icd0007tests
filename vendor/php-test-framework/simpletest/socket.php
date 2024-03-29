<?php

class SimpleSocket extends SimpleStickyError {

    private $handle;
    private bool $is_open = false;
    private string $sent = '';
    private int $block_size;

    public function __construct($host, $port, $timeout, $block_size = 255) {

        parent::__construct();

        if (! ($this->handle = $this->openSocket($host, $port, $error_number, $error, 0))) {
            $this->setError("Cannot open [$host:$port] with [$error] within [$timeout] seconds");
            return;
        }

        stream_set_timeout($this->handle, $timeout);

        $this->is_open = true;
        $this->block_size = $block_size;
    }

    public function write($message): bool {
        if ($this->isError() || ! $this->isOpen()) {
            return false;
        }
        $count = fwrite($this->handle, $message);
        if (! $count) {
            if ($count === false) {
                $this->setError('Cannot write to socket');
                $this->close();
            }
            return false;
        }
        fflush($this->handle);
        $this->sent .= $message;
        return true;
    }

    public function read() {
        if ($this->isError() || ! $this->isOpen()) {
            return false;
        }

        $raw = fread($this->handle, $this->block_size);

        $info = stream_get_meta_data($this->handle);

        if ($raw === false && $info['timed_out']) {
            $this->setError(sprintf(
                'Socket read timeout. Timeout is %s seconds', REQUEST_TIMEOUT));
            $this->setErrorCode(ERROR_N03);
            $this->close();
            return false;
        } else if ($raw === false) {
            $this->setError('Cannot read from socket');
            $this->close();
        }

        return $raw;
    }

    public function isOpen(): bool {
        return $this->is_open;
    }

    public function close(): bool {
        $this->is_open = false;
        return !is_resource($this->handle) || fclose($this->handle);
    }

    public function getSent(): string {
        return $this->sent;
    }

    protected function openSocket($host, $port, &$error_number, &$error, $timeout) {
        return fsockopen($host, $port, $error_number, $error, $timeout);
    }
}

class SimpleSecureSocket extends SimpleSocket {

    public function __construct($host, $port, $timeout) {
        parent::__construct($host, $port, $timeout);
    }

    public function openSocket($host, $port, &$error_number, &$error, $timeout) {
        return parent::openSocket("tls://$host", $port, $error_number, $error, $timeout);
    }
}

class SimpleStickyError {
    private string $error = 'Constructor not chained';
    private string $errorCode = '';

    public function __construct() {
        $this->clearError();
    }

    public function isError(): bool {
        return ($this->error != '');
    }

    public function getError(): string {
        return $this->error;
    }

    public function getErrorCode(): string {
        return $this->errorCode;
    }

    public function setError($error): void {
        $this->error = $error;
    }

    public function setErrorCode($errorCode): void {
        $this->errorCode = $errorCode;
    }

    public function clearError(): void {
        $this->setError('');
    }
}
