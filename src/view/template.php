<!DOCTYPE html>
<html lang="fr">
<head>
	<title><?php echo $this->title ?></title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="src/style/screen.css">
	<style> 
		#feedback {
			display: <?php echo !empty($this->feedback) ? 'block' : 'none'; ?>;
			text-align: center;
			font-weight: bold;
			color: white;
			background-color: crimson;
			border-radius: 1em;
			margin: 1em auto;
			max-width: 90%;
			padding: .5em;
		}
		</style>
</head>
<body>
	<header> 
		<nav> 
			<?php echo $this->getMenu(); ?>
		</nav>
		
	</header>
	<main>
	
		<p id="feedback"><?php echo $this->feedback; ?></p>
		<div class="title">
			<h1><?php echo $this->title; ?></h1>
			<?php if (!empty($this->error)): ?>
				<h6><?php echo $this->error; ?></h6>
			<?php endif; ?>
		</div>
		<div class="content">
			<p id="text"><?php echo $this->content; ?></p>
			<p><?php echo "<br>"."<br>".$this->liste; ?></p>
			
		</div>
	</main>
	<?php echo $this->script; ?>
</body>
</html>
