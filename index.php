<?php
/*
PHP script for downloading remote fonts in a CSS file.

Author: Mathias Beke
Data: June 2014
*/

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


$text = file_get_contents('style.css');
$parser = new Sabberworm\CSS\Parser($text);
$css = $parser->parse();


foreach ($css->getAllRuleSets() as $block) {
	
	$font_src = $block->getRules("src");
	$font_family = $block->getRules("font-family");
	$font_weight = $block->getRules("font-weight");
	$font_style = $block->getRules("font-style");
	
	if( 1 == sizeof($font_src) and 1 == sizeof($font_family) and 1 == sizeof($font_weight) and 1 == sizeof($font_style) ) {
		
		//Extract font metadata
		$font_family = $font_family[0]->getValue();
		$font_weight = $font_weight[0]->getValue();
		$font_style = $font_style[0]->getValue();
		
		//Replace spaces with a dash
		$font_family = implode( '-', explode(' ', $font_family) );
		
		//Remove starting/ending quote
		$font_family = substr($font_family, 1, sizeof($font_family)-2);
		
		//Compose the file name for the font
		// font-name + font-weight + font-style
		$file_name = $font_family . '-' . $font_weight . '-' . $font_style;
		$file_name = $file_name . '.woff';
		$file_name = __DIR__ . '/font/' . $file_name;
		
		//Get the source url of the font	
		foreach ($font_src[0]->getValue()->getListComponents()[0]->getListComponents() as $component) {
		
			if ( get_class($component) == 'Sabberworm\CSS\Value\URL' ) {
				
				$url = $component->getURL();
				$url = substr($url, 1, sizeof($url)-2);
				
				$downloaded_font = @file_get_contents($url);
				
				//Check if the url could be downloaded
				if($downloaded_font === false) {
					echo "Could not download $font_family ($font_style, $font_weight) from '$url'";
					echo '<hr>';
				}
				else {
					file_put_contents( $file_name , $downloaded_font);
					echo "Downloaded $font_family ($font_style, $font_weight) from '$url'";
					echo '<hr>';
				}
				
				break; //No need to search further
				
			}
			
		}
	}
	
}


?>