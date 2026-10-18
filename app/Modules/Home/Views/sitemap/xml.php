<?php echo '<?xml-stylesheet href="' . base_url('public/assets/xml.xsl') . '" type="text/xsl"?>' . "\n"; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
        xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">

    <?php foreach ($items as $item) : ?>
        <url>
            <loc><?= $item['loc'] ?></loc>

            <?php if (!empty($item['translations'])) : ?>
                <?php foreach ($item['translations'] as $translation) : ?>
                    <xhtml:link hreflang="<?= $translation['language'] ?? '' ?>" href="<?= $translation['url'] ?? '' ?>" rel="alternate"/>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($item['alternates'])) : ?>
                <?php foreach ($item['alternates'] as $alternate) : ?>
                    <xhtml:link href="<?= $alternate['url'] ?? '' ?>" rel="alternate" media="<?= $alternate['media'] ?? '' ?>"/>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (isset($item['priority'])) : ?>
                <priority><?= $item['priority'] ?></priority>
            <?php endif; ?>

            <?php if (isset($item['lastmod'])) : ?>
                <lastmod><?= date('Y-m-d\TH:i:sP', strtotime($item['lastmod'])) ?></lastmod>
            <?php endif; ?>

            <?php if (isset($item['freq'])) : ?>
                <changefreq><?= $item['freq'] ?></changefreq>
            <?php endif; ?>

            <?php if (!empty($item['images'])) : ?>
                <?php foreach ($item['images'] as $image) : ?>
                    <image:image>
                        <image:loc><?= $image['url'] ?></image:loc>
                        <image:title><?= $image['title'] ?? '' ?></image:title>
                        <image:caption><?= $image['caption'] ?? '' ?></image:caption>
                        <image:geo_location><?= $image['geo_location'] ?? '' ?></image:geo_location>
                        <image:license><?= $image['license'] ?? '' ?></image:license>
                    </image:image>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (!empty($item['videos'])) : ?>
                <?php foreach ($item['videos'] as $video) : ?>
                    <video:video>
                        <video:thumbnail_loc><?= $video['thumbnail_loc'] ?? '' ?></video:thumbnail_loc>
                        <video:title><![CDATA[<?= $video['title'] ?? '' ?>]]></video:title>
                        <video:description><![CDATA[<?= $video['description'] ?? '' ?>]]></video:description>
                        <video:content_loc><?= $video['content_loc'] ?? '' ?></video:content_loc>
                        <video:duration><?= $video['duration'] ?? '' ?></video:duration>
                        <video:expiration_date><?= $video['expiration_date'] ?? '' ?></video:expiration_date>
                        <video:rating><?= $video['rating'] ?? '' ?></video:rating>
                        <video:view_count><?= $video['view_count'] ?? '' ?></video:view_count>
                        <video:publication_date><?= $video['publication_date'] ?? '' ?></video:publication_date>
                        <video:family_friendly><?= $video['family_friendly'] ?? '' ?></video:family_friendly>
                        <video:requires_subscription><?= $video['requires_subscription'] ?? '' ?></video:requires_subscription>
                        <video:live><?= $video['live'] ?? '' ?></video:live>
                        <video:player_loc allow_embed="<?= $video['player_loc']['allow_embed'] ?? '' ?>" autoplay="<?= $video['player_loc']['autoplay'] ?? '' ?>"><?= $video['player_loc']['player_loc'] ?? '' ?></video:player_loc>
                        <video:restriction relationship="<?= $video['restriction']['relationship'] ?? '' ?>"><?= $video['restriction']['restriction'] ?? '' ?></video:restriction>
                        <video:gallery_loc title="<?= $video['gallery_loc']['title'] ?? '' ?>"><?= $video['gallery_loc']['gallery_loc'] ?? '' ?></video:gallery_loc>
                        <video:price currency="<?= $video['price']['currency'] ?? '' ?>"><?= $video['price']['price'] ?? '' ?></video:price>
                        <video:uploader info="<?= $video['uploader']['info'] ?? '' ?>"><?= $video['uploader']['uploader'] ?? '' ?></video:uploader>
                    </video:video>
                <?php endforeach; ?>
            <?php endif; ?>
        </url>
    <?php endforeach; ?>

</urlset>
