-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost

-- Generato il: Mar 03, 2015 alle 09:45
-- Versione del server: 5.5.37
-- Versione PHP: 5.3.10-1ubuntu3.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `nirbuydb`
--

-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `language`, `translation`) VALUES
(1, 'en', 'Add Product'),
(2, 'en', 'Add Category'),
(1, 'es', 'Añades producto'),
(1, 'it', 'Aggiungi Prodotto'),
(7, 'es', 'Subir o actualizar catálogo vía CSV'),
(110, 'it', 'Solo un messaggio'),
(110, 'es', 'testo en espanol'),
(111, 'it', 'Scegli l''area o la città'),
(111, 'es', 'Eliges ciudad or region'),
(5, 'it', 'Crea prima un catalogo'),
(5, 'es', 'Por favor crea un catalogo antes'),
(6, 'it', 'Carica il tuo catalogo (file CSV)'),
(6, 'es', 'Carga tus catalogo (CSV)'),
(7, 'it', 'Carica o aggiorna il tuo catalogo (file csv)'),
(21, 'it', 'Come creare un file CSV con Microsoft Excel'),
(21, 'es', 'Cómo crear un archivo CSV desde Microsoft Excel'),
(8, 'it', 'Aggiungi e aggiorna il tuo catalogo o sostituiscilo con uno totalmente nuovo'),
(8, 'es', 'Anades y actualiza tus catalogo or sustituirlo totalmente por uno nuevo'),
(9, 'it', 'Torna alla pagina del tuo catalogo'),
(9, 'es', 'Vuelves a la pagine de el catalogo'),
(10, 'it', 'Errore. Formato non supportato. Riprova con un file CSV'),
(10, 'es', 'Error: formato incorrecto! Sólo archivos CSV se permiten!'),
(11, 'it', 'Assegnazione campi'),
(11, 'es', 'Concides los campos'),
(12, 'it', 'Fai combaciare i tuoi campi ai nostri'),
(12, 'es', 'Concides tus campos con lo de nosotros'),
(2, 'it', 'Aggiungi categoria'),
(2, 'es', 'Añades una categoria'),
(3, 'it', 'Carica CSV'),
(3, 'es', 'Carga CSV'),
(4, 'it', 'Aggiungi parole chiave'),
(4, 'es', 'Añades Tags'),
(13, 'it', 'I titoli delle colonne sono vuote'),
(13, 'es', 'encabezado vacía'),
(14, 'it', 'Il nome per la categoria è troppo lungo'),
(14, 'es', 'Nombre para la categoría es demasiado largo!'),
(15, 'it', 'Carica risultati'),
(15, 'es', 'Carga resultados'),
(16, 'it', 'Se pochi prodotti non sono stati caricati puoi aggiungerliu manualmente'),
(16, 'es', 'Si algunos productos no pudieron ser importados, siempre los puede añadir de forma manual'),
(17, 'it', 'Carica o aggiorna il tuo catalogo con un file CSV'),
(17, 'es', 'Subes o actualizas tus catálogo a través de file CSV'),
(18, 'it', 'Aggiungi e aggiorna il tuo catalogo o sostituiscilo interamente con uno nuovo'),
(18, 'es', 'Añades (y actualizas) tu catálogo o sustituirlo totalmente con uno nuevo.'),
(19, 'it', 'Torna alla pagina del catalogo'),
(19, 'es', 'Vuelves a la pagina de catalogo'),
(20, 'it', 'Devi avere in ogni riga del tuo file CSV un nome del prodotto e la sua relativa categoria<br/>\r\nIl nome del prodotto può avere un massimo di 80 caratteri oltre cui nella pagina dei risultati verrà mostrato "...".<br/>\r\n    Una volta che i tuoi prodotti sono stati caricati con successo li troverai nella pagina del catalogo'),
(20, 'es', 'Usted debe tener en cada fila de nombre de su producto archivo CSV y categoría de producto. <br/>\r\n     A nombre del producto puede ser como máximo de 80 caracteres de longitud. Si tiene cualquier persona ya:. En la página de resultados después irà a aparecer "..." <br/>\r\n     Una vez que sus registros se cargan con éxito los encontrarás en tu página de catálogo.'),
(22, 'it', 'Carica'),
(22, 'es', 'Carga'),
(23, 'it', 'Stai caricando dei prodotti per la categoria'),
(23, 'es', 'Usted está cargando los productos de la categoría'),
(24, 'it', 'Tutti i prodotti, per essere caricati, devono avere la stessa categoria di quella in cui la stai importando. Nel caso in cui non vi sia una colonna relativa alla categoria, tutti i prodotti saranno importati.'),
(24, 'es', 'Si hay una columna de categoría, se importarán sólo los que responden a la categoría. De lo contrario, todas las filas se importarán a la categoría'),
(25, 'it', 'Carica un CSV'),
(25, 'es', 'Carga un file CSV'),
(26, 'it', 'Carica un altro CSV'),
(26, 'es', 'Sube un archivo CSV diferente'),
(27, 'it', 'Utilizza l''anteprima per scegliere il giusto separatore di carattere'),
(27, 'es', 'Utilice vista previa para elegir el carácter separador derecho'),
(28, 'it', 'Automatico'),
(28, 'es', 'Detección automática'),
(29, 'it', 'Tab'),
(29, 'es', 'Tab'),
(30, 'it', 'Virgola'),
(30, 'es', 'Coma'),
(31, 'it', 'Punto e virgola'),
(31, 'es', 'Punto y coma'),
(32, 'it', 'Altro'),
(32, 'es', 'Otro'),
(33, 'it', 'Carica l''anteprima'),
(33, 'es', 'Actualización CSV vista previa'),
(34, 'it', 'Area coinvolta'),
(34, 'es', 'Área de efecto'),
(35, 'it', 'Categoria'),
(35, 'es', 'Categoría'),
(36, 'it', 'Tutti (necessario il campo della categoria)'),
(36, 'es', 'Todos (requiere campo de categoría)'),
(37, 'it', 'Cataloghi'),
(37, 'es', 'Catalogos'),
(38, 'it', 'Gestione cataloghi'),
(38, 'es', 'gestionar catálogos'),
(39, 'it', 'Tutti'),
(39, 'es', 'Todos'),
(40, 'it', 'Anteprima'),
(40, 'es', 'Vista anticipada'),
(41, 'it', 'Anteprima completa'),
(41, 'es', 'Vista previa completa de CSV'),
(42, 'it', 'AGGIUNGI'),
(42, 'es', 'AÑADIR'),
(43, 'it', 'Mantiene gli attuali prodotti e ne aggiunge nuovi.<br/> Ne aggiorna i dettagli se riscontra stesso nome e categoria.'),
(43, 'es', 'Mantener los registros antiguos y añadir otras nuevas.<br/> Actualizar registros si la categoría y el nombre son los mismos.'),
(44, 'it', 'SOSTITUISCI'),
(44, 'es', 'SUSTITUIR'),
(45, 'it', 'Cancella i vecchi prodotti e li sostituisce interamente con i nuovi.'),
(45, 'es', 'Cancelar los registros antiguos y reemplazarlos con los nuevos.'),
(46, 'it', 'Prego prima scegliere un file e premere -Carica l''anteprima del csv-'),
(46, 'es', 'Por favor, elija antes un archivo y pulse -Actualización CSV Vista previa-'),
(47, 'it', 'Prima seleziona un file '),
(47, 'es', 'Por favor, sube un archivo primero!'),
(48, 'it', 'Seleziona un catalogo'),
(48, 'es', 'No hay catálogos seleccionados!'),
(49, 'it', 'Stai per aggiungere e aggiornare i prodotti già esistenti'),
(49, 'es', 'Esto agregará y actualizar los productos existentes'),
(50, 'it', 'di tutte le categoria (il tuo CSV deve avere una colonna per la categoria dei prodotti)'),
(50, 'es', 'en todas las categorías (CSV debe tener un campo de categoría)'),
(51, 'it', 'nella categoria'),
(51, 'es', 'en la categoria'),
(52, 'it', 'per i cataloghi'),
(52, 'es', 'para su catálogos '),
(53, 'it', 'Conferma?'),
(53, 'es', 'Avanzar?'),
(54, 'it', 'Sta per cancellare i prodotti esistenti e caricare quelli del file'),
(54, 'es', 'Esto eliminará los productos anteriores y cargar los de el archivo'),
(55, 'it', 'Controllo del CSV'),
(55, 'es', 'Verificación de el CSV\r\n	\r\n'),
(56, 'it', 'Chiudi'),
(56, 'es', 'Cerrar'),
(57, 'it', 'Importazione è avvenuta con successo'),
(57, 'es', 'importado correctamente'),
(58, 'it', 'sono stati aggiornati '),
(58, 'es', 'Se actualizó correctamente'),
(59, 'it', 'Alcune righe del file CSV mancano di nome e/o categoria del prodotto.<br/> Puoi:'),
(59, 'es', 'Algunas de las filas de su archivo CSV nombre del producto se pierda y / o nombre del producto y / o categoría de productos <br/> Usted puede:'),
(60, 'it', 'aggiusta il file CSV sul tuo computer e importalo di nuovo'),
(60, 'es', 'arreglar el CSV en su ordenador y subirlo de nuevo'),
(61, 'it', 'clicca qui per caricare il nuovo file'),
(61, 'es', 'haga clic aquí para cargar nuevo CSV '),
(62, 'it', 'oppure utilizza la nostra sezione'),
(62, 'es', 'o arreglarlos con sección de abajo'),
(63, 'it', 'Visualizza il file importato'),
(63, 'es', 'Compruebe CSV originales'),
(64, 'it', 'Nessuno'),
(64, 'es', 'Ninguno'),
(65, 'it', 'Nome del prodotto'),
(65, 'es', 'Nombre de producto'),
(66, 'it', 'Categoria'),
(66, 'es', 'Categoria'),
(67, 'it', 'Catalogo'),
(67, 'es', 'Catalogo'),
(68, 'it', 'Prezzo'),
(68, 'es', 'Precio'),
(69, 'it', 'Link'),
(69, 'es', 'Link'),
(70, 'it', 'altro'),
(70, 'es', 'otro'),
(71, 'it', 'Scegli una categoria esistente'),
(71, 'es', 'seleccionar categoría existente'),
(72, 'it', 'o inserici una nuova categoria'),
(72, 'es', 'o escriba una nueva categoría'),
(73, 'it', 'Aggiorna'),
(73, 'es', 'Actualizar'),
(74, 'it', 'Contatti'),
(74, 'es', 'Contactos'),
(75, 'it', 'Termini e condizioni'),
(75, 'es', 'Términos y condiciones'),
(76, 'it', 'Privacy'),
(76, 'es', 'Privacy'),
(77, 'it', 'Avviso di importazione'),
(77, 'es', 'Advertencias de importación'),
(78, 'it', 'I seguenti prodotti non sono stati importati'),
(78, 'es', 'Productos siguientes no han sido importados'),
(79, 'it', 'Riga'),
(79, 'es', 'Fila'),
(80, 'it', 'Dev''esserci un nome per questo prodotto'),
(80, 'es', 'tiene que haber un nombre vinculado con este producto'),
(81, 'it', 'Dev''esserci un catalogo assegnato a questo prodotto'),
(81, 'es', 'tiene que haber un catálogo vinculado con este producto'),
(82, 'it', 'Dev''esserci una categoria assegnata a questo prodotto'),
(82, 'es', 'tiene que haber un categoria vinculada con este producto'),
(83, 'it', 'Per aggiustare questi errori puoi'),
(83, 'es', 'Para solucionar estos errores, se puede'),
(84, 'it', 'Aggiustare il file sul tuo computer e caricarlo nuovamente cliccando'),
(84, 'es', 'hacerlo en su propio ordenador y luego subir el archivo CSV fijo de nuevo haciendo clic'),
(85, 'it', 'qui'),
(85, 'es', 'aquì'),
(86, 'it', 'o può andare alla relativa sezione cliccando'),
(86, 'es', 'o puede solucionarlos usando nuestra área de fijación CSV: haga clic en'),
(87, 'it', 'Chiudi'),
(87, 'es', 'Serrar'),
(88, 'it', 'Importazione completata'),
(88, 'es', 'importación completa'),
(89, 'it', 'Il tuo catalogo è stato caricato con successo'),
(89, 'es', 'Su catálogo se cargò con éxito'),
(90, 'it', 'Importazione riuscita'),
(90, 'es', 'importado correctamente'),
(91, 'it', 'sono stati aggiornati'),
(91, 'es', 'Se actualizó correctamente'),
(92, 'it', 'non sono stati importati'),
(92, 'es', 'no pudieron ser importados'),
(93, 'it', 'più informazioni'),
(93, 'es', 'más información'),
(94, 'it', 'Vedi il catalogo importato'),
(94, 'es', 'Ver catálogo importado'),
(95, 'it', 'Torna al gestore catalogo'),
(95, 'es', 'Vuelve a tu catálogo'),
(96, 'it', 'Campo ripetuto'),
(96, 'es', 'Campo repetido'),
(97, 'it', 'Assegna i titoli delle tue colonne.<br/>Nel caso tu abbia sottocategorie aggiungile come altro.<br/>\r\n        Tags: parole chiave usate per i risultati di ricerca dell''utente'),
(97, 'es', 'Coinciden con sus campos de archivos con nuestros campos. <br/> En caso de que tenga subcategorías ellos entran como otros específicos. <br/>\r\n         Tags: palabras escondidas que harán su registro aparecen en los resultados.'),
(98, 'it', 'Assegna ai nosti campi'),
(98, 'es', 'Coincidir con nuestros campos'),
(99, 'it', 'i titoli delle colonne del tuo file'),
(99, 'es', 'Con sus campos de archivo'),
(100, 'it', 'Vuoto'),
(100, 'es', 'Vacio'),
(101, 'it', 'Catalogo'),
(101, 'es', 'Catalogo'),
(102, 'it', 'Nome'),
(102, 'es', 'Nombre'),
(103, 'it', 'Campi aggiuntivi'),
(103, 'es', 'Campos opcionales'),
(104, 'it', 'Facoltativo'),
(104, 'es', 'Facultativo'),
(105, 'it', 'Altro'),
(105, 'es', 'Otro'),
(106, 'it', 'Tag'),
(106, 'es', 'Tag'),
(107, 'it', 'e'),
(107, 'es', 'y'),
(108, 'it', 'Importazione del file'),
(108, 'es', 'Importacion desde CSV'),
(109, 'it', 'Prego attendi. Questo potrebbe necessitare di minuti per grandi file.'),
(109, 'es', 'Por favor espera. Esto puede tardar varios minutos para archivos largos.'),
(112, 'it', 'Il tuo CSV è stato caricato. Puoi vedere i risultati <a href="{url}">here</a>.'),
(112, 'es', 'Su csv ha sido procesado. Usted puede ver los resultados <a href="{url}">here</a>.'),
(113, 'it', 'Stiamo caricando il tuo CSV. Dato che il tuo browser non supporta una barra di progressione ti manderemo una mail una volta completato.'),
(113, 'es', 'Su CSV se importa actualmente. A medida que su navegador no es compatible con una barra de progreso, los resultados serán enviados a usted en un e-mail cuando haya finalizado el proceso.'),
(114, 'it', 'Caricamento file...'),
(114, 'es', 'Cargamento de archivo...'),
(115, 'it', 'Arte'),
(115, 'es', 'Arte'),
(116, 'it', 'Musica'),
(116, 'es', 'Música'),
(117, 'it', 'Fotografia\r\n'),
(122, 'it', 'Salute'),
(122, 'es', 'Salud'),
(123, 'it', 'Altro'),
(123, 'es', 'Otro'),
(124, 'it', 'Tecnologia'),
(124, 'es', 'Tecnología'),
(117, 'es', 'Fotografía\r\n'),
(119, 'it', 'Famiglia'),
(119, 'es', 'Familia'),
(120, 'it', 'Fotografia'),
(120, 'es', 'Fotografía'),
(121, 'it', 'Bellezza'),
(121, 'es', 'Belleza'),
(118, 'it', 'procedere'),
(118, 'es', 'avanzar'),
(128, 'it', 'Prova Ita'),
(128, 'es', 'Prova esp'),
(130, 'it', 'Preferito'),
(130, 'es', 'Favorito'),
(131, 'it', 'Area business'),
(131, 'es', 'Area business'),
(132, 'it', 'Lista dei negozi '),
(132, 'es', 'Lista de las tiendas'),
(133, 'it', 'Area Business'),
(133, 'es', 'Area business'),
(134, 'it', 'area business'),
(134, 'es', 'area business'),
(135, 'it', 'area business'),
(135, 'es', 'area business'),
(136, 'it', 'Area business'),
(136, 'es', 'Area business'),
(136, 'en', 'Business Account'),
(137, 'it', 'Profilo'),
(137, 'es', 'Profilo'),
(137, 'en', ''),
(138, 'it', 'I miei preferiti'),
(138, 'es', 'Mi favoritos'),
(138, 'en', 'My favorites'),
(139, 'it', 'Cronologia'),
(139, 'es', 'Cronologia'),
(139, 'en', 'History'),
(140, 'it', 'Esci'),
(140, 'es', 'Sales'),
(140, 'en', 'Logout'),
(141, 'it', 'Catalogo '),
(141, 'es', 'Catalogo '),
(141, 'en', 'Catalogo'),
(142, 'it', 'Sedi'),
(142, 'es', 'Areas'),
(142, 'en', 'Locations'),
(143, 'it', ''),
(143, 'es', ''),
(143, 'en', ''),
(144, 'it', 'Accedi'),
(144, 'es', 'Registrarse'),
(144, 'en', ''),
(145, 'it', 'Cronologia'),
(145, 'es', 'Cronologia'),
(145, 'en', ''),
(146, 'it', 'Categorie'),
(146, 'es', 'Categorias'),
(146, 'en', 'Prova per inglese'),
(147, 'it', 'I miei preferiti'),
(147, 'es', 'Mis favoritos'),
(147, 'en', 'My favorites'),
(148, 'es', 'Seleciona todos'),
(148, 'it', 'Seleziona tutti'),
(148, 'en', ''),
(149, 'it', 'Cancella preferito'),
(149, 'es', 'Cancela favorito'),
(149, 'en', ''),
(150, 'it', 'Nome attività'),
(150, 'es', 'Nombre tienda'),
(150, 'en', ''),
(151, 'it', 'Sede'),
(151, 'es', 'Area'),
(151, 'en', 'Place'),
(152, 'it', 'Cronologia'),
(152, 'es', 'Historico'),
(152, 'en', 'Chonology'),
(153, 'it', 'Oggi'),
(153, 'es', 'Hoy'),
(153, 'en', ''),
(154, 'it', 'Ieri'),
(154, 'es', 'Ayer'),
(154, 'en', ''),
(155, 'it', 'Scorsa settimana'),
(155, 'es', 'Otra semana'),
(155, 'en', ''),
(156, 'it', 'Ultimi 30 giorni'),
(156, 'es', 'Ultimos 30 dias'),
(156, 'en', ''),
(157, 'it', 'Cambia password'),
(157, 'es', 'Cambiar password'),
(157, 'en', ''),
(158, 'it', 'Password attuale'),
(158, 'es', 'Password corrente'),
(158, 'en', ''),
(159, 'it', 'Nuova password'),
(159, 'es', 'Nueva password'),
(159, 'en', ''),
(160, 'it', 'Conferma password'),
(160, 'es', 'Conferma password'),
(160, 'en', ''),
(161, 'it', 'Italia'),
(161, 'es', 'Italia'),
(161, 'en', 'Italy'),
(162, 'it', 'Vai al catalogo'),
(162, 'es', 'Ir a el catalogo'),
(162, 'en', ''),
(163, 'it', 'aggiungi un nuovo prodotto o servizio (selezionane prima la categoria)'),
(163, 'es', 'anades uno (selecionar antes la categoria)'),
(163, 'en', ''),
(164, 'it', 'Aggiungi parole chiave'),
(164, 'es', 'Anades palabras llave'),
(164, 'en', 'Add keywords'),
(165, 'it', 'Seleziona prima il nome dell''azienda'),
(165, 'es', 'Selecionar antes el nombre de el actividad'),
(165, 'en', ''),
(166, 'it', 'Lo vuoi cancellare dalla tua lista di preferiti'),
(166, 'es', 'Quieres cancelarlo desde la lista de tu favoritos?'),
(166, 'en', 'Do you want to cancel it from your favourites list?"'),
(167, 'it', 'Questa sede è già nella lista dei tuoi preferiti'),
(167, 'es', 'Esto sito ya sta en tu favoritos'),
(167, 'en', ''),
(168, 'it', 'Questa sede è stata aggiunta ai tuoi preferiti'),
(168, 'es', 'Esto sito es ahora anadido como tu favorito'),
(168, 'en', 'Business is now added in your favourite list.'),
(169, 'it', 'Questa sede è stata rimossa dai tuoi preferiti'),
(169, 'es', 'Esto sito ahora no es tu favorito'),
(169, 'en', 'Business has been removed from your favourite list.'),
(170, 'it', 'Vuoi eliminare questa sede dalla tua lista dei preferiti'),
(170, 'es', 'Quieres eliminar esto sito en tu lista de favoritos'),
(170, 'en', 'Do you want to remove this business location from your favorite list'),
(171, 'it', 'Categoria del''attività'),
(171, 'es', 'Categoria de la tienda'),
(171, 'en', ''),
(172, 'it', 'Sito internet'),
(172, 'es', 'Sito internet'),
(172, 'en', ''),
(173, 'it', 'Decrizione del negozio'),
(173, 'es', 'Description de la tienda'),
(173, 'en', ''),
(174, 'it', 'Indirizzo'),
(174, 'es', 'Direction'),
(174, 'en', ''),
(175, 'it', 'Telefono'),
(175, 'es', 'Telefono'),
(175, 'en', ''),
(176, 'it', 'Lista dei prodotti e/o servizi'),
(176, 'es', 'Lista de los productos y/o servicios'),
(176, 'en', 'Product and/or service list'),
(177, 'it', 'Seleziona la categoria'),
(177, 'es', 'Selectiona la categoria'),
(177, 'en', ''),
(178, 'it', 'Nome dell''attività'),
(178, 'es', 'Nombre de la tienda'),
(178, 'en', ''),
(179, 'it', 'Sede'),
(179, 'es', 'Sito'),
(179, 'en', 'Location'),
(180, 'it', 'Prezzo'),
(180, 'es', 'Precio'),
(180, 'en', ''),
(181, 'it', 'Descrizione'),
(181, 'es', 'Descricion'),
(181, 'en', ''),
(182, 'it', 'Regione o città'),
(182, 'es', 'Region o ciudad'),
(182, 'en', 'Region or city'),
(6, 'en', 'Upload products (csv file)'),
(183, 'it', 'Seleziona una regione o una città'),
(183, 'es', 'Seleciona una region o una ciudad'),
(183, 'en', ''),
(184, 'it', 'Nazione'),
(184, 'es', 'Pais'),
(184, 'en', ''),
(185, 'it', 'Seleziona nazione'),
(185, 'es', 'Seleciona pais'),
(185, 'en', ''),
(186, 'it', 'Regione'),
(186, 'es', 'Region'),
(186, 'en', ''),
(187, 'it', 'Seleziona regione'),
(187, 'es', 'Seleciona region'),
(187, 'en', ''),
(188, 'it', 'Seleziona città o area (facoltativo)'),
(188, 'es', 'Seleciona ciudad o area (facultativo)'),
(188, 'en', ''),
(189, 'it', 'Hai cercato'),
(189, 'es', 'Has buscado'),
(189, 'en', ''),
(190, 'it', 'risultati in'),
(190, 'es', 'resultados en'),
(190, 'en', ''),
(191, 'it', 'cataloghi'),
(191, 'es', 'catalogos'),
(191, 'en', ''),
(192, 'it', 'Categorie di attività'),
(192, 'es', 'Categorias de tiendas'),
(192, 'en', ''),
(193, 'it', 'Solo i miei preferiti'),
(193, 'es', 'Solo mi favoritos'),
(193, 'en', ''),
(194, 'it', 'Tutto'),
(194, 'es', 'Todo'),
(194, 'en', ''),
(195, 'it', 'Servizi'),
(195, 'es', 'Servicios'),
(195, 'en', ''),
(196, 'it', 'Nessun risultato per'),
(196, 'es', 'No hay resultados por'),
(196, 'en', ''),
(197, 'it', 'Usa il box a sinistra per accedere e aggiungere un conto business'),
(197, 'es', ''),
(197, 'en', ''),
(198, 'it', 'Deseleziona se sei su un computer pubblico'),
(198, 'es', ''),
(198, 'en', ''),
(199, 'it', 'Accedi per salvare le tue attività preferite'),
(199, 'es', ''),
(199, 'en', ''),
(200, 'it', 'Clicca su "crea account". Riceverai un email. Clicca sul link per procedere.'),
(200, 'es', ''),
(200, 'en', ''),
(201, 'it', 'Se non lo trovi cerca nella cartella spam'),
(201, 'es', ''),
(201, 'en', ''),
(202, 'it', 'Cliccando sul bottone "Crea account" acconsenti a i nostri termini della  <a href="">privacy</a> e ai nostri <a href="">Termini e condizioni d''uso</a>'),
(202, 'es', ''),
(202, 'en', ''),
(203, 'it', 'Sedi'),
(203, 'es', ''),
(203, 'en', ''),
(204, 'it', 'Seleziona l''area dove si trova la tua attività'),
(204, 'es', ''),
(204, 'en', ''),
(205, 'it', 'Seleziona la regione'),
(205, 'es', ''),
(205, 'en', ''),
(206, 'it', 'Seleziona la città'),
(206, 'es', ''),
(206, 'en', ''),
(207, 'it', 'Seleziona il quartiere (Se presente. Facoltativo).'),
(207, 'es', ''),
(207, 'en', ''),
(208, 'it', 'Crea'),
(208, 'es', ''),
(208, 'en', ''),
(209, 'it', 'Salva'),
(209, 'es', ''),
(209, 'en', ''),
(210, 'it', 'Crea e aggiungi un''altra sede'),
(210, 'es', ''),
(210, 'en', ''),
(211, 'it', 'Salva e aggiungi un''altra sede'),
(211, 'es', ''),
(211, 'en', ''),
(212, 'it', 'Cancella e torna all pagina delle sedi'),
(212, 'es', ''),
(212, 'en', ''),
(213, 'it', 'Salva'),
(213, 'es', ''),
(213, 'en', ''),
(214, 'it', 'Aggiungi una sede della tua attività'),
(214, 'es', ''),
(214, 'en', ''),
(215, 'it', 'Collezionismo e hobby'),
(215, 'es', ''),
(215, 'en', ''),
(216, 'it', 'Motori'),
(216, 'es', ''),
(216, 'en', ''),
(217, 'it', 'Sport'),
(217, 'es', 'Deporte'),
(217, 'en', ''),
(218, 'it', 'Vino, birra o liquori'),
(218, 'es', ''),
(218, 'en', ''),
(219, 'it', 'Casa e giardinaggio'),
(219, 'es', ''),
(219, 'en', ''),
(220, 'it', 'Animali'),
(220, 'es', ''),
(220, 'en', ''),
(221, 'it', 'Abbigliamento, scarpe e accessori'),
(221, 'es', ''),
(221, 'en', ''),
(222, 'it', 'Prodotti e tradizioni locali'),
(222, 'es', ''),
(222, 'en', ''),
(223, 'it', 'Religione'),
(223, 'es', ''),
(223, 'en', ''),
(224, 'it', 'Viaggi e prodotti stranieri'),
(224, 'es', ''),
(224, 'en', ''),
(225, 'it', 'Divertimento'),
(225, 'es', ''),
(225, 'en', ''),
(226, 'it', 'Beni e servizi di lusso'),
(226, 'es', ''),
(226, 'en', ''),
(227, 'it', 'Crea account'),
(227, 'es', ''),
(227, 'en', ''),
(228, 'it', 'Abruzzo'),
(228, 'es', ''),
(228, 'en', ''),
(229, 'it', 'Roma'),
(229, 'es', ''),
(229, 'en', ''),
(230, 'it', 'Milano'),
(230, 'es', 'Milano'),
(230, 'en', ''),
(231, 'it', 'Spagna'),
(231, 'es', 'Espana'),
(231, 'en', ''),
(232, 'it', 'Regno unito'),
(232, 'es', ''),
(232, 'en', ''),
(233, 'it', 'Londra'),
(233, 'es', ''),
(233, 'en', ''),
(234, 'it', 'Stati Uniti'),
(234, 'es', ''),
(234, 'en', ''),
(235, 'it', 'Genova'),
(235, 'es', ''),
(235, 'en', ''),
(236, 'it', 'Firenze'),
(236, 'es', ''),
(236, 'en', ''),
(237, 'it', 'Venezia'),
(237, 'es', ''),
(237, 'en', ''),
(238, 'it', 'Torino'),
(238, 'es', ''),
(238, 'en', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
