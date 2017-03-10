<?php
/**
 * [YIQIC System] Copyright (c) 2016 YIQIC.CN
 *  yanglinjun 810153135@qq.com
 */

 class DB{
    private $db_host;
	private $db_user;
	private $db_pwd;
	private $db_database;
	private $conn;
	private $result;
	private $sql;
	private $row;
	private $coding;
	private $bulletin = false;
	private $show_error = false;
	private $is_error = false;

	public function __construct($db_host, $db_user, $db_pwd, $db_database, $conn, $coding)
	{
		$this->db_host = $db_host;
		$this->db_user = $db_user;
		$this->db_pwd = $db_pwd;
		$this->db_database = $db_database;
		$this->conn = $conn;
		$this->coding = $coding;
		$this->connect();
	}

	public function connect()
	{
		if ($this->conn == 'pconn') {
			$this->conn = mysql_pconnect($this->db_host, $this->db_user, $this->db_pwd);
		}
		else {
			$this->conn = mysql_connect($this->db_host, $this->db_user, $this->db_pwd);
		}

		if (!mysql_select_db($this->db_database, $this->conn)) {
			if ($this->show_error) {
				$this->show_error('数据库不可用：', $this->db_database);
			}
		}

		mysql_query('SET NAMES ' . $this->coding);
	}

	public function trealip()
	{
		$ip = gethostbyname($_SERVER['HTTP_HOST']);
		return $ip;
	}

	public function query($sql)
	{
		if ($sql == '') {
			$this->show_error('sql语句错误：', 'sql查询语句为空');
		}

		$this->sql = $sql;
		$result = mysql_query($this->sql, $this->conn);

		if (!$result) {
			if ($this->show_error) {
				$this->show_error('错误sql语句：', $this->sql);
			}
		}
		else {
			$this->result = $result;
		}

		return $this->result;
	}

	public function cmadd($data, $table = NULL)
	{
		$table = (is_null($table) ? $this->table : $table);
		$sql = 'INSERT INTO `' . $table . '`';
		$fields = $values = array();
		$field = $value = '';

		foreach ($data as $key => $val) {
			$fields[] = '`' . $table . '`.`' . $key . '`';
			$values[] = is_numeric($val) ? $val : '\'' . $val . '\'';
		}

		$field = join(',', $fields);
		$value = join(',', $values);
		unset($fields);
		unset($values);
		$sql .= '(' . $field . ') VALUES(' . $value . ')';
		$this->query($sql);
		return true;
	}

	public function cmupdate($data, $where = NULL, $table = NULL)
	{
		$table = (is_null($table) ? $this->table : $table);
		$where = (is_null($where) ? @$this->options['where'] : $where);
		$sql = 'UPDATE `' . $table . '` SET ';
		$values = array();

		foreach ($data as $key => $val) {
			$val = (is_numeric($val) ? $val : '\'' . $val . '\'');
			$values[] = '`' . $table . '`.`' . $key . '` = ' . $val;
		}

		$value = join(',', $values);
		$sql = $sql . $value . ' WHERE ' . $where;
		$this->query($sql);
		return true;
	}

	public function create_database($database_name)
	{
		$database = $database_name;
		$sqlDatabase = 'create database ' . $database;
		$this->query($sqlDatabase);
	}

	public function show_databases()
	{
		$this->query('show databases');
		echo '现有数据库：' . ($amount = $this->db_num_rows($rs));
		echo '<br />';
		$i = 1;

		while ($row = $this->fetch_array($rs)) {
			echo $i . ' ' . $row['Database'];
			echo '<br />';
			$i++;
		}
	}

	public function databases()
	{
		$rsPtr = mysql_list_dbs($this->conn);
		$i = 0;
		$cnt = mysql_num_rows($rsPtr);

		while ($i < $cnt) {
			$rs[] = mysql_db_name($rsPtr, $i);
			$i++;
		}

		return $rs;
	}

	public function show_tables($database_name)
	{
		$this->query('show tables');
		echo '现有数据库：' . ($amount = $this->db_num_rows($rs));
		echo '<br />';
		$i = 1;

		while ($row = $this->fetch_array($rs)) {
			$columnName = 'Tables_in_' . $database_name;
			echo $i . ' ' . $row[$columnName];
			echo '<br />';
			$i++;
		}
	}

	public function mysql_result_li()
	{
		return mysql_result($str);
	}

	public function fetch_array($rs)
	{
		if ($rs == '') {
			$rs = $this->result;
		}

		return mysql_fetch_array($rs);
	}

	public function fetch_assoc()
	{
		return mysql_fetch_assoc($this->result);
	}

	public function fetch_row()
	{
		return mysql_fetch_row($this->result);
	}

	public function fetch_Object()
	{
		return mysql_fetch_object($this->result);
	}

	public function findall($table)
	{
		$this->query('SELECT * FROM ' . $table);
	}

	public function cmselect($table, $columnName, $condition)
	{
		if ($columnName == '') {
			$columnName = '*';
		}

		$this->query('SELECT ' . $columnName . ' FROM ' . $table . ' ' . $condition);
	}

	public function delete($table, $condition)
	{
		$this->query('DELETE FROM ' . $table . ' WHERE ' . $condition);
	}

	public function insert($table, $columnName, $value)
	{
		$this->query('INSERT INTO ' . $table . ' (' . $columnName . ') VALUES (' . $value . ')');
	}

	public function update($table, $mod_content, $condition)
	{
		$this->query('UPDATE ' . $table . ' SET ' . $mod_content . ' WHERE ' . $condition);
	}

	public function insert_id()
	{
		return mysql_insert_id();
	}

	public function db_data_seek($id)
	{
		if (0 < $id) {
			$id = $id - 1;
		}

		if (!@mysql_data_seek($this->result, $id)) {
			$this->show_error('sql语句有误：', '指定的数据为空');
		}

		return $this->result;
	}

	public function db_num_rows()
	{
		if ($this->result == NULL) {
			if ($this->show_error) {
				$this->show_error('sql语句错误', '暂时为空，没有任何内容！');
			}
		}
		else {
			return mysql_num_rows($this->result);
		}
	}

	public function db_affected_rows()
	{
		return mysql_affected_rows();
	}

	public function show_error($message = '', $sql = '')
	{
		if (!$sql) {
			echo '<font color=\'red\'>' . $message . '</font>';
			echo '<br />';
		}
		else {
			echo '<fieldset>';
			echo '<legend>错误信息提示:</legend><br />';
			echo '<div style=\'font-size:14px; clear:both; font-family:Verdana, Arial, Helvetica, sans-serif;\'>';
			echo '<div style=\'height:20px; background:#000000; border:1px #000000 solid\'>';
			echo '<font color=\'white\'>错误号：12142</font>';
			echo '</div><br />';
			echo '错误原因：' . mysql_error() . '<br /><br />';
			echo '<div style=\'height:20px; background:#FF0000; border:1px #FF0000 solid\'>';
			echo '<font color=\'white\'>' . $message . '</font>';
			echo '</div>';
			echo '<font color=\'red\'><pre>' . $sql . '</pre></font>';
			$ip = $this->getip();

			if ($this->bulletin) {
				$time = date('Y-m-d H:i:s');
				$message = $message . "\r\n" . $this->sql . "\r\n客户IP:" . $ip . "\r\n时间 :" . $time . "\r\n\r\n";
				$server_date = date('Y-m-d');
				$filename = $server_date . '.txt';
				$file_path = 'error/' . $filename;
				$error_content = $message;
				$file = 'error';

				if (!file_exists($file)) {
					if (!mkdir($file, 511)) {
						exit('upload files directory does not exist and creation failed');
					}
				}

				if (!file_exists($file_path)) {
					fopen($file_path, 'w+');

					if (is_writable($file_path)) {
						if (!($handle = fopen($file_path, 'a'))) {
							echo '不能打开文件 ' . $filename;
							exit();
						}

						if (!fwrite($handle, $error_content)) {
							echo '不能写入到文件 ' . $filename;
							exit();
						}

						echo '——错误记录被保存!';
						fclose($handle);
					}
					else {
						echo '文件 ' . $filename . ' 不可写';
					}
				}
				else if (is_writable($file_path)) {
					if (!($handle = fopen($file_path, 'a'))) {
						echo '不能打开文件 ' . $filename;
						exit();
					}

					if (!fwrite($handle, $error_content)) {
						echo '不能写入到文件 ' . $filename;
						exit();
					}

					echo '——错误记录被保存!';
					fclose($handle);
				}
				else {
					echo '文件 ' . $filename . ' 不可写';
				}
			}

			echo '<br />';

			if ($this->is_error) {
				exit();
			}
		}

		echo '</div>';
		echo '</fieldset>';
		echo '<br />';
	}

	public function free()
	{
		@mysql_free_result($this->result);
	}

	public function select_db($db_database)
	{
		return mysql_select_db($db_database);
	}

	public function num_fields($table_name)
	{
		$this->query('select * from ' . $table_name);
		echo '<br />';
		echo '字段数：' . ($total = mysql_num_fields($this->result));
		echo '<pre>';

		for ($i = 0; $i < $total; $i++) {
			print_r(mysql_fetch_field($this->result, $i));
		}

		echo '</pre>';
		echo '<br />';
	}

	public function mysql_server($num = '')
	{
		switch ($num) {
		case 1:
			return mysql_get_server_info();
			break;

		case 2:
			return mysql_get_host_info();
			break;

		case 3:
			return mysql_get_client_info();
			break;

		case 4:
			return mysql_get_proto_info();
			break;

		default:
			return mysql_get_client_info();
		}
	}

	public function __destruct()
	{
		if (!empty($this->result)) {
			$this->free();
		}

		mysql_close($this->conn);
	}

 }
?>