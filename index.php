<?php

class Schlumpf 
{
	private $pdo;
	private $dsn;
	private $dbname;
	private $action;

	#####################################################
	#                                                   #
	# Constructor                                       #
	#                                                   #
	#####################################################

	public function __construct() 
	{		
    #choose MySQL or SQLite below
    
	$this->dbname = 'db_name';
#	$this->dsn = "mysql:dbname={$this->dbname};host=127.0.0.1:3306";
	$this->dsn = 'sqlite:Schlumpf.db';
	$user = 'db_user';
	$password = 'db_password';

	$this->action = "http://".$_SERVER['HTTP_HOST'].preg_replace("/(\?.+)$/", "", $_SERVER["REQUEST_URI"]);
	print "{$this->dsn}\n";
	try {
		$this->pdo = new PDO($this->dsn, $user, $password);
	} catch (PDOException $e) {
		echo 'Connection failed: ' . $e->getMessage();
	}
	}
  
	#####################################################
	#                                                   #
	# destructor                                        #
	#                                                   #
	#####################################################
	
	public function __destruct() 
	{
	$this->pdo = null;
	}
	
	public function Action() 
	{
	return $this->action;
	}

	#####################################################
	#                                                   #
	# Populate                                          #
	#                                                   #
	#####################################################
	public function Populate() 
	{
	if (preg_match ("/sqlite/i", $this->dsn) and (filesize ('Schlumpf.db') != 0))
		return;
	
	$strings = array(
	'drop table if exists activity_tb',
	"create table if not exists activity_tb (
	id  integer primary key AUTO_INCREMENT,
	activity text NOT NULL,
	description text NULL,
	category_id int NOT NULL,
	venue text NOT NULL,
	latitude float NULL,
	longitude float NULL,
	distance float NULL,
	travel_time text NULL)",
	"insert into activity_tb (activity, category_id, venue, latitude, longitude) values 
	('spelling bee', 3, 'Hornsby',-33.868820, 151.099010)",
	"insert into activity_tb (activity, category_id, venue, latitude, longitude) values 
	('swimming carnival', 1, 'Mona Vale', -33.677830, 151.302290)",
	"insert into activity_tb (activity, category_id, venue, latitude, longitude) values 
	('zone athletics', 1, 'Castle Hill',  -33.729252, 151.004013)",
	"insert into activity_tb (activity, category_id, venue, latitude, longitude) values 
	('excursion at the zoo', 2, 'Taronga', -33.843548, 151.241348)",
	"insert into activity_tb (activity, category_id, venue, latitude, longitude) values 
	('a day in court', 2, 'Lane Cove', -33.814871, 151.166435)",
	"insert into activity_tb (activity, category_id, venue, latitude, longitude) values 
	('exploring the city', 3, 'Sydney', -33.868820, 151.209290)",

	'drop table if exists category_tb',
	"create table if not exists category_tb (
	id integer primary key AUTO_INCREMENT,
	category text NOT NULL)",
	"insert into category_tb (category) values ('sport')",
	"insert into category_tb (category) values ('visit')",
	"insert into category_tb (category) values ('academic')",
	
	'drop view if exists activity_tb_view',
	"create view if not exists activity_tb_view as 
	select activity_tb.id as id, 
	activity_tb.activity as activity, 
	category_tb.category as category, 
	activity_tb.venue as venue,
	activity_tb.latitude as latitude,
	activity_tb.longitude as longitude,
	activity_tb.distance as distance,
	activity_tb.travel_time as time
	from activity_tb, category_tb 
	where activity_tb.category_id =  category_tb.id",

	'drop table if exists capacity_tb',
	"create table if not exists capacity_tb (
	id integer primary key AUTO_INCREMENT,
	capacity text NOT NULL)",
	"insert into capacity_tb (capacity) values ('organiser')",
	"insert into capacity_tb (capacity) values ('staff')",
	"insert into capacity_tb (capacity) values ('parent')",
	"insert into capacity_tb (capacity) values ('volunteer')",
	"insert into capacity_tb (capacity) values ('student')",

	'drop table if exists person_tb',
	"create table if not exists person_tb (
	id integer primary key AUTO_INCREMENT,
	person text NOT NULL)",
	"insert into person_tb (person) values ('Mrs Smith')",
	"insert into person_tb (person) values ('Mr Doodle')",
	"insert into person_tb (person) values ('Ms Wilson')",
	"insert into person_tb (person) values ('Lucy Sommerfield')",
	"insert into person_tb (person) values ('David Walnaugh')",
	"insert into person_tb (person) values ('Mary Ellison')",
	"insert into person_tb (person) values ('Mrs Sommerfield')",
	"insert into person_tb (person) values ('Mrs Walnaugh')",
	"insert into person_tb (person) values ('Mrs Ellison')",

	'drop table if exists booking',
	"create table if not exists booking (
	id  integer primary key AUTO_INCREMENT,
	activity_id int NOT NULL,
	person_id int NOT NULL,
	capacity_id int NOT NULL,
	authority_id int NOT NULL,
	paid text NULL,
	attended text NULL)",
	"insert into booking (activity_id, person_id, capacity_id, authority_id) values (1, 4, 5, 7)",
	"insert into booking (activity_id, person_id, capacity_id, authority_id) values (2, 4, 5, 7)",
	"insert into booking (activity_id, person_id, capacity_id, authority_id) values (3, 4, 5, 7)",
	"insert into booking (activity_id, person_id, capacity_id, authority_id) values (4, 4, 5, 7)",
	"insert into booking (activity_id, person_id, capacity_id, authority_id) values (5, 4, 5, 7)",
	"insert into booking (activity_id, person_id, capacity_id, authority_id) values (1, 5, 5, 8)",
	"insert into booking (activity_id, person_id, capacity_id, authority_id) values (2, 5, 5, 8)",
	"insert into booking (activity_id, person_id, capacity_id, authority_id) values (3, 5, 5, 8)",
	"insert into booking (activity_id, person_id, capacity_id, authority_id) values (4, 5, 5, 8)",
	"insert into booking (activity_id, person_id, capacity_id, authority_id) values (5, 5, 5, 8)",
	"insert into booking (activity_id, person_id, capacity_id, authority_id) values (1, 6, 5, 9)",
	"insert into booking (activity_id, person_id, capacity_id, authority_id) values (2, 6, 5, 9)",
	"insert into booking (activity_id, person_id, capacity_id, authority_id) values (3, 6, 5, 9)",
	"insert into booking (activity_id, person_id, capacity_id, authority_id) values (4, 6, 5, 9)",
	"insert into booking (activity_id, person_id, capacity_id, authority_id) values (5, 6, 5, 9)",

	"insert into booking (activity_id, person_id, capacity_id, authority_id) values (1, 1, 1, 10)",
	"insert into booking (activity_id, person_id, capacity_id, authority_id) values (2, 2, 2, 10)",
	"insert into booking (activity_id, person_id, capacity_id, authority_id) values (3, 3, 4, 10)",
	"insert into booking (activity_id, person_id, capacity_id, authority_id) values (4, 1, 1, 10)",
	"insert into booking (activity_id, person_id, capacity_id, authority_id) values (5, 2, 2, 10)",
	"insert into booking (activity_id, person_id, capacity_id, authority_id) values (5, 3, 4, 10)",

	'drop view if exists booking_view',
	"create view if not exists booking_view as 
	select booking.id as id, activity_tb.activity as activity, 
	person_tb.person as person, capacity_tb.capacity as capacity, 
	(select person from person_tb where id = booking.authority_id) as authority
	from booking, activity_tb, person_tb, capacity_tb
	where booking.activity_id =  activity_tb.id 
	and booking.person_id = person_tb.id
	and booking.capacity_id = capacity_tb.id"
	);
	
	foreach ($strings as $string)
		{
		if (preg_match ("/sqlite/i", $this->dsn))
			$string = preg_replace("/AUTO_INCREMENT/i", "AUTOINCREMENT", $string);

		if (preg_match ("/mysql/i", $this->dsn))
			$string = preg_replace("/create view if not exists/i", "create or replace view", $string);

		$retval = $this->pdo->exec($string);
		if ($retval === false)
			{
			print $string."\n";
			var_dump($this->pdo->errorInfo());
			die;
			}			
		}
	}

	#####################################################
	#                                                   #
	# EditTable                                         #
	#                                                   #
	#####################################################

	public function EditTable ($table, $row) 
	{
	$colTypes  = $this->FetchColumnTypes($table);
	$colValues = $this->FetchColumnValues($table, $row);
	
	print '<table id="Schlumpf">';
	echo "<form class=\"edittable\" name=\"myform\" action=\"{$this->action}\" method=\"post\" enctype=\"multipart/form-data\">";
	foreach ($colTypes as $key => $value)
		{
		if (strtolower($key) === 'id')
			continue;
		
		else if (preg_match("/_id$/", $key)) # a lookup field
			{
			$lookupName = preg_replace("/_id$/", "", $key);
			print "<tr><td>{$lookupName}</td><td>";
			print "<SELECT NAME=\"$key\">\n";
			$luid = $luval = '';
			foreach ($retval = $this->pdo->query("select id, $lookupName from {$lookupName}_tb order by $lookupName") as $lurow) 
				{
				$selected = '';
				foreach ($lurow as $lukey => $luvalue) # could be other columns we don't care about now...
					{
					if ($lukey === 'id')
						$luid = $luvalue;
					
					if ($lukey === $lookupName)
						$luval = $luvalue;			

					if ($colValues[$key] == $luvalue)
						$selected = "selected";
					}
					
				print "<OPTION VALUE=\"$luid\" $selected>$luval\n"; 					
				}
			print "</td></tr>\n";
			}
		else
			{
			print "<tr><td>$key</td><td>";
			switch ($key)
				{
				case 'datetime':
					print "<input type=\"datetime-local\" name=\"$key\" value=\"{$colValues[$key]}\">";
					break;
					
				case 'int':
				case 'integer':
				case 'float':
					print "<input type=\"number-local\" name=\"$key\" value=\"{$colValues[$key]}\">";
					break;

				case '':
				default:
					print "<input type=\"text\" name=\"$key\" value=\"{$colValues[$key]}\">";
				}
			print "</td></tr>\n";
			}
		}
	print "<tr><td><input type=\"hidden\" name=\"rowid\" value=\"$row\"/>";
	print "<input type=\"hidden\" name=\"table\" value=\"$table\"/>";
	if ($row == -1)
		print "</td><td><input type=\"submit\" name=\"button\" value=\"NEW\"/></td</tr>\n";
	else
		{
		print "<input type=\"submit\" name=\"button\" OnClick=\"return confirm('Are you sure you want to delete this record ?');\"value=\"DELETE\"/></td><td>\n";
		print "<input type=\"submit\" name=\"button\" value=\"SAVE\"/></td</tr>\n";
		}
	print "</table>\n</form>\n";
	}
	
	#####################################################
	#                                                   #
	# PostTable                                         #
	#                                                   #
	#####################################################

	public function PostTable () 
	{
	$rowid =  $_POST['rowid'];
	$table =  $_POST['table'];
	$button = $_POST['button'];
	
	if ($_POST['button'] === "DELETE")		
		$rowid *= -1;
		
	if ($_POST['button'] === "NEW")				
		$rowid = -1;
	
	unset ($_POST['rowid']);					
	unset ($_POST['table']);					
	unset ($_POST['button']);					

	if ((intval($rowid) == -1) and ($button === 'NEW'))
		{
		$sql = "insert into $table (";
		$j = 0;
		foreach ($_POST as $field => $value) 
			{
			if ($j != 0)
				{ $sql .=  ", "; }	
				
			$sql .= $field;
			$j++;
			}
		$sql .= ") values (";
		
		$j = 0;
		foreach ($_POST as $field => $value) 
			{
			if ($j != 0)
				{ $sql .=  ", "; }	
				
#			$sql .= "'" . sqlite_escape_string(trim($value)) . "'";
			$sql .= "'" . trim($value) . "'";
			
			$j++;
			}
					
		$sql .= ");";
		}
	
	else if ((intval($rowid) > 0)and ($button === 'SAVE'))
		{
		$sql = "update $table set ";
		
		$j = 0;
		foreach ($_POST as $field => $value) 
			{
			if ($j != 0)
				{ $sql .=  ", "; }	
				
			$sql .= $field;
#			$sql .= " = '" . sqlite_escape_string(trim($value)) . "'";
			$sql .= " = '" . trim($value) . "'";
				
			$j++;
			}
					
		$sql .= " where id = $rowid;";
		}

	else if ((intval($rowid) < 0)and ($button === 'DELETE'))
		$sql = sprintf("delete from $table where id = %d;", abs($rowid));

	  try 
		{  
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$this->pdo->beginTransaction();
		$this->pdo->exec($sql);
		$this->pdo->commit();
		} 
	catch (Exception $e) 
		{
		$this->pdo->rollBack();
		echo "Failed: " . $e->getMessage();
		}
	}
	
	#####################################################
	#                                                   #
	# FetchColumnValues                                 #
	#                                                   #
	#####################################################

	private function FetchColumnValues ($table, $id)
	{
	$cols = array();
	
    if ($id == -1)
		$idd = 1;
	else
		$idd = $id;
	foreach ($retval = $this->pdo->query("select * from $table where id = $idd") as $row) 
		{
		foreach ($row as $key => $value)
			{
			if (!is_numeric ($key))
				{
				if ($id == -1)
					$value = "";
				$cols[$key] = $value;
				}
			}
		}
	return $cols;
	}
	
	#####################################################
	#                                                   #
	# Display                                           #
	#                                                   #
	#####################################################

	public function Display($sql) 
	{
#	print "<hr>$sql<hr>\n";
	preg_match("/from\s+(\S+)/i", $sql, $matches);
	$view = $matches[1];
	$table = preg_replace ("/_view/i", "", $view);
	print "<div id=\"tablehead\"><h2>".preg_replace("/_tb$/", "", $table)."</h2></div>\n";
	print "<a href=\"{$this->Action()}?table=$table&row=-1\">new item</a>";
	print '<table id="Schlumpf">';
	$j=0;
    foreach ($retval = $this->pdo->query($sql) as $row) 
		{
		$i=0;
		print " <tr>\n";
		if ($j==0)
			{
			print " <tr>\n";
			foreach ($row as $key => $value)
				{
				if (!is_numeric ($key))
					{
					if (isset($_GET['dir']))
                        {
                        if ($_GET['dir'] === 'asc')
                            print "<th><a href=\"{$this->action}?table=$view&order=$key&dir=desc\">$key</a></th>";
                        else        
                            print "<th><a href=\"{$this->action}?table=$view&order=$key&dir=asc\">$key</a></th>";
                        }
                    else
                        print "<th><a href=\"{$this->action}?table=$view&order=$key&dir=desc\">$key</a></th>";
					$i++;
					}
				}
			$i=0;
			print " </tr>\n<tr>";
			}
		

	 	foreach ($row as $key => $value)
			{
			if (!is_numeric ($key))
				{
				if ($key === 'id')
					$value = "<a href=\"{$this->action}?table=$table&row=$value\">$value</a>";

				print "<td>$value</td>";
				$i++;
				}
			}
		print " </tr>\n";
		$j++;
		}
	
	print "</table>\n";
	if (!$retval)
		var_dump($this->pdo->errorInfo());
	}
	
	#####################################################
	#                                                   #
	# FetchColumnTypes                                  #
	#                                                   #
	#####################################################
	
	function FetchColumnTypes($table)
	{
	$cols = array();
	if (preg_match ("/sqlite/i", $this->dsn))
		{
		foreach ($retval = $this->pdo->query("select * from sqlite_master where type='table' and name='$table'") as $row) 
			{
			print "\n";
			foreach ($row as $key => $value)
				{
				if ($key == 'sql')
					{
					if (preg_match ("/\((.+)\)/smU", $value, $matches))
						{
						$fields = preg_split ("/,/", $matches[1], -1, PREG_SPLIT_NO_EMPTY);
						
						foreach ($fields as $field)
							{
							$tokens = preg_split ("/\s/",trim($field), -1, PREG_SPLIT_NO_EMPTY);
							$cols[$tokens[0]] = $tokens[1];
							}
						}
					}
				}
			}
		return $cols;
		}

	if (preg_match ("/mysql/i", $this->dsn))
		{
		foreach ($retval = $this->pdo->query("select column_name, data_type from information_schema.columns where table_name = '$table' and table_schema = '{$this->dbname}'") as $row) 
			{
			$cols[$row[0]] = $row[1];
			}
		return $cols;
		}
	}
} # end of class Schlumpf

$myTitle = "Minimal web app in PHP";

?>
<html>
<head>
<title><?php echo $myTitle; ?></title>
<!-- <link rel="stylesheet" type="text/css" href="/Schlumpf.css" />
For the sake of "all in one" we will have inline styling rather than a css file -->
<style>
#Schlumpf {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}
#Schlumpf div {
    margin: auto;
    width: 50%;
    text-align: center;
    border: 3px solid green;
    padding: 10px;
}
#footer {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: center;
    background-color: black;
    color: white;
    font-style: italic;
    font-weight: bold;
}
#tablehead {
    margin: auto;
    width: 50%;
    text-align: center;
}
#Schlumpf table {
    margin: auto;
//    width: auto;
    padding: 10px;
} 
#Schlumpf td, #Schlumpf th {
    border: 1px solid #ddd;
    padding: 8px;
}

#Schlumpf tr:nth-child(even){background-color: #f2f2f2;}

#Schlumpf tr:hover {background-color: #ddd;}

#Schlumpf th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #4CAF50;
    color: white;
}

#content a, .menu a:link, .menu a:active, .menu a:visited 
{
text-decoration: none;
}
#content a:hover 
{
background-color: black;
color: white;
}

.nav 
{
align: center;
margin: 10px 10px;
padding-top: 8px;
padding-bottom: 10px;
padding-left: 8px;
background: none;
}

.nav li 
{
list-style-type: none;
display: inline;
padding: 10px 30px;
background-color: #e67e22;
margin-left: -11px;
font-size: 120%;
}

.nav li:first-child
{
margin-left: 0;
border-top-left-radius: 10px !important;
border-bottom-left-radius: 10px !important;
}

.nav li:last-child
{
margin-right: 0px;
border-top-right-radius: 10px !important;
border-bottom-right-radius: 10px !important;
}

.nav a, .menu a:link, .menu a:active, .menu a:visited 
{
text-decoration: none;
color: white;
border-bottom: 0;
padding: 10px 10px;
}

.nav a:hover 
{
text-decoration: none;
background: #9b59b6;
padding: 10px 10px;
}

ul.nav li a.current 
{
text-decoration: none;
background: #e74c3c;
padding: 10px 10px;
}
</style>

</head>
<body>

	
<center><h1><?php echo $myTitle; ?></h1></center>
<?php

$doodle = new Schlumpf();
echo  <<<EOHTML
<center><ul class="nav">
<li> <a href="{$doodle->Action()}">Home</a></li>
<li> <a href="{$doodle->Action()}?menu=blog">readme</a></li>
<li> <a href="{$doodle->Action()}?menu=booking">booking</a></li>
<li> <a href="{$doodle->Action()}?menu=activity">activity</a></li>
<li> <a href="{$doodle->Action()}?menu=booking">booking</a></li>
<li> <a href="{$doodle->Action()}?menu=category">category</a></li>
<li> <a href="{$doodle->Action()}?menu=capacity">capacity</a></li>
<li> <a href="{$doodle->Action()}?menu=person">person</a></li>
</ul></center>
EOHTML;

if (isset($_GET['menu']))
	{
	switch ($_GET['menu'])
		{
		case 'blog':
		#readfile ("readme.html");
        echo  <<<EOHTML
<center><div style="width: 75%; padding: 10px 10px; display: block; text-align: left;">
<h1>Really all in one</h1>
<p>
I got peeved with all those frameworks that promise wonders and deliver much less.
<br /><br />
This is the original minimal PHP web app. I did a port in Python also.
<br /><br />
It really lives up to its name of "all in one" as this readme text and the css styling are all
tucked away in the index.php file!
<br /><br />
I tried to segregate my data structures from the executable code. The benefit in my view is that the data definitions exist ONLY in one place
 - the SQL statements to build the tables and views.
<br /><br />
There is a way in both MySQL and SQLite (which are both supported in this project) to interogate
the schema and iterate thru the fields of a data entry form (ie the columns of a table).
Therefore you do not polute your data manager code with knowledge of the data structures. Any changes made
to these doesn't involve any rework - especially in forms and reports.
<br /><br />
This comes at the cost of some begnin naming conventions. If you have a foreign key in your record you call it 
foreign_id and you expect the value to be displayed for this lookup field to be in a column called foreign in a 
table aptly called foreign_tb or a view called foreign_tb_view. Quite simple really.
<br /><br />
</p>
</div></center>                   
EOHTML;
		break;
		
		case 'category':
		$doodle->Display('select * from category_tb');
		break;

		case 'capacity':
		$doodle->Display('select * from capacity_tb');
		break;
		
		case 'person':
		$doodle->Display('select * from person_tb');
		break;

		case 'activity':
		$doodle->Display('select * from activity_tb_view order by category');
		break;

		case 'booking':
		$doodle->Display('select * from booking_view order by person');
		break;
		}
	}
	
else if (isset($_GET['order']))
    {
    if (isset($_GET['dir']))
        $doodle->Display("select * from {$_GET['table']} order by {$_GET['order']} {$_GET['dir']}");
    else
        $doodle->Display("select * from {$_GET['table']} order by {$_GET['order']}");
    }

else if (isset($_GET['table']))
	$doodle->EditTable($_GET['table'], $_GET['row']);

else if (isset($_POST['table']))
	$doodle->PostTable();

else
	{
	$doodle->Populate();
	$doodle->Display('select * from activity_tb_view order by category');
	$doodle->Display('select * from booking_view order by person');
	}
	
?>
<div id="footer">Say NO to bloatware</div>
</body>
</html>

