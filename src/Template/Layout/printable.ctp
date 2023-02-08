<?php /* Template/Layout/printable.php */ 
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
    <?php if (isset($title)) {
    	echo ($title . ' (printable)');
    } else { ?>
        EP Songbook:
        <?= $this->fetch('title') ?>
         (printable)
        <?php } ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css') ?>
    <?= $this->Html->css(Cake\Core\Configure::read('Songbook.css_library')); ?>
    <?php
    $size_config_values = \Cake\Core\Configure::read('Songbook.print_size.'.$print_size);
    $local_css_statement = "\n" . 
	   	    ".line {\n" .
	   	    "    margin-top: " . $size_config_values['lyric_line_top_margin'] .  "px; \n" .
	   	    "\n}" .
	   	    ".line .word {\n" .
	   	    "    font-size: " . $size_config_values['font_sizes']['lyrics'] .  "px; \n" .
	   	    "\n}" .
	   	    ".line .word .chord {\n" .
	   	    "    font-size: " . $size_config_values['font_sizes']['chords'] .  "px; \n" .
	   	    "}\n" .
	   	    ".title-block .song-title h3 {\n" .
	   	    "    font-size: " . $size_config_values['font_sizes']['title'] .  "px; \n" .
	   	    "}\n" .
	   	    ".title-block .attribution .performed-by.written-by th, .title-block .attribution .performed-by.written-by td {\n" .
	   	    "    font-size: " . $size_config_values['font_sizes']['attributions'] .  "px; \n" .
	   	    "}\n" .
	   	    
	    "";
	    echo '<style type="text/css">';
	    echo $local_css_statement;
	    echo '</style>';
    ?>
	<?= $this->Html->script(Cake\Core\Configure::read('Songbook.js_library')); ?>
    

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body class="printable">
    <?php
      echo $title_block_html;
    ?>
    <?= $this->fetch('content') ?>
</body>
</html>
