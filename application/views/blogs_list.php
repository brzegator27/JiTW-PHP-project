<ul>
    <?php foreach($viewData as $blogName): ?>
        <a href="../blog?nazwa=<?= rawurlencode($blogName) ?>">
            <li><?= $blogName ?></li>
        </a>
    <?php endforeach; ?>
</ul>