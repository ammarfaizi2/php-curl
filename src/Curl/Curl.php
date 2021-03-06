<?php

namespace Curl;

use Curl\Contracts\Curl as CurlContract;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com> https://www.facebook.com/ammarfaizi2
 * @license MIT
 * @version 0.0.2
 */
class Curl implements CurlContract
{

    const VERSION = "0.0.2";

    /**
     * @var string
     */
    private $url;

    /**
     * @var array
     */
    private $opt = [];

    /**
     * @var array
     */
    private $info = [];

    /**
     * @var string
     */
    private $error;

    /**
     * @var int
     */
    private $errno;

    /**
     * @var string
     */
    private $output;

    /**
     * @var array
     */
    private $userOpt = [];

    /**
     * @var array
     */
    private $defaultOpt = [];

    /**
     * @var int
     */
    private $currentStep = 0;

    /**
     * @var \Closure|(Invokeable)
     */
    private $successAction;

    /**
     * @var array
     */
    private $headerResponse = [];

    /**
     * Constructor.
     *
     * @param string $url
     */
    public function __construct($url = null)
    {
        $this->url = $url;
        $this->defaultOpt = [
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_SSL_VERIFYHOST  => false,
            CURLOPT_CONNECTTIMEOUT  => 30,
            CURLOPT_TIMEOUT         => 30,
            CURLOPT_HEADER          => true,
            CURLOPT_USERAGENT       => "PHP Curl ".self::VERSION
        ];
    }

    /**
     * Do action on success.
     *
     * @param \Closure|(Invokeable) $action
     * @return bool
     */
    public function success($action)
    {
        if ($this->currentStep > 0) {
            throw new \Exception("success method must be called before exec method.", 1);
        }
        $this->successAction = $action;
        return true;
    }

    /**
     * Register cookie jar.
     *
     * @param string $cookiefile
     */
    public function cookiejar($cookiefile)
    {
        $this->userOpt[CURLOPT_COOKIEFILE] = $cookiefile;
        $this->userOpt[CURLOPT_COOKIEJAR] = $cookiefile;
    }

    /**
     * Set useragent.
     *
     * @param string $useragent
     */
    public function useragent($useragent)
    {
        $this->userOpt[CURLOPT_USERAGENT] = $useragent;
    }

    /**
     * Define user opt.
     *
     * @param array $opt
     */
    public function setOpt($opt)
    {
        if ($this->currentStep > 0) {
            throw new \Exception("setOpt method must be called before exec method.", 1);
        }
        foreach ($opt as $key => $value) {
            $this->userOpt[$key] = $value;
        }
    }

    /**
     * Get error message.
     *
     * @return string
     */
    public function error()
    {
        return $this->error;
    }

    /**
     * Get error code.
     *
     * @return int
     */
    public function errno()
    {
        return $this->errno;
    }

    /**
     * Get header response.
     *
     * @return array
     */
    public function headerResponse()
    {
        return $this->headerResponse;
    }

    /**
     * Build return context.
     */
    private function buildReturnContext()
    {
        $this->buildHeaderResponse();
    }

    /**
     * Build header response context.
     */
    private function buildHeaderResponse()
    {
        foreach (explode(
            "\n",
            substr($this->output, 0, $this->info['header_size'])
        ) as $val) {
            $val = trim($val);
            if (! empty($val)) {
                $this->headerResponse[] = $val;
            }
        }
        $this->output = substr($this->output, $this->info['header_size']);
    }

    /**
     * curl_exec.
     */
    public function exec($url = null)
    {
        $this->buildOpt();
        $ch = curl_init(
            ($this->url ? $this->url : $url)
        );
        curl_setopt_array($ch, $this->opt);
        $this->output = curl_exec($ch);
        $this->info   = curl_getinfo($ch);
        $this->errno  = curl_errno($ch);
        $this->error  = curl_error($ch);
        $this->currentStep++;
        curl_close($ch);
        $this->buildReturnContext();
        return $this->output;
    }

    /**
     * Build opt context.
     */
    private function buildOpt()
    {
        foreach ($this->defaultOpt as $key => $val) {
            $this->opt[$key] = $val;
        }
        foreach ($this->userOpt as $key => $val) {
            $this->opt[$key] = $val;
        }
    }

    /**
     * @return array
     */
    public function info()
    {
        return $this->info;
    }

    /**
     * Private url check.
     *
     * @param string $url
     */
    private function urlGen($url)
    {
        if (empty($url) && empty($this->url)) {
            throw new \Exception("URL not set!", 1);
        }
        return empty($url) ? $this->url : $url;
    }
}
