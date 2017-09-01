<?php
namespace sheld\websocket_router;

class Bootstrap
{
	private $ws_server;

	public function __construct()
	{
		$this->ws_server=new swoole_websocket_server($_GLOBAL['config']['bindHost'], $_GLOBAL['config']['bindPort']);
	}


	public function startServer()
	{
		if($this->ws_server!=null)
		{
			$this->ws_server->start();
		}
	}
}