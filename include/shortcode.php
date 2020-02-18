<?php
add_shortcode("swmaps-directions", "swmaps_directions_render");

function swmaps_directions_render()
{
    ob_start();
    $args = array(
        "post_type" => "sw-itineraries",

    );
    $swmaps_q = new WP_Query($args);
?>
    <div class="row">
        <div class="col-md-2">
            <?php if ($swmaps_q->have_posts()) : ?>
                <ul class="list-unstyled list-itineraries">
                    <?php while ($swmaps_q->have_posts()) : $swmaps_q->the_post();
                        $post_id = get_the_ID();
                    ?>
                        <li><a href="#<?php echo $post_id ?>"><?php the_title() ?></a></li>
                    <?php endwhile; ?>
                </ul>
            <?php endif; ?>
        </div>
        <div class="col-md-10">
            <div class="render-swmaps">
                <div id="map"></div>
            </div>
        </div>
    </div>
    <script>
        jQuery(document).ready(function($) {
            get_swmaps($(".list-itineraries li:first-child a").attr("href"));
            $(".list-itineraries").on("click", "li", function(event) {
                var url = $("a", this).attr("href");
                get_swmaps(url);
                event.preventDefault();

            });

            function get_swmaps(url) {
                $.post("<?php echo admin_url('admin-ajax.php'); ?>", {
                        id: url.replace("#", ""),
                        action: "get_swmaps"
                    },
                    function(data) {
                        $(".render-swmaps").html(data);
                    });
            }

        });
    </script>

<?php

    return ob_get_clean();
}
