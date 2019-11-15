<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel" style="display: none">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Меню', 'options' => ['class' => 'header']],

                    ['label' => 'Боты',       'url' => ['/bots/bot/'], 'icon' => 'reddit'],
                    ['label' => 'Спринты',    'url' => ['/sprints/sprint/'], 'icon' => 'rocket'],
                    ['label' => 'Метки',      'url' => ['/labels/label/'], 'icon' => 'tag'],
                    ['label' => 'Приоритеты', 'url' => ['/priorities/priority/'], 'icon' => 'fire'],
                    ['label' => 'Статусы',    'url' => ['/statuses/status/']],
                    ['label' => 'Должности',  'url' => ['/posts/post/']],
                    ['label' => 'Роли',       'url' => ['/roles/role/']],

                    [
                        'label' => 'Сотрудники',
                        'icon'  => 'user',
                        'url'   => '#',
                        'items' => [
                            ['label' => 'Штат',     'url' => ['/staff/staff/'], 'icon'  => 'user'],
                            ['label' => 'Jira',     'url' => ['/users/user/']],
                            ['label' => 'Телеграм', 'url' => ['/telegram/user/'], 'icon' => 'send']
                        ]
                    ]
                ],
            ]
        ) ?>

    </section>

</aside>
