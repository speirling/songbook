<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Customlist $customlist
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Customlist'), ['action' => 'edit', $customlist->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Customlist'), ['action' => 'delete', $customlist->id], ['confirm' => __('Are you sure you want to delete # {0}?', $customlist->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Customlists'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Customlist'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="customlists view content">
            <h3><?= h($customlist->title) ?></h3>
            <table>
                <tr>
                    <th><?= __('Title') ?></th>
                    <td><?= h($customlist->title) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($customlist->id) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Comment') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($customlist->comment)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Url') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($customlist->url)); ?>
                </blockquote>
            </div>
            
        <p><a href="<?php
                echo("../../viewer?");
                echo($customlist->url);
                echo("&custom_id=");
                echo($customlist->id);
                echo("&custom_title=");
                echo($customlist->title);
                echo("&custom_comment=");
                echo($customlist->comment);
            
            ?>">Open the list in songbook UI</a></p>
            
        <p><a href="<?php
                echo("../../viewer/custom?");
                echo(str_replace("c[]","f[]",$customlist->url));
                echo("&custom_id=");
                echo($customlist->id);
                echo("&custom_title=");
                echo($customlist->title);
                echo("&custom_comment=");
                echo($customlist->comment);
            
            ?>">Open the list in Editing UI</a></p>
        </div>
    </div>
</div>
