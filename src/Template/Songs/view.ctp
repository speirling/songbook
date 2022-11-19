<?php /* Template/Songs/view.php */ ?>

<? /* ------------------------
    Get viewport size, check if it matches what was sent to the Server               */ ?>
               
    <script>
        $(document).ready(function(){
            console.log(SBK.StaticFunctions);
            SBK.StaticFunctions.set_window_size_in_URL();
        });
    </script>
<? /* ----------------------*/ ?>
<div class="songs view content lyrics-display song-row"> <?php /* .song-row required for ajax callbacks for played and vote to work. */?>
    <span class="button float-right top-button-set"><?= $this->Html->link(__('Dashboard'), ['controller' => 'Dashboard', 'action' => 'index']) ?></span>
    <span class="search-form float-right top-button-set">
	    <?= $this->Form->create(null, ['url' => ['controller' => 'dashboard', 'action' => 'index']]) ?>
	    <fieldset>
	        <span class="text-search">
	        <?= $this->Form->input('text_search', ['label'=>False]); ?>
	        </span>
	    </fieldset>
	    <span class="selected-tags-and-performer button"><?= $this->Form->button(__('Search')) ?></span>
	    <?= $this->Form->end() ?>
    </span> 
    <?= $this->element('ajax-button-form-played', ['current_song' => $song]); ?>
    <?= $this->element('ajax-button-form-vote', ['current_song' => $song]); ?>
    <span class="button float-right top-button-set"><?= $this->Html->link(__('Print'), ['controller' => 'Songs', 'action' => 'printable', $song->id, '?' => ['key' => $current_key, 'capo' => $capo]]) ?></span>
    <span class="button edit float-right top-button-set"><?= 
        $this->Html->link(__(
            'edit'
        ), [
            'controller' => 'songs', 
            'action' => 'edit', 
            $song->id
        ]) ?></span>  
    <h3><?= h($song->title) ?></h3>
    <table class="vertical-table">
        <tr class="written-by performed-by">
            <th><?= __('Written By') ?></th>
            <td><?= h($song->written_by) ?>
            <span class="blue-bold"><?= __('Performed By') ?></span>
            <span class="td"><?= h($song->performed_by) ?></span></td>
        </tr>
        <tr class="base-key">
            <th><?= __('Base Key') ?></th>
            <td><?= h($song->base_key) ?>
            <form class="key" action="" method="get" id="key_form" style="display: none;">
                <input type="text" name="key" value = "<?= $current_key ?>" id="key_input"></input>
                <input type="text" name="capo" value = "<?= $capo ?>" id="capo_input"></input>
            </form>
     
            <span class="key-capo-selectors">
                <span class="target-key">
                    <label>key: </label>
                    <select class="data" onchange="SBK.CakeUI.form.submit_value(jQuery(this).val(), '#key_input')">
                        <option value=""></option>
                        <?php 
                            foreach (App\Controller\StaticFunctionController::$NOTE_VALUE_ARRAY as $key => $value) {
                                echo '<option value="' . $key . '"';
                                if ($key === $current_key){
                                    echo ' selected ';
                                }
                                echo '>' . $key . '</option>';
                            }
                        ?>
                    </select>
                </span>
                <span class="capo">
                    <label>capo: </label>
                    <select class="data" onchange="SBK.CakeUI.form.submit_value(jQuery(this).val(), '#capo_input')" name="capo">
                    <?php for($capo_index = 0; $capo_index < 9; $capo_index = $capo_index + 1){
                        echo '<option value="' . $capo_index . '"';
                        if($capo_index == $capo) {
                            echo ' selected';
                        }
                        echo '>' . $capo_index . '</option>';
                    }
                    ?>
                    </select>
                    </span>
                </span>
	
	            <span class="performers">
	                    <label>Known keys: </label>
	                <?php foreach ($song->set_songs as $set_song) {
	                    echo '<span class="performer" onclick="SBK.CakeUI.form.submit_value_json(\'{&quot;key_input&quot;:&quot;'.$set_song['key'].'&quot;, &quot;capo_input&quot;:'.$set_song['capo'].'}\')">';
	                    echo '<span class="nickname">'.$set_song['performer']['nickname'].' : </span>';
	                    echo '<span class="key">'.$set_song['key'].'</span>';
	                    //echo '<span class="capo">'.$set_song['capo'].'</span>'; 
	                    echo '</span>';
	                } ?>
	                
	                   
		            <?= $this->Form->create($setSong, ['url' => ['controller' => 'SetSongs', 'action' => 'addret', 'songs', 'view', $song->id]]) ?>
				    <fieldset>
				        <label><?= __('add:') ?></label>
				        <?php
				            echo $this->Form->hidden('set_id', ['value' => 0]);
				            echo $this->Form->hidden('song_id', ['value' => $song->id]);
				            echo $this->Form->input('performer_id', ['empty' => 'Please select ...', 'options' => $performers]);
				            echo $this->Form->input('key');
				            //echo $this->Form->input('capo');
				        ?>
				    </fieldset>
				    <span class="button"><?= $this->Form->button(__('Submit')) ?></span>
				    <?= $this->Form->end() ?>
	            </span>
            
            </td>
        </tr>
        <tr class="tags">
            <th>
                <?= __('Tags') ?>
            </th>
            <td class="song-tags">
            <?php 
	            echo $this->element('ajax-form-tags', [
	                'current_song' => $song,
	                'all_tags' => $tags,
	                'ajax_callback' => 'SBK.CakeUI.ajaxcallback.song_view.set_tags'
	            ]);
	        ?>
            </td>
        </tr>
    </table>
    <?= $song->content; ?>
</div>
