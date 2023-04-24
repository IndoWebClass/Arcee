<?php
namespace app\core;

class CSRF
{
    protected Application $app;
    protected string $key;
    public function __construct(string $key)
    {
        $this->app = Application::$app;

        $this->key = $key;
    }

    public function getToken(string $formId)
    {
        $token = hash_hmac("sha256", $this->prepareTextForHasing($formId), $this->key);
        return $token;
    }

    public function isTokenValid(string $formId, string $tokenToBeChecked)
    {
        $token = hash_hmac("sha256", $this->prepareTextForHasing($formId), $this->key);

        return $token == $tokenToBeChecked;
    }

    protected function prepareTextForHasing(string $text)
    {
        return $text." valid for ".date("Y-m-d");
    }
}
