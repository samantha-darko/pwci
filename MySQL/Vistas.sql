#----------------------- VISTA PAGOS CURSOS -----------------------#
DROP VIEW IF EXISTS vista_pagos_cursos;
CREATE VIEW vista_pagos_cursos AS
SELECT a.id_pago_curso, a.forma_pago, curso.titulo, 
SUM(a.cantidada_pago) AS total_ventas, 
COUNT(curso_inscrito.id_usuario_f) AS total_alumnos, usuario.id_usuario
FROM pago_curso AS a
JOIN curso_inscrito ON a.id_curso_inscrito_f = curso_inscrito.id_curso_inscrito
JOIN curso ON curso_inscrito.id_curso_f = curso.id_curso
JOIN usuario ON curso.id_usuario_f = usuario.id_usuario
GROUP BY curso.titulo;
#------------------------ VISTA CURSO -----------------------#
DROP VIEW IF EXISTS vista_curso;
CREATE VIEW vista_curso AS
SELECT titulo, descripcion, imagen
FROM curso;
#---------------------- VISTA RECURSOS CURSO -----------------------#
DROP VIEW IF EXISTS vista_recursos_curso;
CREATE VIEW vista_recursos_curso AS
SELECT ci.id_curso_inscrito, c.titulo AS curso, r.id_recursos, r.nombre AS recurso, r.tipo, r.contenido
FROM curso_inscrito ci
JOIN curso c ON ci.id_curso_f = c.id_curso
JOIN nivel n ON n.id_curso_f = c.id_curso
JOIN recursos r ON r.id_nivel_f = n.id_nivel;
#---------------- VISTA CURSO INSCRITO ----------------#
DROP VIEW IF EXISTS vista_curso_inscrito;
CREATE VIEW vista_curso_inscrito AS
SELECT a.fecha_inscripcion, a.finalizado, a.id_usuario_f, b.titulo, b.id_curso
FROM curso_inscrito AS a
JOIN curso AS b
ON a.id_curso_f = b.id_curso;
#-------------------- VISTA CURSO NIVEL -------------------------#
DROP VIEW IF EXISTS vista_curso_nivel;
CREATE VIEW vista_curso_nivel AS
SELECT A.id_curso, A.id_usuario_f, A.titulo AS titulo_curso, A.descripcion AS descripcion_curso,
A.costo AS costo_curso, A.imagen AS imagen_curso, B.id_nivel, B.id_curso_f, B.titulo AS titulo_nivel, 
B.resumen, B.costo AS costo_nivel
FROM curso AS A
JOIN nivel AS B
ON A.id_curso = B.id_curso_f;