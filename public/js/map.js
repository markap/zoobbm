/**var map = new GMap2(document.getElementById("map"));
map.addControl(new GSmallMapControl());
map.addControl(new GMapTypeControl());
map.setCenter(new GLatLng(48.404762048449406, 
12.88447380065918), 11);



var point1 = new GPoint(12.88447380065918,48.404762048449406);
 var html1 = "<h5>Mein Wohnort</h5><p>Hieb 2<br/>84389 Postmünster</p>";

var beck = new GMarker(point1);
GEvent.addListener(beck, "click", function()
 {beck.openInfoWindowHtml(html1)});

map.addOverlay(beck);
*/

 google.load("maps", "2",{"other_params":"sensor=true"});

  function initialize() {
    var map = new google.maps.Map2(document.getElementById("map"));
    map.setCenter(new google.maps.LatLng(37.4419, -122.1419), 13);
  }
  google.setOnLoadCallback(initialize);

