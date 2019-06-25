<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 25/06/19
 */

namespace App\Core;

use App\Domain\Command\CommandInterface;
use App\Domain\CommandHandler\CommandHandlerInterface;
use App\Domain\CommandHandler\CommandResult;
use Psr\Container\ContainerInterface;

/**
 * Class CommandDispatcher
 * @package App\Core
 */
class CommandDispatcher
{
    protected $commands = [];

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * CommandDispatcher constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param array  $commands
     * @param string $handler
     * @return CommandDispatcher
     */
    public function map(array $commands, string $handler): self
    {
        foreach ($commands as $command) {
            $this->commands[$command] = $handler;
        }
        return $this;
    }

    /**
     * @param CommandInterface $command
     * @return CommandResult
     */
    public function dispatch(CommandInterface $command): CommandResult
    {
        $commandName = $this->commands[get_class($command)];

        /** @var CommandHandlerInterface $commandHandler */
        $commandHandler = $this->container->get($commandName);
        return $commandHandler->handle($command);
    }
}
