const ctx=document.getElementById('myChart').getContext("2d");

let gradient = ctx.createLinearGradient(0,0,0,750);
gradient.addColorStop(0,'lime');
gradient.addColorStop(0.9,'black');

let gradient_entry = ctx.createLinearGradient(0,0,0,750);
gradient_entry.addColorStop(0,'rgb(65, 186, 242)');
gradient_entry.addColorStop(0.9,'black');


const labels = dates;

const data={
	labels,
	datasets:[
	{
		data:entry_data,
		label:"visits",
		fill:true,
		backgroundColor: gradient_entry,
		bordercolor:"#fff",
		pointBackgroundColor:'rgb(189,195,199)',
		tension:0.1,
	},
	{
		data:datas,
		label:"Time Spent",
		fill:true,
		backgroundColor: gradient,
		bordercolor:"#fff",
		pointBackgroundColor:'rgb(189,195,199)',
		tension:0.1,
	}
]

}

const config = {
	type:"line",
	data:data,
	scaleLineColor: "red",
	options:{
		radius:5,
		hitRadius:30,
		hoverRadius:10,
		responsive: true,
		scales:{
			x:{
				
			grid:{
					display:false,
					borderColor:'white'
				},
			},

			y:{
				grid:{
					display:false,
					borderColor:'white'
				},
				ticks:{
					callback: function(value){
						return value //+"min"//;
					},
				},

			},
		},
	},
};

const mychart = new Chart(ctx,config)
