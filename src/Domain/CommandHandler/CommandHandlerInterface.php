<?php
declare(strict_types=1);
/**
 * @author Julien Mercier-Rojas <julien@jeckel-lab.fr>
 * Created at : 24/06/19
 */

namespace App\Domain\CommandHandler;
use App\Domain\Command\CommandInterface;

/**
 * Interface CommandHandlerInterface
 * @package App\Domain\CommandHandler
 */
interface CommandHandlerInterface
{
    /**
     * @return array
     */
    public static function getSupportedCommands(): array;

    /**
     * @param CommandInterface $command
     * @return CommandResult
     */
    public function handle(CommandInterface $command): CommandResult;
}
