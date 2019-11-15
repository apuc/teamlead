<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Longman\TelegramBot\Commands\SystemCommands;
use common\modules\telegram\records\Session;
use common\modules\telegram\services\SessionService;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;
/**
 * Callback query command
 *
 * This command handles all callback queries sent via inline keyboard buttons.
 *
 * @see InlinekeyboardCommand.php
 */
class CallbackqueryCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'callbackquery';
    /**
     * @var string
     */
    protected $description = 'Reply to callback query';
    /**
     * @var string
     */
    protected $version = '1.1.1';
    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $callback_query    = $this->getCallbackQuery();
        $callback_query_id = $callback_query->getId();
        $callback_data     = json_decode($callback_query->getData(), true);

        if (isset($callback_data['i'])) {
            SessionService::make($this->getCallbackQuery()->getFrom()->getId(), ['issue' => $callback_data['i']]);
        }

        if (isset($callback_data['p'])) {
            $this->telegram->setCommandConfig($callback_data['c'], ['page' => $callback_data['p']]);
        }

        return $this->telegram->executeCommand($callback_data['c']);
    }
}