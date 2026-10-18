<?php echo '<?xml-stylesheet href="' . base_url('public/assets/style.xsl') . '" type="text/xsl"?>' . "\n"; ?>

<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach($sitemaps as $sitemapItem): ?>
        <sitemap>
            <loc><?= $sitemapItem['loc'] ?></loc>
            <?php if($sitemapItem['lastmod'] !== null): ?>
                <lastmod><?= date('Y-m-d\TH:i:sP', strtotime($sitemapItem['lastmod'])) ?></lastmod>
            <?php endif; ?>
        </sitemap>
    <?php endforeach; ?>
</sitemapindex>
