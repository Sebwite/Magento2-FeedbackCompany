<?php namespace Sebwite\FeedbackCompany\Block;

use Magento\Framework\View\Element\Template;
use Sebwite\FeedbackCompany\Model\FeedbackCompanyApi;

class FCTemplate extends \Magento\Framework\View\Element\Template
{
    /** @var \Sebwite\FeedbackCompany\Model\FeedbackCompanyApi */
    protected $feedbackCompanyApi;

    /**
     * FCTemplate constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param \Sebwite\FeedbackCompany\Model\FeedbackCompanyApi $feedbackCompanyApi
     * @param array                                             $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        FeedbackCompanyApi $feedbackCompanyApi,
        array $data)
    {
        parent::__construct($context, $data);
        
        $this->feedbackCompanyApi = $feedbackCompanyApi;
    }

    /**
     * getTotalReviews method
     *
     * @return mixed
     */
    public function getTotalReviews()
    {
        return $this->feedbackCompanyApi->getTotalReviews();
    }

    /**
     * getAvgScore method
     *
     * @return mixed
     */
    public function getAvgScore()
    {
        return $this->feedbackCompanyApi->getAvgScore();
    }
}