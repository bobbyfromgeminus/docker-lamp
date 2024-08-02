<?php
	include './assets/db.php';

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
	<link rel="stylesheet" href="./assets/style.css">
</head>
<body>
	<div>
		
		<?php echo '<h1>'.gethostname().'</h1>';?>
		<?php echo '<h2>'.shell_exec('uptime -p').'</h2>';?>

		<div class="container">


			<div class="subcontainer">

				<h3>SYSTEM</h3>

				<div class="box">
					<p>SYSTEM CPU</p>
					<div>
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
					</div>
				</div>

				<div class="box">
					<p>OS Distribution</p>
					<div>
						<?php echo "<b>" . $server_vendor . "</b><br>";?>
						<?php echo php_uname('r');?>
					</div>
				</div>

				<div class="box">
					<p>PHP version</p>
					<div>
						<?php echo phpversion();?>
					</div>
				</div>

				<div class="box">
					<p>SQL version</p>
					<div>
						<?php echo $server_version;?>
					</div>
				</div>

			</div>



			<div class="subcontainer">

				<h3>MEMORY</h3>

				<div class="box">
					<p>Total Physical Memory</p>
					<div>
						<?php
						$mem_info = file_get_contents("/proc/meminfo");
						if (preg_match('/^MemTotal:\s+(\d+)\skB$/m', $mem_info, $matches)) {
							// Az érték kilobájtban van, ezért átváltjuk megabájtba
							$mem_total_kb = $matches[1];
							$mem_total_mb = $mem_total_kb / 1024;
							$mem_total_gb = $mem_total_mb / 1024;
							echo "<b>" . round($mem_total_gb, 2)."</b> GB";
						} else {
							echo "Unable to determine total physical memory.";
						}
						?>
					</div>
				</div>

				<div class="box">
					<p>Memory Usage</p>
					<div>
						<?php echo "<b>" .  round(memory_get_usage() / 1024 / 1024 , 3) . "</b> GB";?>
					</div>
				</div>

				<div class="box">
					<p>Memory Peak Usage</p>
					<div>
						<?php echo "<b>" .  round(memory_get_peak_usage() / 1024 / 1024 , 3) . "</b> GB";?>
					</div>
				</div>

				<div class="box">
					<p>Memory Usage Percentage</p>
					<div>
						<?php echo "<b>" . round(memory_get_usage() / $mem_total_kb * 100, 2) . "</b> %";?>
					</div>
				</div>

			</div>



			<div class="subcontainer">

				<h3>DISK</h3>

				<div class="box">
					<p>Total Disk Space</p>
					<div>
						<?php echo "<b>" . round($dt / 1024 / 1024 / 1024, 2) . "</b> GB";?>
					</div>
				</div>

				<div class="box">
					<p>Used Disk Space</p>
					<div>
						<?php echo "<b>" . round($du / 1024 / 1024 / 1024, 2) . "</b> GB";?>
					</div>
				</div>

				<div class="box">
					<p>Free Disk Space</p>
					<div>
						<?php echo "<b>" . round($df / 1024 / 1024 / 1024, 2) . "</b> GB";?>
					</div>
				</div>

				<div class="box">
					<p>Disk Usage Percentage</p>
					<div>
						<?php echo "<b>" . round($dp, 2) . "</b> %";?>
					</div>
				</div>

			</div>


		</div>
		
	</div>
	
</body>
</html>
