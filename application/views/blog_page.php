BLOG!!!


<?php foreach($viewData['entries'] as $entryId => $entry): ?>
<div class="entry" style="background-color: grey; margin-bottom: 10px; padding: 10px;">
    <h3>
        <?= $entry['title'] ?>
    </h3>

    <div class="entry-content" style="background-color: blanchedalmond; padding: 5px;">
        <?= $entry['content'] ?>
    </div>
    <?php foreach($entry['comments'] as $coment): ?>
        <div class="entry-comment">
            <?= $comment ?>
        </div>
    <?php endforeach; ?>
    <br/>
    <a href="comment/add_comment?entry_id=<?= $entryId ?>">
        Dodaj komentarz.
    </a>
</div>
<?php endforeach; ?>

<!--<pre>
<?php // var_dump($viewData); ?>
</pre>-->



