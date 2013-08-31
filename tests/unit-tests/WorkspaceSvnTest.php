<?php 

class WorkspaceSvnTest extends PHPUnit_Framework_TestCase{
	
	public function test_ticketExists_issues_correct_command(){
		$client = $this->getMock('CommandClient', array('execute'));
		$client->expects($this->once())
			->method('execute')
			->with('svn ls http://svn.example.com/frbot/branches/ticket_1234  2>&1');
		
		$log = $this->getMock('MessageLog', array('write'));
		
		$ws = new WorkspaceSvn('http://svn.example.com/frbot', '~/test', $log, $client);
		
		$ws->ticketExists('ticket_1234');	
	}
	
	public function test_ticketExists_returns_true_when_ticket_exists(){
		$client = $this->getMock('CommandClient', array('execute'));
		$client->expects($this->once())
			->method('execute')
			->will($this->returnValue(true));
	
		$log = $this->getMock('MessageLog', array('write'));
	
		$ws = new WorkspaceSvn('http://svn.example.com/frbot', '~/test', $log, $client);
	
		$result = $ws->ticketExists('ticket_1234');
		$this->assertTrue($result);
	}
	
	public function test_ticketExists_returns_false_when_ticket_dosent_exist(){
		$client = $this->getMock('CommandClient', array('execute'));
		$client->expects($this->once())
			->method('execute')
			->will($this->returnValue(false));
	
		$log = $this->getMock('MessageLog', array('write'));
	
		$ws = new WorkspaceSvn('http://svn.example.com/frbot', '~/test', $log, $client);
	
		$result = $ws->ticketExists('ticket_1234');
		$this->assertFalse($result);
	}
	
	public function test_ticketExists_logs_correctly(){
		$client = $this->getMock('CommandClient', array('execute'));
		
		$log = $this->getMock('MessageLog', array('write'));
		$log->expects($this->exactly(2))
			->method('write');
		
		$ws = new WorkspaceSvn('http://svn.example.com/frbot', '~/test', $log, $client);
		
		$ws->ticketExists('ticket_1234');
	}
	
	public function test_releaseExists_issues_correct_command(){
		$client = $this->getMock('CommandClient', array('execute'));
		$client->expects($this->once())
			->method('execute')
			->with('svn ls http://svn.example.com/frbot/releases/Aug3113  2>&1');
	
		$log = $this->getMock('MessageLog', array('write'));
	
		$ws = new WorkspaceSvn('http://svn.example.com/frbot', '~/test', $log, $client);
	
		$ws->releaseExists('Aug3113');
	}
	
}
