<div>
    <ul>
        <?php
            if ( isset( $data ) && is_array( $data ) ) {
                foreach( $data['entry_data']['ProfilePage'][0]['user']['media']['nodes'] as $index => $image ) {
                    $standard_image_url = $image['display_src'];
                    $caption            = $image['caption'];
                    $link               = $this->instagram_url .'p/'. $image['code'];
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