<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 24/06/19
 */

namespace App\Domain\CommandHandler;

use App\Domain\Command\CommandInterface;
use App\Domain\Command\LoadMetricsCommand;
use App\Domain\Entity\LocationEntity;
use App\Domain\Entity\MeasureEntity;
use App\Domain\Entity\MetricCollection;
use App\Domain\Repository\LocationRepositoryInterface;
use App\Domain\Repository\MeasureRepositoryInterface;
use Exception;

/**
 * Class LoadMetricsCommandHandler
 * @package App\Domain\CommandHandler
 */
class LoadMetricsCommandHandler implements CommandHandlerInterface
{
    /** @var LocationRepositoryInterface */
    protected $locationRepository;
    /**
     * @var MeasureRepositoryInterface
     */
    protected $measureRepository;

    /**
     * LoadMetricsCommandHandler constructor.
     * @param LocationRepositoryInterface $locationRepository
     * @param MeasureRepositoryInterface  $measureRepository
     */
    public function __construct(LocationRepositoryInterface $locationRepository, MeasureRepositoryInterface $measureRepository)
    {
        $this->locationRepository = $locationRepository;
        $this->measureRepository = $measureRepository;
    }

    /**
     * @return array
     */
    public static function getSupportedCommands(): array
    {
        return [
            LoadMetricsCommand::class,
        ];
    }

    /**
     * @param CommandInterface|LoadMetricsCommand $command
     * @return CommandResult
     * @throws Exception
     */
    public function handle(CommandInterface $command): CommandResult
    {
        $locations = $this->locationRepository->list();
        //$metrics = new MetricCollection();
        /** @var LocationEntity $location */
        foreach ($locations as $location) {
            /** @var MeasureEntity $measure */
            foreach ($command->getDataSource()->getMetrics($location) as $measure) {
                $this->measureRepository->save($measure);
            }
//            $metrics->merge($command->getDataSource()->getMetrics($location));
        }
//        return new CommandResult(true, $metrics);
        return new CommandResult(true);
    }
}
