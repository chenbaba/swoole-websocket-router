<?php
class Test
{
	public function a()
	{
		$message=json_decode($this->_frame->data);
		var_dump($message);
		return "message i recived is:".$message->m;
	}
}