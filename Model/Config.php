<?php namespace Sebwite\FeedbackCompany\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    /** @var \Magento\Framework\App\Config\ScopeConfigInterface */
    protected $configInterface;

    protected $pathPrefix = 'feedbackcompany/general/%s';

    protected $config = [];

    /**
     * Config constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $configInterface
     */
    public function __construct(ScopeConfigInterface $configInterface)
    {
        $this->configInterface = $configInterface;

        if (!$this->configFileExists()) {
            $this->createConfigFile();
        }

        $this->config = $this->getConfig();
    }

    /**
     * isEnabled method
     *
     * @return bool
     */
    public function isEnabled()
    {
        $enabled = (bool)$this->configInterface->getValue($this->conf('enabled'));

        return $enabled && $this->hasAuth();
    }

    /**
     * conf method
     *
     * @param $key
     *
     * @return string
     */
    protected function conf($key)
    {
        return sprintf($this->pathPrefix, $key);
    }

    /**
     * getValue method
     *
     * @param $key
     *
     * @return bool
     */
    public function getValue($key = false)
    {
        if (!$key) {
            return $this->config;
        }

        return isset($this->config[ $key ]) ? $this->config[ $key ] : false;
    }

    /**
     * setValue method
     *
     * @param $key
     * @param $value
     */
    public function setValue($key, $value)
    {
        $this->config[ $key ] = $value;
        $this->writeConfig();
    }

    /**
     * getAuth method
     *
     * @return array|bool
     */
    public function getAuth()
    {
        $oAuthId     = $this->configInterface->getValue($this->conf('oauth_client_id'));
        $oAuthSecret = $this->configInterface->getValue($this->conf('oauth_client_secret'));

        if (is_null($oAuthId) || is_null($oAuthSecret)) {
            return false;
        }

        return [$oAuthId, $oAuthSecret];
    }

    /**
     * hasAuth method
     *
     * @return bool
     */
    public function hasAuth()
    {
        return $this->getAuth() !== false;
    }

    /**
     * configFileExists method
     *
     * @return bool
     */
    protected function configFileExists()
    {
        return is_file(__DIR__ . '/../cache.json');
    }

    /**
     * createConfigFile method
     */
    protected function createConfigFile()
    {
        file_put_contents(__DIR__ . '/../cache.json', []);
    }

    /**
     * getConfigFile method
     *
     * @return bool|mixed
     */
    protected function getConfig()
    {
        if (!is_file(__DIR__ . '/../cache.json')) {
            return false;
        }
        $config = file_get_contents(__DIR__ . '/../cache.json');

        return $config === 1 ? [] : json_decode($config, true);
    }

    /**
     * writeConfig method
     */
    public function writeConfig()
    {
        file_put_contents(__DIR__ . '/../cache.json', json_encode($this->config));
    }
}