<?php
// echo getcwd() . "\n";
 if(!file_exists("../lib/setup.php"))
	 chdir("../../code");

require_once("../lib/setup.php");
$smarty = new bd;	
//require_once('../lib/verificar.php');

function noexiste($tabla, $campo){
	//verifica si el $campo existe en la $tabla
	$ssql="SELECT sc.name FROM syscolumns AS sc INNER JOIN sysobjects AS so ON
	sc.id=so.id AND	sc.name='$campo' AND so.name='$tabla' ";
	$qquery= consulta($ssql);
	if($rrow = $qquery->fetchRow(DB_FETCHMODE_ASSOC)){
		$resulta = $rrow["name"];
	}else{
		$resulta = '0';
	}
	if($resulta==$campo)
		return false;
	else
		return true;
}

function longitud($tabla, $campo){
	//verifica si el $campo existe en la $tabla
	$ssql="SELECT character_maximum_length AS flen  FROM information_schema.columns  
			WHERE table_name = '$tabla' AND column_name = '$campo'";
	$qquery= consulta($ssql);
	if($rrow = $qquery->fetchRow(DB_FETCHMODE_ASSOC)){
		$resulta = $rrow["flen"];
	}else{
		$resulta = '0';
	}
	
	return $resulta;
}

if(isset($_REQUEST['proceder'])){
	$cambios = '';
	$cnt=0;
	
	
	
	//09/10/2012
	if(noexiste('opciones','id_perfil_cat')){
		$sql = "alter table opciones add id_perfil_cat int default 0";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}

	//09/11/2012
	if(noexiste('ncaso_cfinal','escrituraLinea')){
		$sql = "ALTER TABLE ncaso_cfinal add escrituraLinea varchar(50) default ''";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}
	
	//09/11/2012
	if(noexiste('ncaso_cfinal','fechaescLinea')){
		$sql = "ALTER TABLE ncaso_cfinal add fechaescLinea datetime null";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}

	//09/11/2012
	if(noexiste('ncaso_cfinal','notarioLinea')){
		$sql = "ALTER TABLE ncaso_cfinal add notarioLinea varchar(60) default ''";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}
/*
	//09/11/2012
	if(noexiste('lineas','id_linea')){
		//para crear tabla
		$sql = "CREATE TABLE [lineas](
	[id_linea] [int] IDENTITY(1,1) NOT NULL,
	[id_propietario] [int] NULL,
	[numero] [int] NULL,
	[importe] [nchar](12) NULL,
	[moneda] [nchar](2) COLLATE Modern_Spanish_CI_AS NULL,
	[tipo] [tinyint] NULL,
	[escritura] [nchar](20) COLLATE Modern_Spanish_CI_AS NULL,
	[fechaesc] [datetime] NULL,
	[notario] [nchar](50) COLLATE Modern_Spanish_CI_AS NULL,
 CONSTRAINT [PK_lineas] PRIMARY KEY CLUSTERED 
(
	[id_linea] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>CREATE TABLE lineas</strong><br />";
	}else{
		$cnt++;
	}
*/
	//***********************************************************************************
	// para busquedas de propietarios en contratos
		$sql = "update propietarios set personanatural = 1 where personanatural is null";
		ejecutar($sql);
	//***********************************************************************************
	
	//09/11/2012
	if(noexiste('documentos','tiene_coment')){
		$sql = "ALTER TABLE documentos ADD tiene_coment tinyint default 0";
		ejecutar($sql);
		$sql = "update documentos set tiene_coment = 0";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}

	//09/11/2012
	if(noexiste('opciones','enable_deldoc')){
		//para permitir quitar documentos de la recepcion
		$sql = "alter table opciones add enable_deldoc char(1) default 'N'";
		ejecutar($sql);
		$sql = "update opciones set enable_deldoc = 'N'";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}

	//10/11/2012 -- drop table informes_legales_pj
	if(noexiste('informes_legales_pj','id_informe_legal')){
		//para crear tabla
		$sql = "CREATE TABLE [informes_legales_pj](
	[id_informe_legal] [int] NOT NULL,
	[tipo_sociedad] [tinyint] NULL,
	[actividad] [text] COLLATE Modern_Spanish_CI_AS NULL,
	[duracion] [nchar](40) COLLATE Modern_Spanish_CI_AS NULL,
	[fecha_vence] [datetime] NULL,
	[nomina_dir] [text] COLLATE Modern_Spanish_CI_AS NULL,
	[matricula] [text] COLLATE Modern_Spanish_CI_AS NULL,
	[fecha_matri] [datetime] NULL,
	[nro_escritura] [varchar](20) COLLATE Modern_Spanish_CI_AS NULL,
	[fecha_escri] [datetime] NULL,
	[nro_resol] [varchar](20) COLLATE Modern_Spanish_CI_AS NULL,
	[fecha_resol] [datetime] NULL,
	[notario] [varchar](40) COLLATE Modern_Spanish_CI_AS NULL,
 CONSTRAINT [PK_informes_legales_pj] PRIMARY KEY CLUSTERED 
(
	[id_informe_legal] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>CREATE TABLE informes_legales_pj</strong><br />";
	}else{
		$cnt++;
	}


	if(noexiste('sociedades','id_sociedad')){
		//para crear tabla
		$sql = "CREATE TABLE [sociedades](
	[id_sociedad] [int] IDENTITY(1,1) NOT NULL,
	[sociedad] [varchar](50) COLLATE Modern_Spanish_CI_AS NULL,
	[tipo] [nchar](1) COLLATE Modern_Spanish_CI_AS NULL,
 CONSTRAINT [PK_sociedades] PRIMARY KEY CLUSTERED 
(
	[id_sociedad] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]";
		ejecutar($sql);
		$sql="begin
insert into sociedades (sociedad, tipo) values ('Cooperativa','1');
insert into sociedades (sociedad, tipo) values ('Asociación','1');
insert into sociedades (sociedad, tipo) values ('Fundación','1');
insert into sociedades (sociedad, tipo) values ('Sindicato','1');
insert into sociedades (sociedad, tipo) values ('ONG','1');
insert into sociedades (sociedad, tipo) values ('Deportiva','1');
insert into sociedades (sociedad, tipo) values ('Religiosa','1');
insert into sociedades (sociedad, tipo) values ('Cultural','1');
insert into sociedades (sociedad, tipo) values ('Sociedad Colectiva','2');
insert into sociedades (sociedad, tipo) values ('Sociedad en Comandita Simple','2');
insert into sociedades (sociedad, tipo) values ('Sociedad de Responsabilidad Limitada','2');
insert into sociedades (sociedad, tipo) values ('Sociedad Anónima','2');
insert into sociedades (sociedad, tipo) values ('Sociedad en Comandita por Acciones','2');
insert into sociedades (sociedad, tipo) values ('Asociación Accidental','2');
end 
go";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>CREATE TABLE sociedades</strong><br />";
	}else{
		$cnt++;
	}

	if(noexiste('poderes','id_poder')){
		//para crear tabla
		$sql = "CREATE TABLE [poderes](
	[id_poder] [int] IDENTITY(1,1) NOT NULL,
	[id_informe_legal] [int] NULL,
	[numero] [nchar](20) COLLATE Modern_Spanish_CI_AS NULL,
	[notario] [nchar](50) COLLATE Modern_Spanish_CI_AS NULL,
	[fecha] [datetime] NULL,
	[fojas] [tinyint]  NULL,
	[id_tipo_documento] [tinyint]  NULL,
	[otorgante] [text] COLLATE Modern_Spanish_CI_AS NULL,
	[registro] [text] COLLATE Modern_Spanish_CI_AS NULL,
 CONSTRAINT [PK_poderes] PRIMARY KEY CLUSTERED 
(
	[id_poder] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>CREATE TABLE poderes</strong><br />";
	}else{
		$cnt++;
	}

	if(noexiste('apoderados','id_apoderado')){
		//para crear tabla
		$sql = "CREATE TABLE [apoderados](
	[id_apoderado] [int] IDENTITY(1,1) NOT NULL,
	[id_poder] [int] NULL,
	[apoderado] [nchar](50) COLLATE Modern_Spanish_CI_AS NULL,
	[ci] [nchar](50) COLLATE Modern_Spanish_CI_AS NULL,
	[tipo] [nchar](1) COLLATE Modern_Spanish_CI_AS NULL,
	[vigente] [nchar](1) COLLATE Modern_Spanish_CI_AS NULL,
	[porcentaje] [nchar](3) COLLATE Modern_Spanish_CI_AS NULL,
	[facultades] [text] COLLATE Modern_Spanish_CI_AS NULL,
	[restricciones] [text] COLLATE Modern_Spanish_CI_AS NULL,
 CONSTRAINT [PK_apoderados] PRIMARY KEY CLUSTERED 
(
	[id_apoderado] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>CREATE TABLE apoderados</strong><br />";
	}else{
		$cnt++;
	}



 //29/11/2012 para que el recepcionista autorice su propia solicitud (bisa)
	if(noexiste('opciones','autosolicita')){
		$sql = "alter table opciones add autosolicita char(1) default 'N'";
		ejecutar($sql);
		$sql = "update opciones set autosolicita = 'N'";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}

/* --esto para SEC
-- alter table contrato_final add nrocaso varchar(16) null 
-- */

	//--07/03/2013--para habilitar traslado de carptas en catastro
	if(noexiste('opciones','trasladar')){
		$sql = "ALTER TABLE opciones add trasladar char(1) default 'N'";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}

	if(noexiste('informes_legales_bk','id_informe_legal')){
		//para crear tabla
		$sql = "CREATE TABLE [informes_legales_bk](
	[id_informe_legal] [int] NOT NULL,
	[id_tipo_bien] [int] NOT NULL,
	[id_propietario] [int] NULL DEFAULT (NULL),
	[fecha_eliminacion] [datetime] NULL DEFAULT (NULL),
	[otras_observaciones] [text] COLLATE Modern_Spanish_CI_AS NULL,
	[garantia_contrato] [text] COLLATE Modern_Spanish_CI_AS NULL,
	[nota] [text] COLLATE Modern_Spanish_CI_AS NULL,
	[conclusiones] [text] COLLATE Modern_Spanish_CI_AS NULL,
	[numero_informe] [varchar](20) COLLATE Modern_Spanish_CI_AS NULL,
	[tradicion] [text] COLLATE Modern_Spanish_CI_AS NULL,
	[fecha_aceptacion] [datetime] NULL DEFAULT (NULL),
	[usr_acep] [int] NULL DEFAULT (NULL),
	[bandera] [char](1) COLLATE Modern_Spanish_CI_AS NULL DEFAULT (NULL),
	[nrocaso] [int] NULL,
	[justifica] [text] COLLATE Modern_Spanish_CI_AS NULL,
	[id_us_elim] [int] NULL,
PRIMARY KEY CLUSTERED 
(
	[id_informe_legal] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>CREATE TABLE informes_legales_bk</strong><br />";
	}else{
		$cnt++;
	}

	
	
	
	//14/11/2012
	if(noexiste('oficinas','codigo')){
		//para habilitar traslado de carptas en catastro
		$sql = "alter table oficinas add codigo nchar(10) default ''";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}
	
/* --IMPORTANTE: esto para Banco Sol
--update oficinas set codigo = telefonos
--update oficinas set codigo = case when len(telefonos)=3 then telefonos else '0' end
 */
 
	//29/11/2012
	if(noexiste('opciones','autosolicita')){
		//para habilitar traslado de carptas en catastro
		$sql = "alter table opciones add autosolicita char(1) default 'N'";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}
	
	//actualizaciones a la DB a partir del 07/03/2013. victor rivas
	if(noexiste('opciones','trasladar')){
		//para habilitar traslado de carptas en catastro
		$sql= "ALTER TABLE opciones ADD trasladar char(1) default 'N' ";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}
	
	//actualizaciones a la DB a partir del 21/03/2013. victor rivas
	if(noexiste('usuarios','bloqueado')){
		//para habilitar traslado de carptas en catastro
		$sql= "ALTER TABLE usuarios ADD bloqueado char(1) default 'N' ";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
		
	}
	
	
	//actualizaciones a la DB a partir del 24/04/2013. victor rivas
	if(noexiste('opciones','rutatmp')){
		//para 
		$sql= "ALTER TABLE opciones add rutatmp varchar(200) null ";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
		
	}
	
	
	//actualizaciones a la DB a partir del 12/07/2013. victor rivas
	//para BSOL era nro de cuenta en recepcion de docs 
	//actualizaciones a la DB 30/05/2015. victor rivas
	if(noexiste('informes_legales','instancia')){
		$sql= "ALTER TABLE informes_legales ADD instancia int null ";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
		//fixdesa
		$sql= "UPDATE informes_legales SET instancia = nrocaso";
		ejecutar($sql);
		if(!noexiste('informes_legales','cuenta')){
			$sql= "ALTER TABLE informes_legales DROP COLUMN cuenta";
			ejecutar($sql);
		}
	}else{
		$cnt++;
		
	}
	
	

	//actualizaciones a la DB a partir del 22/08/2013. victor rivas
	if(noexiste('ncaso_cfinal','teac')){
		$sql= "ALTER TABLE ncaso_cfinal add [teac] [decimal](6, 2) NULL ";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
		
	}

	
	//actualizaciones a la DB a partir del 22/08/2013. victor rivas
	if(noexiste('ncaso_cfinal','cuota')){
		$sql= "ALTER TABLE ncaso_cfinal add [cuota] [decimal](10, 2) NULL";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
		
	}
	
	
	//actualizaciones a la DB a partir del 08/10/2013. victor rivas
	if(noexiste('informes_legales','id_oficina')){
		$sql= "ALTER TABLE informes_legales add [id_oficina] [int] NULL";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
		$sql="UPDATE      informes_legales 
				SET      informes_legales.id_oficina = usuarios.id_oficina
				FROM      informes_legales 
				INNER JOIN        usuarios 
				ON          informes_legales.id_us_comun = usuarios.id_usuario
				WHERE      informes_legales.id_oficina != usuarios.id_oficina or
				(usuarios.id_oficina is not null and informes_legales.id_oficina is null) ";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>(Actualizacion de datos IL->Oficina)</strong><br />";
	}else{
		$cnt++;
		
	}

	
	
	//actualizaciones a la DB a partir del 15/10/2013. victor rivas
	if(noexiste('tipos_bien','categoria')){
		//para BEC
		$sql= "ALTER TABLE tipos_bien add [categoria] [char](1) NULL";
		ejecutar($sql);
		$sql = "UPDATE tipos_bien SET categoria = '0'";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}
	
	//actualizaciones a la DB a partir del 15/10/2013. victor rivas
	if(longitud('documentos','documento') < 150){
		$sql= "ALTER TABLE documentos ALTER COLUMN [documento] [VARCHAR](150) NULL";
		ejecutar($sql);
		
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	}
	
	//actualizaciones a la DB a partir del 15/10/2013. victor rivas
	if(noexiste('tipos_bien_documentos','orden')){
		//para BEC
		$sql= "ALTER TABLE tipos_bien_documentos ADD [orden] [tinyint] NULL, requerido [tinyint] NULL ";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
		$sql = "UPDATE tipos_bien_documentos SET orden = 0, requerido = 0";
		ejecutar($sql);
	}else{
		$cnt++;
	}
	
	
	//actualizaciones a la DB a partir del 17/10/2013. victor rivas
	if(noexiste('bancas','codigo')){
		//para BEC
		$sql= "ALTER TABLE bancas ADD [codigo] [tinyint] NULL ";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
		$sql = "UPDATE bancas SET codigo = 0";
		ejecutar($sql);
	}else{
		$cnt++;
	}
	
	//actualizaciones a la DB a partir del 17/10/2013. victor rivas
	if(noexiste('tipos_bien','id_banca')){
		//para BEC
		$sql= "ALTER TABLE tipos_bien ADD [id_banca] [tinyint] NULL ";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
		$sql = "UPDATE tipos_bien SET id_banca = 0";
		ejecutar($sql);
	}else{
		$cnt++;
	}
	
	//actualizaciones a la DB a partir del 30/10/2013. victor rivas
	//este caso especial es para crear tabla contratos_fijos que solo estaba en BEC
	if(noexiste('contratos_fijos','idcontrato')){
		//para BEC
		$sql= "CREATE TABLE contratos_fijos (idcontrato int NULL,
				clase varchar(1) NULL,
				id_banca int NULL) ";
				/*
				para llenar esta tabla se crea la opcion de menu de ADM\bancas.php
				*/
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}
	
	//actualizaciones a la DB a partir del 18/02/2014. victor rivas
	if(noexiste('informes_legales_pj','direccion')){
		//para BEC
		$sql= "ALTER TABLE informes_legales_pj ADD [direccion] [text] NULL ";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}

	
	
	//19/02/2014 para los representantres de cada agencia segun la banca
	if(noexiste('representa','id_oficina')){
		//para crear tabla
		$sql = "CREATE TABLE [dbo].[representa] (
		[id_representa] int IDENTITY(1,1) NOT NULL ,
		[id_oficina] int NOT NULL ,
		[id_banca] int NULL ,
		[idtexto] nchar(20) NULL ,
		[nombre] nchar(50) NULL ,
 CONSTRAINT [PK_representa] PRIMARY KEY CLUSTERED (
	[id_representa] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]";
		ejecutar($sql);
		
		$cambios .= "  Aplicado: <strong>CREATE TABLE representa</strong><br />";
	}else{
		$cnt++;
	}
	
	//actualizaciones a la DB a partir del 20/02/2014. victor rivas
	if(noexiste('ncaso_cfinal','agencia')){ 
		$sql= "ALTER TABLE ncaso_cfinal add [agencia] [smallint] NULL";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
		
	}
	
	
	//para nueva version
	$sql= "UPDATE tipos_bien SET descripcion = tipo_bien WHERE descripcion = ''";
		ejecutar($sql);
	//	$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	
	
	
	//actualizaciones a la DB a partir del 30/09/2014. victor rivas
	if(noexiste('opciones','tipodoc')){
		//para BEC
		$sql= "ALTER TABLE opciones ADD [tipodoc] char(1) default 'W' ";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}
	
	//actualizaciones a la DB a partir del 02/10/2014. victor rivas
	if(longitud('informes_legales_vehiculos ','clase') < 150){
		$sql= "ALTER TABLE informes_legales_vehiculos ALTER COLUMN [clase] [VARCHAR](250) NULL";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	}
	
	//actualizaciones a la DB a partir del 25/03/2015. victor rivas
	if(noexiste('carpetas','suboperacion')){
		//para Bsol
		$sql= "ALTER TABLE carpetas ADD [suboperacion] char(20) default '0' ";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}
	
	//actualizaciones a la DB a partir del 19/05/2015. victor rivas
	if(noexiste('propietarios','fechaeli')){
		//para Bsol
		$sql= "ALTER TABLE propietarios ADD fechaeli DATETIME NULL ";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
		$sql= "ALTER TABLE propietarios ADD motivoeli TEXT NULL  ";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}
	
	//actualizaciones a la DB a partir del 19/05/2015. victor rivas
	if(noexiste('opciones','long_pass')){
		//para Bsol
		$sql= "ALTER TABLE opciones ADD long_pass tinyint ";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
		$sql="UPDATE opciones SET long_pass = long_login";
		ejecutar($sql);
	}else{
		$cnt++;
	}
	
	
	
	
	//actualizaciones a la DB a partir del 26/05/2015. victor rivas
	if(noexiste('sec_opcional','idcontrato')){
		//para bisa, baneco ya tendria
		$sql= "CREATE TABLE sec_opcional (
	[idcontrato] [int] NOT NULL,
	[idclausula] [int] NOT NULL,
	[posicion] [int] NULL,
	[opcional] [bit] NOT NULL,
	[idnumeral] [int] NOT NULL,
	[tc] [nvarchar](250) NULL,
	[tcl] [nvarchar](250) NULL,
	[titulo] [nvarchar](100) NULL,
	[id_banca] [int] NULL
	) ON [PRIMARY]";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";

	}else{
		$cnt++;
	}

	//29/05/2015 para eliminacion de carpetas
	if(noexiste('carpetas_bk','id_propietario')){
		//para crear tabla
		$sql = "CREATE TABLE [dbo].[carpetas_bk] (
		[id_propietario] int NOT NULL ,
		[fechaeli] datetime NULL ,
		[motivoeli] text NULL ,
		[nombres] nchar(50) NULL ,
		[CI] nchar(50) NULL ,
		[usuario_sol] int NULL ,
		[usuario_eli] int NULL ) ";
		ejecutar($sql);
		
		$cambios .= "  Aplicado: <strong>CREATE TABLE carpetas_bk</strong><br />";
	}else{
		$cnt++;
	}
	
	
	
	//actualizaciones a la DB a partir del 01/05/2015. victor rivas
	/*
	if(noexiste('operacionescan','Operacion')){
	//para Bsol
		$sql= "CREATE TABLE [dbo].[operacionescan](
		[Instancia] [int] NULL,
		[Cuenta_Inst] [int] NULL,
		[PaisDoc] [int] NULL,
		[TipoDoc] [int] NULL,
		[NumeroDoc] [varchar](12) NULL,
		[Asesor] [varchar](10) NULL,
		[CodEmp] [int] NULL,
		[Modulo] [int] NULL,
		[Sucursal] [int] NULL,
		[Moneda] [int] NULL,
		[Papel] [int] NULL,
		[Operacion] [int] NULL,
		[SubOperacion] [int] NULL,
		[TipoOperacion] [int] NULL,
		[Importe] [int] NULL,
		[Estado] [int] NULL,
		[Cuenta] [varchar](20) NULL,
		[FechaCan] [datetime] NULL
		[fechabaja] [datetime] NULL,
	) ON [PRIMARY] ";
		ejecutar($sql);

	}
	
	//actualizaciones a la DB a partir del 01/05/2015. victor rivas
	if(noexiste('operacionescan','FechaCan')){
		//para Bsol
		$sql= "ALTER TABLE operacionescan ADD FechaCan [datetime] NULL ";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";

	}else{
		$cnt++;
	}
*/
	
	//actualizaciones a la DB a partir del 01/05/2015. victor rivas
	if(noexiste('ncaso_cfinal','id_informe')){ 
		$sql= "ALTER TABLE ncaso_cfinal ADD [id_informe] [int] NULL";   //para bsol, relacionamos con el i.l. cuando hace solicitud de contrato
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
		//fix desa: 09/06/2015
		$sql= "UPDATE nc SET nc.id_informe = il.id_informe_legal FROM ncaso_cfinal nc INNER JOIN informes_legales il ON nc.nrocaso =  il.nrocaso";
		ejecutar($sql);
		
	}else{
		$cnt++;
		
	}
	
	
	if(noexiste('ncaso_cfinal','fechaFijaPlazo')){ 
		$sql= "ALTER TABLE ncaso_cfinal ADD [fechaFijaPlazo] [datetime] NULL";  
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	}
	
	//actualizaciones a la DB a partir del 01/09/2015. victor rivas BISA
	if(noexiste('ncaso_cfinal','usuario')){ 
		$sql= "ALTER TABLE ncaso_cfinal ADD [usuario] [varchar](10) NULL";  
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
		$sql= "ALTER TABLE ncaso_cfinal ADD [device] [varchar](10) NULL";  
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
		$sql= "ALTER TABLE ncaso_cfinal ADD [fecha] [datetime] NULL";  
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
		$sql= "ALTER TABLE ncaso_cfinal ADD [ipadd] [varchar](15) NULL";  
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
		$sql= "ALTER TABLE ncaso_cfinal ADD [pgmname] [varchar](10) NULL";  
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
		$sql= "ALTER TABLE ncaso_cfinal ADD [jobnum] [varchar](6) NULL";  
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
		$sql= "ALTER TABLE ncaso_cfinal ADD [tipopp] [varchar](1) NULL";  
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;	
	}
	
	
	if(noexiste('ncaso_cfinal','producto')){ 
		$sql= "ALTER TABLE ncaso_cfinal ADD [producto] int NULL";  
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	}
	
	
	if(noexiste('ncaso_cfinal','periodogracia')){ 
		$sql= "ALTER TABLE ncaso_cfinal ADD [periodogracia] [varchar](10) NULL";  
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	}
	
	
	if(noexiste('ncaso_cfinal','ubicaciongarantia')){ 
		$sql= "ALTER TABLE ncaso_cfinal ADD [ubicaciongarantia] [varchar](40) NULL";  
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	}
	
	//23/06/2015 ... pero, ya deberia existir
	if(noexiste('oficinas','id_asesor')){
		//para habilitar traslado de carptas en catastro
		$sql = "alter table oficinas add id_asesor int default 0";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}

//06/07/2015
	if(noexiste('objetos','id_objeto')){
		//para crear tabla
		$sql = "CREATE TABLE [objetos](
	[id_objeto] [int] IDENTITY(1,1) NOT NULL,
	[objeto] [varchar](100) COLLATE Modern_Spanish_CI_AS NULL,
	[tipo] [nchar](1) COLLATE Modern_Spanish_CI_AS NULL,
 CONSTRAINT [PK_objetoes] PRIMARY KEY CLUSTERED 
(
	[id_objeto] ASC
)WITH (PAD_INDEX  = OFF, IGNORE_DUP_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]";
		ejecutar($sql);

		$cambios .= "  Aplicado: <strong>CREATE TABLE objetos</strong><br />";
	}else{
		$cnt++;
	}
	
//10/09/2015
	if(noexiste('producto','codigo')){
		//para crear tabla
		$sql = "CREATE TABLE [producto](
		[codigo] [int] NOT NULL,
		[descripcion] [varchar](40) NULL)";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>CREATE TABLE producto</strong><br />";
		$sql = "INSERT INTO producto (codigo, descripcion) 
			 VALUES (310,'MF Plazo Fijo BS'),
					(311,'MF Plazo Fijo Est BS'),
					(312,'MF Plazo Fijo Org BS'),
					(313,'MF Plazo Fijo US'),
					(314,'MF Plazo Fijo Est US'),
					(315,'MF Plazo Fijo Org US'),
					(316,'MF Amortizable TFIJA BS'),
					(317,'MF Amortizable TFIJA Est BS'),
					(318,'MF Amortizable TFIJA Org BS'),
					(319,'MF Amortizable TFIJA US'),
					(320,'MF Amortizable TFIJA Est US'),
					(321,'MF Amortizable TFIJA Org US'),
					(322,'MF Amortizable TVAR TRE BS'),
					(323,'MF Amortizable TVAR TRE Est BS'),
					(324,'MF Amortizable TVAR TRE Org BS'),
					(325,'MF Amortizable TVAR TRE US'),
					(326,'MF Amortizable TVAR TRE Est US'),
					(327,'MF Amortizable TVAR TRE Org US'),
					(328,'MF Hipotecario TFIJA BS'),
					(331,'MF Hipotecario TFIJA US'),
					(334,'MF Hipotecario TVAR TRE BS'),
					(337,'MF Hipotecario TVAR TRE US')					";
		ejecutar($sql);

	}else{
		$cnt++;
	}
	
	//30/07/2015 ... para bsol
	if(noexiste('oficinas','correos')){
		//para enviar correos
		$sql = "alter table oficinas add correos varchar(150)";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}
	
	//01/09/2015 para bsol añadimos -- a emisiones
	$ssql="SELECT emision FROM emisiones WHERE emision = '--' ";
	$qquery= consulta($ssql);
	if($rrow = $qquery->fetchRow(DB_FETCHMODE_ASSOC)){
		//$resulta = $rrow["name"];
	}else{
		$sql = "INSERT INTO emisiones (emision) VALUES ('--')";
		ejecutar($sql);
		$cambios .= "  Aplicado: <strong>".$sql."</strong><br />";
	}

	
	//actualizaciones a la DB a partir del 07/10/2015. victor rivas
	if(noexiste('usuarios','fecha_eli')){
		//para llevar registro de eliminacion de usuario
		$sql= "ALTER TABLE usuarios ADD fecha_eli datetime null ";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
		$sql= "ALTER TABLE usuarios ADD user_eli varchar(15) null ";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
		
	}
	
	
	//actualizaciones a la DB a partir del 28/10/2015. victor rivas
	if(noexiste('usuarios','login_ldap')){
		//para el login mediante ldap
		$sql= "ALTER TABLE usuarios ADD login_ldap varchar(25) null ";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
		
	}
	
	
	//actualizaciones a la DB a partir del 08/12/2015. victor rivas
	if(noexiste('opciones','enable_catofi')){
		//para cidre
		$sql= "ALTER TABLE opciones ADD enable_catofi char(1) ";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
		$sql="UPDATE opciones SET enable_catofi = 'N'";
		ejecutar($sql);
	}else{
		$cnt++;
	}
	
	//-------------------------------------------------------------- 2016
	
	//actualizaciones a la DB a partir del 04/01/2016. victor rivas
	if(noexiste('opciones','ws_url5')){
		//para la url del ws de banco sol, para recepcion e informe legal
		$sql= "ALTER TABLE opciones ADD ws_url5 varchar(60) null ";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}
	
	
	//actualizaciones a la DB a partir del 05/01/2016. victor rivas
	//para BSOL se adiciona en recepcion de docs busqueda por NRO de OPORTUNIDAD
	//
	if(noexiste('informes_legales','noportunidad')){
		$sql= "ALTER TABLE informes_legales ADD noportunidad int null ";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
		$sql= "UPDATE informes_legales SET noportunidad = 0";
		ejecutar($sql);
	}else{
		$cnt++;
	}
	
	
	//-------------------------------------------------------------- 2017
	
	//actualizaciones a la DB a partir del 17/04/2017. victor rivas
	if(noexiste('informes_legales','inf_nota')){
		//para la url del ws de banco sol, para recepcion e informe legal
		$sql= "ALTER TABLE informes_legales ADD inf_nota varchar(100) null ";
		ejecutar($sql);
		$cambios .= "  Aplica: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}
	//-------------------------------------------------------------- 2022
	
	//actualizaciones a la DB a partir del 13/06/2022. victor rivas
	if(noexiste('documentos_propietarios','archivo')){
		//para guardar el nombre de archivo escaneado del docuemnto correspondiente
		$sql= "ALTER TABLE documentos_propietarios ADD archivo varchar(25) null ";
		ejecutar($sql);
		$sql= "Se puede subir archivo pdf del documento de las carpetas.";
		$cambios .= "  Actualizaci&oacute;n: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
	}
	
	//actualizacion a la DB del 13/06/2022. victor rivas
	if(noexiste('opciones','rutadoc')){
		//para 
		$sql= "ALTER TABLE opciones add rutadoc varchar(200) null ";
		ejecutar($sql);
		$sql= "Se puede especificar carpeta para archivos subidos.";
		$cambios .= "  Actualizaci&oacute;n: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
		
	}
	
	//actualizacion a la DB del 14/06/2022. victor rivas
	if(noexiste('opciones','extension')){
		//para 
		$sql= "ALTER TABLE opciones add extension varchar(50) null ";
		ejecutar($sql);
		$sql= "Se puede especificar extensiones para archivos subidos.";
		$cambios .= "  Actualizaci&oacute;n: <strong>".$sql."</strong><br />";
	}else{
		$cnt++;
		
	}
	
	
	//ir poniendo arriba de esta linea las demas actualizaciones
	$smarty->assign('cambios',$cambios);
	$ok=1;
	
}else{
	$ok=0;
}
	$smarty->assign('ok',$ok);
	$smarty->display('adm/update_DB/update_DB.html');

die();
?>