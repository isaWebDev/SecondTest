<?php
/*
Plugin Name: Widget Lancer De Des 
Plugin URI: http://localhost/wordpress/plugin
Description:  Lancer des dés
Version: 1.0.0
Author: Isa
*/

//lorqu'on lance l'initialisation des widgets dans wordpress on lance la fonction widget_lancer_init de ce plugin
add_action('widgets_init', 'widget_lancer_init');
//hook is fired once WP, all plugins, and the theme are fully loaded and instantiated.
add_action('wp_loaded','boot_session');

function widget_lancer_init(){
	//le but de cette fonction est de créer un widget pr ça on fait un register_widget ds cette fct
	register_widget('lancer_de_des_widget');
}

function boot_session() {
  session_start();
}

class lancer_de_des_widget extends WP_Widget{
	//description du widget affichée dans l'interface widget de wp 
	function __construct() {
		parent::__construct(
			'isa_Lancer_de_des',
			'Jets de dés',
			['description' => 'Lancer des dés de 2, 4, 6, 8, 10, 12, 20, 100 faces']
		);
	}

	/**
		 * Runs the widget code.
	*/
	function widget($args, $instance){
	//on déclare $wpdb en globale pour exécuter le SQL qui retourne un objet PHP 
	global $wpdb;

	//si les tables users et jets n'existent pas, on les crée
	$create_tableUsers_query = "
	            CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}widgetDice_users` (
	              `id` INTEGER unsigned NOT NULL default '0',
	              `name` VARCHAR(255) NOT NULL,
	              `pwd` VARCHAR(255) NOT NULL
	            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
	    ";
	$create_tableJets_query = "
	            CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}widgetDice_jets` (
	              `id` INTEGER unsigned NOT NULL default '0',
	              `idUser` INTEGER unsigned NOT NULL default '0',
	              `DateJet` DATETIME NOT NULL,
	              `StringJet` VARCHAR(255) NOT NULL
	            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
	    ";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $create_tableUsers_query );
    dbDelta( $create_tableJets_query );

	$myrows = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}widgetDice_users WHERE name = 'moi' AND pwd = 'moi'", OBJECT );
	$user_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->users" );
	//on récupère l'historique des 500 derniers lancers dans la base
	$monhistorique = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}widgetDice_jets LEFT JOIN {$wpdb->prefix}widgetDice_users ON {$wpdb->prefix}widgetDice_jets.`idUser` =  {$wpdb->prefix}widgetDice_users.`id`  ORDER BY DateJet DESC LIMIT 500", OBJECT );

	//on récupère les variables
	extract($args);
	//on récupère ce qu'on doit afficher avant le widget
	echo $before_widget;
	//on met le contenu du widget
	//un titre avec les var after et before pour que wp mette autoatiquement les balises et qu'on n'ait rien à règler
	//comme ça on s'adapte au thème et le widget s'affiche correctement
	//on récupère dynamiquement le titre tapé par l'utilisateur avec $instance["titre"]
	echo $before_title.$instance["titre"].$after_title;
	?>
		<!-- on récupère le nom du form -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<!-- <p> <strong><?php echo $instance["nom"]; ?></strong> </p> -->
		<!-- <p> <strong>Ajouter des dés</strong> </p> -->
		<!-- <div class="alert alert-info" role="alert">

		<?php
		if(isset($_POST['loginUser'])){
			//var_dump($_POST['loginUser']);
		}
		else
		{
			//echo "Vous devez vous loguer pour lancer les dés".PHP_EOL.PHP_EOL;
		}
		//if (!isset($_GET['loginUser'])){
			//echo " - Utilisateur non logué";
			echo " Version sans blocage du login activé.";
			//le post retourné par le form est vide : je n'ai pas pu faire la requête pour vérifier le login en base
			//le select est prêt dans la variable $myrows
			?>
			<!-- Login Form -->
		    <form  method="POST" action="" name="formLogin">
		    <!-- <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post"> -->
		    	<div class="container">
		    		<div class="row">
				      <input type="text" id="loginUser" class="fadeIn second" name="login" placeholder="login">
				    </div>
				    <div class="row">
				      <input type="text" id="password" class="fadeIn third" name="login" placeholder="password">
				    </div>
				    <div class="row">
				      <input type="submit" class="fadeIn fourth" value="Log In">
				    </div>
				   <div class="row">
				      <input type="hidden" name="action" value="contact_form">
				    </div>
				</div>
		    </form>
		    <?php
		//}
		//else{
			//echo "Utilisateur logué";
		//$this->check_password( "", "" );
		?>
	
		<form method="post" action="" name="form1">
			<div class="container">    
	             <h4 class="ZoneDes" id="ZoneDes" margin="0">Sélectionnez les dés :</h4>
	             <div class="row">
		            <div class="col-6">
		              <h4>D2</h4>
		              <input type="text" id="d2" value="1" size="3" maxlength="3">
		            </div>
		             <div class="col-6">
		              <h4>D4</h4>
		              <input type="text" id="d4" value="1" size="3" maxlength="3">
	            	</div>
	        	</div>
	        	<div class="row">
		            <div class="col-6">
		              <h4>D6</h4>
		              <input type="text" id="d6" value="1" size="3" maxlength="3">
		            </div>
		             <div class="col-6">
		              <h4>D8</h4>
		              <input type="text" id="d8" value="1" size="3" maxlength="3">
	            	</div>
	        	</div>
	        	 <div class="row">
		            <div class="col-6">
		              <h4>D10</h4>
		              <input type="text" id="d10" value="1" size="3" maxlength="3">
		            </div>
		             <div class="col-6">
		              <h4>D12</h4>
		              <input type="text" id="d12" value="1" size="3" maxlength="3">
	            	</div>
	        	</div>
	        	 <div class="row">
		            <div class="col-6">
		              <h4>D20</h4>
		              <input type="text" id="d20" value="1" size="3" maxlength="3">
		            </div>
		             <div class="col-6">
		              <h4>D100</h4>
		             <input type="text" id="d100" value="1" size="3" maxlength="3">
	            	</div>
	        	</div>
	        	 <div class="row">
		            <div class="col-6">
		              <h4>Modifier</h4>
		              <input type="text" id="dmodifier" value="1" size="3" maxlength="3">
		            </div>
	        	</div>
	        	<br /><br />
	        	<a href="javascript:void(null)" onclick="getResult()"><img src="https://www.wizards.com/dnd/dice/images/roll1.jpg" alt="Roll dices" name="rd" border="0"></a><br /><br />
	        	<textarea name="runningtotal" cols="25" rows="10"></textarea>
     		</div>
		</form>
    <script>
    function getResult() {

	    var jetDesArr = [];
	    d2rolls = parseInt(document.form1.d2.value);  //Nombre de dés de 2 entré exemple 2
	    d4rolls = parseInt(document.form1.d4.value);  //Nombre de dés de 4 entré exemple 2
	    d6rolls = parseInt(document.form1.d6.value);  //Nombre de dés de 6 entré exemple 2
	    d8rolls = parseInt(document.form1.d8.value);  //Nombre de dés de 8 entré exemple 2
	    d10rolls = parseInt(document.form1.d10.value);  //Nombre de dés de 10 entré exemple 2
	    d12rolls = parseInt(document.form1.d12.value);  //Nombre de dés de 12 entré exemple 2
	    d20rolls = parseInt(document.form1.d20.value);  //Nombre de dés de 20 entré exemple 2
	    d100rolls = parseInt(document.form1.d100.value);  //Nombre de dés de 100 entré exemple 2
	    dmodifier = parseInt(document.form1.dmodifier.value);  //Nombre de dés de 100 entré exemple 2

	    dtotal = "";
	    d2res = 0;
	    d4res = 0;
	    d6res = 0;
	    d8res = 0;
	    d10res = 0;
	    d12res = 0;
	    d20res = 0;
	    d100res = 0;
	    rtotal = document.form1.runningtotal.value; 
  
  	  	 //Nombre de dés de 4 entré exemple 2
		dtotal = dtotal + "Roll(" + d2rolls.toString() + "d2" + ")" + ": "; 
		for (r=0; r<d2rolls; r++) {
		   //on génère autant de dés que demandé
	       var d2 = Math.floor((Math.random() * 2) + 1);	
	       d2res = d2res + d2;	
	       dtotal = dtotal + d2.toString() + (r == (d2rolls - 1) ? "" : ",");
		   //=> on prépare l'affichage dans l'historique
	  	}
	  	dtotal = dtotal + "\n"; 
		dtotal = dtotal + "Roll(" + d4rolls.toString() + "d4" + ")" + ": "; 
		for (r=0; r<d4rolls; r++) {
		   //on génère autant de dés que demandé
	       var d4 = Math.floor((Math.random() * 4) + 1);	
	       d4res = d4res + d4;	
		   dtotal = dtotal + d4.toString() + (r == (d4rolls - 1) ? "" : ",");	
	  	}
	  	dtotal = dtotal + "\n"; 
	  	dtotal = dtotal + "Roll(" + d6rolls.toString() + "d62" + ")" + ": "; 
	  	for (r=0; r<d6rolls; r++) {
		   //on génère autant de dés que demandé
	       var d6 = Math.floor((Math.random() * 6) + 1);	
	       d6res = d6res + d6;	
		   dtotal = dtotal + d6.toString() + (r == (d6rolls - 1) ? "" : ",");	
	  	}
	  	dtotal = dtotal + "\n"; 
	  	dtotal = dtotal + "Roll(" + d8rolls.toString() + "d8" + ")" + ": "; 
	  	for (r=0; r<d8rolls; r++) {
		   //on génère autant de dés que demandé
	       var d8 = Math.floor((Math.random() * 8) + 1);	
	       d8res = d8res + d8;	
		   dtotal = dtotal + d8.toString() + (r == (d8rolls - 1) ? "" : ",");	
	  	}
	  	dtotal = dtotal + "\n"; 
	  	dtotal = dtotal + "Roll(" + d10rolls.toString() + "d10" + ")" + ": "; 
	  	for (r=0; r<d10rolls; r++) {
		   //on génère autant de dés que demandé
	       var d10 = Math.floor((Math.random() * 10) + 1);	
	       d10res = d10res + d10;	
		   dtotal = dtotal + d10.toString() + (r == (d10rolls - 1) ? "" : ",");	
	  	}
	  	dtotal = dtotal + "\n"; 
	  	dtotal = dtotal + "Roll(" + d12rolls.toString() + "d12" + ")" + ": "; 
	  	for (r=0; r<d12rolls; r++) {
		   //on génère autant de dés que demandé
	       var d12 = Math.floor((Math.random() * 12) + 1);	
	       d12res = d12res + d12;	
		   dtotal = dtotal + d12.toString() + (r == (d12rolls - 1) ? "" : ",");	
	  	}
	  	dtotal = dtotal + "\n"; 
	  	dtotal = dtotal + "Roll(" + d20rolls.toString() + "d20" + ")" + ": "; 
	  	for (r=0; r<d20rolls; r++) {
		   //on génère autant de dés que demandé
	       var d20 = Math.floor((Math.random() * 20) + 1);	
	       d20res = d20res + d20;	
		   dtotal = dtotal + d20.toString() + (r == (d20rolls - 1) ? "" : ",");	
	  	}
	  	dtotal = dtotal + "\n"; 
	  	dtotal = dtotal + "Roll(" + d100rolls.toString() + "d100" + ")" + ": "; 
	  	for (r=0; r<d100rolls; r++) {
		   //on génère autant de dés que demandé
	       var d100 = Math.floor((Math.random() * 100) + 1);	
	       d100res = d100res + d100;	
		   dtotal = dtotal + d100.toString() + (r == (d100rolls - 1) ? "" : ",");	
	  	}
	  	//on ajoute le modificateur
	  	dtotal = dtotal + "\n" + "Modifier : " + dmodifier.toString();
	  	var dtotalres = d2res + d4res + d6res + d8res + d10res + d12res + d20res + d100res + dmodifier;
		dtotal = dtotal + "\n" + "Total:" + dtotalres;	
	   rtotal = dtotal + "\n\n" + rtotal;  
	   document.form1.runningtotal.value = rtotal;	
	}
	</script> 

	<?php
	//} //fin else le user est logué - fonctionnalité désactivée
	?>
	<br /><br />
	<!-- HISTORIQUE récupéré dans la base de données : il reste à faire l'enregistrement-->
	<div class="container">    
        <h4 class="arrayHistoClass" id="arrayHisto" margin="0">Historique dés :</h4>
        <div class="row"> 
			<div class="col-6">User</div>
			<div class="col-6">Jet</div>
        </div>
  	<div class="row">          
	<?php
	    foreach ($monhistorique as $details) {
	?><div class="col-6"><?php
	      echo $details->name;
	?></div><div class="col-6"><?php
	      echo $details->StringJet;
	?></div><?php
	}  
	?>
	</div>
	</div>
	<br /><br /><br />
	<?php
	//on récupère ce qu'on doit afficher après le widget
	echo $after_widget;
		
	}

	function update($new, $old){
		//pour la sauvegarde 
		//param1 et param2
		//new permet de récupérer les valeurs postées dans le form, et old celles qui étaient avant
		//ici ne sert pas
		//à la fin de cette fonction on doit retourner un tableau qui modifie les valeurs
		//insère la variable titre récupérée ds le form get_field_name('titre') et la sauvegarderait
		//$d["titre"]="test";
		//return $d;
		//au lieu des 2 lignes précédentes on fait : on pourrait aussi faire des traitements avant
		return $new;
	}

	function form($instance){
		$defaut=array(
			"nom"=>"Widget Lancer de Dés",
			"titre"=>"Widget Lancer de Dés",
			"faces"=>"6"
		);
		$instance = wp_parse_args($instance, $defaut);
		?>
		<p>
			<label for="<?php echo $this->get_field_id("titre"); ?>" >Titre : </label>
			<input value="<?php echo $instance["titre"]; ?>" name="<?php echo $this->get_field_name("titre"); ?>" id="<?php echo $this->get_field_id('titre'); ?>" type="text"/>
		</p>
		<?php
	}
}
