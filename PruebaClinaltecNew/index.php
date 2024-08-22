<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Pacientes</title>
    <link rel="stylesheet" href="./includes/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <img src="images/Logo.png" alt="Logo" class="logo-img">
        <h1>Formulario de Pacientes</h1>
        <form id="form-paciente">
            <input type="hidden" id="paciente_id" name="paciente_id"> <!-- Campo oculto para el ID del paciente -->

            <div class="form-group">
                <label for="numero_documento">Número de Documento:</label>
                <input type="text" id="numero_documento" name="numero_documento" required>
            </div>

            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>

            <div class="form-group">
                <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
            </div>

            <div class="form-group">
                <label for="edad">Edad:</label>
                <input type="number" id="edad" name="edad" required readonly>
            </div>

            <div class="form-group">
                <label for="genero">Género:</label>
                <select id="genero" name="genero_id" required>
                    <option value="">Seleccione un género</option>
                </select>
            </div>

            <div class="form-group">
                <label for="departamento">Departamento:</label>
                <select id="departamento" name="departamento_id" required>
                    <option value="">Seleccione un departamento</option>
                </select>
            </div>

            <div class="form-group">
                <label for="municipio">Municipio:</label>
                <select id="municipio" name="municipio_id" required>
                    <option value="">Seleccione un municipio</option>
                </select>
            </div>

            <div class="form-group">
                <input type="submit" value="Guardar" id="btn-submit">
                <input type="button" value="Cancelar" id="btn-cancelar" style="display: none;">
            </div>
        </form>

        <h2>Lista de Pacientes</h2>
        <table id="pacientes-list">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Edad</th>
                    <th>Género</th>
                    <th>Departamento</th>
                    <th>Municipio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- La lista de pacientes se cargará aquí -->
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            // Cargar géneros
            $.ajax({
                url: './peticiones/ConsultasBasicas.php',
                method: 'POST',
                data: { opcion: 'ConsultaGeneros' },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        var generoSelect = $('#genero');
                        $.each(response.data, function(index, genero) {
                            generoSelect.append($('<option>', {
                                value: genero.ID,
                                text: genero.DESCRIPCION
                            }));
                        });
                    } else {
                        alert(response.message);
                    }
                }
            });

            // Cargar departamentos
            $.ajax({
                url: './peticiones/ConsultasBasicas.php',
                method: 'POST',
                data: { opcion: 'ConsultaDepartamentos' },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        var departamentoSelect = $('#departamento');
                        $.each(response.data, function(index, departamento) {
                            departamentoSelect.append($('<option>', {
                                value: departamento.ID,
                                text: departamento.DESCRIPCION
                            }));
                        });
                    } else {
                        alert(response.message);
                    }
                }
            });

            $('#departamento').change(function() {
                var departamentoId = $(this).val();
                var municipioSelect = $('#municipio');
                municipioSelect.empty().append('<option value="">Seleccione un municipio</option>');

                if (departamentoId) {
                    $.ajax({
                        url: './peticiones/ConsultasBasicas.php',
                        method: 'POST',
                        data: { opcion: 'ConsultaMunicipios', departamento_id: departamentoId },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $.each(response.data, function(index, municipio) {
                                    municipioSelect.append($('<option>', {
                                        value: municipio.ID,
                                        text: municipio.DESCRIPCION
                                    }));
                                });
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function() {
                            alert('Error al cargar los municipios.');
                        }
                    });
                }
            });

            // Cargar pacientes
            function cargarPacientes() {
                $.ajax({
                    url: './peticiones/Pacientes_json.php',
                    method: 'POST',
                    data: JSON.stringify({ opcion: 'ConsultarPacientes' }),
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            var pacientesList = $('#pacientes-list tbody');
                            pacientesList.empty();
                            $.each(response.data, function(index, paciente) {
                                pacientesList.append(
                                    $('<tr>').append(
                                        $('<td>').text(paciente.ID),
                                        $('<td>').text(paciente.NOMBRE),
                                        $('<td>').text(paciente.EDAD),
                                        $('<td>').text(paciente.SEXO),
                                        $('<td>').text(paciente.DEPARTAMENTO),
                                        $('<td>').text(paciente.MUNICIPIO),
                                        $('<td>').append(
                                            $('<button>').text('Editar').click(function() {
                                                cargarPaciente(paciente.ID);
                                            }),
                                            $('<button>').text('Eliminar').click(function() {
                                                eliminarPaciente(paciente.ID);
                                            })
                                        )
                                    )
                                );
                            });
                        } else {
                            alert(response.message);
                        }
                    }
                });
            }

            // Cargar paciente para edición
            function cargarPaciente(pacienteId) {
                $.ajax({
                    url: './peticiones/Pacientes_json.php',
                    method: 'POST',
                    data: JSON.stringify({ opcion: 'ConsultarPaciente', paciente_id: pacienteId }),
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            var paciente = response.data;
                            $('#paciente_id').val(paciente.ID);
                            $('#numero_documento').val(paciente.NUMERO_DOCUMENTO);
                            $('#nombre').val(paciente.NOMBRE);
                            $('#edad').val(paciente.EDAD);
                            $('#fecha_nacimiento').val(paciente.FECHA_NACIMIENTO);
                            $('#genero').val(paciente.GENERO_ID);
                            $('#departamento').val(paciente.DEPARTAMENTO_ID);
                            var municipioSelect = $('#municipio');
                            municipioSelect.empty().append('<option value="">Seleccione un municipio</option>');
                            $.ajax({
                                url: './peticiones/ConsultasBasicas.php',
                                method: 'POST',
                                data: { opcion: 'ConsultaMunicipios', departamento_id: paciente.DEPARTAMENTO_ID },
                                dataType: 'json',
                                success: function(response) {
                                    if (response.success) {
                                        $.each(response.data, function(index, municipio) {
                                            municipioSelect.append($('<option>', {
                                                value: municipio.ID,
                                                text: municipio.DESCRIPCION
                                            }));
                                        });
                                        setTimeout(function() {
                                            $('#municipio').val(paciente.MUNICIPIO_ID);
                                        }, 0);
                                    } else {
                                        alert(response.message);
                                    }
                                },
                                error: function() {
                                    alert('Error al cargar los municipios.');
                                }
                            });
                            $('#btn-submit').val('Actualizar');
                            $('#btn-cancelar').show();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            }

            // Eliminar paciente
            function eliminarPaciente(pacienteId) {
                if (confirm('¿Estás seguro de que deseas eliminar este paciente?')) {
                    $.ajax({
                        url: './peticiones/Pacientes_json.php',
                        method: 'POST',
                        data: JSON.stringify({ opcion: 'EliminarPaciente', paciente_id: pacienteId }),
                        contentType: 'application/json; charset=utf-8',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                alert(response.message);
                                cargarPacientes();
                            } else {
                                alert(response.message);
                            }
                        }
                    });
                }
            }
            function calcularEdad(fechaNacimiento) {
                var fechaNacimiento = new Date(fechaNacimiento);
                var hoy = new Date();
                var edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
                var mes = hoy.getMonth() - fechaNacimiento.getMonth();

                if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
                    edad--;
                }

                return edad;
            }

            $('#fecha_nacimiento').change(function() {
                var fechaNacimiento = $(this).val();
                if (fechaNacimiento) {
                    var edad = calcularEdad(fechaNacimiento);
                    $('#edad').val(edad);
                } else {
                    $('#edad').val('');
                }
            });

            // Cancelar edición
            $('#btn-cancelar').click(function() {
                $('#form-paciente')[0].reset();
                $('#paciente_id').val('');
                $('#btn-submit').val('Guardar');
                $(this).hide();
            });

            // Enviar formulario
            $('#form-paciente').submit(function(e) {
                e.preventDefault();

                var formData = $(this).serializeArray();
                formData.push({ name: 'opcion', value: $('#paciente_id').val() ? 'ActualizarPaciente' : 'GuardarPaciente' });

                var jsonData = {};
                $.each(formData, function(index, field) {
                    jsonData[field.name] = field.value;
                });

                $.ajax({
                    url: './peticiones/Pacientes_json.php',
                    method: 'POST',
                    data: JSON.stringify(jsonData),
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            $('#form-paciente')[0].reset();
                            $('#paciente_id').val('');
                            $('#btn-submit').val('Guardar');
                            $('#btn-cancelar').hide();
                            cargarPacientes();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('Error al enviar los datos.');
                    }
                });
            });

            // Cargar pacientes al inicio
            cargarPacientes();
        });
    </script>
</body>
</html>
