<?php

namespace events;


trait JiraUseful
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $guzzle = null;

    public function availableJiraStatuses($key)
    {
        $statuses       = \Yii::$app->jira->getStatuses($key);
        $current_status = \Yii::$app->jira->getStatus($key);

        foreach ($statuses as $k => $status) {
            if ($status['to']['id'] == $current_status['id']) {
                unset($statuses[$k]);
            }
        }

        return $statuses;
    }

    public function getAttachmentUrl(string $url)
    {
        if (null === $this->guzzle) {
            $this->guzzle = new \GuzzleHttp\Client(['allow_redirects' => ['track_redirects' => true]]);
        }

        $response = $this->guzzle->request('GET',  $url, [
            'auth' => [\Yii::$app->params['jiraEmail'], \Yii::$app->params['jiraToken']]]);
        return $response->getHeader(\GuzzleHttp\RedirectMiddleware::HISTORY_HEADER)[0];
    }

    public function prepareDescriptionWithImage(array $attachments, string &$text, bool $only_replace = false)
    {
        $_attachments = [];
        foreach ($attachments as $attachment) {
            if (strpos($attachment['mimeType'], 'image/') !== false) {
                $href = $this->getAttachmentUrl($attachment['content']);
                if (preg_match('/!'.$attachment['filename'].'.*!/', $text)) {
                    // "[$issue_key.$summary]($href)"
                    $text = preg_replace('/!'.$attachment['filename'].'.*!/', '['.$attachment['filename'].']('.$href.')', $text);
                } elseif (false === $only_replace) {
                    $_attachments[] = '['.$attachment['filename'].']('.$href.')';
                }
            }
        }

        if (!empty($_attachments)) {
            $text .= "\n*Вложения*\n" . implode("\n", $_attachments);
        }
    }
}