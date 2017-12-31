<?php
class MySQLDatabase implements Connection
{
	private $connection;

	public function __construct(credentials $db_cred, $opts = NULL)
	{
		try {
			$db_cred = $db_cred->get_credentials();
			$this->connection = new PDO('mysql:host=' . $db_cred['host'] . ';dbname=' . $db_cred['dbname'] . '', $db_cred['usrname'], $db_cred['psword'], $opts);
		} catch (PDOException $e) {
			print "Connection Error!: " . $e->getMessage();
			die();
		}
	}
	public function get_connection()
	{
		return $this->connection;
	}
}
?>