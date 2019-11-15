<?php
namespace App;


interface JiraHookAction
{
    const ISSUE_UPDATED         = 'jira:issue_updated';
    const ISSUE_CREATED         = 'jira:issue_created';
    const ISSUE_DELETED         = 'jira:issue_deleted';
    const ISSUE_DONE            = 'issue_done';
    const SUBISSUE_CREATED      = 'jira:subissue_created';
    const WORKLOG_CHANGED       = 'worklog_created';
    const WORKLOG_UPDATED       = 'worklog_updated';
    const WORKLOG_DELETED       = 'worklog_deleted';
    const ISSUE_LINK_CREATED    = 'issuelink_created';
    const ISSUE_LINK_DELETED    = 'issuelink_deleted';
    const COMMENT_CREATED       = 'comment_created';
    const COMMENT_UPDATED       = 'comment_updated';
    const COMMENT_DELETED       = 'comment_deleted';
    const PROJECT_CREATED       = 'project_created';
    const PROJECT_UPDATED       = 'project_updated';
    const PROJECT_DELETED       = 'project_deleted';
    const VERSION_RELEASED      = 'jira:version_released';
    const VERSION_UNRELEASED    = 'jira:version_unreleased';
    const VERSION_CREATED       = 'jira:version_created';
    const VERSION_MOVED         = 'jira:version_moved';
    const VERSION_UPDATED       = 'jira:version_updated';
    const VERSION_DELETED       = 'jira:version_deleted';
    const VERSION_MERGED        = 'jira:version_deleted';
    const USER_CREATED          = 'user_created';
    const USER_UPDATED          = 'user_updated';
    const USER_DELETED          = 'user_deleted';
    const SPRINT_CREATED        = 'sprint_created';
    const SPRINT_DELETED        = 'sprint_deleted';
    const SPRINT_UPDATED        = 'sprint_updated';
    const SPRINT_STARTED        = 'sprint_started';
    const SPRINT_CLOSED         = 'sprint_closed';
    const BOARD_CREATED         = 'board_created';
    const BOARD_UPDATED         = 'board_updated';
    const BOARD_DELTED          = 'board_deleted';
    const BOARD_CONF_CHANGED    = 'board_configuration_changed';

    const COMPONENT_CREATED     = 'component_created';
    const COMPONENT_UPDATED     = 'component_updated';
    const COMPONENT_DELETED     = 'component_deleted';

    const ASSIGNEE_CREATED      = 'assignee_created';
    const ASSIGNEE_DELETED      = 'assignee_deleted';
    const STATUS_UPDATED        = 'status_updated';
    const LABELS_CREATED        = 'labels_created';
    const LABELS_DELETED        = 'labels_deleted';
    const PRIORITY_UPDATED      = 'priority_updated';

    const DUEDATE_CREATED       = 'duedate_created';
    const DUEDATE_UPDATED       = 'duedate_updated';
    const DUEDATE_DELETED       = 'duedate_deleted';

    const REPORTER_CREATED      = 'reporter_created';

    const FIXVERSION_CREATED    = 'fixversion_created';
    const FIXVERSION_UPDATED    = 'fixversion_updated';
    const FIXVERSION_DELETED    = 'fixversion_deleted';

    const ESTIMATE_CREATED      = 'timeoriginalestimate_created';
    const ESTIMATE_UPDATED      = 'timeoriginalestimate_updated';
    const ESTIMATE_DELETED      = 'timeoriginalestimate_deleted';

    const SPRINT_ATTACH         = 'sprint_attach';
    const SPRINT_REATTACH       = 'sprint_reattach';
    const SPRINT_DETACH         = 'sprint_detach';

    const STORY_POINT_CREATED   = 'story_point_created';
    const STORY_POINT_UPDATED   = 'story_point_updated';
    const STORY_POINT_DELETED   = 'story_point_deleted';

    const DESCRIPTION_UPDATED   = 'description_updated';

    const ISSUE_TYPE_UPDATED    = 'issue_type';

    const SUMMARY_CREATED       = 'summary_created';
    const SUMMARY_UPDATED       = 'summary_updated';
}