<?php
namespace App;


class JiraApi
{
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';
    /**
     * @var string
     */
    private $jira_url;

    /**
     * @var string
     */
    private $jira_user;

    /**
     * @var string
     */
    private $jira_password;

    /**
     * @var string
     */
    private $auth;

    /**
     * Input unencoded data
     * @var string
     */
    private $raw_data;

    /**
     * Input data
     * @var array
     */
    private $data = [];


    private function __construct(string $url)
    {
        $this->jira_url = $url;
    }

    public static function cloud(string $url, string $email, string $token): self
    {
        $api = new self($url);
        $api->jira_user = $email;
        $api->jira_password = $token;

        $api->auth = base64_encode("{$api->jira_user}:{$api->jira_password}");
        return $api;
    }

    public static function server(string $url, string $username, string $password): self
    {
        $api = new self($url);
        $api->jira_user = $username;
        $api->jira_password = $password;

        $api->auth = base64_encode("{$api->jira_user}:{$api->jira_password}");
        return $api;
    }

    public function getIssueLinkTypes()
    {
        return $this->endpoint('issueLinkType');
    }

    public function getIssueLinkType($type_id)
    {
        return $this->endpoint('issueLinkType/'. $type_id);
    }

    public function linkIssues()
    {
        return $this->endpoint("issueLink", [], self::POST);
    }

    public function getRemoteIssueLinks($issueIdOrKey)
    {
        return $this->endpoint("issue/{$issueIdOrKey}/remotelink");
    }

    public function createIssueBetweenTwoIssues(string $type, $in, $out)
    {
        $content = [
            'update' => [
                'issuelinks' => [
                    [
                        'add' => [
                            'type' => [
                                'name' => $type
                            ],
                            'inwardIssue' => [
                                'key' => $in
                            ],
                        ]
                    ]

                ]
            ]
        ];
        return $this->endpoint("issue/{$out}", $content, self::PUT);
    }


    public function getIssueAllTypes()
    {
        return $this->endpoint('issuetype');
    }

    public function getComponents($project_id_or_key): array
    {
        return $this->endpoint("project/{$project_id_or_key}/components");
    }


    public function addComponentToIssue($issue_id_or_key, $component_id)
    {
        $components = $this->getIssueComponents($issue_id_or_key);
        $data = [];
        foreach ($components as $component) {
            $data[] = ['id' => "" . $component['id']];
        }
        $data[] = ['id' => "" . $component_id];
        $data = [
            "fields" => [
                "components" =>
                    $data
            ]
        ];

        return $this->endpoint("issue/$issue_id_or_key", $data, self::PUT);
    }

    public function getIssueComponents($issue_id_or_key)
    {
        $issue = $this->getIssue($issue_id_or_key);
        return $issue['fields']['components'];
    }

    public function deleteIssueComponent($issue_id_or_key, $component_index)
    {
        $components = $this->getIssueComponents($issue_id_or_key);
        unset($components[$component_index]);
        if (empty($components)) {
            $components = [];
        }

        $data = [
            'fields' => [
                "components" => $components
            ]
        ];
        return $this->endpoint("issue/$issue_id_or_key", $data, self::PUT);
    }

    public function assign(string $issue_id_or_key, string $name)
    {
        return $this->endpoint("issue/{$issue_id_or_key}/assignee", compact('name'), self::PUT);
    }

    public function removeAssign(string $issue_id_or_key)
    {
        $name = null;
        return $this->endpoint("issue/{$issue_id_or_key}/assignee", compact('name'), self::PUT);
    }

    public function getAttachment($attachment_id)
    {
        return $this->endpoint('attachment/'.$attachment_id);
    }

    public function getMyself()
    {
        return $this->endpoint('myself');
    }

    public function getUserByEmail(string $email)
    {
        $user = $this->endpoint('user/search?username=' . $email);
        return $user[0];
    }

    public function getUser(string $key)
    {
        $user = $this->endpoint('user', compact('key'));
        return $user;
    }

    public function getUserByUsername(string $username)
    {
        $user = $this->endpoint('user', compact('username'));
        return $user;
    }

    public function updateUser(string $key)
    {
        $data = [
            'displayName' => 'Иван Иванов'
        ];
        return $this->endpoint("user?key={$key}", $data, self::PUT);
    }

    public function getEstimate($issue_id_or_key)
    {
        $issue = $this->getIssue($issue_id_or_key);
        return (string)$issue['fields']['timeestimate'];
    }

    public function deleteStoryPoint($issue_id_or_key)
    {
        return $this->setStoryPoint($issue_id_or_key, '');
    }

    public function getStoryPoint($issue_id_or_key)
    {
        $issue = $this->getIssue($issue_id_or_key);
        return $issue['fields'][\CustomFieldGlobal::STORYPOINT] ?? '';
    }

    public function setStoryPoint($issue_id_or_key, $story_point)
    {
        $data = [
            'fields' => [
                \CustomFieldGlobal::STORYPOINT => empty($story_point)? null : intval($story_point)
            ]
        ];

        return $this->endpoint("issue/{$issue_id_or_key}", $data, self::PUT);
    }

    public function deleteOriginalEstimate($issue_id_or_key)
    {
        return $this->setOriginalEstimate($issue_id_or_key, null);
    }

    public function setTimeSpent($issue_id_or_key, $timespent_in_second)
    {
        $data = [
            'timeSpentSeconds' => $timespent_in_second
        ];

        return $this->endpoint("issue/{$issue_id_or_key}/worklog", $data, self::POST);

    }

    public function setOriginalEstimate($issue_id_or_key, $original_estimate)
    {
        $data = [
            'fields' => [
                'timetracking' => [
                    "originalEstimate" => $original_estimate,
                ]
            ]
        ];

        return $this->endpoint("issue/{$issue_id_or_key}", $data, self::PUT);
    }

    public function getAllUsers()
    {
        return $this->endpoint('user/search?startAt=0&maxResults=1000&username=');
    }

    public function getAllIssues()
    {
        $start = 0;
        $max_results = 100;

        $issues = [];
        $count = 0;

        do {
            $result = $this->endpoint('search?startAt='.$start.'&maxResults='.$max_results.'&project=');
            $start += ($max_results - 1);
            $issues = array_merge($issues, $result['issues']);
           /* foreach ($result['issues'] as $issue) {
                $issues[$issue['key']] = $issue;
            }*/
            $count++;
        } while(!empty($result['issues'] and $count < 10) );
        return $issues;
    }

    public function setPriority($issue_key_or_id, int $priority)
    {

        $data = [
            'fields' => [
                'priority' => [
                    'id' => (string)$priority
                ]
            ]
        ];
        return $this->endpoint("issue/{$issue_key_or_id}", $data, self::PUT);
    }

    public function setDuedate($issue_key_or_id, string $duedate)
    {
        $data = [
            'fields' => [
                'duedate' => $duedate
            ]
        ];
        return $this->endpoint("issue/{$issue_key_or_id}", $data, self::PUT);
    }

    public function getDuedate($issue_key_or_id)
    {
        $issue = $this->getIssue($issue_key_or_id);
        return $issue['fields']['duedate'];
    }

    public function getBoards()
    {
        $result = $this->endpointAgile("board");
        $boards = [];

        foreach ($result['values'] as $item) {
            $project_key = $item['location']['projectKey'];
            $boards[$project_key] = $item;
        }

        return $boards;
    }

    public function getBoard($board_id)
    {
        return $this->endpointAgile("board/{$board_id}");
    }

    public function getIssuesForSprintAndBoard($board_id, $sprint_id)
    {
        return $this->endpointAgile("board/{$board_id}/sprint/{$sprint_id}/issue");
    }

    public function getIssuesForSprint($sprint_id)
    {
        $result = $this->endpointAgile("sprint/{$sprint_id}/issue?maxResults=400");

        return $result['issues'];
    }

    public function getIssuesForBoard($board_id)
    {
        $result = $this->endpointAgile("board/{$board_id}/issue");

        $issues = [];
        foreach ($result['issues'] as $issue){
            $issues[$issue['key']] = $issue;
        }
        return $issues;
    }

    public function getSprints($board_id)
    {
        $result =  $this->endpointAgile("board/{$board_id}/sprint");
        return $result['values'];
    }

    public function getIssuesForBacklog($board_id)
    {
        $result = $this->endpointAgile("board/{$board_id}/backlog");

        $issues = [];
        foreach ($result['issues'] as $issue){
            $issues[$issue['key']] = $issue;
        }

        return $issues;
    }

    public function createSprint($originBoardId, string $name, $startDate = '', $endDate = '')
    {

        $data = compact('name', 'startDate', 'endDate', 'originBoardId');
        return $this->endpointAgile("sprint", $data, self::POST);
    }

    public function getSprint($sprint_id)
    {
        return $this->endpointAgile("sprint/{$sprint_id}");
    }

    public function updateSprint($sprint_id, array $data)
    {
        return $this->endpointAgile("sprint/{$sprint_id}", $data, self::PUT);
    }

    public function moveIssueToSprint($sprint_id, $issue_id_or_key)
    {
        $issues = [$issue_id_or_key];
        return $this->endpointAgile("sprint/{$sprint_id}/issue", compact('issues'), self::POST);
    }

    public function moveIssueToBacklog($issue_id_or_key)
    {
        $issues = [$issue_id_or_key];
        return $this->endpointAgile("backlog/issue",  compact('issues'), self::POST);
    }

    public function partiallyUpdateSprint($sprint_id, array $data)
    {
        return $this->endpointAgile("sprint/{$sprint_id}", $data, self::POST);
    }

    public function deleteSprint($sprint_id)
    {
        return $this->endpointAgile("sprint/{$sprint_id}", [], self::DELETE);
    }

    public function getAllStatuses()
    {
        $result = $this->endpoint("status");
        return $result;
    }

    public function getStatuses($issue_id_or_key)
    {
        $transitions =  $this->endpoint("issue/{$issue_id_or_key}/transitions");
        return $transitions['transitions'];
    }

    public function setStatus($issue_id_or_key, int $id)
    {
        $transition = [
            'id' => $id
        ];

        return $this->endpoint("issue/$issue_id_or_key/transitions", compact('transition'), self::POST);
    }

    public function addLabel($issue_id_or_key, string $label)
    {
        $labels = $this->getLabels($issue_id_or_key);
        $labels[] = $label;
        $data = [
            'fields' => [
                'labels' => $labels
            ]
        ];

        return $this->endpoint("issue/$issue_id_or_key", $data, self::PUT);
    }

    public function addComment($key, string $text)
    {
        $data = [
            "body" =>  $text,
           /* "visibility" => [
                "type" => "role",
                "value" => "Administrators"
            ]*/
        ];


        return $this->endpoint("issue/$key/comment", $data, self::POST);
    }

    public function getTimeTracking($issue_id_or_key)
    {
        $issue = $this->getIssue($issue_id_or_key);
        return $issue['fields']['timetracking'];
    }

    public function isIssueInSprint($issue_id_or_key)
    {
        $issue = $this->getIssueAgile($issue_id_or_key);
        return empty($issue['fields']['sprint']);
    }

    public function deleteLabel($issue_id_or_key, $new_labels)
    {
        $data = [
            'fields' => [
                'labels' => array_values($new_labels)
            ]
        ];
        return $this->endpoint("issue/$issue_id_or_key", $data, self::PUT);
    }

    public function getAllLabels() : array
    {
        $result = $this->endpoint("jql/autocompletedata/suggestions?fieldName=labels");
        return $result['results'] ?? [];
    }

    public function getLabels($issue_id_or_key): array
    {
        $issue = $this->getIssue($issue_id_or_key);
        $labels = $issue['fields']['labels'];
        return $labels;
    }

    public function getProjectForIssue($issue_id_or_key)
    {
        $issue = $this->getIssue($issue_id_or_key);
        return $issue['fields']['project']['id'];
    }

    public function getPriorities()
    {
        return $this->endpoint('priority');
    }

    public function addVersion($issue_id_or_key, string $version)
    {
        $project_id = $this->getProjectForIssue($issue_id_or_key);
        $data = [
            'name' => $version,
            'projectId' => $project_id
        ];
        return $this->endpoint("version", $data, self::POST);
    }

    public function getProjectVersions($project_id_or_key)
    {
        return $this->endpoint("project/{$project_id_or_key}/versions");
    }

    public function addFixVersion($issue_key_or_id, string $version)
    {
        $result = $this->addVersion($issue_key_or_id, $version);

        $data = [
            'update' => [
                'fixVersions' => [
                    [
                        'add' => [
                            'name' => $version
                        ]
                    ]
                ]
            ]
        ];
        return $this->endpoint("issue/$issue_key_or_id", $data, self::PUT);
    }

    public function setFixVersion($issue_key_or_id, $version)
    {
        $result = $this->addVersion($issue_key_or_id, $version);

        $data = [
            'update' => [
                'fixVersions' => [
                    [
                        'set' => [
                            [
                                'name' => $version
                            ]
                        ]
                    ]
                ]
            ]
        ];
        return $this->endpoint("issue/$issue_key_or_id", $data, self::PUT);
    }

    public function deleteFixVersion($issue_key_or_id, string $version)
    {
        $data = [
            'update' => [
                'fixVersions' => [
                    [
                        'remove' => [
                            'name' => $version
                        ]
                    ]
                ]
            ]
        ];
        return $this->endpoint("issue/$issue_key_or_id", $data, self::PUT);
    }

    public function getFixVersions($issue_key_or_id)
    {
        $issue = $this->getIssue($issue_key_or_id);
        return $issue['fields']['fixVersions'];
    }

    public function getVersions($issue_key_or_id)
    {
        $issue = $this->getIssue($issue_key_or_id);
        return $issue['fields']['versions'];
    }

    public function getPriority($issue_id_or_key)
    {
        $issue = $this->getIssue($issue_id_or_key);
        $priority = [
            'id' => $issue['fields']['priority']['id'],
            'name' => $issue['fields']['priority']['name']
        ];
        return $priority;
    }

    public function getStatus($issue_id_or_key)
    {
        $issue = $this->getIssue($issue_id_or_key);
        $status =  $issue['fields']['status'];
        return $status;
    }

    public function createIssue(string $project_key, string $summary, $component_id, string $description = '', string $issue_type = 'Task')
    {
        $data = [
            'fields' => [
                'project' => [
                    'key' => $project_key
                ],
                'summary'     => $summary,
                'description' => $description,
                'issuetype'   => [
                    'name' => $issue_type
                ],
                'components' => [
                    ['id' => ''.$component_id]
                ]
            ]
        ];
        $result = $this->endpoint("issue", $data, self::POST);
        return $result;
    }

    public function getIssue($issue_id_or_key)
    {
        return $this->endpoint("issue/{$issue_id_or_key}");
    }

    public function getIssueAgile($issue_id_or_key)
    {
        return $this->endpointAgile("issue/{$issue_id_or_key}");
    }

    /**
     * @param string $key
     * @param array $content
     * @return mixed
     * \params:
     *   startAt (long) - the page offset, if not specified then defaults to 0
     *   maxResults (int) - how many results on the page should be included. Defaults to 50
     *   orderBy (string) - ordering of the results
     *   expand (string) - optional flags: renderedBody (provides body rendered in HTML)
     */
    public function getComments($key, array $content = [])
    {
        $comments =  $this->endpoint("issue/$key/comment", $content);
        return $comments['comments'];
    }

    public function getCommentProperties($comment_id)
    {
        return $this->endpoint("comment/{$comment_id}/properties");
    }

    public function getRawData()
    {
        return $this->raw_data;
    }

    public function getData()
    {
        if (empty($this->data)) {
            $raw_data = file_get_contents('php://input');
            $this->raw_data = $raw_data;
            return json_decode($raw_data, true);
        } else {
            return $this->data;
        }
    }

    public function endpointAgile($api, array $content = [], $method = self::GET)
    {
        $url = $this->jira_url . '/rest/agile/latest/' . $api;

        $reply = $this->sendAPIRequest($url, $content, $method);

        return json_decode($reply, true);
    }

    public function endpoint($api, array $content = [], $method = self::GET)
    {
        $url = $this->jira_url . '/rest/api/latest/' . $api;

        $reply = $this->sendAPIRequest($url, $content, $method);

        return json_decode($reply, true);
    }

    private function sendAPIRequest($url, array $content = [], $method = self::GET)
    {
        if(($method == self::GET) && !empty($content)){
            $url .= '?'.http_build_query($content);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        switch ($method){
            case self::POST:
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($content));
                break;
            case self::PUT:
            case self::DELETE:
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($content));
                break;
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER,['Content-Type: application/json', "Authorization: Basic {$this->auth}"]);
        $result = curl_exec($ch);
        if ($result === false) {
            $result = json_encode(['ok'=>false, 'curl_error_code' => curl_errno($ch), 'curl_error' => curl_error($ch)]);
        }
        curl_close($ch);

        return $result;
    }


}