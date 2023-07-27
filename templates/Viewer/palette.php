<?php /* Template/Viewer/palette.php*/  
//debug($filter_set);

//echo("<h1>Palette</h2><br />");
echo('<div class="filter-palette">');
?>

<script>
$(document).ready(function(){
	/* Make songnames into links, opening the song in the viewer panel */
    jQuery('#songlist>li').on('click', function (event) {
        this_li = jQuery(event.target).closest('li');

		link_url = '/songbook/songs/view/' + this_li.attr('data-id') + '?key=' + this_li.attr('data-key') + '&capo=' + this_li.attr('data-capo') + '';
    	
    	//console.log(this_li, link_url);
    	window.open(link_url, "_blank");
    });


    /* show/hide the tools panel */
    SBK.StaticFunctions.make_hideable_panel('.container-hideable', "...", "^");

});
</script>


<?php /* Hideable filter panel --------------------------------- */ ?>
    	



<?php
foreach ($filtered_data as $this_dataset) {
    //debug($this_filter);
    echo $this->element('filtered_songlist', [
        'filtered_list' => $this_dataset['query'], 
        'filter_on'=>$this_dataset['filter_on'],
        'selected_performer'=>$this_dataset['selected_performer'],
        'print_title'=>$this_dataset['print_title']
    ]);
}

echo('</div>');
?>
<span class="container-hideable"  style="visibility: hidden;">

<a class="button" href = "/songbook/viewer">Viewer</a>
<a class="button" href = "/songbook/dashboard">Dashboard</a>
        	<ul>
                <li><?= $this->Html->link(__('Palette E-AMU'), ['controller' => 'viewer', 'action' => 'palette', '?'=>['palette_set'=>'Euge AMU']], ['target'=>'_blank']) ?></li>
                <li><?= $this->Html->link(__('Palette M-AMU'), ['controller' => 'viewer', 'action' => 'palette', '?'=>['palette_set'=>'Midge AMU']], ['target'=>'_blank']) ?></li>
                <li><?= $this->Html->link(__('Palette E-Session'), ['controller' => 'viewer', 'action' => 'palette', '?'=>['palette_set'=>'Euge Session']], ['target'=>'_blank']) ?></li>
            </ul>
        </span>
        <?php /* end of Hideable filter panel --------------------------------- */ ?>




        
