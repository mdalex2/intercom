 drop database if exists id5985804_intercom;
 create database id5985804_intercom DEFAULT CHARACTER SET 'utf8' DEFAULT COLLATE 'utf8_general_ci';
 use id5985804_intercom;

create table logs (
  id_log double not null auto_increment COMMENT 'CODIGO IDENTIFICADOR DEL LOG',
  ip varchar(20) not null default '' COMMENT 'DIRECCION IP DE LA MAQUINA QUE ACCEDE AL SISTEMA',
  puerto varchar(20) not null default '' COMMENT 'PUERTO USADO POR LA MAQUINA QUE ACCEDE AL SISTEMA',
  fecha datetime not null COMMENT 'FECHA DE ACCESO A LA PAGINA',
  detalles text not null COMMENT 'DETALLE DE LA ACCION EJECUTADA DENTRO DEL SISTEMA',
  url_pagina text COMMENT 'URL DE LA FUNCION A LA QUE SE ESTÁ ACCEDIENDO',
  usuario varchar(60) not null default '' COMMENT 'NOMBRE DE USUARIO QUE ACCEDE AL SISTEMA',
  primary key (id_log)
) AUTO_INCREMENT=1 COMMENT 'ESTA TABLA ALMACENA UN HISTORICO DE ACCESOS Y ACCIONES AL SISTEMA PARA SEGURIDAD DEL MISMO' engine=innodb;

create table cate_banco(
  id_banco bigint unsigned not null auto_increment,
  deno_banco varchar(100) not null default '' comment 'Denominación del banco',
  nota_obse varchar(350) not null default '' comment 'Información adicional / observaciones',
  item_visi bool not null default true comment 'Indica si el item esta visible para los usuarios del sistema en area de menus, listas y otros (1:visible,0:oculto)',
  primary key (id_banco)
) AUTO_INCREMENT=1 engine = innodb comment 'Almacena los nombres de bancos para cada país donde existen sucurzales';
INSERT INTO `cate_banco` (`deno_banco`, `nota_obse`, `item_visi`) VALUES 
('(Caja propia)', 'Usado para transferir saldo directo para un tipo de divisa en los usuarios finales', 1),
( 'Banesco', 'Banco Universal - Privado', 1),
('Banco de Venezuela', 'Banco Universal - Público', 1),
('BOD', 'Banco Universal - Privado', 1),
('BBVA Provincial', 'Banco Universal - Privado', 1),
('Mercantil', 'Banco Universal - Privado', 1),
('Banco Fondo Común', 'Banco Universal - Privado', 1),
('Bancaribe', 'Banco Universal - Privado', 1),
('Bancrecer', 'Banco Comercial - Privado', 1),
('Banco Exterior', 'Banco Universal - Privado', 1),
('BNC', 'Banco Universal - Privado', 1),
('Venezolano de Crédito', 'Banco Universal - Privado', 1),
('Banplus', 'Banco Universal - Privado', 1),
('Banco Plaza', 'Banco Universal - Privado', 1),
('Banco Caroní', 'Banco Universal - Privado', 1),
('Banfanb', 'Banco Universal - Público', 1),
('Banco del Tesoro', 'Banco Universal - Público', 1),
('DEL SUR', 'Banco Universal - Privado', 1),
('Banco Activo', 'Banco Universal - Privado', 1),
('Bicentenario Banco Universal', 'Banco Universal - Público', 1),
('Banco Sofitasa', 'Banco Universal - Privado', 1),
('100% Banco', 'Banco Universal - Privado', 1),
('Mi Banco', 'Banco Comercial - Privado', 1),
('Banco Agrícola de Venezuela', 'Banco Universal - Público', 1),
('Bancoex', 'Banco Comercial - Público', 1),
('Bancamiga', 'Banco Universal - Privado', 1),
('Instituto Municipal de Crédito Popular (IMCP)', 'Banco Comercial - Público', 1),
('Bangente', 'Banco Comercial - Privado', 1),
('Banco Internacional de Desarrollo', 'Banco Universal - Privado', 1),
('Banco de Exportación y Comercio', 'Banco Comercial - Privado', 1),
('Novo Banco', 'Banco Universal - Privado', 1),
('Citibank', 'Banco Universal - Privado', 1);

create table cate_tipo_usua(
  id_cate_tipo_usua int unsigned not null auto_increment comment 'Código identificador de la categoría / tipo de usuario',
  cate_tipo_usua varchar(100) not null comment 'Tipo de usuario (Administrador, cliente, Gerente, ventas...)',
  cate_tipo_usua_cort varchar(20) not null comment 'Tipo de usuario corto (Adm, clte, Gte, ven...)',
  item_visi bool not null default true COMMENT 'Indica si el item se muestra a los usuarios (1:VISIBLE,0:OCULTO)',
  primary key(id_cate_tipo_usua)
) AUTO_INCREMENT=1 engine=innodb comment 'Almacena las categorías de los tipo de usuarios';
  INSERT into cate_tipo_usua (cate_tipo_usua,cate_tipo_usua_cort) values 
  ('Administrador','Admin.'), 
  ('Mayorista','Mayor.'),
  ('Usuario final','Usua fin.'),
  ('Operador','Ope.'),
  ('Gestor cuentas','Gest. Cuent.');

create table cate_tipo_telf(
  id_cate_tipo_telf int not null auto_increment,
  tipo_telf varchar(100) not null comment 'Tipo de teléfono (móvil, fax, celular, gerencia...)',
  tipo_telf_cort varchar(20) not null comment 'Tipo de teléfono abreviado (móvil, fax, celular, gerencia...)',
  primary key(id_cate_tipo_telf)
) AUTO_INCREMENT=1 engine =innodb comment 'Almacena las categorías del tipo de teléfono';
insert into cate_tipo_telf (tipo_telf,tipo_telf_cort) values
  ('PRINCIPAL','PRIN'),
  ('MÓBIL','MÓV'),
  ('CASA','CASA'),
  ('TRABAJO','TRAB.'),
  ('BUSCA PERSONAS','BP'),
  ('FAX CASA','FAX CAS'),
  ('FAX TRABAJO','FAX TRAB'),
  ('VECINO','VEC'),
  ('OTRO','OTRO');

create table cate_tipo_email(
  id_tipo_email int not null auto_increment,
  tipo_email varchar(100) not null comment 'Tipo de email (móvil, fax, celular, gerencia,principal...)',
  tipo_email_cort varchar(20) not null comment 'Tipo de teléfono abreviado (móvil, fax, celular, gcia...)',
  primary key(id_tipo_email)
) AUTO_INCREMENT=1 engine =innodb comment 'Almacena las categorías del tipo de email';
insert into cate_tipo_email (tipo_email,tipo_email_cort) values
  ('PRINCIPAL','PRIN'),
  ('TRABAJO','TRAB'),
  ('GERENCIA','GER'),
  ('ADMINISTRACIÓN','ADM'),
  ('OTRO','OTRO');

create table cate_tipo_cuenta(
	id_cate_tipo_cuenta int not null auto_increment,
	tipo_cuenta varchar(100) not null comment 'Tipo de cuenta',
	tipo_cuenta_cort varchar(20) not null comment 'Tipo de cuenta abreviado',
	item_visi bool not null default true COMMENT 'Indica si el ítem está visible para los usuarios (1:VISIBLE,0:OCULTO)',
	primary key(id_cate_tipo_cuenta)
) AUTO_INCREMENT=1 engine =innodb comment 'Almacena las categorías del tipo de cuenta';  
insert into cate_tipo_cuenta (tipo_cuenta,tipo_cuenta_cort) values 
('Corriente','Corr.'),
('Ahorros','Aho.'),
('Electronica','Elec.');

create table cate_tipo_divisa(
	id_cate_tipo_divisa int not null auto_increment,
	tipo_divisa varchar(100) not null comment 'Tipo de divisa',
	tipo_divisa_cort varchar(20) not null comment 'Tipo de divisa abreviado',
	item_visi bool not null default true COMMENT 'Indica si el ítem está visible para los usuarios (1:VISIBLE,0:OCULTO)',
	primary key(id_cate_tipo_divisa)
) AUTO_INCREMENT=1 engine =innodb comment 'Almacena las categorías del tipo de divisa';  
insert into cate_tipo_divisa (tipo_divisa,tipo_divisa_cort) values 
('Dolares','USD'),
('Pesos','COP'),
('Bolivares','VEF'),
('Euros','EUR');

create table cate_tipo_movi (
	id_cate_tipo_movi int not null auto_increment comment 'Código identificador del tipo de movimiento',
    cate_tipo_movi varchar(40) not null default '' comment 'Categoría del tipo de movimiento',
    cate_tipo_movi_abr varchar(15) not null default '' comment 'Categoría del tipo de movimiento corto',
    tipo_oper varchar(2) not null default '+' comment 'Tipo de operador aritmetico (suma, resta)',
    item_visi bool not null default true comment 'Visible para los usuarios',
    primary key(id_cate_tipo_movi)
) engine = innodb comment 'Categorias del tipo de movimiento';
insert into cate_tipo_movi (cate_tipo_movi,cate_tipo_movi_abr,tipo_oper) values
('Abono','Abo.','+'),
('Débito / transferencia','Déb. / Trans.','-');

create table clie (
  id_clie varchar(100) not null default '' comment 'Código identificador del cliente (numero identificación)',  
  ciudad varchar(50) not null default '' comment 'Ciudad',
  desc_clie varchar(300) not null default '' comment 'Descripción del cliente',
  dire_fisc varchar(300) not null default '' comment 'Dirección fiscal del cliente',
  telf varchar(300) not null default '' comment 'Número telefónico del cliente',
  email varchar(300) not null default '' comment 'email del cliente',
  fech_regi date not null comment 'Fecha de registro',
  nota_obse varchar(200) not null default '' comment 'Notas / observaciones',
  item_visi bool not null default true COMMENT 'Indica si el ítem está visible para los usuarios (1:VISIBLE,0:OCULTO)',
  primary key(id_clie)
) engine = innodb comment 'Almacena los datos de los clientes que se registran por la web';
insert into clie (id_clie,desc_clie,dire_fisc,fech_regi) values 
('SA','Sin asignar','No posee dirección','2018-02-08'),
('2J000000000','Administrador del sistema','No posee dirección','2018-02-08'),
('2J000000001','mayorista Usuario ','No posee dirección','2018-02-08'),
('2J000000002','Final Usuario ','No posee dirección','2018-02-08'),
('2J000000003','Operador Usuario ','No posee dirección','2018-02-08'),
('2J000000004','gestor-cuentas Usuario ','No posee dirección','2018-02-08'),
('2J000000006','Operador2 usuario ','No posee dirección','2018-02-08');

update clie set item_visi=false where id_clie='SA'; /* pongo invisible el usuario genrico para poner sin asignacion las solicitudes cuando no estan en linea los operadores  */
create table usua_sist (
  id_usua_sist varchar(100) not null comment 'Código identificador del usuario (tipo identificación + numero identificación)',
  login varchar(200) not null default '' comment 'Login o usuario (e-mail)',
  pass blob  not null comment 'Clave del usuario',
  fech_regi date not null comment 'Fecha de registro',
  nota_obse varchar(200) not null default '' comment 'Notas / observaciones',
  bloqueado bool not null default false comment 'Indica al sistema si el usuario está bloqueado',
  tiem_expi int(2) not null default 5 comment 'Tiempo de expiración de la sesión',
  on_line bool not null default false comment 'indica al sistema si el operador está en línea',
  ultimo_acceso datetime not null comment 'Fecha y hora de la ultiima vez que se accedió para veríficar si el usuario esta en linea',
  primary key (id_usua_sist),
  index (id_usua_sist), foreign key (id_usua_sist) references clie(id_clie) on update cascade
) engine = innodb comment 'Almacena los datos de los usuarios';
  
  insert into usua_sist (id_usua_sist,login,pass,fech_regi,ultimo_acceso,tiem_expi) values 
  ('2J000000000','admin@intercom.com',aes_encrypt('1234','1234'),'2017-12-17','2018-06-13 13:00:00',60),
  ('2J000000001','mayorista@intercom.com',aes_encrypt('1234','1234'),'2017-12-17','2018-06-13 13:00:00',60),
  ('2J000000002','usuariofinal@intercom.com',aes_encrypt('1234','1234'),'2017-12-17','2018-06-13 13:00:00',60),
  ('2J000000003','operador@intercom.com',aes_encrypt('1234','1234'),'2017-12-17','2018-06-13 13:00:00',60),
  ('2J000000004','gestorcuentas@intercom.com',aes_encrypt('1234','1234'),'2017-12-17','2018-06-13 13:00:00',60),
  ('2J000000006','operador2@intercom.com',aes_encrypt('1234','1234'),'2017-12-17','2018-06-13 13:00:00',60);

create table usua_sist_tipo_usua(
  id_usua_sist varchar(100) not null comment 'Código identificador del usuario (tipo identificación + numero identificación)',  
  id_cate_tipo_usua int unsigned not null comment 'Código identificador del tipo de usuario/cliente admin, mayorista, usuario final...',
  nota_obse varchar(200) not null default '' comment 'Notas / observaciones',
  primary key(id_usua_sist,id_cate_tipo_usua),
  index(id_usua_sist), FOREIGN KEY (id_usua_sist) REFERENCES usua_sist (id_usua_sist) ON UPDATE CASCADE,
  index(id_cate_tipo_usua), FOREIGN KEY (id_cate_tipo_usua) REFERENCES cate_tipo_usua (id_cate_tipo_usua) ON UPDATE CASCADE
) engine=innodb comment 'Almacena los privilegios o tipos de usuario a los que el usuario tiene acceso, esto sirve para establecer privilegios de acceso vendedor, gerente etc'; 
  insert into usua_sist_tipo_usua (id_usua_sist,id_cate_tipo_usua) values 
  ('2J000000000',1),
  ('2J000000001',2),
  ('2J000000002',3),
  ('2J000000006',3),
  ('2J000000003',4),
  ('2J000000004',5);
  
create table clie_cuentas_bancos (
	id_cuenta varchar(200) not null default '' comment 'Código identificador de la cuenta del cliente id_banco + num cuenta',
	id_clie varchar(100) not null default '' comment 'Código identificador del cliente (tipo identificación + numero identificación)',  
	id_banco bigint unsigned not null comment 'Código del banco',
    id_cate_tipo_usua int unsigned not null comment 'Código identificador de la categoría / tipo de usuario',
	id_cate_tipo_cuenta int not null default 0 comment 'Código del tipo de cuenta',
	id_cate_tipo_divisa int not null default 0 comment 'Código del tipo de divisa',
	num_cuenta varchar(150) not null default '' comment 'Numero de cuenta',
    monto_max_tranf double not null default 0 comment 'Monto máximo permitido diario para transferencias',
    nume_movi_maxi int(4) not null default 0 comment 'Número máximo de movimientos en las cuentas permitido por el banco',
    item_visi bool not null default true comment 'Indíca al sistema, si el item se muestra a los usuarios',
    primary key (id_cuenta),
    index (id_clie), foreign key (id_clie) references clie(id_clie) on update cascade,
    index (id_banco), foreign key (id_banco) references cate_banco(id_banco) on update cascade,
    index (id_cate_tipo_cuenta), foreign key (id_cate_tipo_cuenta) references cate_tipo_cuenta(id_cate_tipo_cuenta) on update cascade,
    index (id_cate_tipo_divisa), foreign key (id_cate_tipo_divisa) references cate_tipo_divisa(id_cate_tipo_divisa) on update cascade,
    index (id_cate_tipo_usua), foreign key(id_cate_tipo_usua) references cate_tipo_usua(id_cate_tipo_usua) on update cascade
) auto_increment=1 engine = innodb comment 'Almacena las cuentas de los clientes';
INSERT INTO clie_cuentas_bancos (id_cuenta,id_clie,id_banco,id_cate_tipo_usua,id_cate_tipo_cuenta,id_cate_tipo_divisa,num_cuenta,monto_max_tranf) values
('XCUENTAUSUFIN01','2J000000000',1,1,3,1,'S/C',1500000),
('XCUENTAUSUFIN02','2J000000000',1,1,3,2,'S/C',1500000);

create table clie_operarios(
	id_clie varchar(100) not null default '' comment 'Código identificador del cliente (numero identificación)',   
    id_oper varchar(100) not null default '' comment 'Código identificador del operario',  
	id_cuenta varchar(200) not null default '' comment 'Código identificador de la cuenta del cliente id_banco + num cuenta',
    id_cate_tipo_usua int unsigned not null comment 'Código identificador de la categoría / tipo de usuario',
    monto_max_tranf double not null default 0 comment 'Monto máximo permitido diario para transferencias para el operario',
    fecha_reg datetime not null comment 'Fecha de asignación como operario de la cuenta',
    primary key(id_clie,id_oper,id_cuenta,id_cate_tipo_usua),
    index (id_clie), foreign key (id_clie) references clie(id_clie) on update cascade,
    index (id_oper), foreign key (id_oper) references clie(id_clie) on update cascade,
    index (id_cuenta), foreign key (id_cuenta) references clie_cuentas_bancos(id_cuenta) on update cascade,    
    index (id_cate_tipo_usua), foreign key(id_cate_tipo_usua) references cate_tipo_usua(id_cate_tipo_usua) on update cascade    
) engine=innodb comment 'Almacena la asignación de operarios (reseller, admin, usuarios finales, operadores de cuentas) a los distintos distribuidores';
insert into clie_operarios (id_clie,id_oper,id_cuenta,id_cate_tipo_usua,monto_max_tranf,fecha_reg) values 
('2J000000000','2J000000000','XCUENTAUSUFIN01',1,1500000,'2018-06-13 13:00:00'),
('2J000000000','2J000000002','XCUENTAUSUFIN01',3,1500000,'2018-06-13 13:00:00'),
('2J000000000','2J000000003','XCUENTAUSUFIN01',4,1500000,'2018-06-13 13:00:00'),
('2J000000000','2J000000006','XCUENTAUSUFIN01',4,1500000,'2018-06-13 13:00:00'),
('2J000000000','2J000000004','XCUENTAUSUFIN01',5,1500000,'2018-06-13 13:00:00'),

('2J000000000','2J000000000','XCUENTAUSUFIN02',1,1500000,'2018-06-13 13:00:00'),
('2J000000000','2J000000002','XCUENTAUSUFIN02',3,1500000,'2018-06-13 13:00:00'),
('2J000000000','2J000000003','XCUENTAUSUFIN02',4,1500000,'2018-06-13 13:00:00'),
('2J000000000','2J000000006','XCUENTAUSUFIN02',4,1500000,'2018-06-13 13:00:00'),
('2J000000000','2J000000004','XCUENTAUSUFIN02',5,1500000,'2018-06-13 13:00:00');

create table cuentas_bancos_movimientos (
	id_movi double not null auto_increment comment 'Código identificador del movimiento',
    id_cate_tipo_movi int(6) not null comment 'Código identificador del tipo de movimiento retiro abono transferencia',    
    id_cuenta varchar(200) not null default '' comment 'Código identificador de la cuenta del cliente id_banco + num cuenta',
    id_cuenta_orig varchar(200) not null default '' comment 'Código identificador de la cuenta destino del cliente id_banco + num cuenta',
    fecha_mov date not null comment 'Fecha del deposito / transferencia',
    num_ref varchar(100) not null default '' comment 'Número de referencia de la transacción',
    monto double not null default 0 comment 'Monto de dinero del movimiento',
    notas varchar(300) not null default '',    
    id_oper varchar(100) not null default '' comment 'Código identificador del operario que realizó la transacción',  
	fecha_reg datetime not null comment 'Fecha del registro',
	primary key(id_movi),
    index (id_cate_tipo_movi), foreign key(id_cate_tipo_movi) references cate_tipo_movi(id_cate_tipo_movi) on update cascade,
    index (id_oper), foreign key (id_oper) references clie(id_clie) on update cascade,
    index (id_cuenta), foreign key (id_cuenta) references clie_cuentas_bancos(id_cuenta) on update cascade,
    index (id_cuenta_orig), foreign key (id_cuenta_orig) references clie_cuentas_bancos(id_cuenta) on update cascade
) engine=innodb  auto_increment=1 comment 'Almacena los movimientos de las cuentas para los distintos usuarios';
  insert into cuentas_bancos_movimientos (id_cate_tipo_movi,id_cuenta,id_cuenta_orig,fecha_mov,num_ref,monto,id_oper,fecha_reg) values 
  (1,'XCUENTAUSUFIN01','XCUENTAUSUFIN01','2018-06-25','S/N',30000000,'2J000000000','2018-06-25 13:00:00'),
  (1,'XCUENTAUSUFIN02','XCUENTAUSUFIN01','2018-06-25','S/N',20000000,'2J000000000','2018-06-25 13:00:00');
  
create table solicitudes(
	id_sol double not null auto_increment comment 'Código identificador del movimiento',
    id_cuenta_orig varchar(200) not null default '' comment 'Código identificador de la cuenta del cliente id_banco + num cuenta',
    guard_por varchar(100) not null default '' comment 'Código identificador del operario que realizó la transacción',
    id_oper varchar(100) not null default '' comment 'Código identificador del operario al que se le asignó el trabajo',  
	id_banco bigint unsigned not null comment 'Código del banco',
	id_cate_tipo_cuenta int not null default 0 comment 'Código del tipo de cuenta',
    num_ide varchar(100) not null default '' comment 'Código identificador del cliente (numero identificación)', 
    desc_clie varchar(300) not null default '' comment 'Descripción del cliente',
    telf varchar(300) not null default '' comment 'Número telefónico del cliente',
    email varchar(300) not null default '' comment 'email del cliente',
    notas varchar(300) not null default '', 
	num_cuenta varchar(150) not null default '' comment 'Numero de cuenta',
    monto_soli double not null default 0 comment 'Monto máximo permitido diario para transferencias',
    fecha_soli datetime not null comment 'Fecha de solicitud del registro',
    item_proc bool not null default false comment 'Indica al sistema si el item se procesó',
    fecha_proc datetime not null comment 'Fecha de proceso del registro',
	item_canc bool not null default false comment 'Indica al sistema si el item fue cancelado',
    fecha_canc datetime not null comment 'Fecha de cancelacion (devuelta) del registro',
	fecha_trans date not null comment 'Fecha de transferencia',
	nume_refe varchar(150) not null default '' comment 'Número de referencia de la transacción',
    exte_arch varchar(5) not null default '' comment 'Extensión del archivo de la transferencia (para la descarga-visualización)',
	primary key(id_sol),
    index (guard_por), foreign key (guard_por) references clie(id_clie) on update cascade,
    index (id_oper), foreign key (id_oper) references clie(id_clie) on update cascade,
    index (id_banco), foreign key (id_banco) references cate_banco(id_banco) on update cascade,
	index (id_cate_tipo_cuenta), foreign key (id_cate_tipo_cuenta) references cate_tipo_cuenta(id_cate_tipo_cuenta) on update cascade,
    index (id_cuenta_orig), foreign key (id_cuenta_orig) references clie_cuentas_bancos(id_cuenta) on update cascade
) engine = innodb comment 'Almacena las solicitudes de los clientes';