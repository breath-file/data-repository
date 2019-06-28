<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 28/06/19
 */

namespace App\Core\Cli\Cron;

use App\Repository\Task;
use App\Repository\TaskHistory;
use App\Repository\TaskHistoryRepository;
use App\Repository\TaskRepository;
use Cron\CronExpression;
use Psr\Container\ContainerInterface;

/**
 * Class TaskProvider
 * @package App\Core\Cli\Cron
 */
class TaskProvider
{
    /** @var TaskRepository */
    protected $taskRepository;

    /** @var TaskHistoryRepository */
    protected $taskHistoryRepository;

    /**
     * TaskProvider constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->taskRepository = $container->get(TaskRepository::class);
        $this->taskHistoryRepository = $container->get(TaskHistoryRepository::class);
    }

    /**
     * @return Task[]|array
     */
    public function getDueTasks(): array
    {
        $tasks = $this->taskRepository->getAll()->all();

        return array_filter($tasks, [$this, 'isDue']);
    }

    /**
     * @param Task $task
     * @return bool
     */
    protected function isDue(Task $task): bool
    {
        $cron = CronExpression::factory($task->schedule);
        /** @var TaskHistory|null $lastSuccess */
        $lastSuccess = $this->taskHistoryRepository->lastTaskSuccess($task);

        if ($lastSuccess === null) {
            return true;
        }

        return $cron->getPreviousRunDate() > $lastSuccess->started_at;
    }
}
