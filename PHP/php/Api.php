<?php

include_once("conexion.php");
include_once("Clases.php");

class ApiAlumno
{
    public function VerCurso($idcurso)
    {
        try {
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = "CALL sp_consulta(?, 0, 0, 'VerCursoNivel');";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $idcurso);
            $stmt->execute();

            $result = array();
            while ($row = $stmt->fetch()) {
                $result = array(
                    "id_curso" => $row['id_curso'],
                    "id_usuario_f" => $row['id_usuario_f'],
                    "titulo_curso" => $row['titulo_curso'],
                    "descripcion_curso" => $row['descripcion_curso'],
                    "costo_curso" => $row['costo_curso'],
                    "imagen_curso" => $row['imagen_curso'],
                    "id_nivel" => $row['id_nivel'],
                    "id_curso_f" => $row['id_curso_f'],
                    "titulo_nivel" => $row['titulo_nivel'],
                    "resumen" => $row['resumen'],
                    "costo_nivel" => $row['costo_nivel']
                );
            }

            if (!empty($result)) {
                return $result;
            }
        } catch (PDOException $e) {
            $msj = "Error en servidor: " . $e->getMessage();
        }
    }
    public function Kardex($idusuario)
    {
        try {
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = ("call sp_vista(?,'vista_curso_inscrito');");
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $idusuario);
            $stmt->execute();
            $result = $stmt->fetchAll((PDO::FETCH_ASSOC));
            $array = [];

            foreach ($result as $row) {
                $array[] = array(
                    'fecha_inscripcion' => $row['fecha_inscripcion'],
                    'finalizado' => $row['finalizado'],
                    'id_usuario_f' => $row['id_usuario_f'],
                    'titulo' => $row['titulo'],
                    'id_curso' => $row['id_curso']
                );
            }
            return $array;
        } catch (PDOException $e) {
            $msj = "Error en servidor: " . $e->getMessage();
            return $msj;
        }
    }

    public function FinalizarCurso($idcursoinscrito)
    {
        try {
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = ("call sp_inscribir_curso(?, 0, 0, 0, 1, 'finalizarcursoinscrito');");
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $idcursoinscrito);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                $msj = array($row['codigo'], $row['mensaje']);
            } else
                $msj = false;
            return $msj;
        } catch (PDOException $e) {
            $msj = "Error en servidor: " . $e->getMessage();
            return $msj;
        }
    }

    public function vista_recursos_curso()
    {
        try {
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = ("call sp_vista(0,'vista_recursos_curso');");
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                $msj = array($row['codigo'], $row['mensaje']);
            } else
                $msj = false;
            return $msj;
        } catch (PDOException $e) {
            $msj = "Error en servidor: " . $e->getMessage();
            return $msj;
        }
    }

    public function ListaCursoInscrito($iduser)
    {
        try {
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = ("call sp_consulta(?, 0, 0, 'CursoInscritoInfo');");
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $iduser);

            $stmt->execute();
            $result = $stmt->fetchAll((PDO::FETCH_ASSOC));
            $array = [];

            foreach ($result as $row) {
                $array[] = array(
                    'idcurso' => $row['idcurso'],
                    'titulo_curso' => $row['titulo_curso'],
                    'descripcion_curso' => $row['descripcion_curso'],
                    'imagen_curso' => $row['imagen_curso'],
                    'fecha_inscripcion' => $row['fecha_inscripcion'],
                    'finalizado' => $row['finalizado']
                );
            }
            return $array;
        } catch (PDOException $e) {
            $msj = "Error en servidor: " . $e->getMessage();
            return $msj;
        }
    }

    public function ListaRecursos($iduser)
    {
        try {
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = ("select * from vista_recursos_curso where id_curso_inscrito=?;");
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $iduser);

            $stmt->execute();
            $result = $stmt->fetchAll((PDO::FETCH_ASSOC));
            $array = [];

            foreach ($result as $row) {
                $array[] = array(
                    "id_recursos" => $row['id_recursos'],
                    "recurso" => $row['recurso'],
                    "tipo" => $row['tipo'],
                    "contenido" => $row['contenido'],
                    "titulo_curso" => $row['curso']
                );
            }
            return $array;
        } catch (PDOException $e) {
            $msj = "Error en servidor: " . $e->getMessage();
        }

        return $msj;
    }
}

class ApiCliente
{
    public function Agregar($datos)
    {
        try {
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = ("call sp_usuario(?, ?, ?, ?, ?, ?, ?, ?, ?, 'I');");
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $datos->email);
            $stmt->bindValue(2, $datos->contra);
            $stmt->bindValue(3, $datos->rol);
            $stmt->bindParam(4, $datos->imagen, PDO::PARAM_LOB);
            $stmt->bindValue(5, $datos->nombre);
            $stmt->bindValue(6, $datos->apellido_p);
            $stmt->bindValue(7, $datos->apellido_m);
            $stmt->bindValue(8, $datos->fch_nacimiento);
            $stmt->bindValue(9, $datos->genero);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                $msj = array($row['codigo'], $row['mensaje']);
            } else
                $msj = false;
        } catch (PDOException $e) {
            $msj = "Error en servidor: " . $e->getMessage();
        }

        return $msj;
    }

    public function Editar($datos)
    {
        try {
            $msj = '';
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = ("call sp_usuario(?, ?, ?, ?, ?, ?, ?, ?, ?, 'U');");
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $datos->email);
            $stmt->bindValue(2, $datos->contra);
            $stmt->bindValue(3, $datos->rol);
            $stmt->bindParam(4, $datos->imagen, PDO::PARAM_LOB);
            $stmt->bindValue(5, $datos->nombre);
            $stmt->bindValue(6, $datos->apellido_p);
            $stmt->bindValue(7, $datos->apellido_m);
            $stmt->bindValue(8, $datos->fch_nacimiento);
            $stmt->bindValue(9, $datos->genero);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();

                if (!empty($row['codigo'])) {
                    $msj = $row['codigo'];
                }
            }
        } catch (PDOException $e) {
            $msj = "Error en servidor: " . $e->getMessage();
        }

        return $msj;
    }

    public function IniciarSesion($user, $pass)
    {
        try {
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = '';
            $sql = ("call sp_usuario_inicio_sesion(?, ?);");
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $user);
            $stmt->bindValue(2, $pass);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                $row = $stmt->fetch();

                if (!empty($row['mensaje'])) {
                    return $row['mensaje'];
                }

                $item = array(
                    "id_usuario" => $row['id_usuario'],
                    "email" => $row['email'],
                    "contra" => $row['contra'],
                    "rol" => $row['rol'],
                    "imagen" => $row['imagen'],
                    "nombre" => $row['nombre'],
                    "apellido_p" => $row['apellido_p'],
                    "apellido_m" => $row['apellido_m'],
                    "fch_nacimiento" => $row['fch_nacimiento'],
                    "genero" => $row['genero'],
                    "errores" => $row['errores'],
                    "baja_logica" => $row['baja_logica'],
                    "fch_ingreso" => $row['fch_ingreso']
                );
                return $item;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            $msj = "Error en servidor: " . $e->getMessage();
        }

        return $msj;
    }

    public function InscribirCurso($idcurso, $idusuario)
    {
        try {
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = '';
            $sql = ("call sp_inscribir_curso(0,?,?,0,0,'I');");
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $idcurso);
            $stmt->bindValue(2, $idusuario);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();

                if (!empty($row['codigo'])) {
                    $msj = array(
                        $row['codigo'],
                        $row['mensaje']
                    );
                }
            }
        } catch (PDOException $e) {
            $msj = "Error en servidor: " . $e->getMessage();
        }

        return $msj;
    }

    public function MisCursos($idusuario)
    {
        $db = new DB();
        $conn = $db->connect();
        if (is_array($conn)) {
            $msj = $conn['error'];
            return $msj;
        }
        $sql = ("SELECT id_curso, id_usuario_f, titulo, descripcion, 
        activo, imagen, costo from curso_inscrito");
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll((PDO::FETCH_ASSOC));

        return $result;
    }

    function Listado($inicio, $registros)
    {
        $db = new DB();
        $conn = $db->connect();
        if (is_array($conn)) {
            $msj = $conn['error'];
            return $msj;
        }
        $sql = ("SELECT id_curso, id_usuario_f, titulo, descripcion, 
        activo, imagen, costo from curso_inscrito LIMIT $inicio,$registros");
        $stmt = $conn->prepare($sql);

        return $stmt;
    }
}

class ApiNivel
{
    public function Agregar($datos)
    {
        try {
            $msj = false;
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = ("call sp_nivel(0, ?, ?, ?, ?, ?, ?, 'I')");
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $datos->id_curso_f);
            $stmt->bindValue(2, $datos->titulo);
            $stmt->bindValue(3, $datos->resumen);
            $stmt->bindValue(4, $datos->contenido);
            $stmt->bindValue(5, $datos->costo);
            $stmt->bindValue(6, $datos->video);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                $msj = array($row['idnivel'], $row['codigo'], $row['mensaje']);
            }
            return $msj;
        } catch (PDOException $e) {
            $msj = "Error en servidor: " . $e->getMessage();
            return $msj;
        }
    }

    public function TotalNiveles($id)
    {
        try {
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = ("call sp_consulta(?, '', '', 'TotalNiveles');");
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $id);

            $stmt->execute();
            $result = $stmt->fetchAll((PDO::FETCH_ASSOC));

            return $result;
        } catch (PDOException $e) {
            $result = "Error en servidor: " . $e->getMessage();
            return $result;
        }
    }

    public function CostoCurso($id)
    {
        try {
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = ("call sp_consulta(?, '', '', 'CostoCurso');");
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $id);

            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                $msj = array($row['costo'], $row['titulo']);
                return $msj;
            }
        } catch (PDOException $e) {
            $result = "Error en servidor: " . $e->getMessage();
            return $result;
        }
    }

    function ListadoNiveles($id, $inicio, $registros)
    {
        try {
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = ("call sp_consulta(?, ?, ?, 'ListadoNiveles');");
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $id);
            $stmt->bindValue(2, $inicio);
            $stmt->bindValue(3, $registros);

            $stmt->execute();

            return $stmt;
        } catch (PDOException $e) {
            $stmt = "Error en servidor: " . $e->getMessage();
            return $stmt;
        }
    }
}

class ApiCurso
{
    public function Agregar($datos)
    {
        try {
            $msj = false;
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = ("call sp_curso(0, ?, ?, ?, ?, ?, 'I');");
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $datos->id_usuario_f);
            $stmt->bindValue(2, $datos->titulo);
            $stmt->bindValue(3, $datos->descripcion);
            $stmt->bindParam(4, $datos->imagen, PDO::PARAM_LOB);
            $stmt->bindValue(5, $datos->costo);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                $msj = array($row['codigo'], $row['mensaje']);
            }
            return $msj;
        } catch (PDOException $e) {
            $msj = "Error en servidor: " . $e->getMessage();
        }
    }
    public function Editar($datos)
    {
        try {
            $msj = '';
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = ("call sp_curso(?, ?, ?, ?, ?, ?, 'U');");
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $datos->id_curso);
            $stmt->bindValue(2, $datos->id_usuario_f);
            $stmt->bindValue(3, $datos->titulo);
            $stmt->bindValue(4, $datos->descripcion);
            $stmt->bindParam(5, $datos->imagen, PDO::PARAM_LOB);
            $stmt->bindValue(6, $datos->costo);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                $msj = array($row['codigo'], $row['mensaje']);
            }
        } catch (PDOException $e) {
            $msj = "Error en servidor: " . $e->getMessage();
        }

        return $msj;
    }
    public function Ver($idcurso, $idusuario)
    {
        try {
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = ("select id_curso, id_usuario_f, titulo, descripcion, 
            activo, imagen, costo from curso where id_curso = ? and id_usuario_f = ?;");
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $idcurso);
            $stmt->bindValue(2, $idusuario);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                $msj = array(
                    "id_curso" => $row['id_curso'],
                    "id_usuario_f" => $row['id_usuario_f'],
                    "titulo" => $row['titulo'],
                    "descripcion" => $row['descripcion'],
                    "activo" => $row['activo'],
                    "imagen" => base64_encode($row['imagen']),
                    "costo" => $row['costo']
                );
                return $msj;
            }
        } catch (PDOException $e) {
            $msj = "Error en servidor: " . $e->getMessage();
        }
    }

        public function VerCurso($idcurso)
    {
        try {
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = "CALL sp_consulta(?, 0, 0, 'VerCursoNivel');";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $idcurso);
            $stmt->execute();

            $result = array();
            while ($row = $stmt->fetch()) {
                $result[] = array(
                    "id_curso" => $row['id_curso'],
                    "id_usuario_f" => $row['id_usuario_f'],
                    "titulo_curso" => $row['titulo_curso'],
                    "descripcion_curso" => $row['descripcion_curso'],
                    "costo_curso" => $row['costo_curso'],
                    "id_nivel" => $row['id_nivel'],
                    "id_curso_f" => $row['id_curso_f'],
                    "titulo_nivel" => $row['titulo_nivel'],
                    "resumen" => $row['resumen'],
                    "costo_nivel" => $row['costo_nivel']
                );
            }

            if (!empty($result)) {
                return $result;
            }
        } catch (PDOException $e) {
            $msj = "Error en servidor: " . $e->getMessage();
        }
    }
    
    function LosMasVistos()
    {
        try {
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = ("call sp_consulta('', '', '', 'LosMasVistos');");
            $stmt = $conn->prepare($sql);

            $stmt->execute();
            $result = $stmt->fetchAll((PDO::FETCH_ASSOC));

            return $result;
        } catch (PDOException $e) {
            $stmt = "Error en servidor: " . $e->getMessage();
            return $stmt;
        }
    }
    function ListadoLosMasVistos($inicio, $registros)
    {
        try {
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = ("call sp_consulta('', ?, ?, 'ListadoLosMasVistos');");
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $inicio);
            $stmt->bindValue(2, $registros);

            $stmt->execute();

            return $stmt;
        } catch (PDOException $e) {
            $stmt = "Error en servidor: " . $e->getMessage();
            return $stmt;
        }
    }
    function ListadoMaestro($id, $inicio, $registros)
    {
        try {
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = ("call sp_consulta(?, ?, ?, 'ListadoMaestro');");
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $id);
            $stmt->bindValue(2, $inicio);
            $stmt->bindValue(3, $registros);

            $stmt->execute();

            return $stmt;
        } catch (PDOException $e) {
            $stmt = "Error en servidor: " . $e->getMessage();
            return $stmt;
        }
    }
    public function TodosCursosMaestro($id)
    {
        try {
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = ("call sp_consulta(?, '', '', 'TodosCursosMaestro');");
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $id);

            $stmt->execute();
            $result = $stmt->fetchAll((PDO::FETCH_ASSOC));

            return $result;
        } catch (PDOException $e) {
            $result = "Error en servidor: " . $e->getMessage();
            return $result;
        }
    }
}
class ApiCategoria
{
    public function Agregar($datos)
    {
        try {
            $msj = false;
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = ("call sp_categoria(0, ?, ?, ?, 'I');");
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $datos->id_usuario_f);
            $stmt->bindValue(2, $datos->titulo);
            $stmt->bindValue(3, $datos->descripcion);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                $msj = array($row['codigo'], $row['mensaje']);
            }
            return $msj;
        } catch (PDOException $e) {
            $msj = "Error en servidor: " . $e->getMessage();
        }
    }
    function Listado($id, $inicio, $registros)
    {
        try {
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = ("call sp_consulta(?, ?, ?, 'ListadoCategorias');");
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $id);
            $stmt->bindValue(2, $inicio);
            $stmt->bindValue(3, $registros);

            $stmt->execute();

            return $stmt;
        } catch (PDOException $e) {
            $stmt = "Error en servidor: " . $e->getMessage();
            return $stmt;
        }
    }
    public function Total($id)
    {
        try {
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = ("call sp_consulta(?, '', '', 'TotalCategorias');");
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $id);

            $stmt->execute();
            $result = $stmt->fetchAll((PDO::FETCH_ASSOC));

            return $result;
        } catch (PDOException $e) {
            $result = "Error en servidor: " . $e->getMessage();
            return $result;
        }
    }
}

class ApiPagos
{
    public function Agregar($datos)
    {
        try {
            $msj = false;
            $db = new DB();
            $conn = $db->connect();
            if (is_array($conn)) {
                $msj = $conn['error'];
                return $msj;
            }
            $sql = ("call sp_pagos(0, ?, ?, ?, ?, ?, 'I');");
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(1, $datos->id_usuario_f);
            $stmt->bindValue(2, $datos->titulo);
            $stmt->bindValue(3, $datos->descripcion);
            $stmt->bindParam(4, $datos->imagen, PDO::PARAM_LOB);
            $stmt->bindValue(5, $datos->costo);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                $msj = array($row['codigo'], $row['mensaje']);
            }
            return $msj;
        } catch (PDOException $e) {
            $msj = "Error en servidor: " . $e->getMessage();
        }
    }
}
