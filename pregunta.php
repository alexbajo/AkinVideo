<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$dbname = "preguntas";
$conn = new mysqli($host, $user, $password, $dbname);

// Obtener la ID de la pregunta desde los parámetros GET
$id = $_GET['id'];

// Consulta SQL para obtener la pregunta y respuestas
$sql = "SELECT p.texto AS pregunta_texto, r.id AS respuesta_id, r.texto AS respuesta_texto, r.siguiente_pregunta_id
		FROM preguntas p
		INNER JOIN respuestas r ON p.id = r.pregunta_id
		WHERE p.id = $id";
$result = $conn->query($sql);

// Crear un arreglo para almacenar los datos
$data = array();

// Si hay resultados
if ($result->num_rows > 0) {
	// Obtener los datos de la pregunta
	$row = $result->fetch_assoc();
	$data['pregunta'] = $row['pregunta_texto'];
	
	// Crear un arreglo para almacenar las respuestas
	$respuestas = array();
	
	// Obtener las respuestas y sus siguientes preguntas
	do {
		$respuesta = array();
		$respuesta['id'] = $row['respuesta_id'];
		$respuesta['texto'] = $row['respuesta_texto'];
		$respuesta['siguiente_pregunta'] = $row['siguiente_pregunta_id'];
		$respuestas[] = $respuesta;
	} while ($row = $result->fetch_assoc());
	
	// Agregar las respuestas al arreglo de datos
	$data['respuestas'] = $respuestas;
}

// Enviar los datos como JSON
header('Content-type: application/json');
echo json_encode($data);
?>
