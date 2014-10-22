/*!
 * Responder v1.1.0
 * www.atlanticbt.com
 *
 * Copyright (c) Atlantic BT
 */

/*
 * At a high level, Responder allows developers to register 
 * javascript functions to fire when certian conditions
 * associated with the browser width are met.
 *
 * Developers can register callback functions to media queries.
 * Some convenience methods are provided that construct these media queries.
 * On registration and when the window is resized each registered checkpoint 
 * will be evaluated. If the media query for a given checkpoint is evaluated 
 * to true then its corresponding callback function will be fired. Once a 
 * checkpoint's media query has evaluated to true it will not fire again 
 * until it has evaluated to false at least once.
 *
 * For this library to run properly it requires the following plugins:
 * -Modernizer (http://modernizr.com/)
 * -Respond (https://github.com/scottjehl/Respond)
 *
 * For this library to run properly the following meta tag 
 * must be present in the header:
 * <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1">
 * This meta tag will be added if it does not exist when this class is intantiated.
 *
 * Compatible with FF, Chrome, Opera, Safari, & IE6-9
 *
 * Authors        Erik Murphy, Mark Riggan
 *
 * @param {class} evaluateor		Class used to evaluate the media queries. Must have a function mq that takes a media query as a parameter.
 */
function Responder (evaluator){

	// stores media query checkpoints
	var checkpoints = [];

	/**
	 * Call the given function when the media query 
	 * evaluates to true.
	 *
	 * @param {string} query		A CSS3 media query.
	 * @param {function} callback	A callback function.
	 * @param {bool} evaluate		Evaluate the checkpoint on register?
	 */
	this.query = function(query, callback, evaluate) {
		registerCheckpoint(query, callback, evaluate);
	}
	
	/**
	 * Call the given function when the boundary 
	 * is reached from a higher value.
	 *
	 * @param {int} boundary		A pixel value for a window width boundary.
	 * @param {function} callback	A callback function.
	 * @param {bool} evaluate		Evaluate the checkpoint on register?
	 */
	this.top = function(boundary, callback, evaluate) {
		// convert to a media query
		var query = "(max-width: " + boundary + "px)";
		
		// register the checkpoint
		registerCheckpoint(query, callback, evaluate);
	}
	
	/**
	 * Call the given function when the boundary 
	 * is reached from a lower value.
	 *
	 * @param {int} boundary		A pixel value for a window width boundary.
	 * @param {function} callback	A callback function.
	 * @param {bool} evaluate		Evaluate the checkpoint on register?
	 */
	this.bottom = function(boundary, callback, evaluate) {
		// convert to a media query
		var query = "(min-width: " + boundary + "px)";
		
		// register the checkpoint
		registerCheckpoint(query, callback, evaluate);
	}
	
	/**
	 * Call the given function when the range boundary 
	 * is passed from the outside to the inside.
	 * The range is inclusive.
	 *
	 * @param {int} min				A pixel value for the lower boundary of the window width.
	 * @param {int} max				A pixel value for the upper boundary of the window width.
	 * @param {function} callback	A callback function.
	 * @param {bool} evaluate		Evaluate the checkpoint on register?
	 */
	this.rangeIn = function(min, max, callback, evaluate) {
		// convert to a media query
		var query = "(min-width: " + min + "px) and (max-width: " + max + "px)";
		
		// register the checkpoint
		registerCheckpoint(query, callback, evaluate);
	}
	
	/**
	 * Call the given function when the range boundary 
	 * is passed from the inside to the outside.
	 * The range is exclusive.
	 *
	 * @param {int} min				A pixel value for the lower boundary of the window width.
	 * @param {int} max				A pixel value for the upper boundary of the window width.
	 * @param {function} callback	A callback function.
	 * @param {bool} evaluate		Evaluate the checkpoint on register?
	 */
	this.rangeOut = function(min, max, callback, evaluate) {
		// Convert to a media query.
		// Decrement the min and increment the max to make the range exclusive.
		var query =  "(max-width: " + (min - 1) + "px), (min-width: " + (max + 1) + "px)";
		
		// register the checkpoint
		registerCheckpoint(query, callback, evaluate);
	}
	
	/**
	 * Register a media query as a checkpoint.
	 * If the checkpoint is meant to not be evaluated on register 
	 * then it will be set to inactive and evaluated so that
	 * it wont run but if it evaluates to false then it will be set to active 
	 * so that it will be evaluated on the next resize event.
	 *
	 * @param {string} query		A CSS3 media query.
	 * @param {function} callback	A callback function.
	 * @param {bool} evaluate		Evaluate the checkpoint on register?
	 */
	function registerCheckpoint(query, callback, evaluate) {
		// default to false
		evaluate = typeof evaluate !== 'undefined' ? evaluate : true;
		
		// Create the checkpoint.
		var checkpoint = { query: query, callback: callback, active: evaluate };
		
		// if the checkpoint should be evaluated then evaluate it
		evaluateCheckpoint(checkpoint);
		
		// register the checkpoint
		checkpoints.push(checkpoint);
	}
	
	/**
	 * Evaluate each checkpoint and fire their corresponding 
	 * callback if they evaluate to true.
	 */
	function evaluateCheckpoints() {
		for (index in checkpoints) {
			evaluateCheckpoint(checkpoints[index]);
		}
	}
	
	/**
	 * Evaluate a checkpoint and fire its corresponding callback 
	 * function if it evaluates to true. If the checkpoint is active 
	 * and the query evalutes to true then the checkpoint, 
	 * which is passed by reference, will be set to inactive.
	 *
	 * @param {object} checkpoint	Contains a CSS3 media query and a callback function.
	 */
	function evaluateCheckpoint(checkpoint) {
		// evaluate the query
		var queryResult = evaluator.mq(checkpoint.query);
			
		// if the checkpoint is not active
		if (!checkpoint.active) {
			// if the query evaluated to false 
			// then the checkpoint will be set to active
			checkpoint.active = !queryResult;
			
		// else if it is active
		// and the query evaluated to true
		} else if (queryResult) {
			// then call the callback function
			checkpoint.callback.call();
			
			// set that it was fired
			checkpoint.active = false;
		}
	}
	
	// add the event listener to the window resize
	window.onresize = evaluateCheckpoints;
	
	/**
	 * Verify that the viewport meta is pressent.
	 */
	function verifyViewportMeta() {
		var viewportMetaExists = false;
		var metas = document.getElementsByTagName('meta');
		for (index in metas) {
			var meta = metas[index];
			if (meta.name == "viewport") {
				viewportMetaExists = true;
			}
		}
		if (!viewportMetaExists) {
			var meta = document.createElement('meta');
			meta.setAttribute('name', 'viewport');
			meta.setAttribute('content', 'width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1');
			document.getElementsByTagName('head')[0].appendChild(meta);
		}
	}
	verifyViewportMeta();
}

 /*
  * FakeRespondJS is used to mimic the media query evaluation and 
  * browser width calculation performed by Respond.js.
  *
  * The assumption is that as Respond.js will be used to enable media queries 
  * in non-HTML5 browsers. This class performs the browser width calculation 
  * the same way that Respond.js does so that the CSS and Responder stay in sync.
  * This class also evaluates basic min/max media queries agains the calculated
  * browser width.
  *
  * Media queries are always assumed to have an AND if both a min and max are specified.
  * This is because that is the way respond.js works.
  * 
  * Credit		https://github.com/scottjehl/Respond
  */
 function FakeRespondJS () {
 	
	// value of one em stored for future use
	var eminpx;
	
	/**
	 * Calculate the browser width as respond.js does.
	 */
	this.respondWidth = function()
	{
		var width = "clientWidth";
		var docElemProp	= document.documentElement[ width ];
		return document.compatMode === "CSS1Compat" && docElemProp || document.body[ width ] || docElemProp;
	}
	
	/**
	 * Evaluate the given query the same way respond.js does.
	 *
	 * @param {string} query		A CSS3 media query.
	 */
	this.mq = function(query) {
		var min = query.match( /\(min\-width:[\s]*([\s]*[0-9\.]+)(px|em)[\s]*\)/ ) && parseFloat( RegExp.$1 ) + ( RegExp.$2 || "" );
		var max = query.match( /\(max\-width:[\s]*([\s]*[0-9\.]+)(px|em)[\s]*\)/ ) && parseFloat( RegExp.$1 ) + ( RegExp.$2 || "" );
		var minnull = min === null;
		var maxnull = max === null;
		var currWidth = this.respondWidth();
		if( !!min ){
			min = parseFloat( min ) * ( min.indexOf( 'em' ) > -1 ? ( eminpx || this.getEmValue() ) : 1 );
		}
		if( !!max ){
			max = parseFloat( max ) * ( max.indexOf( 'em' ) > -1 ? ( eminpx || this.getEmValue() ) : 1 );
		}
		return !(query.indexOf("(") > -1) || ( !minnull || !maxnull ) && ( minnull || currWidth >= min ) && ( maxnull || currWidth <= max );
	}
	
	/**
	 * Returns the value of 1em in pixels.
	 */
	this.getEmValue = function() {
		var ret;
		var div = doc.createElement('div');
		var body = doc.body;
		var fakeUsed = false;

		div.style.cssText = "position:absolute;font-size:1em;width:1em";

		if( !body ){
			body = fakeUsed = doc.createElement( "body" );
			body.style.background = "none";
		}

		body.appendChild( div );

		docElem.insertBefore( body, docElem.firstChild );

		ret = div.offsetWidth;

		if( fakeUsed ){
			docElem.removeChild( body );
		}
		else {
			body.removeChild( div );
		}

		//also update eminpx before returning
		ret = eminpx = parseFloat(ret);

		return ret;
	}
 }
 
/*
 * For HTML5 browsers the checkpoint media queries are evaluated by using 
 * Modernizer.mq(). For no HTML5 browsers the expectation is that Respond.js
 * is being used. Because of this assumption the class FakeRespondJS is used 
 * to evaluate the checkpoint media queries.
 */
if (Modernizr) {
	var evaluator;
	// if browser supports media queries then use modernizer
	if (Modernizr.mq('(min-width: 0px)')) {
		evaluator = Modernizr;
	// if not then evaluate the queries like respond
	} else {
		evaluator = new FakeRespondJS();
	}

	// Create the instance of responder
	var Responder = new Responder(evaluator);
}