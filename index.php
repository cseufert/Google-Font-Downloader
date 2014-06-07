<?php

require_once(__DIR__ . '/lib/Sabberworm/CSS/Property/AtRule.php');
require_once(__DIR__ . '/lib/Sabberworm/CSS/Property/Charset.php');
require_once(__DIR__ . '/lib/Sabberworm/CSS/Property/CSSNamespace.php');
require_once(__DIR__ . '/lib/Sabberworm/CSS/Property/Import.php');
require_once(__DIR__ . '/lib/Sabberworm/CSS/Property/Selector.php');
require_once(__DIR__ . '/lib/Sabberworm/CSS/CSSList/CSSList.php');
require_once(__DIR__ . '/lib/Sabberworm/CSS/CSSList/CSSBlockList.php');
require_once(__DIR__ . '/lib/Sabberworm/CSS/CSSList/AtRuleBlockList.php');
require_once(__DIR__ . '/lib/Sabberworm/CSS/CSSList/Document.php');
require_once(__DIR__ . '/lib/Sabberworm/CSS/CSSList/KeyFrame.php');
require_once(__DIR__ . '/lib/Sabberworm/CSS/Parsing/UnexpectedTokenException.php');
require_once(__DIR__ . '/lib/Sabberworm/CSS/Rule/Rule.php');
require_once(__DIR__ . '/lib/Sabberworm/CSS/RuleSet/RuleSet.php');
require_once(__DIR__ . '/lib/Sabberworm/CSS/RuleSet/AtRuleSet.php');
require_once(__DIR__ . '/lib/Sabberworm/CSS/RuleSet/DeclarationBlock.php');
require_once(__DIR__ . '/lib/Sabberworm/CSS/Settings.php');
require_once(__DIR__ . '/lib/Sabberworm/CSS/Value/Value.php');
require_once(__DIR__ . '/lib/Sabberworm/CSS/Value/ValueList.php');
require_once(__DIR__ . '/lib/Sabberworm/CSS/Value/CSSFunction.php');
require_once(__DIR__ . '/lib/Sabberworm/CSS/Value/Color.php');
require_once(__DIR__ . '/lib/Sabberworm/CSS/Value/PrimitiveValue.php');
require_once(__DIR__ . '/lib/Sabberworm/CSS/Value/RuleValueList.php');
require_once(__DIR__ . '/lib/Sabberworm/CSS/Value/Size.php');
require_once(__DIR__ . '/lib/Sabberworm/CSS/Value/String.php');
require_once(__DIR__ . '/lib/Sabberworm/CSS/Value/URL.php');
require_once(__DIR__ . '/lib/Sabberworm/CSS/Parser.php');


$sMyId = "#my_id";
$sText = file_get_contents('style.css');
$oParser = new Sabberworm\CSS\Parser($sText);
$oCss = $oParser->parse();


foreach ($oCss->getAllRuleSets() as $block) {
	$font_src = $block->getRules("src");
	$font_family = $block->getRules("font-family");
	$font_weight = $block->getRules("font-weight");
	$font_style = $block->getRules("font-style");
	
	if( 1 == sizeof($font_src) and 1 == sizeof($font_family) and 1 == sizeof($font_weight) and 1 == sizeof($font_style) ) {
		
		//Compose the file name for the font
		// font-name + font-weight
		$file_name = implode( '-', explode(' ', $font_family[0]->getValue()) );
		$file_name = substr($file_name, 1, sizeof($file_name)-2);
		$file_name = $file_name . '-' . $font_weight[0]->getValue() . '-' . $font_style[0]->getValue();
		$file_name = $file_name . '.woff';
		$file_name = __DIR__ . '/font/' . $file_name;
		
		//Get the source url of the font	
		foreach ($font_src[0]->getValue()->getListComponents()[0]->getListComponents() as $component) {
		
			if ( get_class($component) == 'Sabberworm\CSS\Value\URL' ) {
				$url = $component->getURL();
				$url = substr($url, 1, sizeof($url)-2);
				echo $url;
				
				file_put_contents( $file_name , file_get_contents($url));
			}
			
		}
		
		echo "<hr><br>";
	}
	
}


?>
<pre><?php echo $oCss; ?></pre>