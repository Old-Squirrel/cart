<?php

namespace Cart;

final class Session
{
	private static $session;

	public static function start()
	{
		self::init();
		session_start();
		self::$session = &$_SESSION;
	}
	/**
	 * get session id
	 */
	public static function session_id()
	{
		return session_id();
	}

	/**
	 * store session data
	 */
	public static function set($key, $value): void
	{
		self::$session[$key] = $value;
	}

	/**
	 * get session data
	 * 
	 */
	public static function get($key)
	{
		return isset(self::$session[$key]) ? self::$session[$key] : '';
	}


	/**
	 * check session status
	 * @return bool
	 */
	public static function status(): bool
	{
		return session_status() === PHP_SESSION_ACTIVE;
	}

	/**
	 * delete data
	 * @return void
	 */
	public static function delete(string $key, string $item = ''): void
	{
		switch (true) {
			case empty($item):
				unset(self::$session[$key]);
				break;
			case !empty($item):
				unset(self::$session[$key][$item]);
				break;
		}
	}

	/**
	 * destroy all session data
	 * @return void
	 */
	public static function destroy(): bool
	{
		$params   = session_get_cookie_params();
		$_SESSION = array();
		$time     = time() - 42000;
		setcookie(
			session_name(),
			'',
			[
				'lifetime' => $time,
				'path'	   => '/',
				'domain'   => '.'. $_SERVER['HTTP_HOST'],
				'secure'   => $params['secure'],
				'httponly' => $params['httponly'],
				'samesite' => $params['samesite'],
			]
		);

		$destroyed = session_destroy();
		session_gc();
		return $destroyed;
	}


	/**
	 * check data equality
	 * @return bool
	 */
	public static function check_data(string $data_name, string $value): bool
	{
		return self::get($data_name) == $value;
	}

	/**
	 * set session .ini 
	 */
	public static function init()
	{


		session_name('edcart');
		session_set_cookie_params(
			[
				'lifetime' 	=> 60 * 60 * 4,
				'path' 		=> constant('COOKIEPATH'),
				'domain' 	=> constant('COOKIE_DOMAIN'),
				'secure' 	=> true,
				'httponly' 	=> true,
				'samesite'	=> "lax"
			]
		);
	}

	/**
	 * 
	 */
	public static function plus_item(string $product_slug)
	{
		$items = self::get('items');
		$items = is_array($items) ? $items : [];
		switch (true) {
			case empty($items):
				$items[$product_slug] = 1;
				break;
			case !empty($items[$product_slug]):
				$items[$product_slug] += 1;
				break;
			case empty($items[$product_slug]):
				$items[$product_slug] = 1;
				break;
		}
		self::set('items', $items);
	}
	/**
	 * 
	 */
	public static function minus_item(string $product_slug)
	{
		$items = self::get('items');
		switch (!empty($items[$product_slug])) {
			case $items[$product_slug] = 1:
				self::delete('items', $product_slug);
				return;
			case  $items[$product_slug] > 1:
				$items[$product_slug] -= 1;
				self::set('items', $items);
				return;
		}
	}

	/**
	 * 
	 */
	public static function remove_item(string $product_slug)
	{
		self::delete('items', $product_slug);
	}
}
