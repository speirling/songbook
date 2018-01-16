<?php /* Template/Songs/view.php */ ?>

    <h3 class="printable"><?= h($song->title) ?></h3>
    <table class="vertical-table attribution printable">
        <tr class="written-by performed-by">
            <th><?= __('Written By') ?></th>
            <td><?= h($song->written_by) ?>
            <th><?= __('Performed By') ?></th>
            <td><?= h($song->performed_by) ?></td>
        </tr>
    </table>
    <?= $song->printable_content; ?>

