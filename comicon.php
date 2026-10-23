<?php

declare(strict_types=1);

// mostrar tods los errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

$tiposEntrada = [
    'general'  => 'General',
    'vip'      => 'VIP',
    'infantil' => 'Infantil'
];

$talleresDisponibles = [
    'torneo_videojuegos' => ['nombre' => 'Torneo de Videojuegos', 'precio' => 10],
    'taller_cosplay'     => ['nombre' => 'Taller de Cosplay',     'precio' => 15],
    'zona_rol'           => ['nombre' => 'Zona de Rol en Vivo',   'precio' => 5]
];

function calcularPrecioBase(string $tipo): int
{
    switch ($tipo) {
        case 'general':
            $precio = 30;
            break;
        case 'vip':
            $precio = 60;
            break;
        case 'infantil':
            $precio = 10;
            break;
        default:
            throw new Exception("El tipo de entrada seleccionado no es válido.");
    }

    return $precio;
}

function procesarInscripcion(string $tipo, array $talleresSeleccionados): array
{
    global $talleresDisponibles;

    $precioBase = calcularPrecioBase($tipo);
    $total = $precioBase;
    $detalleTalleres = [];

    foreach ($talleresSeleccionados as $clave) {
        // comprobar que el taller existe
        if (!is_string($clave) || !isset($talleresDisponibles[$clave])) {
            throw new Exception("Se ha enviado un taller que no existe.");
        }

        $taller = $talleresDisponibles[$clave];
        array_push($detalleTalleres, $taller); // guaradr el nombre y el precio
        $total += $taller['precio'];           // acumular el coste
    }

    return [
        'precioBase' => $precioBase,
        'talleres'   => $detalleTalleres,
        'total'      => $total
    ];
}

$exito = false;
$mensajeError = '';
$resguardo = [];
$nombre = '';
$edadTexto = '';
$tipo = '';
$talleresMarcados = [];

// procesar el formulario enviado por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nombreRecibido   = $_POST['nombre'] ?? '';
        $edadTexto        = $_POST['edad'] ?? '';
        $tipo             = $_POST['tipo_entrada'] ?? '';
        $talleresMarcados = $_POST['talleres'] ?? [];

        $nombre = htmlspecialchars(trim($nombreRecibido), ENT_QUOTES, 'UTF-8');

        // edad positiva y mayor que cero
        $opciones = [
            'options' => ['min_range' => 1]
        ];
        $edad = filter_var($edadTexto, FILTER_VALIDATE_INT, $opciones);

        if ($nombre === '') {
            throw new Exception("El nombre es obligatorio.");
        } elseif ($edad === false) {
            throw new Exception("La edad debe ser un número entero positivo.");
        } elseif (!is_string($tipo) || !isset($tiposEntrada[$tipo])) {
            throw new Exception("Debes seleccionar un tipo de entrada válido.");
        } elseif (!is_array($talleresMarcados)) {
            throw new Exception("Los talleres no tienen un formato válido.");
        } elseif ($edad < 18 && $tipo === 'vip') {
            throw new Exception("La entrada VIP está restringida a mayores de edad");
        } else {
            // calcular el precio.
            $resguardo = procesarInscripcion($tipo, $talleresMarcados);
            $exito = true;
        }
    } catch (Exception $e) {
        $mensajeError = $e->getMessage();
    } finally {
        $resultado = $exito ? 'EXITOSO' : 'FALLIDO';
        error_log("[" . date('Y-m-d H:i:s') . "] Inscripción Comic-Con procesada. Resultado: " . $resultado);
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comic-Con</title>
</head>

<body>
    <h1>Inscripción Comic-Con</h1>

    <form action="comicon.php"
        method="POST">
        <br>

        <label for="nombre">Nombre completo:</label>
        <input type="text"
            id="nombre"
            name="nombre"
            value="<?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') ?>" required />
        <br>

        <label for="edad">Edad:</label>
        <input type="number"
            id="edad"
            name="edad"
            value="<?= htmlspecialchars($edadTexto, ENT_QUOTES, 'UTF-8') ?>" required />
        <br>

        <label for="tipo_entrada">Tipo de entrada:</label>
        <select id="tipo_entrada" name="tipo_entrada" title="Tipo de entrada:">
            <option value="">Seleccione un tipo</option>
            <?php
            foreach ($tiposEntrada as $clave => $texto) {
                $seleccionado = ($tipo === $clave) ? ' selected' : '';
                echo '<option value="' . $clave . '"' . $seleccionado . '>' . $texto . ' (disponible)</option>';
            }
            ?>
        </select>
        <br>

        <p>Talleres especiales:</p>
        <?php
        foreach ($talleresDisponibles as $clave => $taller) {
            $marcado = (is_array($talleresMarcados) && in_array($clave, $talleresMarcados)) ? ' checked' : '';
            echo '<label for="' . $clave . '">' . $taller['nombre'] . ' (' . $taller['precio'] . ' euros)</label>';
            echo '<input type="checkbox" id="' . $clave . '" name="talleres[]" value="' . $clave . '"' . $marcado . '>';
            echo '<br>';
        }
        ?>
        <br>

        <button type="submit">Enviar</button>

    </form>

    <?php if ($mensajeError !== ''): ?>
        <p><strong>Error:</strong> <?= htmlspecialchars($mensajeError, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <?php if ($exito): ?>
        <h2>Resguardo de inscripción</h2>
        <p><strong>Asistente:</strong> <?= $nombre ?></p>
        <p><strong>Entrada:</strong> <?= $tiposEntrada[$tipo] ?> - <?= $resguardo['precioBase'] ?> euros</p>

        <p><strong>Talleres:</strong></p>
        <ul>
            <?php
            if (count($resguardo['talleres']) === 0) {
                echo '<li>No se ha apuntado a ningún taller.</li>';
            } else {
                foreach ($resguardo['talleres'] as $taller) {
                    echo '<li>' . $taller['nombre'] . ' - ' . $taller['precio'] . ' euros</li>';
                }
            }
            ?>
        </ul>

        <p><strong>Total a pagar: <?= $resguardo['total'] ?> euros</strong></p>
    <?php endif; ?>

</body>

</html>