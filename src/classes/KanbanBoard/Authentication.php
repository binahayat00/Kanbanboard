<?php

namespace App\Classes\KanbanBoard;

use App\Classes\Utilities;

class Authentication
{

	private $client_id = NULL;
	private $client_secret = NULL;

	public function __construct()
	{
		$this->client_id = Utilities::env('GH_CLIENT_ID');
		$this->client_secret = Utilities::env('GH_CLIENT_SECRET');
	}

	public function logout()
	{
		unset($_SESSION['gh-token']);
	}

	public function login()
	{
		session_start();
		$token = $this->_setTokenForLogin();
		$this->logout();
		$_SESSION['gh-token'] = $token;
		return $_SESSION['gh-token'];
	}

	private function _redirectToGithub()
	{
		$url = 'Location: https://github.com/login/oauth/authorize';
		$url .= '?client_id=' . $this->client_id;
		$url .= '&scope=repo';
		$url .= '&state='.STATE;
		header($url);
		exit();
	}

	private function _returnsFromGithub($code)
	{
		$url = ACCESS_TOKEN_LINK;
		$options = $this->_buildParamsForGithubAccessToken($code);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		return $this->_buildResultForGithubAccessToken($result);
	}

	private function _buildParamsForGithubAccessToken($code)
	{
		$data = [
			'code' => $code,
			'state' => STATE,
			'client_id' => $this->client_id,
			'client_secret' => $this->client_secret
		];

		$options = [
			'http' => [
				'method' => 'POST',
				'header' => "Content-type: application/x-www-form-urlencoded\r\n",
				'content' => http_build_query($data),
			],
		];
		return $options;
	}

	private function _buildResultForGithubAccessToken($result)
	{
		if ($result === FALSE)
			die('Error: can not get data from access token.');
		$result = explode('=', explode('&', $result)[0]);
		array_shift($result);
		return array_shift($result);
	}

	private function _setTokenForLogin()
	{
		if (array_key_exists('gh-token', $_SESSION)) {
			$token = $_SESSION['gh-token'];
		} else if (
			Utilities::hasValue($_GET, 'code') && 
			Utilities::hasValue($_GET, 'state') && 
			$_SESSION['redirected']
		) {
			$_SESSION['redirected'] = false;
			$token = $this->_returnsFromGithub($_GET['code']);
		} else {
			$_SESSION['redirected'] = true;
			$this->_redirectToGithub();
		}

		return (isset($token)) ? $token : NULL;
	}
}
