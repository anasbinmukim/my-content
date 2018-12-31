//AIzaSyCTK1MGD3Z31zXRJbg-Xpk1wLj4W1RK-dg

function initElevateMap() {
  var map_styles = [
    {
      "stylers": [
        { "hue": "#006eff" },
        { "saturation": -77 }
      ]
    }
  ];

  var map = new google.maps.Map(document.getElementById('footer-map-container'), {
    zoom: 15,
    center: new google.maps.LatLng(33.147941, -96.862586),
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    styles: map_styles,
    scrollwheel: false
  });

  var directionsDisplay = new google.maps.DirectionsRenderer({
    map: map
  });

  // // Set destination, origin and travel mode.
  // var request = {
  //   destination: indianapolis,
  //   origin: chicago,
  //   travelMode: 'DRIVING'
  // };
  //
  // // Pass the directions request to the directions service.
  // var directionsService = new google.maps.DirectionsService();
  // directionsService.route(request, function(response, status) {
  //   if (status == 'OK') {
  //     // Display the route on the map.
  //     directionsDisplay.setDirections(response);
  //   }
  // });
}
