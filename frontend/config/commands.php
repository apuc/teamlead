<?php


return [
    'отменить поиск'       => '/cancel_in_reply',
    'отмена'               => '/cancel_in_reply',
    'прервать'             => '/interrupt',
    'завершить общение'    => '/speak_end',
    'завершить и '.
    'сохранить беседу'     => '/speak_save_end',
    'открыть меню'         => \Longman\TelegramBot\Commands\UserCommands\IssuepanelCommand::class,

    'сменить задачу'       => '/change_issue',

    'все задачи'           => '/all_issues',

    'пропустить для этой'  => '/skip_planning',

    'детали задачи'        => \Longman\TelegramBot\Commands\UserCommands\IssuedetailsCommand::class,

    'задачи'               => \Longman\TelegramBot\Commands\UserCommands\IssuesCommand::class,
    'планирование'         => '/planning',
    'настройки'            => '/settings',
    'мои задачи'           => \Longman\TelegramBot\Commands\UserCommands\MyissuesCommand::class,
    'текущие задачи'       => '/current_issues',

    'приход'               => '/in',
    'уход'                 => '/out',

    'создать задачу'       => 'create_issue',

    'подтвердить создание' => 'create_issue_confirm',
    'отменить создание'    => 'cancel_create',

    'поиск задачи'         => '/search_issues_menu',
    'по проектам'          => '/allbypr',
    'по исполнителям'      => '/allbyve',
    'по релизам'           => '/allbyve',
    'по меткам'            => '/allbyla',
    'по спринтам'          => '/allbysp',
    'поиск по названию'    => '/search_issue',
    'повторить поиск'      => '/research_issue',

    'метки'                => \Longman\TelegramBot\Commands\UserCommands\LabelsCommand::class,
    'текущие метки'        => \Longman\TelegramBot\Commands\UserCommands\CurrentlabelsCommand::class,
    'добавить метку'       => \Longman\TelegramBot\Commands\UserCommands\AddlabelCommand::class,
    'удалить метку'        => \Longman\TelegramBot\Commands\UserCommands\DeletelabelCommand::class,
    'создать метку'        => \Longman\TelegramBot\Commands\UserCommands\CreatelabelCommand::class,

    'срок выполнения'            => \Longman\TelegramBot\Commands\UserCommands\DuedateCommand::class,
    'установить срок выполнения' => \Longman\TelegramBot\Commands\UserCommands\SetduedateCommand::class,
    'изменить срок выполнения'   => \Longman\TelegramBot\Commands\UserCommands\SetduedateCommand::class,
    'удалить срок '.
    'выполнения'           => \Longman\TelegramBot\Commands\UserCommands\DeleteduedateCommand::class,

    'версии'               => \Longman\TelegramBot\Commands\UserCommands\VersionsCommand::class,
    'добавить версию'      => \Longman\TelegramBot\Commands\UserCommands\AddversionCommand::class,
    'создать версию'       => \Longman\TelegramBot\Commands\UserCommands\CreateversionCommand::class,
    'удалить версию'       => \Longman\TelegramBot\Commands\UserCommands\DeleteversionCommand::class,

    'исполнители'          => \Longman\TelegramBot\Commands\UserCommands\AssigneesCommand::class,
    'назначить исполнителя'=> \Longman\TelegramBot\Commands\UserCommands\SetAssigneeCommand::class,
    'переназначить исполнителя'=> \Longman\TelegramBot\Commands\UserCommands\SetAssigneeCommand::class,
    'назначить отложенно'  => '/assignee_delay',
    'снять исполнителя'    => \Longman\TelegramBot\Commands\UserCommands\DeleteassigneeCommand::class,

    'приоритет'            => \Longman\TelegramBot\Commands\UserCommands\PriorityCommand::class,
    'изменить приоритет'   => \Longman\TelegramBot\Commands\UserCommands\ChangepriorityCommand::class,

    'спринты'              => \Longman\TelegramBot\Commands\UserCommands\SprintsCommand::class,
    'добавить в спринт'    => \Longman\TelegramBot\Commands\UserCommands\AddsprintCommand::class,
    'убрать из спринта'    => \Longman\TelegramBot\Commands\UserCommands\MovebacklogCommand::class,
    'переместить'          => \Longman\TelegramBot\Commands\UserCommands\AddsprintCommand::class,
    'статус спринта'       => \Longman\TelegramBot\Commands\UserCommands\StatussprintCommand::class,

    'связать задачу'       => \Longman\TelegramBot\Commands\UserCommands\BindsCommand::class,
    'блокирует связанную'  => '/link_blocks',
    'блокируется связанной'=> '/link_blocked_by',
    'дублирует'            => '/link_duplicates',
    'ссылается'            => '/link_relates',

    'компоненты'           => \Longman\TelegramBot\Commands\UserCommands\ComponentsCommand::class,
    'текущие компоненты'   => \Longman\TelegramBot\Commands\UserCommands\CurrentcomponentsCommand::class,
    'добавить компонент'   => \Longman\TelegramBot\Commands\UserCommands\AddcomponentCommand::class,
    'удалить компонент'    => \Longman\TelegramBot\Commands\UserCommands\DeletecomponentCommand::class,

    'комментарии'          => \Longman\TelegramBot\Commands\UserCommands\CommentsCommand::class,
    'вывести комментарии'  => \Longman\TelegramBot\Commands\UserCommands\GetcommentsCommand::class,
    'просмотреть '.
    'комментарии'          => \Longman\TelegramBot\Commands\UserCommands\GetcommentsCommand::class,
    'написать комментарий' => \Longman\TelegramBot\Commands\UserCommands\AddcommentCommand::class,

    'оценка времени'       => '/timestamps',
    'текущие оценка и ' .
    'storypoint'           => '/read_est_story',
    'добавить оценку и '.
    'storypoint'           => '/sel_est_story',

    'статус'               => \Longman\TelegramBot\Commands\UserCommands\StatusesCommand::class,
    'изменить статус'      => \Longman\TelegramBot\Commands\UserCommands\ChangestatusCommand::class,

    'задачи для оценки'    => '/select_issues',
    'настоящие задачи '.
    'для оценки'           => '/get_slc_issues',
    'указать задачи '.
    'для оценки'           => '/set_slc_issues',
    'удалить задачи '.
    'для оценки'           => '/del_slc_issues',

    'пообщаться'           => '/select_speaker',
    'напоминания'          => '/notifies',
    'все напоминания'      => '/all_notifies',
    'задать напоминание'   => '/notify',

    'ввести версию'        => '/print_version',
    'ввести оценку'        => '/print_est_story',
    'ввести вручную оценку'=> '/print_planning',

    'сохранить напоминание'=> '/store_notify',
    'удалить напоминание'  => '/notify_close',
    'ввести еще раз '.
    'дату и время'         => '/date_notify_again',
    'подтвердить дату '.
    'и время'              => '/delay_notify',
    'отменить '.
    'дополнительное '.
    'уведомление'          => '/notify_close',

    'описание'             => '/issue_description',

    '« задача'             => \Longman\TelegramBot\Commands\UserCommands\IssuepanelCommand::class,
    '« метки'              => \Longman\TelegramBot\Commands\UserCommands\LabelsCommand::class,
    '« версии'             => \Longman\TelegramBot\Commands\UserCommands\VersionsCommand::class,
    '« исполнители'        => \Longman\TelegramBot\Commands\UserCommands\AssigneesCommand::class,
    '« приоритет'          => \Longman\TelegramBot\Commands\UserCommands\PriorityCommand::class,
    '« спринты'            => \Longman\TelegramBot\Commands\UserCommands\SprintsCommand::class,
    '« компоненты'         => \Longman\TelegramBot\Commands\UserCommands\ComponentsCommand::class,
    '« комментарии'        => \Longman\TelegramBot\Commands\UserCommands\CommentsCommand::class,
    '« статус'             => \Longman\TelegramBot\Commands\UserCommands\StatusesCommand::class,
    '« оценка времени'     => '/timestamps',
    '« задачи для оценки'  => '/select_issues',
    '« поиск задач'        => '/search_issues_menu',
    '« задачи'             => \Longman\TelegramBot\Commands\UserCommands\IssuesCommand::class,
    'на главную'           => \Longman\TelegramBot\Commands\UserCommands\PanelCommand::class,
];