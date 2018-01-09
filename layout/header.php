<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php if(isset($title)){ echo $title; }?></title>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/main.css">
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
   	<script src="ckeditor/ckeditor.js"></script>
	<script src="ckeditor/samples/js/sample.js"></script>
	<link rel="stylesheet" href="ckeditor/samples/css/samples.css">
	<link rel="stylesheet" href="ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css">
</head>
<body>
	<div class="container-fluid">
		<nav class="navbar navbar-default">
				<div class="logo">
					MaxDS.com
				</div>
				<div class="user-header pull-right">
					User : <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES); ?>
				</div>
			<ul class="nav navbar-nav pull-right" style="margin-top: -25px;">
				<li><a href='dashboard.php'><span class="glyphicon glyphicon-check"></span> Dashboard</a></li>
				<li><a href='uploader.php'><span class="glyphicon glyphicon-cloud-upload"></span> Uploader</a></li>
				<li><a href='active_listings.php'> <span class="glyphicon glyphicon-th-list"></span> Active Listings</a></li>
				<li><a href='monitor.php'><span class="glyphicon glyphicon-search"></span> Monitor</a></li>
				<li><a href='monitor.php'><span class="glyphicon glyphicon-signal"></span> Statistics</a></li>
				<li><a href='settings.php'><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
				<li><a href='logout.php'><span class="glyphicon glyphicon-off"></span> Logout</a></li>
			</ul>
		</nav>
	</div>
<div class="container">