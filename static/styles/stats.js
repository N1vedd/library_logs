const ctx=document.getElementById('myChart').getContext("2d");

let gradient = ctx.createLinearGradient(0,0,0,400);
gradient.addColorStop(0,'rgba(19, 89, 16,0.77)');
gradient.addColorStop(1,'rgba(19, 89, 16,0.8)');


const labels = dates;

const data={
	labels,
	datasets:[{
		data:datas,
		label:"Time Spent",
		fill:true,
		backgroundColor: gradient,
		bordercolor:"#fff",
		pointBackgroundColor:'rgb(189,195,199)',
		tension:0.1,
	}]
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
						return value +"min";
					},
				},

			},
		},
	},
};

const mychart = new Chart(ctx,config)
