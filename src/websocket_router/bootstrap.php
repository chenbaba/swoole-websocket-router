<?php
namespace sheld\websocket_router;

class Bootstrap
{
	private $ws_server;
	private $inited=false;

	public function __construct()
	{
		$this->ws_server=new \swoole_websocket_server($GLOBALS['config']['bindHost'], $GLOBALS['config']['bindPort']);
		$this->init();
	}


	public function init()
	{
		$pipe=new \sheld\websocket_router\Websocket_pipe($this->ws_server);
		$pipe->pipe();
		$this->inited=true;
	}


	public function startServer()
	{
		if($this->ws_server!=null && $this->inited===true)
		{
			$this->ws_server->start();
		}
	}
}