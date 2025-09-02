<?php

namespace app\Telegram\Commands;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    /**
    * This command can be triggered in two ways:
    * /start and /join due to the alias.
    */
    protected string $name = 'start';
    protected array $aliases = ['join'];
    // protected string $pattern = '{username}';
    // OR
    protected string $pattern = '{username}
    {age: \d+}';
    protected string $description = 'Start Command to get you started';

    public function handle()
    {
        //                **  Simple Send a start message  **
        // $this->replyWithMessage([
        //     'text' => 'Hey, there! Welcome to our bot!',
        // ]);



        //                **  Describe $pattern value  **
        //  Input   => /start johndoe
        //  Output  => Hello johndoe! Welcome to our bot :)

        # username from Update object to be used as fallback.
        // $fallbackUsername = $this->getUpdate()->getMessage()->from->username;
        # Get the username argument if the user provides,
        # (optional) fallback to username from Update object as the default.
        // $username = $this->argument(
        //     'username',
        //     $fallbackUsername
        // );
        // $this->replyWithMessage([
        //     'text' => "Hello {$username}! Welcome to our bot :)"
        // ]);



        //                **  OR  **
        // $username = $this->argument('username');
        // $age = $this->argument('age');
        // if(!$username) {
        //     $this->replyWithMessage([
        //         'text' => "Please provide your username! Ex: /start jasondoe"
        //     ]);
        //     return;
        // }
        // if(!$age) {
        //     $this->replyWithMessage([
        //         'text' => "Please provide your age with the username! Ex: /start jasondoe 24"
        //     ]);
        //     return;
        // }
        // $this->replyWithMessage([
        //     'text' => "Hello {$username}! Welcome to our bot :)"
        // ]);




        //                **  Comprehensive Example  **
        # username from Update object to be used as fallback.
        $fallbackUsername = $this->getUpdate()->getMessage()->from->username;
        # Get the username argument if the user provides,
        # (optional) fallback to username from Update object as the default.
        $username = $this->argument(
            'username',
            $fallbackUsername
        );
        $this->replyWithMessage([
            'text' => "Hello {$username}! Welcome to our bot, Here are our available commands:"
        ]);
        # This will update the chat status to "typing..."
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        # Get all the registered commands.
        $commands = $this->getTelegram()->getCommands();
        $response = '';
        foreach ($commands as $name => $command) {
            $response .= sprintf('/%s - %s' . PHP_EOL, $name, $command->getDescription());
        }
        $this->replyWithMessage(['text' => $response]);
        if($this->argument('age', 0) >= 18) {
            $this->replyWithMessage(['text' => 'Congrats, You are eligible to buy premimum access to our membership!']);
        } else {
            $this->replyWithMessage(['text' => 'Sorry, you are not eligible to access premium membership yet!']);
        }
    }
}
