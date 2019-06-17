<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 08.12.2016
 * Time: 14:43
 */
declare(strict_types = 1);

namespace Alpipego\AWP\Custom;

abstract class AbstractCustom
{
	protected $singular;
	protected $plural;
	protected $labels = [];
	protected $roles = ['administrator'];
	protected $args = ['public' => true];
	protected $capability_type = '';
	protected $capabilities = [];

	public function __call($name, $arguments) : self
	{
		if ( ! isset($arguments[0])) {
			throw new \InvalidArgumentException(sprintf('You must pass a value to %s', $name));
		}

		return $this->set($name, $arguments[0]);
	}

	protected function set($name, $value) : self
	{
		if (property_exists($this, $name)) {
			if (is_array($this->$name)) {
				if ( ! is_array($value)) {
					$this->$name[] = $value;
				} else {
					$this->$name = array_merge($this->$name, $value);
				}
			} else {
				$this->$name = $value;
			}
		} else {
			$this->args[$name] = $value;
		}

		return $this;
	}

	public function __get(string $field)
	{
		if (property_exists($this, $field)) {
			return $this->{$field};
		}
	}

	public function capabilities(array $caps = [])
	{
		if ( ! empty($caps)) {
			$this->capabilities = $caps;
		}

		return $this;
	}

	public function getArgs()
	{
		return $this->args;
	}

	abstract public function create();

	abstract protected function defaultCaps() : array;

	protected function mapArgs() : array
	{
		$args       = [];
		$unfiltered = array_merge($this->args, ['labels' => $this->labels], ['capabilities' => $this->capabilities]);
		foreach ($unfiltered as $key => $arg) {
			if (is_array($arg)) {
				$arg = array_unique($arg);
			}
			$args[$key] = $arg;
		}

		return $args;
	}

	protected function labels(array $labels = [])
	{
		if ( ! empty($labels)) {
			$this->labels = $labels;
		} elseif (empty($this->labels)) {
			$this->labels = $this->defaultLabels();
		}

		return $this;
	}

	abstract protected function defaultLabels() : array;
}
