use basededatosmultimedia;
###----------------------------------ALTAS BAJAS Y CAMBIOS USUARIO ###
DROP PROCEDURE IF EXISTS sp_usuario;
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_usuario`(
in	sp_email 					varchar(250),
in	sp_contra 					varchar(250),
in	sp_rol 						varchar(50),
in	sp_imagen 					longblob,
in	sp_nombre 					varchar(200),
in	sp_apellido_p 				varchar(200),
in	sp_apellido_m 				varchar(200),
in	sp_fch_nacimiento 			date,
in	sp_genero 					varchar(25),
in 	opcion						varchar(2)
)
SQL SECURITY INVOKER
begin

	DECLARE EXIT HANDLER FOR 1062
		BEGIN
			SELECT 1062 as codigo,
            CONCAT('ERROR, El correo  (',sp_email,') ya esta en uso, vuelva intentar con otro correo') 
            AS mensaje;
		END;

	DECLARE EXIT HANDLER FOR 1146 
		SELECT 1146 as codigo,
        'Tabla no encontrada' as mensaje;
        
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 100 as codigo,
        'Error en base de datos' as mensaje; 

	if opcion =	'I' then
	INSERT INTO usuario(email, contra, rol, imagen, nombre, apellido_p, apellido_m, fch_nacimiento, genero)
    VALUES(sp_email, sp_contra, sp_rol, sp_imagen, sp_nombre, sp_apellido_p, sp_apellido_m, sp_fch_nacimiento, sp_genero);
	
    select 1 as codigo,
    concat('registro exitoso') as mensaje;
	end if;
    
        if opcion = 'U' then
    
    update usuario set
    contra = if(sp_contra <> '', sp_contra, contra),
    imagen = if(sp_imagen <> '', sp_imagen, imagen),
    nombre = if(sp_nombre <> '', sp_nombre, nombre),
    apellido_p = if(sp_apellido_p <> '', sp_apellido_p, apellido_p),
    apellido_m = if(sp_apellido_m <> '', sp_apellido_m, apellido_m),
    fch_nacimiento = if(sp_fch_nacimiento <> '', sp_fch_nacimiento, fch_nacimiento),
    genero = if(sp_genero <> '', sp_genero, genero)
    where id_usuario = (select id_usuario from usuario where email = sp_email)
    ;
    
     select 1 as codigo,
    concat('Usuario modificado exitosamente') as mensaje;
    end if;
    
     if opcion = 'D' then 
		update usuario set baja_logica = 1 
        where id_usuario = (select id_usuario from usuario where email = sp_email)
        ;
        select 1 as codigo, 
        'Baja exitosa' as mensaje;
    end if;
end$$
DELIMITER ;

# call sp_usuario('jairfurry@gmail.com', 'hola', 'usuario', '01010', 'luis', 'ignacio', 'castro', now(), 'hombre', 'I');
# call sp_usuario('jairfurry@gmail.com', 'hola', '', '', '', '', '', '', '', 'L');

# select * from usuario;

###----------------------------------INICIO SESION USUARIO
DROP PROCEDURE IF EXISTS sp_usuario_inicio_sesion;
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_usuario_inicio_sesion`(
in 	sp_email					varchar(250),
in	sp_contra 					varchar(250)
)
SQL SECURITY INVOKER
begin

	DECLARE EXIT HANDLER FOR 1146 
		SELECT 1146 as codigo,
        'Tabla no encontrada' as mensaje;
    
	set @temp_contra = (select contra from usuario where baja_logica = 0 and email = sp_email);
	set @temp_errores = (select errores from usuario where baja_logica = 0 and email = sp_email);
    if @temp_contra <> '' then
        if @temp_contra = sp_contra and @temp_errores < 3 then
			#update usuario set errores = 0 where id_usuario = (select id_usuario from usuario  where baja_logica = 0 and email = sp_email);
			UPDATE usuario SET errores = 0 WHERE id_usuario IN (SELECT id_usuario FROM (SELECT id_usuario FROM usuario WHERE baja_logica = 0 AND email = sp_email) AS temp);
            select * from usuario where baja_logica = 0 and email = sp_email and contra = sp_contra;
		else
			if @temp_errores < 3 then
				#update usuario set errores = errores + 1 where id_usuario = (select id_usuario from usuario  where baja_logica = 0 and email = sp_email);
				UPDATE usuario SET errores = errores + 1 WHERE id_usuario IN (SELECT id_usuario FROM (SELECT id_usuario FROM usuario WHERE baja_logica = 0 AND email = sp_email) AS temp);
                select CONCAT('te quedan ', (3 - (@temp_errores + 1)), ' intentos') as mensaje;
            else
				select 'cuenta bloqueada' as mensaje;
            end if;
        end if;
	else
		select 'correo no registrado' as mensaje;
    end if;
end$$
DELIMITER ;

###------------ NIVEL ------------###
DROP PROCEDURE IF EXISTS sp_nivel;
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_nivel`(
in sp_idnivel 		int,
in sp_idcursof		int,
in sp_titulo 		varchar(64),
in sp_resumen 		varchar(300),
in sp_contenido 	varchar(2000),
in sp_costo		 	decimal(15, 2),
in sp_video			longblob,
in opcion 			varchar(100)
)
SQL SECURITY INVOKER
begin
	
    if opcion =	'I' then
		INSERT INTO nivel(id_curso_f, titulo, resumen, contenido, costo, video)
		VALUES(sp_idcursof, sp_titulo, sp_resumen, sp_contenido, sp_costo, sp_video);
        SELECT LAST_INSERT_ID() as idnivel, 1 as codigo, concat('registro exitoso') as mensaje;
		
		#select 1 as codigo,
		#concat('registro exitoso') as mensaje;
	end if;
    
    if opcion = 'U' then    
		update nivel set
		id_curso_f = if(sp_idcursof <> '', sp_idcursof, id_curso_f),
		titulo = if(sp_titulo <> '', sp_titulo, titulo),
		resumen = if(sp_resumen <> '', sp_resumen, resumen),
		contenido = if(sp_contenido <> '', sp_contenido, contenido),
		costo = if(sp_costo <> '', sp_costo, costo),
		video = if(sp_video <> '', sp_video, video)
		where id_nivel = sp_idnivel;
    
		select 1 as codigo,
		concat('Nivel modificado exitosamente') as mensaje;
	end if;
    
     if opcion = 'D' then 
		update nivel set baja_logica = 1 
        where id_nivel = sp_idnivel;
        select 1 as codigo, 
        'Baja exitosa' as mensaje;
    end if;
    
end$$
DELIMITER ;


###----ALTAS BAJAS Y CAMBIOS CATEGORIA----###
DROP PROCEDURE IF EXISTS sp_categoria;
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_categoria`(
in	sp_id_categoria					int,
in	sp_id_usuario_f 				int,
in	sp_titulo 						varchar(64),
in	sp_descripcion 					varchar(500),
in 	opcion						varchar(2)
)
SQL SECURITY INVOKER
begin
	DECLARE EXIT HANDLER FOR 1146 
		SELECT 1146 as codigo,
        'Tabla no encontrada' as mensaje;
        
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 100 as codigo,
        'Error en base de datos' as mensaje; 

	if opcion =	'I' then
		INSERT INTO categoria(id_usuario_f, titulo, descripcion)
		VALUES(sp_id_usuario_f, sp_titulo, sp_descripcion);
		
		select 1 as codigo,
		concat('registro exitoso') as mensaje;
	end if;
    
	if opcion = 'U' then
    
    update categoria set
    titulo = if(sp_titulo <> '', sp_titulo, titulo),
    descripcion = if(sp_descripcion <> '', sp_descripcion, descripcion)
    where id_categoria = sp_id_categoria
    ;
    
     select 1 as codigo,
    concat('Categoria modificado exitosamente') as mensaje;
    end if;
    
     if opcion = 'D' then 
		update categoria set baja_logica = 1 
        where id_categoria = sp_id_categoria
        ;
        select 1 as codigo, 
        'Baja exitosa' as mensaje;
    end if;
end$$
DELIMITER ;

###-------- ALTAS BAJAS, CAMBIOS CONSULTAS CURSO --------#
DROP PROCEDURE IF EXISTS sp_curso;
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_curso`(
in	sp_id_curso				int,
in  sp_id_usuario_f			int,
in	sp_titulo				varchar(64),
in	sp_descripcion			varchar(500),
in  sp_imagen				longblob,
in  sp_costo				decimal(15, 2),
in	opcion					varchar(20)
)
SQL SECURITY INVOKER
begin

	DECLARE EXIT HANDLER FOR 1146 
		SELECT 1146 as codigo,
        'Tabla no encontrada' as mensaje;
        
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 100 as codigo,
       'Error en base de datos' as mensaje;
       
	if opcion =	'I' then
		INSERT INTO curso(id_usuario_f, titulo, descripcion, imagen, costo)
		VALUES(sp_id_usuario_f, sp_titulo, sp_descripcion, sp_imagen, sp_costo);
		
		select 1 as codigo,
		concat('registro exitoso') as mensaje;
	end if;
    
	if opcion = 'U' then    
		update curso set
		titulo = if(sp_titulo <> '', sp_titulo, titulo),
		descripcion = if(sp_descripcion <> '', sp_descripcion, descripcion),
		imagen = if(sp_imagen <> '', sp_imagen, imagen),
		costo = if(sp_costo <> '', sp_costo, costo)
		where id_curso = sp_id_curso;
		
		select 1 as codigo,
		concat('Curso modificado exitosamente') as mensaje;
    end if;
    
     if opcion = 'D' then 
		update curso set activo = 0 
        where id_curso = sp_id_curso
        ;
        select 1 as codigo, 
        'Baja exitosa' as mensaje;
    end if;
end$$
DELIMITER ;

###----------------------------------INSCRIBIR CURSO
DROP PROCEDURE IF EXISTS sp_inscribir_curso;
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_inscribir_curso`(
in sp_id_curso_inscrito int,
in sp_id_curso_f	int,
in sp_id_usuario_f int,
in sp_nivel_actual int,
in sp_finalizado bit,
in opcion varchar(100)
)
SQL SECURITY INVOKER
begin

	DECLARE EXIT HANDLER FOR 1146 
		SELECT 1146 as codigo,
        'Tabla no encontrada' as mensaje;
        
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 100 as codigo,
        'Error en base de datos' as mensaje; 
        
	if opcion = 'finalizarcursoinscrito' then
		UPDATE curso_inscrito
		SET finalizado = 1
		WHERE id_curso_inscrito = sp_id_curso_inscrito;
        SELECT sp_id_curso_inscrito as id_curso_inscrito, 1 as codigo, concat("registro exitoso") as mensaje;	
    end if;

	if opcion =	'I' then
		INSERT INTO curso_inscrito(id_curso_f, id_usuario_f, nivel_actual, finalizado, fecha_inscripcion) 
        VALUES(sp_id_curso_f, sp_id_usuario_f, sp_nivel_actual, 0, CURRENT_TIMESTAMP); 
        SELECT LAST_INSERT_ID() as idcursoinscrito, 1 as codigo, concat("registro exitoso") as mensaje;		
	end if;
    
	if opcion = 'U' then
    
    update curso_inscrito set
    id_curso_f = if(sp_id_curso_f <> '', sp_id_curso_f, id_curso_f),
    descripcion = if(sp_id_usuario_f <> '', sp_id_usuario_f, id_usuario_f),
    imagen = if(sp_nivel_actual <> 0, sp_nivel_actual, nivel_actual),
    costo = if(sp_finalizado <> 0, sp_finalizado, finalizado)
    where id_curso_inscrito = sp_id_curso_inscrito
    ;
    
     select 1 as codigo,
    concat('Categoria modificado exitosamente') as mensaje;
    end if;
    
     if opcion = 'D' then 
		update curso_inscrito set baja_logica = 1 
        where id_curso_inscrito = sp_id_curso_inscrito
        ;
        select 1 as codigo, 
        'Baja exitosa' as mensaje;
    end if;
end$$
DELIMITER ;

###------------ CONSULTAS ------------###
DROP PROCEDURE IF EXISTS sp_consulta;
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consulta`(
in sp_id		 	int,
in sp_inicio		int,
in sp_cantidad 		int,
in opcion 			varchar(100)
)
SQL SECURITY INVOKER
begin
	if opcion = 'suma_total_alumnos' then
		SELECT SUM(total_alumnos) AS suma_total_alumnos
		FROM vista_pagos_cursos
		WHERE id_usuario = sp_id;
    end if;
    
	if opcion = 'CursoInscritoInfo' then
		select a.id_curso AS idcurso, a.titulo AS titulo_curso, a.descripcion AS descripcion_curso, a.imagen AS imagen_curso,
		b.id_curso_inscrito, b.fecha_inscripcion, b.finalizado, b.id_curso_inscrito
		from curso AS a
		JOIN curso_inscrito AS b
		ON b.id_curso_f = a.id_curso
		where b.id_usuario_f = sp_id;
	end if;
    
	if opcion =	'TotalRegistros' then
		SELECT COUNT(*) AS total_filas
		FROM (
			SELECT n.titulo AS nivel_titulo,
				   c.titulo AS curso_titulo,
				   c.descripcion AS curso_descripcion,
				   c.imagen AS curso_imagen,
				   r.id_nivel_f AS recurso_idnivel,
				   r.nombre AS recurso_nombre,
				   r.tipo AS recurso_tipo,
				   r.contenido AS recurso_contenido
			FROM Curso c
			JOIN Curso_Inscrito ci ON c.id_curso = ci.id_curso_f
			JOIN Nivel n ON c.id_curso = n.id_curso_f
			JOIN Recursos r ON n.id_nivel = r.id_nivel_f
			WHERE ci.id_usuario_f = sp_id
		) AS subquery;
	end if;

    if opcion = 'CursoInscritoAlumno' then
		SELECT n.titulo AS nivel_titulo,
		c.titulo AS curso_titulo, c.descripcion AS curso_descripcion, c.imagen AS curso_imagen,
		r.id_nivel_f AS recurso_idnivel, r.nombre AS recurso_nombre, r.tipo AS recurso_tipo, r.contenido AS recurso_contenido
		FROM Curso c
		JOIN Curso_Inscrito ci ON c.id_curso = ci.id_curso_f
		JOIN Nivel n ON c.id_curso = n.id_curso_f
		JOIN Recursos r ON n.id_nivel = r.id_nivel_f
		where ci.id_usuario_f = sp_id;
    end if;
    
	if opcion = 'VerCursoHistorial' then
		select id_curso, id_usuario_f, a.titulo as titulo_curso, a.descripcion as descripcion_curso, a.costo as costo_curso, a.imagen as imagen_curso,
		id_nivel, id_curso_f, b.titulo as titulo_nivel, resumen, b.costo as costo_nivel
		from curso as A
		JOIN nivel as B
		ON A.id_curso = B.id_curso_f
		WHERE a.id_curso = sp_id;
	end if;

	if opcion = 'VerCursoNivel' then
		select id_curso, id_usuario_f, a.titulo as titulo_curso, a.descripcion as descripcion_curso, a.costo as costo_curso, a.imagen as imagen_curso,
		id_nivel, id_curso_f, b.titulo as titulo_nivel, resumen, b.costo as costo_nivel
		from curso as A
		JOIN nivel as B
		ON A.id_curso = B.id_curso_f
		WHERE a.id_curso = sp_id AND a.activo = 1 AND b.baja_logica = 0;
	end if;
    
	if opcion =	'ListadoMaestro' then
		SELECT id_curso, id_usuario_f, titulo, descripcion, 
        activo, imagen, costo from curso 
        where id_usuario_f = sp_id
        LIMIT sp_inicio, sp_cantidad;
	end if;
        
	if opcion =	'TodosCursosMaestro' then
		SELECT id_curso, id_usuario_f, titulo, descripcion, activo, imagen, costo from curso
        where id_usuario_f = sp_id;
	end if;
        
	if opcion =	'LosMasVistos' then
		select a.id_curso, a.id_usuario_f, a.titulo, a.descripcion, a.activo, a.imagen, a.costo
		from curso as A
		JOIN nivel as B
		ON A.id_curso = B.id_curso_f
		WHERE a.activo = 1 AND b.baja_logica = 0  LIMIT 10;
	end if;
    
    if opcion =	'ListadoLosMasVistos' then
		SELECT A.id_curso, A.id_usuario_f, A.titulo, A.descripcion, A.activo, A.imagen, A.costo
		FROM curso AS A
		JOIN nivel AS B ON A.id_curso = B.id_curso_f
		WHERE A.activo = 1 AND B.baja_logica = 0
		GROUP BY A.id_curso
		LIMIT sp_inicio, sp_cantidad;
	end if;
    
    if opcion = 'TotalRecursos' then
		SELECT id_recursos, id_nivel_f, nombre, tipo, contenido from recursos
        where id_nivel_f = sp_id;
    end if;
    
    if opcion = 'ListadoRecursos' then
		SELECT id_recursos, id_nivel_f, nombre, tipo, contenido from recursos
        where id_nivel_f = sp_id
        LIMIT sp_inicio, sp_cantidad;
    end if;
    
    if opcion = 'TotalNiveles' then
		SELECT id_nivel, id_curso_f, titulo, resumen, contenido, costo, video, baja_logica from nivel
        where id_curso_f = sp_id;
    end if;
    
    if opcion = 'ListadoNiveles' then
		SELECT id_nivel, id_curso_f, titulo, resumen, contenido, costo, video, baja_logica from nivel
        where id_curso_f = sp_id
        LIMIT sp_inicio, sp_cantidad;
    end if;
    
    if opcion = 'TotalCategorias' then
		SELECT id_categoria, id_usuario_f, titulo, descripcion, fecha_creacion, baja_logica from categoria
        where id_usuario_f = sp_id;
    end if;
    
    if opcion = 'ListadoCategorias' then
		SELECT id_categoria, id_usuario_f, titulo, descripcion, fecha_creacion, baja_logica from categoria
        where id_usuario_f = sp_id
        LIMIT sp_inicio, sp_cantidad;
    end if;
    
    if opcion = 'CostoCurso' then
		select costo, titulo from curso where id_curso = sp_id;
	end if;
end$$
DELIMITER ;

###------------ VISTAS ------------###
DROP PROCEDURE IF EXISTS sp_vista;
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_vista`(
in idusuario		int,
in opcion 			varchar(100)
)
SQL SECURITY INVOKER
begin
	if opcion = 'vista_pagos_cursos' then
		SELECT id_pago_curso, forma_pago, titulo, total_ventas, total_alumnos, id_usuario
		FROM vista_pagos_cursos 
        WHERE id_usuario = idusuario;
	end if;
    
	if opcion = 'vista_curso_inscrito' then
		SELECT vista.fecha_inscripcion, vista.finalizado, vista.id_usuario_f, vista.titulo, vista.id_curso
		FROM vista_curso_inscrito AS vista
		WHERE vista.id_usuario_f = idusuario;
	end if;
    
	if opcion = 'vista_recursos_curso' then
		DROP VIEW IF EXISTS vista_recursos_curso;
		CREATE VIEW vista_recursos_curso AS
		SELECT ci.id_curso_inscrito, c.titulo AS curso, 
        r.id_recursos, r.nombre AS recurso, r.tipo, r.contenido,
        n.titulo AS titulo_curso
		FROM curso_inscrito ci
		JOIN curso c ON ci.id_curso_f = c.id_curso
		JOIN nivel n ON n.id_curso_f = c.id_curso
		JOIN recursos r ON r.id_nivel_f = n.id_nivel;
        
        select 1 as codigo,
		concat('se ha actualizado la vista correctamente') as mensaje;
	end if;
end$$
DELIMITER ;