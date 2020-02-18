<?php
add_action('add_meta_boxes', 'swmaps_register_metabox');
function swmaps_register_metabox()
{
    add_meta_box(
        'dettagli itinerario',
        __('Dettaglio itinerario'),
        'sw_render_details',
        'sw-itineraries',
        'normal',
        'default'
    );
}


function sw_render_details()
{
    global $post;
    $name = get_post_meta($post->ID, "swmaps_name_itinerary", true);
    $icon = get_post_meta($post->ID, "swmaps_icon", true);
    $waypoints = get_post_meta($post->ID, "swmaps_waypoints", true);
    //var_dump($waypoints);
    echo "<input type='hidden' name='create_line_nonce' value='" . wp_create_nonce(plugin_basename(__FILE__)) . "' >";
?>
    <div class="wrap">
        <div class="inner-wrap">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" name="swmaps_name_itinerary" placeholder="Nome Itinerario" class="form-control" value="<?php echo $name = empty($name) ? "" : $name ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" hidden name="swmaps_icon" class="h-swmaps-icon form-control" value="<?php echo $icon ?>">
                        <div class="render-swmaps-icons text-center">
                            <?php echo (empty($icon)) ? "Selezionare Icona" : "<i class='".$icon."'></i>" ?>
                        </div>
                        <div class="swmaps-icons"><?php echo swmaps_get_icons(); ?></div>
                    </div>
                </div>
            </div>
            <div class="swmaps-itineraries">
            <?php if ( !empty($waypoints) ){
             foreach ( $waypoints as $single ){
                 ?>
                 <div class="row">
                     <div class="col-md-6">
                         <div class = "form-group" >
                             <input type = "text" name = "swmaps_waypoint[]" placeholder = "Indirizzo" class = "form-control" value="<?php echo $single['waypoint'] ?>" >
                        </div>
                     </div>
                     <div class="col-md-5">
                         <div class = "form-group" >
                             <input type = "text" name = "swmaps_times[]" placeholder = "Indirizzo" class = "form-control" value="<?php echo $single['hours'] ?>" >
                        </div>
                     </div>
                     <div class="col-md-1">
                         <button class="btn btn-primary btn-remove"><i class="fa fa-minus"></i></button>
                     </div>
                 </div>
                 <?php
             }              
            }
            ?>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <button class="btn btn-primary btn-block btn-add-row">Nuova riga</button>
                </div>
            </div>

        </div>
    </div>
    <script>
        (function($) {
            $(document).ready(function() {
                $(".render-swmaps-icons").on("click", function(event) {
                    $(".swmaps-icons").toggleClass("open-box");
                    event.preventDefault();
                });
                $(".swmaps-icons").on("click", "li", function(event) {
                    const icon = $(this).html();
                    $(".render-swmaps-icons").html(icon);
                    var text = $("i", this).attr("class");
                    $(".h-swmaps-icon").val(text);
                    $(this).parent().parent().removeClass("open-box");
                    event.preventDefault();
                });
                $(".btn-add-row").on("click", function(event) {
                    event.preventDefault();
                    var row = '<div class="row">';
                    row += '<div class="col-md-6">';
                    row += '<div class = "form-group" >';
                    row += '<input type = "text" name = "swmaps_waypoint[]" placeholder = "Indirizzo" class = "form-control" >';
                    row += '</div></div>';
                    row += '<div class="col-md-5">';
                    row += '<div class = "form-group" >';
                    row += '<input type = "text" name = "swmaps_times[]" placeholder = "Orari" class = "form-control" >';
                    row += '</div></div>'
                    row += '<div class="col-md-1"><button class="btn-primary btn-remove"><i class="fa fa-minus"></i></button></div></div>';
                    $(".swmaps-itineraries").append(row);

                });

                $(".swmaps-itineraries").on("click", ".btn-remove", function(event) {
                    event.preventDefault();
                    $(this).parent().parent().remove();
                });

            });
        })(jQuery);
    </script>
<?php
}

add_action('save_post', 'swmaps_save');

function swmaps_save($post_id)
{

    if (!isset($_POST['create_line_nonce']) || !wp_verify_nonce($_POST['create_line_nonce'], plugin_basename(__FILE__))) {
        return $post_id;
    }

    //var_dump($_POST['desc_time']);
    if (isset($_POST['swmaps_waypoint'])){
    $waypoints = array(); 
    foreach ($_POST['swmaps_waypoint'] as  $key => $single ){
        
        $waypoints[] =array(
            "waypoint" =>$single,
            "hours"    => $_POST['swmaps_times'][$key]
        ); 
    }
    
    update_post_meta($post_id, "swmaps_waypoints", $waypoints);
    }

    //var_dump($_POST);
    //wp_die();

    update_post_meta($post_id, "swmaps_name_itinerary", $_POST['swmaps_name_itinerary']);
    update_post_meta($post_id, "swmaps_icon", $_POST['swmaps_icon']);
}
