<?php declare(strict_types = 1);

namespace WebChemistry\MonologExtras\Handler;

use Monolog\Handler\AbstractHandler;
use Monolog\Handler\StreamHandler as MonologStreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\LogRecord;
use Psr\Log\LogLevel;

final class StreamHandler extends MonologStreamHandler
{

	/** @var int[] */
	private array $levels = [];

	/**
	 * Sets minimum logging level at which this handler will be triggered.
	 *
	 * @param Level|LogLevel::* $level Level or level name
	 *
	 * @phpstan-param value-of<Level::VALUES>|value-of<Level::NAMES>|Level|LogLevel::* $level
	 */
	public function setLevel(int|string|Level $level): AbstractHandler
	{
		return parent::setLevel($level);
	}

	/**
	 * Sets exact logging levels at which this handler will be triggered.
	 *
	 * @param Level|LogLevel::* ...$level Level or level name
	 *
	 * @phpstan-param value-of<Level::VALUES>|value-of<Level::NAMES>|Level|LogLevel::* ...$level
	 */
	public function setLevels(int|string|Level ... $level): AbstractHandler
	{
		$this->levels = array_map(
			fn (int|string|Level $level): int => Logger::toMonologLevel($level)->value,
			$level,
		);

		return $this;
	}

	public function isHandling(LogRecord $record): bool
	{
		if ($levels = $this->levels) {
			return in_array($record->level->value, $levels, true);
		}

		return parent::isHandling($record);
	}

}
