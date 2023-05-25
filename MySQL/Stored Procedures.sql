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
        'Error en base de datos' as Message; 

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
			update usuario set errores = 0 where id_usuario = (select id_usuario from usuario  where baja_logica = 0 and email = sp_email);
			select * from usuario where baja_logica = 0 and email = sp_email and contra = sp_contra;
		else
			if @temp_errores < 3 then
				update usuario set errores = errores + 1 where id_usuario = (select id_usuario from usuario  where baja_logica = 0 and email = sp_email);
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

# call sp_usuario_inicio_sesion('jairfurry@gmail.com', 'holaa');
# select * from usuario;
# update usuario set errores = 0 where id_usuario = 261305;


###----------------------------------ALTAS BAJAS Y CAMBIOS CATEGORIA


DROP PROCEDURE IF EXISTS sp_categoria;
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_categoria`(
in	sp_id_categirua					int,
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
        'Error en base de datos' as Message; 

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

###----------------------------------ALTAS BAJAS Y CAMBIOS CURSO #
DROP PROCEDURE IF EXISTS sp_curso;
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_curso`(
in	sp_id_curso				int,
in  sp_id_usuario_f			int,
in	sp_titulo					varchar(64),
in	sp_descripcion				varchar(500),
in  sp_imagen					longblob,
in  sp_costo					decimal(15, 2),
in	opcion					varchar(2)
)
SQL SECURITY INVOKER
begin

	DECLARE EXIT HANDLER FOR 1146 
		SELECT 1146 as codigo,
        'Tabla no encontrada' as mensaje;
        
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 100 as codigo,
       'Error en base de datos' as Message; 

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
    where id_curso = sp_id_curso
    ;
    
     select 1 as codigo,
    concat('Categoria modificado exitosamente') as mensaje;
    end if;
    
     if opcion = 'D' then 
		update curso set baja_logica = 1 
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
in opcion varchar(10)
)
SQL SECURITY INVOKER
begin

	DECLARE EXIT HANDLER FOR 1146 
		SELECT 1146 as codigo,
        'Tabla no encontrada' as mensaje;
        
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
		SELECT 100 as codigo,
        'Error en base de datos' as Message; 

	if opcion =	'I' then
		INSERT INTO curso_inscrito(id_curso_f, id_usuario_f, nivel_actual, finalizado)
		VALUES(sp_id_curso_f, sp_id_usuario_f, sp_nivel_actual, sp_finalizado);
		
		select 1 as codigo,
		concat('registro exitoso') as mensaje;
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