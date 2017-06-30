(function($) {
	var settings, dount_back, layer_front, layer_back, layer_ui, layer_tooltip, tooltip, save_button;
	var width_stage, height_stage;

	$.fn.dountGraphic = function(options){

		var canvas = this;
		settings = $.extend({
			width: $(this).width(),
			height: $(this).width(),
			full: 0,
			total: 0,
			color:'#000',
			title: '',
			radius: 120,
			labels: ['','']
		}, options);
		
		canvas.addClass('dountGraphic');

		var stage =  new Kinetic.Stage({
			x:0,
			y:0,
			width: settings.width,
			height: settings.height,
			container: canvas.attr('id'),
		});

		width_stage = stage.getWidth();
		height_stage = stage.getHeight();

		layer_front =  new Kinetic.Layer();
		layer_back =  new Kinetic.Layer();
		layer_ui =  new Kinetic.Layer();
		
		stage.add(new Kinetic.Layer({
			x:0,
			y:0,
			width: width_stage ,
			height: height_stage,
			fill:'#ffffff'
		}));
		
		stage.add(layer_back);
		stage.add(layer_front);
		stage.add(layer_ui);



		draw_title(settings.title);
		draw_labels(stage);
		draw_save(stage);
		draw_tootip(stage, settings.full, settings.total);
		


		var full = value2percent(settings.full, settings.full);
		var total = value2percent(settings.full, settings.total);
		var diff = value2percent(settings.full, settings.full - settings.total);

		if(diff > 0){
			draw_pie(((width_stage / 2) - (settings.radius)), total, '#4897F1', 0.5, layer_front);
			draw_dount(((width_stage / 2) - (settings.radius) + 25), 0, '#ff0000', 0.9,layer_back);
		}else{
			draw_pie(((width_stage / 2) - (settings.radius)), full,settings.color, 0.5, layer_front);
			draw_dount(((width_stage / 2) - (settings.radius) + 25), Math.abs(diff), '#306699', 0.9,layer_back);
		}
		return this;
	}

	function draw_tootip(stage, full, total){

		var tooltip = new Kinetic.Group({
			visible:false
		});

		tooltip.full = full;
		tooltip.total = total;

		var tooltip_text = new Kinetic.Text({
			fontSize: 12,
			fontFamily: '"Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif',
			fill: '#274b6d',
			padding: 5
		});

		var tooltip_rect = new Kinetic.Rect({
			width: 200,
			height: 50,
			stroke: settings.color,
			strokeWidth: 1,
			fill: 'white',
			cornerRadius: 2
		});

		tooltip.add(tooltip_rect);
		tooltip.add(tooltip_text);



		var layer_tooltip =  new Kinetic.Layer({name:'tooltip'});
		layer_tooltip.add(tooltip);
		stage.add(layer_tooltip);
	}

	function draw_save(stage){

		var save_button = new Kinetic.Group({
			x:width_stage - 30,
			y:20
		});

		var y = 0;
		for(var i = 0; i < 3; i++){
			var rec = new Kinetic.Rect({
				y: y,
				width:20,
				height:3,
				fill: '#ccc'
			});	
			y+=5;
			save_button.add(rec);
		};
		
		save_button.on('click', function(){
			var cs = new CanvasSaver(site_url+'dashboard/download_pie_graphic');
			
			 stage.toDataURL({
			 mimeType:'image/jpeg',
	         callback: function(dataUrl) {
	         		cs.savePNG(dataUrl)
          		}
        	});
		});

		layer_ui.add(save_button);
		layer_ui.draw();
	}	

	function CanvasSaver(url) {
	  this.url = url;
	  this.savePNG = function(dataurl, fname) {
	  if(!url) return;
	    fname = fname || 'picture';

	    var data = dataurl;
	    data = data.substr(data.indexOf(',') + 1).toString();
	    var dataInput = document.createElement("input") ;
	    dataInput.setAttribute("name", 'imgdata') ;
	    dataInput.setAttribute("value", data);

	    var nameInput = document.createElement("input") ;
	    nameInput.setAttribute("name", 'name') ;
	    nameInput.setAttribute("value", fname + '.jpeg');

	    var myForm = document.createElement("form");
	    myForm.method = 'post';
	    myForm.action = url;
	    myForm.appendChild(dataInput);
	    myForm.appendChild(nameInput);

	    document.body.appendChild(myForm) ;
	    myForm.submit() ;
	    document.body.removeChild(myForm) ;
	  };
	}

	function draw_title(text){
		var title = new Kinetic.Text({
		  x: 0,
		  y: 0,
		  width: width_stage,
		  text: text,
		  fontSize: 16,
		  fontFamily: '"Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif',
		  fill: '#274b6d',
		  padding: 20,
		  align: 'center'
		});

		layer_ui.add(title);
		layer_ui.draw();
	}

	function draw_labels(stage){
		var posy = 25;
		var op = 0.7;
		var color = settings.color;
		$.each(settings.labels, function(index, val) {


			var group_label = new Kinetic.Group({
				x: 30,
				y: height_stage - posy,
			});

			var rect = new Kinetic.Rect({
				x:0,
				y:0,
				width:20,
				height:20,
				fill: color,
				opacity: op
			})
			var label = new Kinetic.Text({
				x:5,
				y:-15,
				text: val,
				fontSize: 12,
				fontFamily: '"Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif',
				fill: '#274b6d',
				padding: 20,
			});

			group_label.add(rect);
			group_label.add(label);
			layer_ui.add(group_label); 
			posy+=25;
			op = (op == 0.5) ? 0.7 : 0.5;
			color = (color = settings.color) ? '#ccc' : settings.color ;
		});
		
		layer_ui.draw();
	}

	function draw_markers_dount(stage){
		

		var lineV= new Kinetic.Line({
			points: [0, 20,0, 40],
			stroke: '#CCC',
			strokeWidth: 2,
			lineCap: 'round',
			lineJoin: 'round'
		});

		var lineH = new Kinetic.Line({
			points: [20, 0, 40, 0],
			stroke: '#CCC',
			strokeWidth: 2,
			lineCap: 'round',
			lineJoin: 'round'
		});

		var text = new Kinetic.Text({
			fontSize: 12,
			fontFamily: '"Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif',
			fill: '#274b6d',
		});

		var labelUp = new Kinetic.Group({x: width_stage / 2, y:64});
		var labelDown = new Kinetic.Group({x: width_stage / 2, y:(height_stage)-(settings.radius+3)});
		var labelLeft = new Kinetic.Group({x: 64, y: height_stage / 2});
		var labelRigth = new Kinetic.Group({x: (width_stage)-(settings.radius+3) , y: height_stage / 2});
		
		labelUp.add(lineV.clone())
		labelUp.add(text.clone({
			x: -3,
			text:'0%'
		}));
		
		labelDown.add(lineV.clone())
		labelDown.add(text.clone({
			x: -6,
			y: 50,
			text:'50%'
		}));
		
		labelLeft.add(lineH.clone());
		labelLeft.add(text.clone({
			x:-8,
			y:-5,
			text:'75%'
		}));

		labelRigth.add(lineH.clone());
		labelRigth.add(text.clone({
			x:45,
			y:-5,
			text:'25%'
		}));


		layer_ui.add(labelUp); 
		layer_ui.add(labelDown); 
		layer_ui.add(labelLeft); 
		layer_ui.add(labelRigth); 
		layer_ui.draw();
	}

	function draw_circle(rad){
		var cirlce  = new Kinetic.Shape({
			x: width_stage / 2,
			y: height_stage / 2,
			stroke: '#CCC',
			strokeWidth: 20,
			 drawFunc: function(ctx) {
		        var radius = rad;
		        var startAngle = 0 * Math.PI;
		        var endAngle = 2 * Math.PI;
		        ctx.beginPath();
		        ctx.arc(0, 0, radius, startAngle, endAngle, false);
		        ctx.fillStrokeShape(this);
		    },
		});

		return cirlce;
	}

	function draw_base_pie(rad){
		var base_pie = new Kinetic.Circle({
			x: width_stage / 2,
			y: height_stage / 2,
			radius: rad,
			fill: '#CCC'
		});

		return base_pie;
	}



	function draw_pie(rad, p, color, op,layer){
		p = (p > 0) ? (percent2Radiant(p) + 1.5): 1.5;
		var dount_front = new Kinetic.Shape({
			x: width_stage / 2,
			y: height_stage / 2,
			stroke: '#0D233A',
			strokeWidth: 0,
			fill: '#0D233A',
			 drawFunc: function(ctx) {
		        var radius =  rad + 10;
		        var startAngle = 1.5 * Math.PI;
		        var endAngle = p * Math.PI;
		        ctx.beginPath();
		        ctx.moveTo(0,0);
		        ctx.arc(0, 0, radius, startAngle, endAngle, false);
		        ctx.fillStrokeShape(this);
		    },
		});
		dount_front.on('mouseover', MouseOverEventHandler);
		dount_front.on('mouseout', MouseOutEventHandler);
		
		var base_pie = draw_base_pie(rad + 10);

		base_pie.on('mouseover', MouseOverEventHandler);
		base_pie.on('mouseout', MouseOutEventHandler);
		
		layer.add(base_pie)

		layer.add(dount_front);
		layer.draw();
	}

	function draw_dount(rad, p, color, op,layer){
		p = (p > 0) ? (percent2Radiant(p) + 1.5): 1.5;
		var dount_front = new Kinetic.Shape({
			x: width_stage / 2,
			y: height_stage / 2,
			stroke: '#A40800',
			strokeWidth: 20,
			 drawFunc: function(ctx) {
		        var radius = rad;
		        var startAngle = 1.5 * Math.PI;
		        var endAngle = p * Math.PI;
		        ctx.beginPath();
		        ctx.arc(0, 0, radius, startAngle, endAngle, false);
		        ctx.fillStrokeShape(this);
		    },
		});
		dount_front.on('mouseover', MouseOverEventHandler);
		dount_front.on('mouseout', MouseOutEventHandler);
		dount_front.opacity(op);
		
		var base_c = draw_circle(rad);
		if(layer == layer_back)
			base_c.opacity(0.2);

		//dibujar guias
		draw_markers_dount();

		layer.add(base_c)
		layer.add(dount_front);
		layer.draw();
	}

	function percent2Radiant(p){
		return (p * 2) / 100;
	}

	function value2percent(full, value){
		return (value * 100) / full;
	}

	function MouseOverEventHandler(event){
		document.body.style.cursor = 'pointer';
		var stage = this.parent.parent;
		var layer_tooltip =stage.getLayers()[4];
		var tooltip = layer_tooltip.getChildren()[0];
		var mousePos = stage.getPointerPosition();

		var items = [];

		var full = tooltip.full;
		var total = tooltip.total;
		var diferencia = (Math.floor(tooltip.full - tooltip.total));

		var series = [['Presupuestado', full],['Liquidado', total],['Diferencia', diferencia]];

		var diff = 5
		if(mousePos.x > (stage.getWidth() / 2))
			var diff = -200

		tooltip.position({
			x: mousePos.x + diff,
			y: mousePos.y + 5
		});


		var y = 0;
		$.each(series, function(index, val) {
		 	var tooltip_title = new Kinetic.Text({
				x:0,
				y: y,
				fontSize: 12,
				fontFamily: '"Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif',
				fontStyle: 'bold',
				fill: '#274b6d',
				padding: 5,
				text: val[0] + ': ',
				width: 110
			});

			var tooltip_text = new Kinetic.Text({
				x: 95,
				y: y,
				fontSize: 12,
				fontFamily: '"Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif',
				fill: '#274b6d',
				padding: 5,
				text: formatMoney(val[1], 0,'.',',')
			});

			 y+=15

			items.push(tooltip_title);
			items.push(tooltip_text);

			tooltip.add(tooltip_title);
			tooltip.add(tooltip_text);
		});
		this.opacity(0.8)
		stage.draw()
		tooltip.show();
		this.parent.draw();
		layer_tooltip.draw();

		tooltip.items = items;
	}
	function MouseOutEventHandler(event){
		
		var stage = this.parent.parent;
		var layer_tooltip =stage.getLayers()[4];
		var tooltip = layer_tooltip.getChildren()[0];
		
		tooltip.hide();
		$.each(tooltip.items, function(index, val) {
			 val.remove();
		});
		this.opacity(1)
		stage.draw()
		document.body.style.cursor = '';
		layer_tooltip.draw();
		this.parent.draw();
	}

	var formatMoney = function(val ,c, d, t){
	var n = val, 
	    c = isNaN(c = Math.abs(c)) ? 2 : c, 
	    d = d == undefined ? "." : d, 
	    t = t == undefined ? "," : t, 
	    s = n < 0 ? "-" : "", 
	    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
	    j = (j = i.length) > 3 ? j % 3 : 0;
	   return '$' + s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
	 };
})(jQuery);