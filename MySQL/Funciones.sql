use basededatosmultimedia;
DROP FUNCTION IF EXISTS desbloquear_usuario;
DELIMITER $$
CREATE FUNCTION desbloquear_usuario(F_email varchar(250)) 
RETURNS VARCHAR(50) 
begin 
	declare rtn varchar(50);
    set @temp_usuario = (select id_usuario from usuario where baja_logica = 0 and email = F_email);
    if @temp_usuario <> '' then
		update usuario set errores = 0 where id_usuario = @temp_usuario;
		set rtn = 'Usuario desbloqueado';
	else
		set rtn = 'No se encontro al usuario';
    end if;
	return rtn;
end$$
DELIMITER ;

#----------------------------------------------------------------#
DROP VIEW IF EXISTS vista_curso;
CREATE VIEW vista_curso AS
SELECT titulo, descripcion, imagen
FROM curso;
#----------------------------------------------------------------#
DROP VIEW IF EXISTS vista_recursos_curso;
CREATE VIEW vista_recursos_curso AS
SELECT ci.id_curso_inscrito, c.titulo AS curso, r.id_recursos, r.nombre AS recurso, r.tipo, r.contenido
FROM curso_inscrito ci
JOIN curso c ON ci.id_curso_f = c.id_curso
JOIN nivel n ON n.id_curso_f = c.id_curso
JOIN recursos r ON r.id_nivel_f = n.id_nivel;
#----------------------------------------------------------------#
DROP VIEW IF EXISTS vista_curso_inscrito;
CREATE VIEW vista_curso_inscrito AS
SELECT a.fecha_inscripcion, a.finalizado, a.id_usuario_f, b.titulo, b.id_curso
FROM curso_inscrito AS a
JOIN curso AS b
ON a.id_curso_f = b.id_curso;
#----------------------------------------------------------------#
DROP VIEW IF EXISTS vista_curso_nivel;
CREATE VIEW vista_curso_nivel AS
SELECT 
    A.id_curso,
    A.id_usuario_f,
    A.titulo AS titulo_curso,
    A.descripcion AS descripcion_curso,
    A.costo AS costo_curso,
    A.imagen AS imagen_curso,
    B.id_nivel,
    B.id_curso_f,
    B.titulo AS titulo_nivel,
    B.resumen,
    B.costo AS costo_nivel
FROM curso AS A
JOIN nivel AS B
    ON A.id_curso = B.id_curso_f;