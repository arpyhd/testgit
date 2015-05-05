<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/HomepageTopMenu.css" />

<?php
   $baseUrl = Yii::app()->request->baseUrl; 
   $cs = Yii::app()->getClientScript();
   $cs->registerCssFile($baseUrl.'/css/language_dropdown.css');
   $cs->registerScriptFile($baseUrl.'/js/language_dropdown.js');
?>

<?php echo CHtml::form(Yii::app()->createUrl('site/language')); ?>

	<div class="container-top" >
		<a id="logo" href="<?php  echo Yii::app()->createAbsoluteUrl('site/index') ?>"><img src="<?php  echo Yii::app()->request->baseUrl; ?>/images/logo-top.png" alt="Nirbuy" /></a>

	    <div id="langdrop" >
	        <?php echo CHtml::dropDownList('_lang', $currentLang, array(
	            'en_GB' => 'English', 
				'es' => 'Spanish',
				'it' => 'Italian',
				), array(
					'submit' => '',
                	'style'=>'color:#fff;'
				)); 
			?>
	    </div>
	
	<?php echo CHtml::endForm(); ?>

	<dl id="sample-dropdown" class="dropdown lang-dropdown" >
		<dt><a  href="#" ><img class="flag" src="<?php echo Yii::app()->request->baseUrl; ?>/images/gb.png" alt="" /><span class="value">en_us</span></a></dt>
		<dd>
	    	<ul>
		        <li><a  href="#" ><img class="flag" src="<?php echo Yii::app()->request->baseUrl; ?>/images/gb.png" alt="" /><span class="value">en_us</span></a></li>
		        <li><a href="#" ><img class="flag" src="<?php echo Yii::app()->request->baseUrl; ?>/images/es.png" alt="" /><span class="value">es</span></a></li>
		        <li><a href="#"><img class="flag" src="<?php echo Yii::app()->request->baseUrl; ?>/images/it.png" alt="" /><span class="value">it</span></a></li>
		    </ul>
		</dd>
	</dl>

	<?php if (Yii::app()->user->isGuest): ?>
	    <div class="businessright">
			<a href="<?php echo Yii::app()->createUrl('site/login');?>"><?php echo Yii::t('menu',"Business Account"); ?></a>
	     </div>
	<?php endif; ?>

	<?php if (!Yii::app()->user->isGuest): ?>
				 
		 <style type="text/css">
			.navbar-default .navbar-toggle {
			    border-color: #ddd;
			}
			.navbar-toggle {
			    background-color: yellowgreen;
			    background-image: none;
			    border: 1px solid transparent;
			    border-radius: 4px;
			    float: right;
			    padding: 9px 10px;
			    position: relative;
			}
			.sr-only {
			    border: 0 none;
			    clip: rect(0px, 0px, 0px, 0px);
			    height: 1px;
			    margin: -1px;
			    overflow: hidden;
			    padding: 0;
			    position: absolute;
			    width: 1px;
			}
			.navbar-toggle .icon-bar {
			    border-radius: 1px;
			    display: block;
			    height: 2px;
			    width: 22px;
				margin-top:3px;
				  background-color: #999;
			}
		 </style>		 
						
		<div id="businessmenu" class="mobile">
			<ul class="nav">
				<li class="dropdown">
					<a class="dropdown-toggle" href="#" data-toggle="dropdown">
						<button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
					</a>

					<ul class="dropdown-menu">
						<li><?php echo CHtml::link(preg_replace('/([^@]*).*/', '$1', Yii::app()->user->email), Yii::app()->urlManager->createUrl('profile/changepassword', array('id' => Yii::app()->user->getId())));?></li>
						<li><?php echo CHtml::link("- ".Yii::t('menu','Favourites'), Yii::app()->urlManager->createUrl('profile/favourite'));?></li>
						<li><?php echo CHtml::link("- ".Yii::t('menu','History'), Yii::app()->urlManager->createUrl('profile/history'));?></li>
						<li><?php echo CHtml::link(Yii::t('menu','Business Account'), Yii::app()->urlManager->createUrl('/business/update', array('id' => Yii::app()->session['business'])));?></li>
						<li><?php echo CHtml::link("- ".Yii::t('menu','Catalogue'), Yii::app()->urlManager->createUrl('/catalogue'));?></li>
						<li><?php echo CHtml::link("- ".Yii::t('menu', 'Location(s)'), Yii::app()->urlManager->createUrl('location/admin'));?></li>
						<li><?php//echo CHtml::link(Yii::t('menu','Account'), Yii::app()->urlManager->createUrl('/business/update', array('id' => $this -> business)));?></li>
						<li><?php echo CHtml::link(Yii::t('menu','Logout'), Yii::app()->urlManager->createUrl('/site/logout')); ?></li>
					</ul>
			 	</li>
			</ul>
		</div>
					
	<?php endif; ?>					

	<div class="signin-top">
		<?php if(Yii::app()->user->isGuest) : ?>
			<a href="<?php echo Yii::app()->createUrl('site/userlogin');?>"><?php echo Yii::t('menu',"Sign Up / Sign In"); ?></a>
		<?php else : ?>	
			<div id="businessmenu2" class="desktopMenu" style="margin-right:8px !important;">
				<ul class="nav">
				 	<li class="dropdown">
						<?php echo CHtml::link(Yii::t('menu','Business Account'), Yii::app()->urlManager->createUrl('/business/update', array('id' => Yii::app()->session['business'],)),array('class'=>"dropdown-toggle aColor",'data-toggle'=>"dropdown" )); ?>
						<ul class="dropdown-menu">
							<!--<li><?php echo CHtml::link(Yii::t('menu','Business Account'), Yii::app()->urlManager->createUrl('/business/update', array('id' => Yii::app()->session['business'])));?></li>-->
							<li><?php echo CHtml::link("- ".Yii::t('menu','Overview'), Yii::app()->urlManager->createUrl('/business/overview'));?></li>
                                                        <li><?php echo CHtml::link("- ".Yii::t('menu','Catalogue'), Yii::app()->urlManager->createUrl('/catalogue'));?></li>
							<li><?php echo CHtml::link("- ".Yii::t('menu', 'Location(s)'), Yii::app()->urlManager->createUrl('location/admin'));?></li>
                                                        <li><?php echo CHtml::link("- ".Yii::t('menu','Profile'), Yii::app()->urlManager->createUrl('/business/update', array('id' => Yii::app()->session['business'])));?></li>
						</ul>
			 		</li>
				</ul>
			</div>

			<div id="businessmenu1"  class="desktopMenu">
				<ul class="nav">
					<li class="dropdown">
						<a class="dropdown-toggle aColor" href="#" data-toggle="dropdown"><?php echo preg_replace('/([^@]*).*/', '$1', Yii::app()->user->email); ?></a>
						<ul class="dropdown-menu">							
							<li><?php echo CHtml::link("- ".Yii::t('menu','Favourites'), Yii::app()->urlManager->createUrl('profile/favourite'));?></li>
							<li><?php echo CHtml::link("- ".Yii::t('menu','History'), Yii::app()->urlManager->createUrl('profile/history'));?></li>
							<li><?php echo CHtml::link("- ".Yii::t('menu','Profile'), Yii::app()->urlManager->createUrl('profile/changepassword', array('id' => Yii::app()->user->getId())));?></li>
							<li><?php echo CHtml::link(Yii::t('menu','Logout'), Yii::app()->urlManager->createUrl('/site/logout')); ?></li>
						</ul>
				 	</li>
				</ul>
			</div>
		<?php endif; ?>
	</div>
	<div class="clear"></div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		var language = '<?php echo $currentLang; ?>';
		if (language == ''){
			var language = navigator.language;
			if (language != 'es' || language != 'it'){
				language = 'en_GB';
			}
		}

		$(".dropdown dd ul li").each(function(){		
			if ($(this).children('a').children('span.value').text() == language){
				$(".dropdown dt a").html($(this).children('a').html());
        		$(".dropdown dd ul").hide();
			}
		});
	});
</script>	

<!--</nav>-->