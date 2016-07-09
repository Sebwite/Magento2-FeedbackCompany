<?php namespace Sebwite\FeedbackCompany\Model;

class Cron
{
    /** @var \Magento\Framework\App\Config\ScopeConfigInterface */
    protected $config;

    /** @var \Sebwite\FeedbackCompany\Model\FeedbackCompanyApi */
    private $feedbackCompanyApi;

    /**
     * Cron constructor.
     *
     * @param \Sebwite\FeedbackCompany\Model\Config             $config
     * @param \Sebwite\FeedbackCompany\Model\FeedbackCompanyApi $feedbackCompanyApi
     */
    public function __construct(
        Config $config,
        FeedbackCompanyApi $feedbackCompanyApi
    ) {
        $this->config = $config;
        $this->feedbackCompanyApi = $feedbackCompanyApi;
    }

    public function runCron()
    {
        if($this->config->isEnabled()) {
            $this->feedbackCompanyApi->fetchReviewsAndScore();
        }
    }
}