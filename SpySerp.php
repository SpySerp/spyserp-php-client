<?php

namespace phpclient;

use http\Exception\InvalidArgumentException;

/**
 * The Spyserp API Client
 *
 * This client assists in making calls to spyserp.com API.
 *
 * @url https://spyserp.com
 *
 * @version 1.0.0
 */
class SpySerp
{
    /**
     * @var string $token The token for the spyserp.com API
     */
    private $token;

    /**
     * @var string $host The base URL to use for calls to the API
     */
    private $host = 'https://spyserp.com/panel/api';

    /**
     * @param string $token The token for the spyserp.com API
     * @param array $params Params to use for calls to the API
     */
    public function __construct($token, $params = false)
    {
        $this->setToken($token);
        if ($params) {
            if (isset($params['host'])) {
                $this->setHost($params['host']);
            }
        }
    }

    /**
     * @param $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @param string $url
     */
    public function setHost($url)
    {
        $this->host = $url;
    }

    /**
     * @throws InvalidArgumentException
     *
     * @return string
     */
    protected function getToken()
    {
        if (!is_string($this->token) && !strlen($this->token)) {
            throw new InvalidArgumentException('Current Token is incorrect!');
        }
        return $this->token;
    }

    /**
     * @throws InvalidArgumentException
     * @return string
     */
    protected function getHost()
    {
        if (!is_string($this->host) && !strlen($this->host)) {
            throw new InvalidArgumentException('Current URL is incorrect!');
        }
        return $this->host;
    }

    /**
     * Method for make request to Spyserp API
     *
     * @param string $method
     * @param array $data
     * @return array
     */
    private function makeRequest($method, $data = [])
    {
        try {
            $ch = curl_init($this->getHost());
            $request = [
                'method' => $method,
                'token' => $this->token
            ];
            if ($data) {
                $request = array_merge($request, $data);
            }

            $request_json = json_encode($request);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($request_json)));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain; charset=UTF-8'));

            $response = curl_exec($ch);
            curl_close($ch);
            if ($response === FALSE) {
                throw new \Exception(400, 'Response fail: ' . curl_error($ch));
            }
            $response = json_decode($response, TRUE);
            return $response;
        } catch (\Exception $e) {
            die('Error: ' . $e->getMessage() . "\n");
        }
    }

    /**
     * List of all search engines
     * @param boolean $withSettingValues If true - search engines with setting_values
     * @return array
     */
    public function searchEngines($withSettingValues = false)
    {
        $requestData = [
            'withSettingValues' => $withSettingValues,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * List of SE parameter values - for parameters with great number of values, for example - google_location
     *
     * @param string $key Title of search engine parameter
     * @param string $search Find from values of $key SE parameter by $search string
     * @return array
     */
    public function searchEnginesLoadSettings($key, $search = false)
    {
        $requestData = [
            'key' => $key,
            'search' => $search,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Return list of allowed types of conformities of yandex frequencies
     * @return array
     */
    public function projectFrequencyYandexConformity()
    {
        $data = $this->makeRequest(__FUNCTION__);
        return $data;
    }

    /**
     * Return current user balance in all used currencies
     *
     * @return array
     */
    public function balance()
    {
        $data = $this->makeRequest(__FUNCTION__);
        return $data;
    }

    /**
     * List of all user projects
     *
     * @return array
     */
    public function projects()
    {
        $data = $this->makeRequest(__FUNCTION__);
        return $data;
    }

    /**
     * List of all user messages
     *
     * @param integer $page
     * @param integer $pageSize
     * @return array
     */
    public function messages($page = false, $pageSize = false)
    {
        $requestData = [
            'page' => $page,
            'pageSize' => $pageSize,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * List of data of last schedules for all projects
     *
     * @return array
     */
    public function timeline()
    {
        $data = $this->makeRequest(__FUNCTION__);
        return $data;
    }

    /**
     * Return data of current user package
     *
     * @return array
     */
    public function package()
    {
        $data = $this->makeRequest(__FUNCTION__);
        return $data;
    }

    /**
     * Return data of project with id $project_id
     *
     * @param integer $project_id Project ID
     * @return array
     */
    public function project($project_id)
    {
        $requestData = [
            'project_id' => $project_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Create new project
     *
     * @param string $name Name for new project
     * @param bool $group_id Id of exist project group. If not set - project will be without group
     * @return array
     */
    public function projectCreate($name, $group_id = false)
    {
        $requestData = [
            'name' => $name,
            'group_id' => $group_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Delete exist project
     *
     * @param integer $project_id Project ID
     * @return array
     */
    public function projectDelete($project_id)
    {
        $requestData = [
            'project_id' => $project_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Disable active project
     *
     * @param integer $project_id Project ID
     * @return array
     */
    public function projectDisable($project_id)
    {
        $requestData = [
            'project_id' => $project_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Change group of project
     *
     * @param integer $project_id Project ID
     * @param integer $group_id Id of new group of project
     * @return array
     */
    public function projectGroupChange($project_id, $group_id)
    {
        $requestData = [
            'project_id' => $project_id,
            'group_id' => $group_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Rename project
     *
     * @param integer $project_id Project ID
     * @param string $name New project name
     * @return array
     */
    public function projectRename($project_id, $name)
    {
        $requestData = [
            'project_id' => $project_id,
            'name' => $name,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * List of projects settings
     *
     * @return array
     */
    public function projectsSettings()
    {
        $data = $this->makeRequest(__FUNCTION__);
        return $data;
    }

    /**
     * Change value of project setting
     *
     * @param string $setting Name of projects setting for change
     * @param string $value New value of $setting
     * @return array
     */
    public function projectsSettingsChange($setting, $value)
    {
        $requestData = [
            'setting' => $setting,
            'value' => $value,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Current value of projects setting
     *
     * @param string $setting Name of project setting
     * @return array
     */
    public function projectsSettingValues($setting)
    {
        $requestData = [
            'setting' => $setting
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }


    /**
     * List of all user groups of projects
     *
     * @param bool|integer $page
     * @param bool|integer $pageSize
     * @return array
     */
    public function groups($page = false, $pageSize = false)
    {
        $requestData = [
            'page' => $page,
            'pageSize' => $pageSize,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Add new group of projects
     *
     * @param string $group New group name
     * @return array
     */
    public function groupAdd($group)
    {
        $requestData = [
            'group' => $group
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Delete exist projects group
     *
     * @param integer $groupId Id of group for delete
     * @return array
     */
    public function groupDelete($groupId)
    {
        $requestData = [
            'groupId' => $groupId
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Change group title
     *
     * @param integer $groupId Id of group for update
     * @param string $groupName New name of group
     * @return array
     */
    public function groupUpdate($groupId, $groupName)
    {
        $requestData = [
            'groupId' => $groupId,
            'groupName' => $groupName,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Return all user limits
     *
     * @return array
     */
    public function limits()
    {
        $data = $this->makeRequest(__FUNCTION__);
        return $data;
    }

    /**
     * Return user limit by title $limit for project
     *
     * @param string $limit Limit title
     * @param $project_id
     * @return array
     */
    public function limit($limit, $project_id)
    {
        $requestData = [
            'limit' => $limit,
            'project_id' => $project_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * List of all projects, shared for current user
     *
     * @return array
     */
    public function sharedProjects()
    {
        $data = $this->makeRequest(__FUNCTION__);
        return $data;
    }

    /**
     * List of project search engines
     * @param integer $project_id Project ID
     * @return array
     */
    public function projectSearchEngines($project_id)
    {
        $requestData = [
            'project_id' => $project_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Project search engine data
     * @param integer $project_id Project ID
     * @param integer $project_se_id Project Search Engine ID
     * @return array
     */
    public function projectSearchEngine($project_id, $project_se_id)
    {
        $requestData = [
            'project_id' => $project_id,
            'project_se_id' => $project_se_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Add new search engine to project
     *
     * @param integer $project_id Project ID
     * @param array $engines Array of search engines with params for add to project
     * @return array
     */
    public function projectSearchEnginesAdd($project_id, $engines)
    {
        $requestData = [
            'project_id' => $project_id,
            'engines' => $engines,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Delete search engine relation from project
     *
     * @param integer $project_id Project ID
     * @param integer $relation_id Id of project search engine (You can get it with method projectSearchEngines)
     * @return array
     */
    public function projectSearchEnginesDelete($project_id, $relation_id)
    {
        $requestData = [
            'project_id' => $project_id,
            'relation_id' => $relation_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Change parameters of project search engines
     *
     * @param integer $project_id Project ID
     * @param integer $engine_id
     * @param array $settings Array of new search engine settings
     * @return array
     */
    public function projectSearchEnginesEdit($project_id, $engine_id, $settings)
    {
        $requestData = [
            'project_id' => $project_id,
            'engine_id' => $engine_id,
            'settings' => $settings,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }


    /**
     * List of domains of project
     *
     * @param integer $project_id Project ID
     * @param integer $page
     * @param integer $pageSize
     * @param integer $is_own Type of requested domains. If set - return only domains of this type
     * @return array
     */
    public function projectDomains($project_id, $page = false, $pageSize = false, $is_own = false)
    {
        $requestData = [
            'project_id' => $project_id,
            'page' => $page,
            'pageSize' => $pageSize,
            'is_own' => $is_own,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Add domain to project
     *
     * @param integer $project_id Project ID
     * @param array $domains Array of new domains for add to project
     * @return array
     */
    public function projectDomainsAdd($project_id, $domains)
    {
        $requestData = [
            'project_id' => $project_id,
            'domains' => $domains,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Delete domains from project
     *
     * @param integer $project_id Project ID
     * @param array $domain_ids Array of domain ids for delete from project
     * @return array
     */
    public function projectDomainsDelete($project_id, $domain_ids)
    {
        $requestData = [
            'project_id' => $project_id,
            'domain_ids' => $domain_ids,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Change type of domains
     *
     * @param integer $project_id Project ID
     * @param integer[] $domain_ids Array of domain ids for change type
     * @param bool|integer $type New type of domain - own (1) or competitor (0)
     * @return array
     */
    public function projectDomainsChangeType($project_id, $domain_ids, $type = false)
    {
        $requestData = [
            'project_id' => $project_id,
            'domain_ids' => $domain_ids,
            'type' => $type,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Collect new domains to project form statistics
     *
     * @param integer $project_id Project ID
     * @return array
     */
    public function projectDomainsCollect($project_id)
    {
        $requestData = [
            'project_id' => $project_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * List of project keywords
     *
     * @param integer $project_id Project ID
     * @param integer $category_id Find keywords by id of category
     * @param integer $withColumns Get keywords with all columns data
     * @param integer $page
     * @param integer $pageSize
     * @param string $search Find keywords by search string
     * @return array
     */
    public function projectKeywords($project_id, $category_id = false, $withColumns = false,
                                    $page = false, $pageSize = false, $key_ids = false, $search = false)
    {
        $requestData = [
            'project_id' => $project_id,
            'category_id' => $category_id,
            'withColumns' => $withColumns,
            'page' => $page,
            'pageSize' => $pageSize,
            'key_ids' => $key_ids,
            'search' => $search,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * @param integer $project_id Project ID
     * @param array $keywords Array of keywords for add to project (no more than 1000 elements in array)
     * @param integer $category_id Category id for new keywords
     * @param integer $skipFailed If equal 1 - keywords with errors wil not be add to project
     * @return array
     */
    public function projectKeywordsAdd($project_id, $keywords, $category_id = false, $skipFailed = false)
    {
        $requestData = [
            'project_id' => $project_id,
            'keywords' => $keywords,
            'category_id' => $category_id,
            'skipFailed' => $skipFailed,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Delete keywords from project
     *
     * @param integer $project_id Project ID
     * @param integer[] $keyword_ids Array of keywords ids for delete
     * @return array
     */
    public function projectKeywordsDelete($project_id, $keyword_ids)
    {
        $requestData = [
            'project_id' => $project_id,
            'keyword_ids' => $keyword_ids,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Rename keywords of project
     *
     * @param integer $project_id Project ID
     * @param array $keywords Array of keywords for rename.Every element of array must contain key_id and keyword
     * @return array
     */
    public function projectKeywordsRename($project_id, $keywords)
    {
        $requestData = [
            'project_id' => $project_id,
            'keywords' => $keywords,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Change category for project keywords
     *
     * @param integer $project_id Project ID
     * @param integer[] $keyword_ids Array of keywords ids for change category
     * @param integer $category_id Id of new category of keywords
     * @return array
     */
    public function projectKeywordsChangeCategory($project_id, $keyword_ids, $category_id)
    {
        $requestData = [
            'project_id' => $project_id,
            'keyword_ids' => $keyword_ids,
            'category_id' => $category_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Change title of project column
     *
     * @param integer $project_id Project ID
     * @param array $keywords Array of keywords for change column data
     * @return array
     */
    public function projectKeywordsColumnsEdit($project_id, $keywords)
    {
        $requestData = [
            'project_id' => $project_id,
            'keywords' => $keywords,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Delete all keywords from project
     *
     * @param integer $project_id Project ID
     * @return array
     */
    public function projectKeywordsDeleteAll($project_id)
    {
        $requestData = [
            'project_id' => $project_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Return all links for schedule, SE and keyword
     *
     * @param integer $project_id Project ID
     * @return array
     */
    public function projectKeywordsPick($project_id, $key, $schedule_id, $se)
    {
        $requestData = [
            'project_id' => $project_id,
            'key' => $key,
            'schedule_id' => $schedule_id,
            'se' => $se,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * List of project keywords categories
     *
     * @param integer $project_id Project ID
     * @param integer $page
     * @param integer $pageSize
     * @return array
     */
    public function projectCategories($project_id, $page = false, $pageSize = false)
    {
        $requestData = [
            'project_id' => $project_id,
            'page' => $page,
            'pageSize' => $pageSize,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Add categories to project
     *
     * @param integer $project_id Project ID
     * @param string[] $categories Array of categories for add to project
     * @return array
     */
    public function projectCategoriesAdd($project_id, $categories)
    {
        $requestData = [
            'project_id' => $project_id,
            'categories' => $categories,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Delete categories from project
     *
     * @param integer $project_id Project ID
     * @param array $category_ids Array of category ids for delete
     * @return array
     */
    public function projectCategoriesDelete($project_id, $category_ids)
    {
        $requestData = [
            'project_id' => $project_id,
            'category_ids' => $category_ids,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Edit project categories
     *
     * @param integer $project_id Project ID
     * @param integer $category_id Id of category for edit
     * @param string $categoryTitle New category title
     * @param bool|integer $default_category If 1 - set current category as default
     * @return array
     */
    public function projectCategoriesEdit($project_id, $category_id, $categoryTitle, $default_category = false)
    {
        $requestData = [
            'project_id' => $project_id,
            'category_id' => $category_id,
            'category' => $categoryTitle,
            'default_category' => $default_category,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * List of project columns data
     *
     * @param integer $project_id Project ID
     * @param integer $page
     * @param integer $pageSize
     * @param integer $withFilled Get project columns with count of filled keywords
     * @return array
     */
    public function projectColumns($project_id, $page = false, $pageSize = false, $withFilled = false)
    {
        $requestData = [
            'project_id' => $project_id,
            'page' => $page,
            'pageSize' => $pageSize,
            'withFilled' => $withFilled,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Add columns to project
     *
     * @param integer $project_id Project ID
     * @param array $column Array of data of new column
     * @return array
     */
    public function projectColumnsAdd($project_id, $column)
    {
        $requestData = [
            'project_id' => $project_id,
            'column' => $column,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Get exist available columns to add for project (exist in other projects or common columns)
     *
     * @param integer $project_id Project ID
     * @return array
     */
    public function projectColumnsAvailable($project_id)
    {
        $requestData = [
            'project_id' => $project_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Delete columns from project
     *
     * @param integer $project_id Project ID
     * @param integer $column_id Id of column for delete from project
     * @return array
     */
    public function projectColumnsDelete($project_id, $column_id)
    {
        $requestData = [
            'project_id' => $project_id,
            'column_id' => $column_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Edit
     * @param integer $project_id Project ID
     * @param integer $column_id
     * @param array $column Array with data of new column
     * @return array
     */
    public function projectColumnsEdit($project_id, $column_id, $column)
    {
        $requestData = [
            'project_id' => $project_id,
            'column_id' => $column_id,
            'column' => $column,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Hide project column on statistic page
     *
     * @param integer $project_id Project ID
     * @param integer $column_id Id of column for hide on statistic page
     * @return array
     */
    public function projectColumnsHide($project_id, $column_id)
    {
        $requestData = [
            'project_id' => $project_id,
            'column_id' => $column_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Autocollect relevant urls from statistic or start collect frequencies for frequency columns
     * @param integer $project_id Project ID
     * @param integer $column_id Id of column for start autocollect keyword data
     * @return array
     */
    public function projectColumnsAutocollect($project_id, $column_id)
    {
        $requestData = [
            'project_id' => $project_id,
            'column_id' => $column_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * List of project schedules from last to old
     *
     * @param integer $project_id Project ID Project ID
     * @param integer $page
     * @param integer $pageSize
     * @return array
     */
    public function projectSchedules($project_id, $page = false, $pageSize = false)
    {
        $requestData = [
            'project_id' => $project_id,
            'page' => $page,
            'pageSize' => $pageSize,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Data of project schedule
     *
     * @param integer $project_id Project ID
     * @param integer $schedule_id
     * @return array
     */
    public function projectSchedule($project_id, $schedule_id)
    {
        $requestData = [
            'project_id' => $project_id,
            'schedule_id' => $schedule_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Save some setting for project
     *
     * @param integer $project_id Project ID
     * @param string $setting Setting name
     * @param array $value Setting value
     * @return array
     */
    public function projectSaveSetting($project_id, $setting, $value)
    {
        $requestData = [
            'project_id' => $project_id,
            'setting' => $setting,
            'value' => $value,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * List of emails for which project is shared
     * @param integer $project_id Project ID
     * @return array
     */
    public function projectSharedEmails($project_id)
    {
        $requestData = [
            'project_id' => $project_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Create new public url for access to project
     *
     * @param integer $project_id Project ID
     * @return array
     */
    public function projectSharedUrlCreate($project_id)
    {
        $requestData = [
            'project_id' => $project_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Get current public url of project
     *
     * @param integer $project_id Project ID
     * @return array
     */
    public function projectSharedUrlGet($project_id)
    {
        $requestData = [
            'project_id' => $project_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Share project for user by email
     *
     * @param integer $project_id Project ID
     * @param string $email Email of user for share project
     * @return array
     */
    public function projectSharedUser($project_id, $email)
    {
        $requestData = [
            'project_id' => $project_id,
            'email' => $email,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Remove email from lists of email for which project is shared
     *
     * @param integer $project_id Project ID
     * @param integer $user_id Id of user for delete from list of project shared emails
     * @return array
     */
    public function projectSharedUserDelete($project_id, $user_id)
    {
        $requestData = [
            'project_id' => $project_id,
            'user_id' => $user_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * @param integer $project_id Project ID
     * @param array $conformities Array of types of conformities of Yandex frequencies
     * @param integer $skipExist If 1 - skip collect frequencies for keywords with data
     * @param integer $category Collect frequencies for keywords of category, if $category=-1 - for all categories
     * @param array $regions Array of ids of yandex regions for collect frequencies
     * @return array
     */
    public function projectFrequencyYandexRun($project_id, $conformities, $category, $regions = false, $skipExist = false)
    {
        $requestData = [
            'project_id' => $project_id,
            'conformities' => $conformities,
            'skipExist' => $skipExist,
            'category' => $category,
            'regions' => $regions,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * List of project frequency columns
     *
     * @param integer $project_id Project ID
     * @return array
     */
    public function projectFrequencyColumns($project_id)
    {
        $requestData = [
            'project_id' => $project_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * List of project yandex regions
     *
     * @param integer $project_id Project ID
     * @return array
     */
    public function projectFrequencyYandexRegions($project_id)
    {
        $requestData = [
            'project_id' => $project_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Add yandex region to project regions
     *
     * @param integer $project_id Project ID
     * @param integer $region_id
     * @return array
     */
    public function projectFrequencyYandexRegionsAdd($project_id, $region_id)
    {
        $requestData = [
            'project_id' => $project_id,
            'region_id' => $region_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Delete yandex region from project regions
     *
     * @param integer $project_id Project ID
     * @param integer $region_id
     * @return array
     */
    public function projectFrequencyYandexRegionsDelete($project_id, $region_id)
    {
        $requestData = [
            'project_id' => $project_id,
            'region_id' => $region_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * List of project tasks of frequency collection
     *
     * @param integer $project_id Project ID
     * @param integer|boolean $page
     * @param integer|boolean $pageSize
     * @return array
     */
    public function projectFrequencyTasks($project_id, $page = false, $pageSize = false)
    {
        $requestData = [
            'project_id' => $project_id,
            'page' => $page,
            'pageSize' => $pageSize,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Return schedules, that are currently being processed
     *
     * @param integer $project_id Project ID
     * @return array
     */
    public function projectActiveSchedule($project_id)
    {
        $requestData = [
            'project_id' => $project_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }

    /**
     * Return tasks, that are currently being processed
     *
     * @param $project_id Project ID
     * @return array
     */
    public function projectActiveTasks($project_id)
    {
        $requestData = [
            'project_id' => $project_id,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }


    /**
     * Return array with statistic data for current request settings
     *
     * @param integer $project_id Project ID
     * @param integer|bool $se Search engine ID or 0 for all
     * @param integer|bool $domain Domain ID or 0 for all
     * @param string|bool $show Show keywords with positions or all lkeywords
     * @param integer|bool $start_date Timestamp of start of time interval
     * @param integer|bool $end_date Timestamp of end of time interval
     * @param string|bool $schedules String with schedule ids separated with comma
     * @param integer|bool $category Category ID or -1 for all
     * @param string|bool $search Search string for search by keyword
     * @param integer|bool $page Page number. Start from 0
     * @param string|bool $top Search only keywords with position >= then "top". Can be equal 3, 10 or 30. "All" for all positions
     * @param integer|bool $sort Flag for sort keywords
     * @param integer|bool $withcat Display keywords with categories or without
     * @param integer|bool $pageSize Size of page for schedule
     * @param integer|bool $keyPage Page for pagination by keywords
     * @param integer|bool $withLinks Display links in statistic
     * @param integer|bool $withAnchors Display anchors in statistic
     * @param integer|bool $withSnippets Display snippets in statistic
     *
     * @return array
     */
    public function statistic(
        $project_id,
        $domain,
        $se = false,
        $show = false,
        $start_date = false,
        $end_date = false,
        $schedules = false,
        $category = false,
        $search = false,
        $page = false,
        $top = false,
        $sort = false,
        $withcat = false,
        $pageSize = false,
        $keyPage = false,

        $withLinks = false,
        $withAnchors = false,
        $withSnippets = false
    )
    {
        $requestData = [
            'project_id' => $project_id,
            'se' => $se,
            'domain' => $domain,
            'show' => $show,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'schedules' => $schedules,
            'category' => $category,
            'search' => $search,
            'page' => $page,
            'top' => $top,
            'sort' => $sort,
            'withcat' => $withcat,
            'pageSize' => $pageSize,
            'keyPage' => $keyPage,
            'withLinks' => $withLinks,
            'withAnchors' => $withAnchors,
            'withSnippets' => $withSnippets,
        ];
        $data = $this->makeRequest(__FUNCTION__, $requestData);
        return $data;
    }
}