<?php

declare(strict_types=1);

namespace Acme\Helper;

class Env
{
	/** @var string */
	protected string $path;

	/** @var mixed */
	protected mixed $parseContent;

	protected bool $parsed = false;

	public function __construct(string $path)
	{
		$this->path = $path;
	}

	public function getParseContent(): ?array
	{
		if (!$this->parsed) {
			$this->parseContent = parse_ini_file($this->path, true);
		}

		return $this->parseContent;
	}

	public function get(string $name, $default = null)
	{
		$content = $this->getParseContent();

		if ($content === null) {
			return $default;
		}
		$nameSeparate = explode('.', $name);
		foreach ($nameSeparate as $item) {
			if (!isset($content[$item])) {
				return $default;
			}
			$content = $content[$item];
		}
		return $content;
	}

	public function all(): ?array
	{
		return $this->getParseContent();
	}
}
