<?php
function swmaps_get_icons()
{
    $url = SWMAPS_PLUGIN_URL . "/include/json/awesome.json";
    $json = file_get_contents($url);
    $obj = json_decode($json, true);
    $list = '<ul class="list-inline">';
    foreach ($obj['470'] as $single) {
        $list .= '<li class="list-inline-item"><i class="fa ' . $single . '"></i></li>';
    }
    $list .= '</ul>';
    return $list;
}

add_action("wp_ajax_nopriv_get_swmaps", "get_swmaps_directions_callback");
add_action("wp_ajax_get_swmaps", "get_swmaps_directions_callback");

function get_swmaps_directions_callback()
{
    ob_start();
    $icon = "https://treninidiriccione.it/wp-content/uploads/2020/02/logo_blue_treninidiriccioine-e1581991832585.jpeg";
    $id = $_POST['id'];
    $waypoints = get_post_meta($id, "swmaps_waypoints", true);
    $list_route = array();
    $list_content= array();
    foreach ($waypoints as $single) {
        $list_name[]    =  $single['waypoint'];
        $list_route[]   =  $single['waypoint'].",Riccione,Rn";
        $list_content[] =  $single['hours'];
    }

    $start  = $list_route[0];
    $end    = end($list_route);

?>

    <div id="map"></div>
    <script>
        (function($) {
            $(document).ready(function() {
            var list_address  = <?php echo json_encode($list_route); ?>;
            var list_time     = <?php echo json_encode($list_content); ?>;    
            var list_name     = <?php echo json_encode($list_name); ?>;
            var map;
            var map_select = document.getElementById('map');
            var geocoder = new google.maps.Geocoder();
            var directionsService = new google.maps.DirectionsService();
                var renderOptions = {
                    draggable: true,
                    polylineOptions: {
                        strokeColor: 'red'
                    },
                    suppressInfoWindows: false,
                    suppressMarkers: true
                };
                var directionDisplay = new google.maps.DirectionsRenderer(renderOptions);

                setCenter(geocoder);



function setCenter(geocoder) {
var myOptions = "";
    geocoder.geocode({
        "address": "riccione"
        }, function(results, status) {

 if (status == 'OK') 
    {
        myOptions = {
        zoom: 12,
        center: results[0].geometry.location
    }

var map = new google.maps.Map(map_select, myOptions);
    
    codeAddress(geocoder, list_address, map);
    directionDisplay.setMap(map);
    
    var items = list_address;
    var waypoints = [];
    var address;

    for (var i = 0; i < items.length; i++) {
        address = items[i];
        if (address !== "") {
            waypoints.push({
                location: address,
                stopover: false
            });
        }
    }
    var originAddress = "<?php echo $start ?>";
    var destinationAddress = "<?php echo $end ?>";
    var request = {
         origin: originAddress,
         destination: destinationAddress,                           
         waypoints: waypoints, //an array of waypoints
         optimizeWaypoints: false, //set to true if you want google to determine the shortest route or false to use the order specified.
         travelMode: google.maps.DirectionsTravelMode.DRIVING
    };                                                      
    //get the route from the directions service
        directionsService.route(request, function(response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionDisplay.setDirections(response);
        } else {
            //handle error
        }
    });




    }
    });
}


    function codeAddress(geocoder, list_address, map) {
        var marker = [];
        var infowindow = [];
        list_address.forEach(function(address, index) {

        infowindow[index] = new google.maps.InfoWindow({
                content: "<p><i class='fa fa-train'></i> "+list_name[index]+"<br/>Orari:"+list_time[index]+"</p>"
            });

            geocoder.geocode({
                'address': address
            }, function(results, status) {
                if (status == 'OK') {
                    console.log(index);
                    map.setCenter(results[0].geometry.location);
                    marker[index] = new google.maps.Marker({
                        map: map,
                        position: results[0].geometry.location,
                        icon: "<?php echo $icon ?>"
                    });
                    marker[index].addListener('click', function() {
                        infowindow[index].open(map, marker[index]);
                    });
                } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                }
            });

        });
    }
});
        })(jQuery);
    </script>
<?php
    echo ob_get_clean();
    exit();
}
