<!-- <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Bases de Datos</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>Verificación de Bases de Datos</h1>
        <form id="db-form">
            <div class="form-group">
                <label for="db_name">Nombre de la Base de Datos:</label>
                <input type="text" id="db_name" name="db_name" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Verificar">
            </div>
        </form>

        <div id="result"></div>
    </div> -->

    <script>
        $(document).ready(function() {

            $.ajax({
                url: './peticiones/procesos.php',
                method: 'POST',
                data: { opcion: 'EjecutarCreteInsertBDS' },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert("Se crearon correctamente las tablas en la base de datos");
                    } else {
                        alert(response.message);
                    }
                }
            });





            // $('#db-form').submit(function(e) {
            //     e.preventDefault();

            //     var dbName = $('#db_name').val();

            //     // Simular la verificación de existencia de la base de datos
            //     var existingDatabases = ['database1', 'database2', 'database3']; // Bases de datos existentes

            //     if (existingDatabases.includes(dbName)) {
            //         $('#result').html('<p>La base de datos <strong>' + dbName + '</strong> ya existe.</p>');
            //     } else {
            //         $('#result').html('<p>La base de datos <strong>' + dbName + '</strong> no existe. Ejecutando acción...</p>');
            //         // Aquí puedes agregar la acción que desees realizar si la base de datos no existe
            //         // Por ejemplo, simular la creación de la base de datos
            //         existingDatabases.push(dbName);
            //         $('#result').append('<p>Base de datos <strong>' + dbName + '</strong> creada.</p>');
            //     }
            // });
        });
    </script>
<!-- </body>
</html> -->
