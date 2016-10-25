(function (lib, img, cjs, ss) {

var p; // shortcut to reference prototypes
lib.webFontTxtInst = {};
var loadedTypekitCount = 0;
var loadedGoogleCount = 0;
var gFontsUpdateCacheList = [];
var tFontsUpdateCacheList = [];

// library properties:
lib.properties = {
	width: 480,
	height: 270,
	fps: 20,
	color: "#FFFFFF",
	opacity: 1.00,
	webfonts: {},
	manifest: []
};



lib.ssMetadata = [];



lib.updateListCache = function (cacheList) {
	for(var i = 0; i < cacheList.length; i++) {
		if(cacheList[i].cacheCanvas)
			cacheList[i].updateCache();
	}
};

lib.addElementsToCache = function (textInst, cacheList) {
	var cur = textInst;
	while(cur != exportRoot) {
		if(cacheList.indexOf(cur) != -1)
			break;
		cur = cur.parent;
	}
	if(cur != exportRoot) {	//we have found an element in the list
		var cur2 = textInst;
		var index = cacheList.indexOf(cur);
		while(cur2 != cur) { //insert all it's children just before it
			cacheList.splice(index, 0, cur2);
			cur2 = cur2.parent;
			index++;
		}
	}
	else {	//append element and it's parents in the array
		cur = textInst;
		while(cur != exportRoot) {
			cacheList.push(cur);
			cur = cur.parent;
		}
	}
};

lib.gfontAvailable = function(family, totalGoogleCount) {
	lib.properties.webfonts[family] = true;
	var txtInst = lib.webFontTxtInst && lib.webFontTxtInst[family] || [];
	for(var f = 0; f < txtInst.length; ++f)
		lib.addElementsToCache(txtInst[f], gFontsUpdateCacheList);

	loadedGoogleCount++;
	if(loadedGoogleCount == totalGoogleCount) {
		lib.updateListCache(gFontsUpdateCacheList);
	}
};

lib.tfontAvailable = function(family, totalTypekitCount) {
	lib.properties.webfonts[family] = true;
	var txtInst = lib.webFontTxtInst && lib.webFontTxtInst[family] || [];
	for(var f = 0; f < txtInst.length; ++f)
		lib.addElementsToCache(txtInst[f], tFontsUpdateCacheList);

	loadedTypekitCount++;
	if(loadedTypekitCount == totalTypekitCount) {
		lib.updateListCache(tFontsUpdateCacheList);
	}
};
// symbols:



(lib.oxy_zahnradai = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 2
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#CE00A2").s().p("AgwNhQgbAAgTgTQgUgUAAgbIAAhDQiRgXh+hNIhIBTQgTAVgdADQgeADgWgTIhShCQgXgTgCgdQgDgcATgVIBOhaQhDhTgphjIhrAZQgbAHgagOQgZgPgIgbIgdhjQgIgbAOgYQAOgXAcgIIB4gcIgCgvQAAhMARhPIhpgnQgbgLgNgZQgMgaAJgaIAhheQAJgaAZgMQAagLAaAKIBzArQAzhXBLhGIg8hWQgQgXAEgcQAEgbAXgPIBRg6QAWgPAbAFQAcAGAQAWIA9BYQBeguBrgQIAAhUQAAgcAUgSQATgUAbAAIBhAAQAbAAAUAUQATASAAAcIAABUQCLAYB4BIIBUhgQATgWAdgDQAdgCAXASIBSBDQAXASACAdQADAcgTAWIhYBmQBBBNAqBgIB1gcQAcgHAaAOQAZAPAIAbIAdBiQAIAbgOAZQgOAYgcAGIh+AfQACAiAAAaQAABGgNBFIBrApQAbAJANAaQAMAagJAaIghBfQgJAagZALQgaALgagKIhvgrQgzBchKBIIA3BPQAQAXgEAcQgEAbgXAPIhRA5QgWAQgbgGQgcgEgQgYIg2hNQhjAyhtASIAABEQAAAbgTAUQgUATgbAAgAk1koQiACAAAC0QAAC1CACAQCBCBC0AAQC0AACBiBQCAiAAAi1QAAi0iAiAQiBiAi0gBQi0ABiBCAg");
	this.shape.setTransform(90.4,86.6);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,181,173.1);


(lib.char_y = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#666666").s().p("Ah0EjIBPiyIipmTIBOAAICAE6ICEk6IBLAAIj2JFg");
	this.shape.setTransform(25.1,67);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,50.3,98.8);


(lib.char_x = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#666666").s().p("AB1DUIh2ijIh6CjIhTAAICljUIikjTIBaAAIB2ChIB5ihIBTAAIinDRICnDWg");
	this.shape.setTransform(25.1,59.1);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,50.3,98.8);


(lib.char_r = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#666666").s().p("AiDDUIAAmnIBHAAIAAA/QArgjAfgOQAggOAiAAIAbABIAZADIAABKIgEAAQgQgEgOgBQgQgCgUAAQghAAgdAPQgfAOgdAXIAAEsg");
	this.shape.setTransform(22.3,59.1);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,37.5,98.8);


(lib.char_o = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#666666").s().p("AiOCjQg1g7AAhoQAAhmA1g9QA1g8BZAAQBZAAA1A8QA2A9AABmQAABog2A7Qg1A9hZAAQhZAAg1g9gAhZh5QggAoAABRQAABPAgAqQAhApA4AAQA5AAAhgoQAggqAAhQQAAhRgggoQghgpg5AAQg5AAggApg");
	this.shape.setTransform(25.7,59.2);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,51.5,98.8);


(lib.char_m = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#666666").s().p("ADuDaIAAjwQAAgcgCgZQgDgZgIgQQgIgRgRgHQgRgJgeAAQgeAAgeAPQgfAPgeAYIACAUIABAXIAAEOIhFAAIAAjwQgBgdgCgZQgCgZgJgQQgIgQgRgHQgQgJgfAAQgdAAgdAOQgeAPgeAXIAAE7IhHAAIAAmnIBHAAIAAAvQAigcAhgQQAigPAlAAQAsAAAfASQAbATAQAgQArglAjgQQAkgQApAAQBGAAAhArQAiApAABNIAAESg");
	this.shape.setTransform(40.1,58.6);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,80.1,98.8);


(lib.char_a = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.shape = new cjs.Shape();
	this.shape.graphics.f("#666666").s().p("AiLC5QgogmAAg7QAAgxAVgdQAUgcAngRQAngSA2gGQA1gGA+gDIAAgLQAAgYgIgQQgJgQgQgJQgPgJgWgDQgVgDgVAAQgcAAgjAHQgjAIglAOIgDAAIAAhJQAVgGAngHQAogGAmAAQArAAAiAHQAhAHAYASQAYASANAcQAMAbAAAqIAAEeIhHAAIAAgtIgZASQgRAMgPAGQgSAJgXAGQgVAGggAAQg5AAgogmgAAggEQgqAEgaAFQgfAJgTASQgTATAAAgQAAAkAWATQAWASAtAAQAkAAAfgOQAfgPAbgUIAAh1QghACgsAEg");
	this.shape.setTransform(24,59.2);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(0,0,51.1,98.8);


// stage content:
(lib.oxymora_zahnradshort = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 2
	this.instance = new lib.oxy_zahnradai("synched",0);
	this.instance.parent = this;
	this.instance.setTransform(93.3,136,0.437,0.436,90,0,0,91.6,87.7);

	this.timeline.addTween(cjs.Tween.get(this.instance).to({rotation:180,x:93.4,y:135.9},20).to({rotation:270,y:136},20).to({_off:true},1).wait(1));

	// Layer 4
	this.instance_1 = new lib.char_r("synched",0);
	this.instance_1.parent = this;
	this.instance_1.setTransform(366.4,128.5,1,1,0,0,0,18.7,49.4);

	this.instance_2 = new lib.char_a("synched",0);
	this.instance_2.parent = this;
	this.instance_2.setTransform(405.2,128.6,1,1,0,0,0,25.5,49.4);

	this.shape = new cjs.Shape();
	this.shape.graphics.f("#666666").s().p("AiOCjQg1g7AAhoQAAhnA1g7QA1g9BZAAQBZAAA1A9QA2A7AABnQAABog2A7Qg1A9hZAAQhZAAg1g9gAhZh5QggAoAABRQAABQAgApQAhApA4AAQA5AAAhgpQAggpAAhQQAAhRgggoQghgog5AAQg5AAggAog");
	this.shape.setTransform(326.1,137.6);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#666666").s().p("ADuDaIAAjwQAAgcgCgZQgDgagIgPQgIgRgRgIQgRgIgeAAQgeAAgeAPQgfAPgeAXIACAVIABAWIAAEPIhFAAIAAjwQgBgcgCgZQgCgagJgQQgIgPgRgJQgQgIgfAAQgdAAgdAOQgeAPgdAWIAAE8IhIAAIAAmoIBIAAIAAAwQAhgcAhgPQAigQAlAAQAsAAAfASQAbATAQAgQArglAjgQQAkgQApAAQBGAAAhArQAiAqAABMIAAESg");
	this.shape_1.setTransform(264.7,137);

	this.shape_2 = new cjs.Shape();
	this.shape_2.graphics.f("#666666").s().p("Ah0EjIBPiyIipmTIBOAAICAE6ICEk6IBLAAIj2JFg");
	this.shape_2.setTransform(203.5,145.4);

	this.shape_3 = new cjs.Shape();
	this.shape_3.graphics.f("#666666").s().p("AB1DUIh3ijIh5CjIhUAAICnjUIiljTIBaAAIB2ChIB4ihIBVAAIioDRICoDWg");
	this.shape_3.setTransform(157.5,137.6);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.instance_2},{t:this.instance_1}]}).to({state:[{t:this.shape_3},{t:this.shape_2},{t:this.shape_1},{t:this.shape},{t:this.instance_2},{t:this.instance_1}]},19).to({state:[{t:this.shape_3},{t:this.shape_2},{t:this.shape_1},{t:this.shape},{t:this.instance_2},{t:this.instance_1}]},22).wait(1));

	// char_x
	this.instance_3 = new lib.char_x("synched",0);
	this.instance_3.parent = this;
	this.instance_3.setTransform(157.6,127.9,1,1,0,0,0,25.2,49.4);

	this.timeline.addTween(cjs.Tween.get(this.instance_3).wait(42));

	// char_y
	this.instance_4 = new lib.char_y("synched",0);
	this.instance_4.parent = this;
	this.instance_4.setTransform(203.7,127.9,1,1,0,0,0,25.2,49.4);

	this.timeline.addTween(cjs.Tween.get(this.instance_4).wait(42));

	// char_m
	this.instance_5 = new lib.char_m("synched",0);
	this.instance_5.parent = this;
	this.instance_5.setTransform(264.6,127.9,1,1,0,0,0,40,49.4);

	this.timeline.addTween(cjs.Tween.get(this.instance_5).wait(42));

	// char_o
	this.instance_6 = new lib.char_o("synched",0);
	this.instance_6.parent = this;
	this.instance_6.setTransform(326.2,127.9,1,1,0,0,0,25.8,49.4);

	this.timeline.addTween(cjs.Tween.get(this.instance_6).wait(42));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(296.1,213.5,374.7,99.5);

})(lib = lib||{}, images = images||{}, createjs = createjs||{}, ss = ss||{});
var lib, images, createjs, ss;
