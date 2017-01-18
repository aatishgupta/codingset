<!DOCTYPE html>
<html>
<head>
	<title>Aatish Web Test</title>
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' />
	<style>
       #map {
        height: 400px;
        width: 100%;
       }
       .error
       {
       	color:red;
       }
    </style>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<!--   this plugin is used to validate user form data  -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.min.js"></script>
	<script type="text/javascript">

	$(document).ready(function()
	 {


				$("#weather_form").validate({
					rules: {
						city:
						{
							"required":function()
							{
								var zip=$("#zipcode").val();
									if(zip!="")
									{
											return false;
									}else{
										return true;
									}
							}
						},
						zipcode:
						{
							"required":function()
							{
								var city=$("#city").val();
									if(city!="")
									{
											return false;
									}else{
										return true;
									}
							},
							"digits":true
						}
					},
					messages:{
								city:
						{
							"required":"Please enter value of city"
						},
						zipcode:
						{
							"required":"Please enter value of zipcode",
							"digits":"zipcode must containe digits only"
						}
					},
					submitHandler:function(from)
					{

						/*  this done because of passing multiple parameters in api */
						var parameter="";
						var zipcode=$("#zipcode").val();
						var city=$("#city").val();
						if(zipcode!="")
						{
							parameter+=zipcode;
						}
						if(city!="" && parameter!="")
						{
							parameter+=","+city;
						}else if(parameter=="" && city!="")
						{
							parameter+=city;
						}
						if(city=="" && zipcode=="")
						{
								alert("Incorrect data provided");
								return false;
						}
						/* ajax call to fetch weather information of particular city or provided zipcode */
							$.ajax({
								url:"http://api.openweathermap.org/data/2.5/weather?zip="+parameter+"&appid=e48c54b58b3e3f8196593b6ee70ec1bd",
								dataType:"jsonp",
								success:function(response)
								{
										var image="";
										if(response.weather['main']=="Clear")
										{
														image="clear_sky.jpg'";
										}else{
														image="sunny.png'";
										}
									var weather_info="<p><img src='"+image+"' style='height: 50px;width: :50px;'' /> <span> weather status : </span> "+response.weather[0].main+" </p>"+
														"<p><span></span >temperature : </span>"+response.main['temp']+"</p>"+
														"<p><span></span >Humidity : </span>"+response.main['humidity']+"</p>"+
														"<p><span></span >Pressure : </span>"+response.main['pressure']+"</p>";
											$("#weather_info").html(weather_info);
											$("#weather_info").removeClass('hide');
									 var pos = {
				              lat: response.coord.lat,
				              lng: response.coord.lon
				            };

											 var map = new google.maps.Map(document.getElementById('map'), {
							          zoom: 4,
							          center: pos
							        });
								   		 var marker = new google.maps.Marker({
							         position: pos,
							         map: map
							        });
								}
							});
					}
			});
	});
   function initMap()
   {
   	/* this line will check weather geolocation supported by your browser or not */
   	 if (navigator.geolocation)
   	  {
   	  	/*  get longitude and latitude of current location */
          navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };

/*  create google map at particular position */
	   		 var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 4,
          center: pos
        });
	   	/* set marker on google map*/
	   		 var marker = new google.maps.Marker({
         position: pos,
         map: map
        });
	   	/* display info on marker  when user  clicks */
	   		 var contentString="<address>Sunrise Business Park, SG Barve Rd & Road #16, Wagle Industrial Estate,, Thane West, Maharashtra 400604</address>";
	   		 var infowindow = new google.maps.InfoWindow({
			    content: contentString
			  });
	   		 marker.addListener('click', function() {
			    infowindow.open(map, marker);
			  });

        });
      }else{
      	$("#map").html("geolocation is not supported by your browser");
      }

   }

	</script>

</head>
<body>
	<div class="container">
			<div class="panel panel-success">
		 		 <div class="panel-heading">Weather</div>
				  <div class="panel-body">
						<form class="form-inline" id="weather_form" name="weather_form">

						  <label class="sr-only" for="city">City</label>
						  <input type="text" class="form-control" id="city" name="city" placeholder="City">

							 <label class="sr-only" for="zipcode">Zip Code</label>
						   <input type="text" class="form-control" id="zipcode"  name="zipcode" placeholder="ZipCode">

						  <button type="submit" class="btn btn-primary">Submit</button>
						</form>
						<br/>
						<br/>
							<!--   this div is used to display weather info  -->
						<div class="well well-lg hide" id="weather_info" >
						</div>
				  </div>
		</div>
		<!--   this div is used to display map  -->
		<div id="map"></div>
	</div>
	<!--    this js is required for use google map and api key must be created -->
	<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCbKiX2Qj1ZO4qNJLZtRE2p3uN51giHVxU&callback=initMap">
  </script>

</body>
</html>