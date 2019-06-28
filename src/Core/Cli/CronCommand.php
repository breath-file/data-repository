<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 28/06/19
 */

namespace App\Core\Cli;

use App\Core\Cli\Cron\TaskProvider;
use App\Core\CommandDispatcher;
use App\Repository\Task;
use App\Repository\TaskHistory;
use DateTime;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Throwable;

/**
 * Class CronCommand
 * @package App\Core\Cli
 */
class CronCommand implements CliCommandInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /** @var ContainerInterface */
    protected $container;

    /** @var CommandDispatcher */
    protected $commandDispatcher;

    /** @var TaskProvider */
    protected $taskProvider;

    /**
     * TaskRunner constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        /** @var LoggerInterface logger */
        $this->logger = $container->get('logger');

        $this->taskProvider = $container->get(TaskProvider::class);
        $this->commandDispatcher = $container->get(CommandDispatcher::class);
    }

    /**
     * @param array $args
     * @return string
     * @throws Exception
     */
    public function command(array $args): string
    {
        $tasks = $this->taskProvider->getDueTasks();

        /** @var Task $task */
        foreach ($tasks as $task) {
            $this->runTask($task);
        }

        return '';
    }

    /**
     * @param Task $task
     * @return bool
     * @throws Exception
     */
    protected function runTask(Task $task): bool
    {
        $this->logger->debug(sprintf("Task %s ==> Starting\n", $task->command));
        $history = $this->initHistory($task);

        $success = false;
        try {

            $command = $this->container->get($task->command);
            if ($command === null) {
                throw new RuntimeException(printf('Unknown command %s', $task->command));
            }

            $result = $this->commandDispatcher->dispatch($command);

            $history->exit_code = 0;
            $history->comment = (string) $result->getResult();
            $this->logger->debug(sprintf("Task %s ==> Success: %s\n", $task->command, (string) $result->getResult()));
            $success = true;
        } catch (Throwable $exception) {
            $history->exit_code = $exception->getCode() ?: 1;
            $history->comment = $exception->getMessage();
            $this->logger->error(sprintf("Task %s ==> Error(%s): %s\n", $task->command, $exception->getCode(), $exception->getMessage()));
        } finally {
            $history->ended_at = new DateTime();
            $history->save();
        }
        return $success;
    }

    /**
     * @param Task $task
     * @return TaskHistory
     * @throws Exception
     */
    protected function initHistory(Task $task): TaskHistory
    {
        $history = new TaskHistory();
        $history->task_id = $task->task_id;
        $history->started_at = new DateTime();
        $history->exit_code = -1;
        $history->save();
        return $history;
    }

}
