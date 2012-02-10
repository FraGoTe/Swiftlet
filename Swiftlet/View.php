<?php

namespace Swiftlet;

class View implements Interfaces\View
{
	protected
		$app,
		$name,
		$variables = array()
		;

	/**
	 * Constructor
	 * @param object $app
	 * @param object $view
	 */
	public function __construct(App $app, $name)
   	{
		$this->app  = $app;
		$this->name = $name;
	}

	/**
	 * Get the view name
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set the view name
	 * @paran string $view
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * Get a view variable
	 * @params string $variable
	 * @params bool $htmlEncode
	 * @return mixed
	 */
	public function get($variable, $htmlEncode = true)
   	{
		if ( isset($this->variables[$variable]) ) {
			if ( $htmlEncode ) {
				return $this->htmlEncode($this->variables[$variable]);
			} else {
				return $this->variables[$variable];
			}
		}
	}

	/**
	 * Set a view variable
	 * @param string $variable
	 * @param mixed $value
	 */
	public function set($variable, $value = null)
	{
		$this->variables[$variable] = $value;
	}

	/**
	 * Recursively make a value safe for HTML
	 * @param mixed $value
	 * @return mixed
	 */
	public function htmlEncode($value)
   	{
		switch ( gettype($value) ) {
			case 'array':
				foreach ( $value as $k => $v ) {
					$value[$k] = $this->htmlEncode($v);
				}

				break;
			case 'object':
				foreach ( $value as $k => $v ) {
					$value->$k = $this->htmlEncode($v);
				}

				break;
			default:
				$value = htmlentities($value, ENT_QUOTES, 'UTF-8');
		}

		return $value;
	}

	/**
	 * Recursively decode an HTML encoded value
	 * @param mixed $value
	 * @return mixed
	 */
	public function htmlDecode($value)
   	{
		switch ( gettype($value) ) {
			case 'array':
				foreach ( $value as $k => $v ) {
					$value[$k] = $this->htmlDecode($v);
				}

				break;
			case 'object':
				foreach ( $value as $k => $v ) {
					$value->$k = $this->htmlDecode($v);
				}

				break;
			default:
				$value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
		}

		return $value;
	}

	/**
	 * Render the view
	 * @param mixed $value
	 * @return mixed
	 */
	public function render()
   	{
		if ( is_file($file = 'views/' . $this->name . '.html.php') ) {
			header('X-Generator: Swiftlet');

			require $file;
		} else {
			throw new \Exception('View not found');
		}
	}
}
