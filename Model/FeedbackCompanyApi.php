<?php namespace Sebwite\FeedbackCompany\Model;

class FeedbackCompanyApi extends Request
{
    /** @var string */
    protected $reviewsUrl = 'https://beoordelingen.feedbackcompany.nl/api/v1/review/all/';

    /**
     * fetchReviews method
     *
     * @return array|\SimpleXMLElement
     */
    public function fetchReviewsAndScore()
    {
        $response = $this->doRequest($this->reviewsUrl);
        $response = json_decode($response, true);

        if (!$response[ 'success' ]) {
            return false;
        }

        $reviews      = $response[ 'data' ][ 0 ][ 'reviews' ];
        $totalReviews = $response[ 'data' ][ 0 ][ 'review_summary' ][ 'total_reviews' ];
        $avgScore     = round(((array_sum(array_column($reviews, 'total_score')) / $totalReviews) * 2), 1);
        $reviewData   = ['totalReviews' => $totalReviews, 'avgScore' => $avgScore];

        $this->config->setValue('reviews', $reviewData);

        return $reviewData;
    }

    /**
     * getReviewsAndScore method
     *
     * @return array|bool|\SimpleXMLElement
     */
    public function getReviewsAndScore()
    {
        if ($this->config->getValue('reviews')) {
            return $this->config->getValue('reviews');
        }

        return $this->fetchReviewsAndScore();
    }

    /**
     * getTotalReviews method
     *
     * @return mixed
     */
    public function getTotalReviews()
    {
        $reviewsAndScore = $this->getReviewsAndScore();

        return $reviewsAndScore[ 'totalReviews' ];
    }

    /**
     * getAvgScore method
     *
     * @return mixed
     */
    public function getAvgScore()
    {
        $reviewsAndScore = $this->getReviewsAndScore();

        return $reviewsAndScore[ 'avgScore' ];
    }
}