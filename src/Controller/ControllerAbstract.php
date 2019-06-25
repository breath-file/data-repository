<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 25/06/19
 */

namespace App\Controller;

use App\Core\CommandDispatcher;
use App\Domain\Command\CommandInterface;
use App\Domain\CommandHandler\CommandResult;
use Psr\Container\ContainerInterface;

/**
 * Class ControllerAbstract
 * @package App\Controller
 */
abstract class ControllerAbstract
{
    /** @var ContainerInterface */
    protected $container;

    /** @var CommandDispatcher */
    protected $commandDispatcher;

    /**
     * MetricController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->commandDispatcher = $container->get(CommandDispatcher::class);
    }

    /**
     * @param CommandInterface $command
     * @return CommandResult
     */
    protected function dispatchCommand(CommandInterface $command): CommandResult
    {
        return $this->commandDispatcher->dispatch($command);
    }
}
