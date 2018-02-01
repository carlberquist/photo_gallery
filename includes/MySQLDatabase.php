<?php
class MySQLDatabase implements Connection
{
	private $connection;

	public function __construct(credentials $db_cred, $opts = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false))
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