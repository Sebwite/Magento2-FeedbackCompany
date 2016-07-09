<?php namespace Sebwite\FeedbackCompany\Model;

class Request
{
    protected $oauth_token;

    /** @var \Sebwite\FeedbackCompany\Model\Config */
    protected $config;

    protected $oAuthUrl = 'https://beoordelingen.feedbackcompany.nl/api/v1/oauth2/token?client_id=%s&client_secret=%s&grant_type=authorization_code';

    public function __construct(Config $config)
    {
        $this->config      = $config;
        $this->oauth_token = $this->getOAuthToken();
    }

    /**
     * doRequest method
     *
     * @param       $url
     * @param array $headers
     *
     * @return mixed
     */
    public function doRequest($url, $headers = [])
    {
        $headers = array_merge([sprintf('Authorization: Bearer %s', $this->oauth_token)], $headers);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $output = curl_exec($ch);

        curl_close($ch);

        return $output;
    }

    /**
     * getOAuthToken method
     */
    protected function getOAuthToken()
    {
        $oAuth = $this->config->getValue('oauth');

        if (!$oAuth || $this->isExpiredToken($oAuth)) {
            return $this->fetchOauthToken();
        }

        return $oAuth[ 'token' ];
    }

    /**
     * isExpiredToken method
     *
     * @param $oAuth
     *
     * @return bool
     */
    protected function isExpiredToken($oAuth)
    {
        return date('Y-m-d', strtotime($oAuth[ 'expires_at' ])) < date('Y-m-d');
    }

    /**
     * fetchOauthToken method
     */
    protected function fetchOauthToken()
    {
        list($clientId, $clientSecret) = $this->config->getAuth();
        $url      = sprintf($this->oAuthUrl, $clientId, $clientSecret);
        $response = json_decode($this->doRequest($url));

        if (!$response->error) {
            $this->oauth_token = $response->access_token;
            $expiresAt         = \DateTime::createFromFormat('F, d Y H:i:s O', $response->expires_on);
            $this->config->setValue('oauth', ['token' => $response->access_token, 'expires_at' => $expiresAt->format('Y-m-d')]);

            return $response->access_token;
        }

        return false;
    }
}