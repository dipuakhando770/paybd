    <section class="breadcrumbs">
        <div class="container">

            <ol>
                <li><a href="<?= base_url() ?>">Home</a></li>
                <li>Blog</li>
            </ol>
            <h2>Blog</h2>

        </div>
    </section><!-- End Breadcrumbs -->

    <section id="blog" class="blog">
        <div class="container" data-aos="fade-up">

            <div class="row">

                <div class="col-lg-8 entries">
                    <?php foreach ($items as $item) : ?>
                        <article class="entry">

                            <div class="entry-img">
                                <img src="<?= $item->thumbnail ?>" alt="" class="img-fluid">
                            </div>

                            <h2 class="entry-title">
                                <a href="<?= base_url('blog/' . $item->uri) ?>"><?= $item->title; ?></a>
                            </h2>

                            <div class="entry-meta">
                                <ul>
                                    <li class="d-flex align-items-center"><i class="bi bi-person"></i>Admin</li>
                                    <li class="d-flex align-items-center"><i class="bi bi-clock"></i> <a href="<?= base_url('blog/' . $item->uri) ?>"><time><?= time_format($item->created_at); ?></time></a></li>
                                </ul>
                            </div>

                            <div class="entry-content">
                                <p>
                                    <?= shorten_string($item->description, 240) ?>
                                </p>
                                <div class="read-more">
                                    <a href="<?= base_url('blog/' . $item->uri) ?>">Read More</a>
                                </div>
                            </div>

                        </article><!-- End blog entry -->

                    <?php endforeach; ?>



                </div><!-- End blog entries list -->

                <div class="col-lg-4">

                    <div class="sidebar">

                        <h3 class="sidebar-title">Recent Posts</h3>
                        <div class="sidebar-item recent-posts">
                            <?php foreach ($items as $item) : ?>
                                <div class="post-item clearfix">
                                    <img src="<?= base_url($item->thumbnail) ?>" alt="">
                                    <h4><a href="<?= base_url('blog/' . $item->uri) ?>"><?= $item->title ?></a></h4>
                                    <time><?= time_ago($item->created_at) ?></time>
                                </div>
                            <?php endforeach; ?>



                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>