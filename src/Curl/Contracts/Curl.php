<?php

namespace Curl\Contracts;

interface Curl
{
	/**
	 * Do action on success.
	 *
	 * @param \Closure|(Invokeable) $action
	 * @return bool
	 */
	public function success($action);

	/**
	 * Define user opt.
	 *
	 * @param array $opt
	 */
	public function setOpt($opt);

	/**
	 * Run get method.
	 *
	 * @return string
	 */
	public function get();

	/**
	 * Get curl info.
	 *
	 * @return array
	 */
	public function info();

	/**
	 * Run post method.
	 *
	 * @return string
	 */
	public function post();

	/**
	 * Get error message.
	 *
	 * @return string
	 */
	public function error();

	/**
	 * Get error code.
	 *
	 * @return int
	 */
	public function errno();

	/**
	 * Get header response.
	 *
	 * @return array
	 */
	public function headerResponse();
}