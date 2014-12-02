<div>
    <ul>
        <?php
            if ( isset( $data ) && is_array( $data ) ) {
                foreach( $data['entry_data']['UserProfile'][0]['userMedia'] as $index => $image ) {
                    $standard_image_url = $image['images']['standard_resolution']['url'];
                    $caption            = $image['caption']['text'];
                    $link               = $image['link'];
                    ?>
                    <li>
                        <a href="<?php echo $link; ?>">
                            <img src="<?php echo $standard_image_url; ?>" alt="<?php echo $caption; ?>">
                        </a>
                    </li>
                    <?php
                }
            }
        ?>
    </ul>
</div>