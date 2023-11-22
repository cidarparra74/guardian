<?php
require_once("../lib/conexionMNU.php");

$sql= "truncate table `barramenu` ";
$c=ejecutar($sql);

$sql= "INSERT INTO `barramenu` (`codigo`, `nivel`, `descripcion`, `imagen`, `comando`, `verimagen`, `vertexto`, `activo`) VALUES
('1000', 1, 'CATASTRO', 'REPORT.PNG', '', '', 'S', 'S'),
('1100', 2, 'ARCHIVO', 'report.png', '', 'S', 'S', 'S'),
('1101', 3, 'Carpetas', 'page.png', 'propietarios.php', 'S', 'S', 'S'),
('1102', 3, 'Reportes Archivo', 'page.png', 'reportesar.php', 'S', 'S', 'S'),
('1200', 2, 'BANDEJAS', 'report.png', '', 'S', 'S', 'S'),
('1201', 3, 'Mensaje Archivo', 'page.png', 'buscamen.php', 'S', 'S', 'S'),
('1202', 3, 'Mensajes Autoriza', 'page.png', 'mensajeau.php', 'S', 'S', 'S'),
('1203', 3, 'Mensajes Comun', 'page.png', 'mensajeco.php', 'S', 'S', 'S'),
('1300', 2, 'SOLICITUDES', 'report.png', '', 'S', 'S', 'S'),
('1301', 3, 'Solicitar carpeta', 'page.png', 'solicitud_carpeta.php', 'S', 'S', 'S'),
('3000', 1, 'INFORME LEGAL', 'cash_machine.png', '', 'S', 'S', 'S'),
('3100', 2, 'OPCIONES', 'report.png', '', 'S', 'S', 'S'),
('3103', 3, 'Solicitud de Carpetas', 'page.png', 'solicitud_carpeta.php', 'S', 'S', 'S'),
('1104', 3, 'Estado Carpetas', 'page.png', 'lista_mensajes.php', 'S', 'S', 'S'),
('3105', 3, 'Ver Informe Legal', 'page.png', 'ver_informe_legal.php', 'S', 'S', 'S'),
('3106', 3, 'Informe Legal (au)', 'page.png', 'informe_legal.php', 'S', 'S', 'S'),
('3107', 3, 'Informe Legal x Aprobar', 'page.png', 'informe_legal_aprob.php', 'S', 'S', 'S'),
('3108', 3, 'Excepciones', 'page.png', 'excepciones.php', 'S', 'S', 'S'),
('3109', 3, '---', 'page.png', '', 'S', 'S', 'S'),
('4000', 1, 'REPORTES', 'tools.png', '', 'S', 'S', 'S'),
('4100', 2, 'OPCIONES', 'report.png', '', 'S', 'S', 'S'),
('4101', 3, 'REPORTES Ar', 'page.png', '', 'S', 'S', 'S'),
('4102', 3, 'REPORTES Au', 'page.png', '', 'S', 'S', 'S'),
('4103', 3, 'REPORTES Co', 'page.png', '', 'S', 'S', 'S'),
('5000', 1, 'ADMINISTRACION', 'tools.png', '', 'S', 'S', 'S'),
('5100', 2, 'OPCIONES', 'report.png', '', 'S', 'S', 'S'),
('5101', 3, 'USUARIOS ', 'page.png', 'adm/usuarios.php ', 'S', 'S', 'S'),
('5102', 3, 'OFICINAS ', 'page.png', 'adm/oficinas.php ', 'S', 'S', 'S'),
('5103', 3, 'RESPONSABLES ', 'page.png', 'adm/responsables.php', 'S', 'S', 'S'),
('5104', 3, 'ASESORES ', 'page.png', 'adm/asesores.php ', 'S', 'S', 'S'),
('5105', 3, 'NOTARIOS ', 'page.png', 'adm/notarios.php ', 'S', 'S', 'S'),
('5106', 3, 'TRAMITADORES ', 'page.png', 'adm/tramitadores.php', 'S', 'S', 'S'),
('5107', 3, 'ENTIDADES ', 'page.png', 'adm/entidades.php ', 'S', 'S', 'S'),
('5108', 3, 'TIPOS DOCUMENTOS ', 'page.png', 'adm/tipos_documentos.php', 'S', 'S', 'S'),
('5109', 3, 'GRUPOS DOCUMENTOS ', 'page.png', 'adm/grupos_documentos.php', 'S', 'S', 'S'),
('5110', 3, 'DOCUMENTOS ', 'page.png', 'adm/documentos.php ', 'S', 'S', 'S'),
('5111', 3, 'TIPOS CARPETAS ', 'page.png', 'adm/tipos_carpetas.php', 'S', 'S', 'S'),
('5112', 3, 'TIPOS BIEN ', 'page.png', 'adm/tipos_bien.php ', 'S', 'S', 'S'),
('5113', 3, 'TIPOS IDENTIFICACION ', 'page.png', 'adm/tipos_identificacion.php', 'S', 'S', 'S'),
('5114', 3, 'OPCIONES DEL MENU ', 'page.png', 'admmenu.php', 'S', 'S', 'S'),
('5115', 3, 'PERFILES', 'page.png', 'admperfil.php', 'S', 'S', 'S'),
('3101', 3, 'Recepción documentos', 'page.png', 'ver_informe_legal.php&adicionar', 'S', 'S', 'S'),
('5116', 3, 'RECINTO CUSTODIA', 'page.png', 'adm/almacenes.php', 'S', 'S', 'S'),
('5117', 3, 'PERSONAS', 'page.png', 'adm/personas.php', 'S', 'S', 'S'),
('1103', 3, 'Adicionar Persona', 'page.png', 'personas.php', 'S', 'S', 'S') ";
$c=ejecutar($sql);
?>