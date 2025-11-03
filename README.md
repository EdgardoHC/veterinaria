Este proyecto utiliza sesiones en PHP para gestionar los roles de usuario que ingresan a la plataforma.
El objetivo es restringir el acceso a ciertas secciones (tanto en el menú como por URL directa) dependiendo del rol que haya iniciado sesión.
Al momento de iniciar sesión se crea una SESSION como esta:
   $_SESSION['usuario'] = [
            "id" => $row['idusuario'],
            "nombre" => $row['nombrecompleto'],
            "usuario" => $row['nombreusuario'],
            "email" => $row['correoelectronico'],
            "rol_nombre" => $row['nombre_rol']
        ];
        en la parte rol_nombre, encontraran el rol que se ha logueado en base a este, se restingiran accesos en el menu y se obligara a que exista este campo para bloquear acceso por url
        a estas páginas.
