<?php
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
        name="nombre" required/>
        <br>

        <label for="edad">Edad:</label>
        <input type="number"
        id="edad"
        name="edad" required/>
        <br>

        <label for="tipo_entrada">Tipo de entrada:</label>
                <select id="tipo_entrada" name="tipo_entrada" title="Tipo de entrada:">
                    <option value="">Seleccione un tipo</option>
                    <option value="general">General (disponible)</option>
                    <option value="vip">VIP (disponible)</option>
                    <option value="infantil">Infantil (disponible)</option>
                </select>
        <br>

        <p>Talleres especiales:</p>
        <label for="torneo_videojuegos">Torneo de Videojuegos (10 euros)</label>
        <input type="checkbox" 
        id="torneo_videojuegos" 
        name="talleres[]">
        <br>
            
        <label for="taller_cosplay">Taller de Cosplay (15 euros)</label>
        <input type="checkbox" 
        id="taller_cosplay" 
        name="talleres[]">
        <br>

        <label for="zona_rol">Zona de Rol en Vivo (5 euros)</label>
        <input type="checkbox" 
        id="zona_rol" 
        name="talleres[]">
        <br>

        <button type="submit">Enviar</button>

    </form>
</body>
</html>