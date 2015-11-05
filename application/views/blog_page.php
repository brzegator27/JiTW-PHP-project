<?php 
    if(count($viewData) === 0) {
        echo 'Blog o podanej nazwie nie istnieje!';
        return;
    } 
?>

<h1>
    <!--<a href="blog?nazwa=<?= rawurlencode($viewData['blog_name']) ?>">-->
        Blog: <?= $viewData['blog_name'] ?>
    <!--</a>-->
</h1>

<?php foreach($viewData['entries'] as $entryId => $entry): ?>
<div class="entry" style="background-color: grey; margin-bottom: 10px; padding: 10px;">
    <h3>
        <?= $entry['title'] ?>
    </h3>

    <div class="entry-content" style="background-color: blanchedalmond; padding: 5px;">
        <?= $entry['content'] ?>
    </div>
    <br/>
    Pliki do pobrania:<br/>
    <?php foreach($entry['files'] as $fileNumber => $filePath): ?>
        <a href="<?= $filePath ?>">
            Plik <?= $fileNumber + 1 ?><br/>
        </a>
    <?php endforeach; ?>
    <br/><br/>
    Komentarze: <br/>
    <?php foreach($entry['comments'] as $comment): ?>
        <div class="entry-comment" style="background-color: <?= getCommentBackground($comment['type']) ?> ; padding: 5px; margin: 5px;">
            Komentarz jest: <?= mapCommentTypeToString($comment['type']) ?><br/>
            Data wysłania komentarza: <?= $comment['date'] ?><br/>
            <?= $comment['content'] ?>
        </div>
    <?php endforeach; ?>
    <br/>
    <a href="comment/add_comment?entry_id=<?= $entryId ?>&blog_name=<?= rawurlencode($viewData['blog_name']) ?>">
        Dodaj komentarz.
    </a>
</div>
<?php endforeach; ?>

<?php
    function mapCommentTypeToString($commentType) {
        switch($commentType) {
            case 1:
            case '1':
                return 'pozytywny';
            case 0:
            case '0':
                return 'negatywny';
        }
    }
    
    function getCommentBackground($commentType) {
        switch($commentType) {
            case 1:
            case '1':
                return 'lawngreen';
            case 0:
            case '0':
                return 'red';
        }
    }



