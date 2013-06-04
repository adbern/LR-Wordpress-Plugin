var allOrganizations = [], followed = [], allTerms = [], query = "<?php echo empty($_GET['lr_resource'])?sanitize_lr($_GET['query'], ' '):sanitize_lr($_GET['lr_resource'], ''); ?>";
var temp = new mainViewModel([]), activeModalName = null, lastSearchCache = "";
var iframeHidden = true;
var tempBookmarksArray = [];


handleMainResourceModal(<?php echo empty($_GET['lr_resource']) ? 'false' : 'query'; ?>);


var qmarkUrl = qmarkUrl?qmarkUrl:'<?php echo plugins_url( "templates/images/qmark.png" , dirname(__FILE__) ) ?>';
temp.permalink = '<?php echo add_query_arg(array("lr_resource"=>"LRreplaceMe", 'query'=>false)); ?>';

for (var f in followed){
	temp.followers.push({name:followed[f], content:[]});
}

var handlePerfectSize = function(){};
var serviceHost = "<?php echo $host; ?>";
var initialGraphBuild = false;

totalSlice = 15;
newLoad = 15;


temp.handleStandardsClick = function(item, e){
		
	var baseEncoded = Base64.encode(item.title());

	window.location = '<?php echo add_query_arg(array("query"=> "LRreplaceMe", "standard"=> "LRstandardReplaceMe"), get_page_link( $options['results']));?>'.replace("LRreplaceMe", 
	encodeURIComponent(item.id())).replace("LRstandardReplaceMe", baseEncoded);	
	
	return true;
};

var standardCollapseAllAndOpen = function(e){
	
	console.log(e);
	if(!window.location.hash || e){
	
	$(".saveOpen").data("isOpen", true);
		$('.standard-plus').each(function(i, element){
			standardPlusCollapse({preventDefault:function(){}}, element);
		});	
	}
	
	if(window.location.hash){
	
		$(".saveOpen").data("isOpen", false);
		$(".standard-div").hide();
		
		if(window.location.hash.charAt(1) == 't'){
			var openTreeStateArr = parseInt(window.location.hash.slice(2,window.location.hash.length))-1;

			if(openTreeStateArr >= 0){
				var cacheStandardDiv = $($('.subjectTree')[openTreeStateArr]).parents('.standard-div');
				
				cacheStandardDiv.show();		
				cacheStandardDiv.siblings('.standard-plus').each(function(i, element){
				
					standardPlusCollapse({preventDefault:function(){}}, this);
				});	
			}
		}
		
		if(window.location.hash.charAt(1) == 's'){

			openTreeStateArr = window.location.hash?window.location.hash.slice(2,window.location.hash.length).split(',') : false;
			
			if(openTreeStateArr !== false){
				var tempPointer = temp.standards().children()[openTreeStateArr[1]];
				for(var i = 2; openTreeStateArr.length > i; i++){

					tempPointer.loadChildren();
					
					if(tempPointer.children){
						
						console.log(tempPointer.title(), openTreeStateArr[i-1], i-1);
						standardPlusCollapse({preventDefault:function(){}}, '[name="'+tempPointer.title()+'"]');
						tempPointer = tempPointer.children()[openTreeStateArr[i]];
					}
				}	
			}
		}
	}
};

var standardPlusCollapse = function(e, self){

	e.preventDefault();
	
	self = self ? self : this;

	var isOpen = $(self).siblings(".saveOpen").data("isOpen");
	if(isOpen == undefined){
		isOpen = true;
		$(self).siblings(".saveOpen").data("isOpen", true);
		$(self).siblings(".standard-div").show();
		
		//Arrow pointing right
		$(self).parent().children(".standard-plus").html("&#9660;");
		return;
	}
	else
		$(self).siblings(".saveOpen").data("isOpen", ! isOpen);
	
	if(isOpen){
		//Arrow pointing down
		$(self).parent().children(".standard-plus").html("&#9654;");
		$(self).siblings(".standard-div").hide();
	}
	else{
		//Arrow pointing right
		$(self).parent().children(".standard-plus").html("&#9660;");
		$(self).siblings(".standard-div").show();
	}
	
	return false;
};
$(document).on("click", ".standard-plus", standardPlusCollapse);

jQuery(document).ready(function($){

	///////////////////////////////////////////
	//An attempt to stop frame redirect      //
	///////////////////////////////////////////
	if(iframeHidden !== true){
		var prevent_bust = 0;
		var bustHappened = 0;
		
		window.onbeforeunload = function() { 

			prevent_bust++;
			bustHappened++;
			if(tInterval != "done" && bustHappened > 0)
				return "";
		};  
		
		var tInterval = setInterval(function() {  
			
		  lrConsole(prevent_bust);
		  if (prevent_bust > 0) {  
			prevent_bust -= 2  
			
			window.top.location = serviceHost + '/frame'  
		  }  
		}, 1);
		
		setTimeout(function(){
			
			clearInterval(tInterval);
			tInterval = "done";
		}, 3000);
	}
	/////////////////////////////////////////////
	
	$('input, textarea').placeholder();

	enableModal();
	
	$("#bookmark").click(function(){
		
		if("{{user}}".length < 1){
			alert("You must be logged in to bookmark resources.");
			return;
		}
		
		//add element to observable array, send a request via socketio, and remove current textarea value
		var paradata = genParadataDoc("{{user.jobTitle}}", "{{user._id}}", "bookmarked");

		//temp.currentObject().timeline.push(paradata);
		scrollbarFix();
		
		
		$.ajax({
			type: "POST",
			url: "/main",
			dataType: "json",
			jsonp: false,
			contentType: 'application/json',
			data: createJSON(paradata, "bookmark"),
			success: function(data){

				lrConsole("added");
				lrConsole("Response data: ", data);
				$("#bookmark").addClass("disabled");
				$("#bookmark").off();
			},
			error: function(error){
				console.error(error);
			}
		});
		
		
	});
	
	$(".icon-flag").click(function(){
						
		if("{{user}}".length < 1){
			alert("You must be logged in to flag resources.");
			return;
		}
		
		//add element to observable array, send a request via socketio, and remove current textarea value
		var paradata = genParadataDoc("{{user.jobTitle}}", "{{user._id}}","flagged");
		temp.currentObject().timeline.push(paradata);
		paradataStoreRequest(paradata);
		
		scrollbarFix();
		
	});
	
	$(".chatBox textarea").keyup(function(e){
		
		//Enter was pressed
		if(e.which == 13){
			
			if("{{user}}".length < 1){
				alert("You must be logged in to comment.");
				return;
			}

			//add element to observable array, send a request via socketio, and remove current textarea value
			var paradata = genParadataDoc("{{user.jobTitle}}", "{{user._id}}","commented", $(this).val().trim());

			temp.currentObject().timeline.push(paradata);
			scrollbarFix();
			
			paradataStoreRequest(paradata);

			$(this).val("");
		}
	});

	$(".resultModal").on("click", ".resultClick", function(e){

		e.preventDefault();
		e.stopPropagation();

		$("#visualBrowser").modal("hide");
		handleMainResourceModal($(this).attr("name"));
		lrConsole("show click");
	});

	$("table").on("click", ".author-timeline", function(evt){

		$(".author-timeline").not(this).popover('hide');
		$(this).popover('toggle');

		//Enable tooltips
		$(".bottomBar i").tooltip();

		//evt.stopPropagation();
	});
		
	var hidePopover = function(){

		$(".author-timeline").popover('hide');
	};
	
	ko.applyBindings(temp);
});

var handleOnclickUserBar = function(obj){
	
	var cacheObj = $(obj);
	var name = cacheObj.attr("name");
	var className = cacheObj.attr("class");

	if(className == "icon-star")
		self.followUser(name);
		
	else if(className == "icon-file"){
		
		
	}
	
	//Substr gets the number portion of "paradataX"
	var test = cacheObj.attr("name").substr(8, cacheObj.attr("name").length-8);
	lrConsole(test, self.currentObject().timeline()[test]);
	displayObjectData(self.getReversedTimeline()[test]);

};