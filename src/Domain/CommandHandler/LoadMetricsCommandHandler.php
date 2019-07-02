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
use App\Domain\Repository\LocationRepositoryInterface;
use App\Domain\Repository\MeasureRepositoryInterface;
use Exception;
use Psr\Container\ContainerInterface;

/**
 * Class LoadMetricsCommandHandler
 * @package App\Domain\CommandHandler
 */
class LoadMetricsCommandHandler implements CommandHandlerInterface
{
    /** @var LocationRepositoryInterface */
    protected $locationRepository;

    /**
     * LoadMetricsCommandHandler constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->locationRepository = $container->get(LocationRepositoryInterface::class);
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
        /** @var LocationEntity $location */
        foreach ($locations as $location) {

            $command->getDataSource()->getMeasures($location, $command->getCategory());
        }
        return new CommandResult(true);
    }
}
