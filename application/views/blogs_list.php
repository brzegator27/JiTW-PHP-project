<ul>
    <?php foreach($viewData as $blogName): ?>
        <a href="<?= Config::URL_BASE . '/' . Config::INDEX_PAGE ?>/blog?nazwa=<?= rawurlencode($blogName) ?>">
            <li><?= $blogName ?></li>
        </a>
    <?php endforeach; ?>
</ul>