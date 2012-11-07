<?php
//exit;
$ops = array(
	'Movistar',
	'Vodafone',
	'Orange',
	'Yoigo',
	'Simyo ',
	'Euskaltel ',
	'Lebara Mobile',
	'Digi',
	'Pepephone',
	'Carrefour Mobile',
	'BT Mobile',
	'ONO M',
	'HITS Mobile',
	'TeleCable',
	'Tuenti'
);
$randomOp = array_rand($ops);
?>
<!DOCTYPE html>
<html>
	<head>
		<style type="text/css">
			body {
				font-family: sans-serif;
			}
			input[type='text'], select {
				width: 250px;
			}
		</style>
	</head>
	<body>
		<form autocomplete="off" target="serviceTestTarget" method="GET" action="/service">
			<input type="hidden" name="task" value="generate_pin" />
			<table>
				<tr>
					<th><label for="telefono" >Telefono</label></th>
					<td><input type="text" id="telefono" name="telefono" value="07<?php echo str_pad(rand(000, 999), 3, 0, STR_PAD_RIGHT) ?> <?php echo rand(111, 999) ?> <?php echo rand(333, 999) ?>" /></td>
				</tr>
				<tr>
					<th><label for="texto" >Texto</label></th>
					<td><input type="text" id="texto" name="texto" value="SIDA" /></td>
				</tr>
				<tr>
					<th><label for="fecha" >Fecha</label></th>
					<td><input type="text" id="fecha" name="fecha" value="<?php echo date('c') ?>" /></td>
				</tr>
				<tr>
					<th><label for="operadora" >Operadora</label></th>
					<td>
						<select name="operadora" id="operadora">
							<?php foreach ( $ops AS $opKey => $op ): ?>
								<option <?php echo $randomOp == $opKey ? 'selected="selected" ' : '' ?> value="<?php echo $op ?>"><?php echo $op ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" /></td>
				</tr>
			</table>
		</form>
		<iframe name="serviceTestTarget" id="serviceTestTarget" width="800px" height="400px"></iframe>
		
	</body>
</html>