<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/HomepageTopMenu.css" />
    <?php
   $baseUrl = Yii::app()->request->baseUrl; 
   $cs = Yii::app()->getClientScript();
   $cs->registerCssFile($baseUrl.'/css/language_dropdown.css');
   $cs->registerScriptFile($baseUrl.'/js/language_dropdown.js');
 ?>

<?php echo CHtml::form(Yii::app()->createUrl('site/language')); ?>

<div class="container-top" >


    <div id="langdrop" >
        <?php echo CHtml::dropDownList('_lang', $currentLang, array(
            'en-US' => 'English', 
			'es' => 'Spanish',
			'it' => 'Italian',
			), array(
			'submit' => '',
                            'style'=>'color:#fff;'
			)); 
		?>
    </div>
<?php echo CHtml::endForm(); ?>
<?php /*

       <li><a  href="javascript:void(0);" title="<?php echo Yii::t('site',"English"); ?>"><?php echo Yii::t('site',"English"); ?><img class="flag" src="<?php echo Yii::app()->request->baseUrl; ?>/images/gb.png" alt="" /><span class="value">en-US</span></a></li>
        <li><a href="javascript:void(0);" title="<?php echo Yii::t('site',"Spanish"); ?>"><?php echo Yii::t('site',"Spanish"); ?><img class="flag" src="<?php echo Yii::app()->request->baseUrl; ?>/images/es.png" alt="" /><span class="value">es</span></a></li>
        <li><a href="javascript:void(0);" title="<?php echo Yii::t('site',"Italian"); ?>"><?php echo Yii::t('site',"Italian"); ?><img class="flag" src="<?php echo Yii::app()->request->baseUrl; ?>/images/it.png" alt="" /><span class="value">it</span></a></li>
 */?>


<dl id="sample-dropdown" class="dropdown" style="margin-top:4px;" >
<dt><a  href="javascript:void(0);" title="<?php echo Yii::t('site',"English"); ?>"><img class="flag" src="<?php echo Yii::app()->request->baseUrl; ?>/images/gb.png" alt="" /><span class="value">en-US</span></a></dt>
<dd >
    <ul >
        <li><a  href="javascript:void(0);" title="<?php echo Yii::t('site',"English"); ?>"><img class="flag" src="<?php echo Yii::app()->request->baseUrl; ?>/images/gb.png" alt="" /><span class="value">en-US</span></a></li>
        <li><a href="javascript:void(0);" title="<?php echo Yii::t('site',"Spanish"); ?>"><img class="flag" src="<?php echo Yii::app()->request->baseUrl; ?>/images/es.png" alt="" /><span class="value">es</span></a></li>
        <li><a href="javascript:void(0);" title="<?php echo Yii::t('site',"Italian"); ?>"><img class="flag" src="<?php echo Yii::app()->request->baseUrl; ?>/images/it.png" alt="" /><span class="value">it</span></a></li>
    </ul>
</dd>
</dl>



                 <div class="signin-top">
		<?php if(Yii::app()->user->isGuest) { ?>
		<a href="<?php echo Yii::app()->createUrl('site/login');?>"><?php echo Yii::t('site',"Sign Up / Sign In"); ?></a>
		<?php } else { ?>
			<p><?php echo preg_replace('/([^@]*).*/', '$1', Yii::app()->user->email); ?></p>
		<?php } ?>
                </div>



<div class="social">
<a href="http://www.facebook.com" target="_blank">
    <img class="facebook" src="<?php echo Yii::app()->request->baseUrl; ?>/images/facebook.png" alt="<?php echo Yii::t('site',"Like us on Facebook");  ?>" />
</a> 
<a href="http://www.twitter.com" target="_blank"> 
    <img class="twitter" src="<?php echo Yii::app()->request->baseUrl; ?>/images/twitter.png" alt="<?php echo Yii::t('site',"Follow us on Twitter");  ?>" />
</a>  
 <a href="http://www.google.com" target="_blank">   
    <img class="google" src="<?php echo Yii::app()->request->baseUrl; ?>/images/google.png" alt="<?php echo Yii::t('site',"Google");  ?>" />
 </a>  
</div>




<?php if (Yii::app()->user->isGuest) { ?>
    <div class="businessright">
        
        <a  href="<?php echo Yii::app()->createAbsoluteUrl('site/login'); ?>"><?php echo Yii::t('site', 'Business Account'); ?></a>

            </div>
<?php } ?>

<div class="clear"></div>
</div>


<script type="text/javascript">
	$(document).ready(function(){
		var language = '<?php echo $currentLang; ?>';
		if (language == ''){
			var language = navigator.language;
			if (language != 'es' || language != 'it'){
				language = 'en_us';
			}
			//$('select').val(language); this was affecting all selects in the page
		}
		$(".dropdown dd ul li").each(function(){
			if ($(this).children('a').children('span.value').text() == language){
				$(".dropdown dt a span").html($(this).children('a').html());
        		$(".dropdown dd ul").hide();
			}
		});
	});
</script>

<!--</nav>-->