<?php

	$host 		= "mariadb";
	$user 		= "sqluser";
	$password	= "123456";
	$database	= "information_schema";
	$charset 	= 'utf8mb4';

	$connection = "mysql:host=$host;dbname=$database;charset=$charset";
	$options = [
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES   => false,
	];
	
	try {
		$pdo = new PDO($connection, $user, $password, $options);
	} catch (\PDOException $e) {
		throw new \PDOException($e->getMessage(), (int)$e->getCode());
	}
	
	$info = $pdo->query("SHOW VARIABLES like '%version%'")->fetchAll(PDO::FETCH_KEY_PAIR);
	$server_vendor  = $info['version_compile_os'];
	$server_version = $info['version'];

	$df = disk_free_space("/");
	$dt = disk_total_space("/");
	$du = $dt - $df;
	$dp = ($du / $dt) * 100;

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>SYSInfo</title>
	<style>
		* {
			padding: 0;
			margin: 0;
			box-sizing: border-box;
			font-family: Arial, Helvetica, sans-serif;
		}

		body {
			width: 100%;
			height: 100vh;
			background-color: rgba(40, 40, 40, 1);
			color: white;
			display: flex;
			flex-wrap: wrap;
			align-items: center;
			justify-content: center;
		}

		body table tr td {
			height: 2rem;
			padding: 0.5rem;
		}

		body table tr td:nth-child(even) {
			color: #ffc107;
		}
	</style>
</head>
<body>
	<div>
		<table>
			<tbody>
				<tr>
					<td>System CPU</td>
					<td>
						<?php
						$cpu_info = shell_exec("cat /proc/cpuinfo");
						preg_match_all('/^model name\s*:\s*(.*)$/m', $cpu_info, $matches);
						if (isset($matches[1])) {
							$model_names = array_unique($matches[1]);
							foreach ($model_names as $model_name) {
								echo "$model_name";
							}
						} else {
							echo "No processor model name found.";
						}
						?>
					</td>
				</tr>
				<tr>
					<td>Total Physical Memory</td>
					<td>
						<?php
						$mem_info = file_get_contents("/proc/meminfo");
						if (preg_match('/^MemTotal:\s+(\d+)\skB$/m', $mem_info, $matches)) {
							// Az érték kilobájtban van, ezért átváltjuk megabájtba
							$mem_total_kb = $matches[1];
							$mem_total_mb = $mem_total_kb / 1024;
							$mem_total_gb = $mem_total_mb / 1024;
							echo round($mem_total_gb, 2)." GB";
						} else {
							echo "Unable to determine total physical memory.";
						}
						?>
					</td>
				</tr>
				<tr>
					<td>Memory Usage</td>
					<td><?php echo memory_get_usage() . " bytes";?></td>
				</tr>
				<tr>
					<td>Memory Peak Usage</td>
					<td><?php echo memory_get_peak_usage() . " bytes";?></td>
				</tr>
				<tr>
					<td>Memory Usage Percentage</td>
					<td><?php echo round(memory_get_usage() / $mem_total_kb * 100, 2) . "%";?></td>
				</tr>
				<tr>
					<td>Disk Total Space</td>
					<td><?php echo round($dt / 1024 / 1024 / 1024, 2) . " GB";?></td>
				</tr>
				<tr>
					<td>Disk Used Space</td>
					<td><?php echo round($du / 1024 / 1024 / 1024, 2) . " GB";?></td>
				</tr>
				<tr>
					<td>Disk Free Space</td>
					<td><?php echo round($df / 1024 / 1024 / 1024, 2) . " GB";?></td>
				</tr>
				<tr>
					<td>Disk Usage Percentage</td>
					<td><?php echo round($dp, 2) . "%";?></td>
				</tr>
				<tr>
					<td>OS Distribution</td>
					<td>
						<?php echo $server_vendor;?><br>
						<small><?php echo php_uname('r');?></small>
					</td>
				</tr>
				<tr>
					<td>PHP version</td>
					<td><?php echo phpversion();?></td>
				</tr>
				<tr>
					<td>SQL version</td>
					<td><?php echo $server_version;?></td>
				</tr>
				
			</tbody>
		</table>
	</div>
	
</body>
</html>
