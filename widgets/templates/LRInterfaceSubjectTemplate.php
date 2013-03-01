<script type="text/html" id="subject-template">
		<div>
			<!-- ko if: children.length > 0 -->
				<a href="#" data-bind="text: '[ + ] '" class="standard-plus"></a>
				<a href="#" data-bind="text: name" class="standard-link"></a>
				<!--<span class="childrenResourceNumber" data-bind="text: ' ( ' + children.length + ' )' "></span>--><br/><br/>
				<div class="saveOpen"></div>
				<div style="padding-left: 40px;" data-bind="'template':{'name': 'subject-template', 'foreach': children}, 'attr':{'class':'standard-div standard-' + children.length}"></div>
			<!-- /ko -->
			
			<!-- ko if: children.length == 0 -->
				<a href="#" data-bind="text: name" class="standard-link"></a>
				<!--<span class="childrenResourceNumber" > ( 0 )</span>--><br/><br/>
				<div class="noChildren"></div>
			<!-- /ko -->
		</div>
</script>
<div style="clear:both; overflow:hidden; margin: 0 auto; width: 100%;">
	<div id="subject-map-left" style="width: 40%; float: left; padding-left:10%;" data-bind="'template':{'name': 'subject-template', 'foreach': children.slice(0, children.length * .5)}"></div>
	<div id="subject-map-right" style="width: 40%; float: right;" data-bind="'template':{'name': 'subject-template', 'foreach': children.slice(children.length * .5, children.length)}"></div>
</div>
<div style="clear:both; overflow:hidden; margin-bottom: 20px; width: 100%;"></div>
<script type="text/javascript">
	
	
	var serviceHost = "<?php echo $host; ?>";
	var permalink = '<?php echo add_query_arg(array("lr_resource"=>"LRreplaceMe", 'query'=>false)); ?>';
	$(function(){
		$.getJSON(serviceHost + "/data/sitemap", function(data){

			self.children = data.children;
			ko.applyBindings(self, $("#subject-map-left")[0]);
			ko.applyBindings(self, $("#subject-map-right")[0]);

			$(".standard-div").hide();
			$(".standard-link").click(function(e){
			
				e.preventDefault();
				e.stopPropagation();
				
				window.location.href = '<?php echo add_query_arg("query", "LRreplaceMe", get_page_link( $instance['results']));?>'.replace("LRreplaceMe", encodeURIComponent($(this).text()));
					return false;
			});
			
			$(".standard-plus").click(function(e){
				
				e.preventDefault();
				e.stopPropagation();
				
				var isOpen = $(this).siblings(".saveOpen").data("isOpen");
				if(isOpen == undefined){
					isOpen = true;
					$(this).siblings(".saveOpen").data("isOpen", true);
					$(this).siblings(".standard-div").show();
					
					$(this).parent().children(".standard-plus").text("[ - ] ");
					return;
				}
				else
					$(this).siblings(".saveOpen").data("isOpen", ! isOpen);
				
				if(isOpen){
					$(this).parent().children(".standard-plus").text("[ + ] ");
					$(this).siblings(".standard-div").hide();
				}
				else{
					$(this).parent().children(".standard-plus").text("[ - ] ");
					$(this).siblings(".standard-div").show();
				}
				
				return false;
			});
		});
	});	
</script>