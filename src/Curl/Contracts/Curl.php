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
	 * Run get method.
	 *
	 * @return string
	 */
	public function get();

	/**
	 * Run post method.
	 *
	 * @return string
	 */
	public function post();
}