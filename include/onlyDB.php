<?
/*
 *	数据库类
 *	class onlyDB define
 *	author: lj
 *	lastmodify: 2004-07-29
 *	Cole modify: 2008-10-15
*/

class onlyDB
{
	var $dbHost       = "";
	var $dbUser       = "";
	var $dbPassword   = "";
	var $dbName       = "";

	var $linkId;

	var $debugMsg = array();

	var $debugModule = false;

	function onlyDB($dbHost, $dbUser, $dbPassword, $dbName = "")
	{
		$this->dbHost      = $dbHost;
		$this->dbUser      = $dbUser;
		$this->dbPassword  = $dbPassword;
		$this->dbName      = $dbName;
		$this->connect();
	}

	function connect()
	{
		$this->linkId = mysql_connect($this->dbHost, $this->dbUser, $this->dbPassword);

		if (!$this->linkId)
		{
			raise( new OnlyException(CONNECT_FAILD) );
			return false;
		}

		$this->query("SET NAMES 'utf8'");

		if ($this->dbName)
		{
			return $this->select_db($this->dbName);
		}

		return true;
	}

	function pconnect()
	{
		$this->linkId = mysql_pconnect($this->dbHost, $this->dbUser, $this->dbPassword);

		if (!$this->linkId)
		{
			raise( new OnlyException(CONNECT_FAILD) );
			return false;
		}

		if ($this->dbName)
		{
			return $this->select_db($this->dbName);
		}

		return true;
	}

	function close()
	{
		mysql_close($this->linkId);
	}

	function select_db($dbName)
	{
		$rt = mysql_select_db($dbName);

		if (!$rt)
		{
			raise( new OnlyException( $this->error() ) );
		}
		else
		{
			$this->dbName = $dbName;
		}

		return $rt;
	}

	function query($query)
	{
		if (!trim($query)) return false;

		if ($this->debugModule)
		{
			$start_time = $this->getMicroTime();
		}

		$rt = mysql_query($query, $this->linkId);

		if (!$rt) raise( new SQLException( $this->error(), 0, $query ) );

		if ($this->debugModule)
		{
			preg_match('/\s*([a-zA-Z]+).*/i', $query, $qtype);

			$qtype = strToLower($qtype[1]);

			$item['Type']        = 'Query';
			$item['Sql']         = $query;
			$item['Result']      = $qtype == "select" ? $this->num_rows($rt) : $this->affected_rows($rt);
			$item['Error']       = $this->errno();
			$item['ProcessTime'] = $this->getMicroTime() - $start_time;

			$this->debugMsg[] = $item;
		}

		return $rt;
	}

	function affected_rows()
	{
		return mysql_affected_rows();
	}

	function num_rows($result)
	{
		$rt = mysql_num_rows($result);

		if ($rt === false) raise( new OnlyException( $this->error() ) );

		return $rt;
	}

	function num_fields($result)
	{
		$rt = mysql_num_fields($result);

		if (!$rt) raise( new OnlyException( $this->error($this->linkId) ) );

		return $rt;
	}

	function fetch_array($result, $fetchModule = MYSQL_ASSOC)
	{
		return mysql_fetch_array($result, $fetchModule);
	}

	function fetch_object($result)
	{
		return mysql_fetch_object($result);
	}



	function getMicroTime()
	{
		list($a, $b) = explode(' ', microtime());

		return (double)$b + (double)$a;
	}




	function error()
	{
		return mysql_error($this->linkId);
	}

	function errno()
	{
		return mysql_errno($this->linkId);
	}




	function data_seek($result, $row)
	{
		$rt = mysql_data_seek($result, $row);

		if (!$rt) raise( new OnlyException( $this->error() ) );

		return $rt;
	}

	function insert_id()
	{
		$rt = mysql_insert_id();

		if (!$rt) raise( new OnlyException( $this->error() ) );

		return $rt;
	}

	function get_total_querys()
	{
		return count($this->debugMsg);
	}

	function get_total_process_time()
	{
		$total_process_time = 0;

		for ($i = 0, $cnt = count($this->debugMessage); $i < $cnt; $i++)
		{
			$total_process_time += $this->debugMsg[$i]['ProcessTime'];
		}

		return $total_process_time;
	}

	function debug()
	{
		$total_sql            = 0;
		$total_process_time   = 0;

		$str = "<table width=100% border=0 cellspacing=1 cellpadding=0 style='background-color:#CCCCCC;word-break:break-all;'>\n";

		$str .= "<tr style='height:30px;background-color:#0099CC;color:#FFFFFF;text-align:center;font-family:Arial;'>
		           <td width='60'>Type</td>
				   <td>Sql</td>
				   <td width='40'>Result</td>
				   <td width='40'>Error</td>
				   <td width='80'>ProcessTime</td>
				</tr>\n";

		for ($i=0, $cnt = count($this->debugMsg); $i < $cnt; $i++)
		{
			$str .= "<tr style='background-color:#EEEEEE;height:25px;text-align:center;font-family:Arial;'>
		               <td>". $this->debugMsg[$i]['Type'] ."</td>
				       <td aling=left>". HtmlSpecialChars($this->debugMsg[$i]['Sql']) ."</td>
				       <td>". $this->debugMsg[$i]['Result'] ."</td>
				       <td>". $this->debugMsg[$i]['Error'] ."</td>
				       <td>". sprintf('%.4f', $this->debugMsg[$i]['ProcessTime']) ."</td>
					</tr>\n";

			$total_sql++;
			$total_process_time += $this->debugMsg[$i]['ProcessTime'];
		}

		$str .= "<tr style='background-color:#EEEEEE;height:30px;text-align:center;font-family:Arial;'>
				   <td colspan=5>
					   Total execute queries: ". $total_sql
				       ."&nbsp;Total ProcessTime:". sprintf('%.4f', $total_process_time) . "
					</td>
				</tr>\n";

		$str .= "</table>";

		return $str;
	}

    //not in common use

	function get_version()
	{
		$result = $this->query('select version() as Version', $this->linkId);
		$arr = $this->fetch_array($result);

		return 'MySQL' . $arr['Version'];
	}

	function get_db_host()
	{
	    return $this->dbHost;
	}

	function get_table_size($tbName = '')
	{
		$sql = 'show table status';

		if ($tbName) $sql .= " like '$tbName'";

		$rst = $this->query($sql);

		if (!$rst) return false;

		$size = 0;

		while ($row = $this->fetch_array($rst))
		{
			$size += $row['Data_length'] = $row['Index_length'];
		}

		return $size;
	}

	function escape_string($query)
	{
		return mysql_escape_string($query);
	}

	function get_pack_char()
	{
		return '`';
	}

	function db_backup($dbName = '')
	{
		if ($dbName == '') $dbName = $this->dbName;

		$backupStr = '';
		$tables = Array();

		$rstDB = $this->list_tables($dbName);
		for ($i = 0, $cnt = $this->num_rows($rstDB); $i < $cnt; $i++)
		{
			$tables[] = $this->tablename($rstDB, $i);
		}

		for ($i = 0, $cnt = count($tables); $i < $cnt; $i++)
		{

			$backupStr .= "DROP TABLE IF EXISTS `{$tables[$i]}`;\n\n";

			$rstTable   = $this->query("SHOW CREATE TABLE `{$tables[$i]}`");
			$rowTable   = $this->fetch_array($rstTable);

			$backupStr .= $rowTable['Create Table'] . ";\n\n";

			$backupStr .= "LOCK TABLES `{$tables[$i]}` WRITE;\n\n";

			$rstRecord  = $this->query("SELECT * FROM `{$tables[$i]}`");
			while ($rowRecord = $this->fetch_array($rstRecord))
			{
				$comma = "";
				$backupStr .= "INSERT INTO `{$tables[$i]}` VALUES (";

				foreach ($rowRecord as $v)
				{
					$backupStr .= $comma . "'" . $v . "'";
					$comma = " ,";
				}

				$backupStr .= ");\n";
			}

			$backupStr .= "\n" . "UNLOCK TABLES;\n\n";

		}


		return $backupStr;


	}

	//about db
	function create_db($dbName)
	{
		$rt = mysql_create_db($dbName, $this->linkId);

		if (!$rt) raise( new OnlyException( $this->error() ) );

		return $rt;
	}

	function drop_db($dbName)
	{
		$rt = mysql_drop_db($dbName, $this->linkId);

		if (!$rt) raise( new OnlyException( $this->error() ) );

		return $rt;
	}

	function list_dbs()
	{
		$rt = mysql_list_dbs($this->linkId);

		if (!$rt) raise( new OnlyException( $this->error() ) );

		return $rt;
	}

	function db_name($result, $row)
	{
		$rt = mysql_db_name($result, $row);

		if (!$rt) raise( new OnlyException( $this->error() ) );

		return $rt;
	}

	//about table
	function list_tables($dbName = '')
	{
		if (!$dbName) $dbName = $this->dbName;

		$rt = mysql_list_tables($dbName, $this->linkId);

		if (!$rt) raise( new OnlyException( $this->error() ) );

		return $rt;
	}

	function tablename($result, $row)
	{
		$rt = mysql_tablename($result, $row);

		if (!$rt) raise( new OnlyException( $this->error() ) );

		return $rt;
	}

	//about field
	function list_fields($tableName, $dbName = '')
	{
		if (!$dbName) $dbName = $this->dbName;

		$rt = mysql_list_fields($dbName, $tableName, $this->linkId);

		if (!$rt) raise( new OnlyException( $this->error() ) );

		return $rt;
	}

	function field_name($result, $offset)
	{
		$rt = mysql_field_name($result, $offset);

		if (!$rt) raise( new OnlyException( $this->error() ) );

		return $rt;
	}

	function field_type($result, $offset)
	{
		$rt = mysql_field_type($result, $offset);

		if (!$rt) raise( new OnlyException( $this->error() ) );

		return $rt;
	}

	function field_len($result, $offset)
	{
		$rt = mysql_field_len($result, $offset);

		if (!$rt) raise( new OnlyException( $this->error() ) );

		return $rt;
	}

	function fetch_field($result, $offset = 0)
	{
		if ($offset)
		{
			return mysql_fetch_field($result, $offset);
		}
		else
		{
			return mysql_fetch_field($result);
		}
	}

	function field_table($result, $offset)
	{
		$rt = mysql_field_table($result, $offset);

		if (!$rt) raise( new OnlyException( $this->error() ) );

		return $rt;
	}

	function field_seek($result, $offset)
	{
		$rt = mysql_field_seek($result, $offset);

		if (!$rt) raise( new OnlyException( $this->error() ) );

		return $rt;
	}

	function getMax($table_name, $field_name, $where_condition = "")
	{
		if ($where_condition == "")
		{
			$sql = "select max($field_name) as cnt from $table_name";
		}
		else
		{
			$sql = "select max($field_name) as cnt from $table_name where $where_condition";
		}
		$rst = $this->query($sql);
		$row = $this->fetch_array($rst);
		return $row["cnt"];
	}

	function getCount($table_name, $where_condition)
	{
		if ($where_condition == "")
		{
			$sql = "select count(*) as cnt from $table_name";
		}
		else
		{
			$sql = "select count(*) as cnt from $table_name where $where_condition";
		}
		$rst = $this->query($sql);
		$row = $this->fetch_array($rst);
		return $row["cnt"];
	}

	/*
	*/
	function getTableFieldValue($table, $getField, $where)
	{
		if ($table != "" && $getField != "" && $where != "")
		{
			$sql = "select $getField as value from $table $where";
			$rst = $this->query($sql);
			if ($row = $this->fetch_array($rst))
			{
				return $row["value"];
			}
			else
			{
				return null;
			}
		}
		else
		{
			return null;
		}
	}

	function record($adminid, $title, $class)
	{ 
		$unow	= date("Y-m-d H:m:s");
		$uip	= $_SERVER["REMOTE_ADDR"];
 		$usql	= "insert into record(adminid, date, ip, title, class) values(" . $adminid . ", '$unow', '$uip', '$title', '$class')";
		//echo $usql;exit;
 		$rst = $this->query($usql);
 		
 	} 
}
?>
