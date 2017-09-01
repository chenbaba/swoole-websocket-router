<?php
namespace sheld\websocket_router;

class Bootstrap
{
	private $ws_server;

	public function __construct($ws_server)
	{
		$this->ws_server=$ws_server;
	}


	public function startServer()
	{
		if($this->ws_server!=null)
		{
			$this->ws_server->start();
		}
	}
}