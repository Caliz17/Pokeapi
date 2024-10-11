<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Consumiendo API de Pokémon - Desarrollo Web</title>
	<link rel="stylesheet" href="style.css"> <!-- Enlace al archivo CSS -->
</head>

<body>
	<!-- Información introductoria sobre el proyecto -->
	<h2>Consumo de API de la página Pokeapi.co</h2>

	<!-- Formulario para seleccionar un Pokémon por su ID -->
	<form method="POST" action="api_1.php">
		<label for="pokemon-id">Seleccione un Pokémon por su ID:</label>
		<select name="id" id="pokemon-id">
			<option value="0">Seleccione</option>
			<?php
            for ($i = 1; $i <= 150; $i++) {
                echo "<option value='$i'>$i</option>";
            }
			?>
		</select>
		<button type="submit">Buscar Pokémon</button>
	</form>

	<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && $_POST['id'] != 0) {
    $id = $_POST['id'];
    $ch1 = curl_init();
    curl_setopt($ch1, CURLOPT_URL, "https://pokeapi.co/api/v2/pokemon/$id/");
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
    $resp = curl_exec($ch1);
    curl_close($ch1);
    $poke = json_decode($resp, true);

    echo "<h3>Detalles del Pokémon ID: $id</h3>";

    if (!empty($poke['name'])) {
        $ch = curl_init();
        $url = "https://pokeapi.co/api/v2/pokemon/" . $poke['name'];
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo '<p>Error al conectarse al API</p>';
        } else {
            curl_close($ch);
            $data = json_decode($response, true);

            echo '<div class="pokemon-card">';
            echo '<h1>' . ucfirst($data['name']) . '</h1>';
            echo '<img src="' . $data['sprites']['front_default'] . '" alt="' . $data['name'] . '">';
            echo '<ul class="pokemon-details">';
            echo '<li><strong>Nombre: </strong>' . ucfirst($data['name']) . '</li>';
            echo '<li><strong>Altura: </strong>' . $data['height'] . ' dm</li>';
            echo '<li><strong>Peso: </strong>' . $data['weight'] . ' hg</li>';
            echo '<li><strong>Habilidades: </strong>';
            echo '<ul>';
            foreach ($data['abilities'] as $habilidad) {
                echo '<li>' . ucfirst($habilidad['ability']['name']) . '</li>';
            }
            echo '</ul>';
            echo '</li>';
            echo '</ul>';
            echo '</div>';
        }
    } else {
        echo '<p>No se encontró información para el ID seleccionado.</p>';
    }
}
			?>
</body>

</html>