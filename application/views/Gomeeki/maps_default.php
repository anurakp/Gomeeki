<script>
						$(document).ready(function () {
						var map;
						var elevator;
						var myOptions = {
							zoom: 5,
							center: new google.maps.LatLng(15, 100),
							mapTypeId: 'terrain'
						};
						map = new google.maps.Map($('#map_canvas')[0], myOptions);				
						
					});
			</script>