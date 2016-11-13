<?php /* Template/Songs/view.php */ ?>

<div class="songs view content lyrics-display">
    <span class="button float-right"><?= $this->Html->link(__('List Playlists'), ['controller' => 'Playlists', 'action' => 'index']) ?></span>
    <span class="search-form float-right"><?= $this->Form->create('search songs', ['url' => ['controller' => 'Songs', 'action' => 'index']]) ?>
        <fieldset><?= $this->Form->input('Search', ['label' => false]) ?></fieldset>
        <span class="button"><?= $this->Form->button(__('Search for Songs')) ?></span>
        <?= $this->Form->end() ?>
    </span> 
    <span class="button vote float-right"><?= 
        $this->Html->link(__(
            'vote'
        ), [
            'controller' => 'SongVotes', 
            'action' => 'addret', 
            $song->id,
            'songs', 'view', $song->id
        ]) ?></span>  
    <span class="button performance float-right"><?= 
        $this->Html->link(__(
            'played'
        ), [
            'controller' => 'SongPerformances', 
            'action' => 'addret', 
            $song->id,
            'songs', 'view', $song->id
        ]) ?></span>   
    <span class="button edit float-right"><?= 
        $this->Html->link(__(
            'edit'
        ), [
            'controller' => 'songs', 
            'action' => 'edit', 
            $song->id
        ]) ?></span>  
    <h3><?= h($song->title) ?></h3>
    <table class="vertical-table">
        <tr class="title">
            <th><?= __('Title') ?></th>
            <td><?= h($song->title) ?></td>
        </tr>
        <tr class="written-by">
            <th><?= __('Written By') ?></th>
            <td><?= h($song->written_by) ?></td>
        </tr>
        <tr class="performed-by">
            <th><?= __('Performed By') ?></th>
            <td><?= h($song->performed_by) ?></td>
        </tr>
        <tr class="base-key">
            <th><?= __('Base Key') ?></th>
            <td><?= h($song->base_key) ?>
            <form class="key" action="" method="get">
                <span class="target-key">
                    <label>key: </label>
                    <select class="data" onchange="this.form.submit()" name="key">
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
                    <select class="data" onchange="this.form.submit()" name="capo">
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
            </form>
            </td>
        </tr>
        <tr class="id">
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($song->id) ?></td>
        </tr>
        <tr class="tags">
            <th>
                <?= __('Tags') ?>
                <?= $this->Form->create($songTag, ['url' => ['controller' => 'SongTags', 'action' => 'match_list', 'songs', 'view', $song->id]]) ?>
                <span class="tag-add-submit button"><?= $this->Form->button(__('(update)')) ?></span>
            </th>
            <td class="song-tags">
                <?php 
                    $selected_tags = [];
                    foreach ($song->song_tags as $this_tag) {
                        array_push($selected_tags, $this_tag['tag_id']);
                    }
                ?><fieldset>
                    <?php
                        echo '<span class="tag-id">'.$this->Form->input('tag_id', ['label' => '', 'empty' => '', 'options' => $tags, 'multiple' => true, 'default' => $selected_tags]).'</span>';
                        echo $this->Form->hidden('song_id', ['value' => $song->id]);
                    ?>
                </fieldset>
                <?= $this->Form->end() ?> 
            </td>
        </tr>
    </table>
    <?= $song->content; ?>
    </div>
</div>
