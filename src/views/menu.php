<ul class="table-of-contents">
    <?php foreach ( $pages as $page ) : ?>

        <li <?php echo $page['directory'] === $active_directory ? 'class="active"' : '';?> >
            <a href="/<?php echo $page['directory'];?>"><?php echo $page['title']; ?></a>

            <?php if ( isset( $page['files'] ) && count( $page['files'] ) ) : ?>
                <ul>
                    <?php foreach ( $page['files'] as $file ) :?>
                        <li>
                            <?php
                                $link = '#'.$file['file'];

                                if ( $page['directory'] !== $active_directory ):
                                    $link = '/'.$page['directory'].$link;
                                endif;
                            ?>

                            <a href="<?php echo $link;?>"><?php echo $file['title'];?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </li>

    <?php endforeach; ?>
</ul>