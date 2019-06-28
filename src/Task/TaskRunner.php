<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 28/06/19
 */

namespace App\Task;

use App\Repository\Task;
use App\Repository\TaskHistory;
use Cron\CronExpression;
use DateTime;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Class TaskRunner
 * @package App\Task
 */
class TaskRunner implements TaskInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var array
     */
    protected $taskMap = [
        'ImportOpenWeatherMapTask' => ImportOpenWeatherMapTask::class
    ];

    /**
     * TaskRunner constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        /** @var LoggerInterface logger */
        $this->logger = $container->get('logger');
    }

    /**
     * @param array $args
     * @return string
     * @throws Exception
     */
    public function command(array $args): string
    {
        $tasks = Task::all();

        /** @var Task $task */
        foreach ($tasks as $task) {
            if ($this->isDue($task)) {
                $this->runTask($task);
            }
        }

        return "Tasks completed\n";
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

        $jobClass = $this->taskMap[$task->command];
        $success = false;
        try {
            /** @var TaskInterface $job */
            $job = new $jobClass($this->container);
            $result = $job->command([]);
            $history->exit_code = 0;
            $history->comment = $result;
            $this->logger->debug(sprintf("Task %s ==> Success: %s\n", $task->command, $result));
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

    /**
     * @param Task $task
     * @return bool
     */
    protected function isDue(Task $task): bool
    {
        $cron = CronExpression::factory($task->schedule);
        /** @var TaskHistory|null $lastSuccess */
        $lastSuccess = $task->history()->where('exit_code', '=', 0)->orderBy('started_at', 'desc')->first();

        if ($lastSuccess === null) {
            $this->logger->info(sprintf("Task '%s' was never executed\n", $task->command));
            return true;
        }

        $this->logger->debug(sprintf(
            "Task %s: last success: %s, last due date %s, next run: %s\n",
            $task->command,
            $lastSuccess->started_at->format('d/m/Y H:i:s'),
            $cron->getPreviousRunDate()->format('d/m/Y H:i:s'),
            $cron->getNextRunDate()->format('d/m/Y H:i:s')
        ));

        return $cron->getPreviousRunDate() > $lastSuccess->started_at;
    }
}
