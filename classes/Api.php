<?php
class Api
{
    private $_key;
    private $_url;

    public function __construct(array $settings)
    {
        $this->_key = trim($settings['key']);
        $this->_url = trim($settings['url']);
    }

    // Getters
    public function getUrl() { return $this->_url; }
    public function getKey() { return $this->_key; }
}
