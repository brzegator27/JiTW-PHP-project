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

<div class="entry" style="background-color: grey; margin-bottom: 10px; padding: 10px;">
    Opis blogu:<br/>
    <?= $viewData['description'] ?>
</div>




<!--Beginning of live comment module.-->
<div class="live-comment" style="background-color: aquamarine; margin-bottom: 10px; padding: 10px; height: 500px;">
    komunikator bloga:<br/>
    <div id="live-comment-box" style="background-color: #6BCAE2; margin-bottom: 10px; padding: 10px; height: 200px; overflow-y: scroll;">
        <div class="empty-comment" timestamp="">
        </div>
    </div>
    Imię: <input type="text" id="live-comment-username"/><br/>
    Tekst: <textarea id="live-comment-text" cols="40" rows="5"></textarea><br/>
    <button type="button" onclick="manageNewLiveComment(this)" id="live-comment-send-button" disabled>Wyślij</button>
    <input type="checkbox" onchange="manageLiveCommentState(this)">Komunikator aktywny<br/>
</div>
<script>
    var blogName = '<?= $viewData['blog_name'] ?>',
            liveCommentUrlSend = '<?= getConfig()->getFullBaseUrl() . 'blog_live_comment/add_live_comment'?>',
            liveCommentUrlPull = '<?= getConfig()->getFullBaseUrl() . 'blog_live_comment/get_live_comments'?>',
            liveCommentBox = document.getElementById('live-comment-box'),
            comments = liveCommentBox.childNodes;
    
    comments[1].timestamp = '99999999999999999';
</script>
<!--End of live comment module.-->



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
            case 2:
            case '2':
                return 'neutralny';
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
            case 2:
            case '2':
                return 'grey';
            case 1:
            case '1':
                return 'lawngreen';
            case 0:
            case '0':
                return 'red';
        }
    }



