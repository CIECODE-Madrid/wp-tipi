

(function($) {
	
var res; 

	var request = $.ajax({
      url: 'http://tipiciudadano.es/api/v1/stats/'+object.filter+"/"+object.dict,
      type:"GET",
      contentType: "application/json",
      
      
    });

	request.done(function(dataJson) {
		data =[]

		if (object.filter=="bydeputies"){
			text= dataJson[0]['deputies'][0]._id +": "+dataJson[0]['deputies'][0].count+'<br>';
			text += dataJson[0]['deputies'][1]._id +": "+dataJson[0]['deputies'][1].count+'<br>';
			text += dataJson[0]['deputies'][2]._id +": "+dataJson[0]['deputies'][2].count+'<br>';
			
			$("#widget_tipi").html(text);
		}else if(object.filter=="bygroups"){
			console.log(dataJson)
			text= dataJson[0]['groups'][0]._id +": "+dataJson[0]['groups'][0].count+'<br>';
			text += dataJson[0]['groups'][1]._id +": "+dataJson[0]['groups'][1].count+'<br>';
			text += dataJson[0]['groups'][2]._id +": "+dataJson[0]['groups'][2].count+'<br>';
			
			$("#widget_tipi").html(text);
		}


		


		

	});



})( jQuery );