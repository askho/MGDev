/*
Initalizing handlers 
*/
$( document ).ready(function() {
    $(".nav.navbar-nav.navbar-right").click(function(event){
        if($(event.target).is("#gallery")) {
            $(document).prop('title', 'Gallery - Mike Gonzales Photography');
            history.pushState(null, null, "viewCategories.php")
            event.preventDefault();
            getCategories();
        } else if($(event.target).is("#blog")) {
            event.preventDefault();
            history.pushState(null, null, "viewBlog.php")
            getBlogPosts();
        } else if($(event.target).is("#booking")) {
            alert("Booking was pressed");
        }
    });
});
/*
    This checks the URL and updates the content accordingly. 
    This allows for ajax calls while still being able to use the
    forward and backwards button
*/
$(window).on('popstate', function() {
    var fileName = location.pathname.split("/").pop();
    if(fileName == "viewAlbum.php") {
        getAlbumThumbs(getQueryVariable("categoryID"));
    } else if(fileName == "viewPhotos.php") {
        loadPictures(getQueryVariable("albumID"), getQueryVariable("albumName"));
    } else if(fileName == "viewCategories.php") {
        getCategories();
    } else if(fileName == "viewBlog.php") {
        getBlogPosts(getQueryVariable("page"));
    } else if(fileName == "viewPost.php") {
        getPost(getQueryVariable("postID"));
    } else if(fileName == "viewPrivate.php") {
        if(!getQueryVariable("pageNumber")) {
            var pageNumber = 0;
        } else {
            var pageNumber = getQueryVariable("pageNumber");
        }
        loadPictures(getQueryVariable("albumID"), getQueryVariable("albumName"), pageNumber, getQueryVariable("privateLink"));
    }
    if(fileName == "viewPhotos.php") {
        if(!getQueryVariable("pageNumber")) {
            var pageNumber = 0;
        } else {
            var pageNumber = getQueryVariable("pageNumber");
        }
        loadPictures(getQueryVariable("albumID"), getQueryVariable("albumName"), pageNumber);
    }
});
/*
    This grabs the get varibles from the URL
*/
function getQueryVariable(variable)
{
       var query = window.location.search.substring(1);
       var vars = query.split("&");
       for (var i=0;i<vars.length;i++) {
               var pair = vars[i].split("=");
               if(pair[0] == variable){return decodeURI(pair[1]);}
       }
       return(false);
}
/*
    This grabs the post title, post date, and post id of all the blog posts.
*/
function getBlogPosts(pageNo) {
    document.title = "Blog - Mike Gonzales Photography";
    $("#content").fadeOut(function() {
        $("#content").removeClass();
        $("#content").addClass("container");
    });
    if(pageNo == null || pageNo == 0) {
        pageNo = 1;
    }
    $.ajax({
        url: "php/getBlogPosts.php",
        dataType: "json",
        type: "POST",
        data:{page: pageNo}
    })
    .done(function( data ) {
        console.log(data);
        $("#content").html("<div class = 'well' id = 'blogContainer'></div> ").hide();
        if(data.length == 0) {
            alert("No posts to be seen");
        }
        for(i = 0; i < data["posts"].length; i++) {
            $("#blogContainer").append("<a href = 'viewPost.php?postID="+data["posts"][i].postID+"' id = 'link"+data["posts"][i].postID+"'><div class = 'panel panel-default' id ='blogHead"+i+"'></div></a>")
            $("#blogHead"+i).append("<div class = 'panel-heading' id ='blogTitle'"+i+"><h1 class='panel-title'>"+data["posts"][i].title+"</h1><br /><h6>Posted on: "+data["posts"][i].date+"</h6></div>");
            (function(j){
                $("#link"+data["posts"][j].postID).click(function(event){
                    event.preventDefault();
                    history.pushState(null, null, $(this).attr('href'));
                    getPost(data["posts"][j].postID)
                })
            })(i);
        }
        $("#blogContainer").append("<nav><ul id = 'pagination' class ='pagination'></ul></nav>");
        createPagination(pageNo, data["totalPages"].totalPages, "pagination");
        window.scrollTo(0, 0);
        $("#content").fadeIn();
    })
    .fail(function() {
        alert("Failed to blog posts");
    });
}
function createPagination(currentPage, totalPages, idName) {
    currentPage = parseInt(currentPage);
    totalPages = parseInt(totalPages);
    /*
        This next section puts in the previous button if it is needed
    */
    if(currentPage > 1) {
        $("#"+idName).append("<li><a href='viewBlog.php?page="+ (currentPage -1) +"' aria-label='Previous' id = 'paginationPrevious'><span aria-hidden='true'>&laquo;</span></a></li>");
        $("#paginationPrevious").click(function(event) {
            event.preventDefault();
            history.pushState(null, null, $(this).attr('href'));
            getBlogPosts(currentPage - 1);
        });
    }
    /*
        This part populates the pagination section
    */
    for(i = currentPage - 3; i <= currentPage + 3 && i <= totalPages; i++) {
        if(i > 0) {
            if(i == currentPage) {
                $("#"+idName).append("<li class='active'><a href='viewBlog.php?page="+ i +"' id = 'pagination"+i+"'>"+i+"<span class='sr-only'>(current)</span></a></li>");
            } else {
                $("#"+idName).append("<li><a href='viewBlog.php?page="+ i +"' id = 'pagination"+i+"'>"+i+"</a></li>");
            }
            (function(j){
                $("#pagination"+j).click(function(event) {
                    event.preventDefault();
                    history.pushState(null, null, $(this).attr('href'));
                    getBlogPosts(j);
                });
            })(i);
        }
    }
    if(currentPage < totalPages) {
        $("#"+idName).append("<li><a href='viewBlog.php?page="+ (currentPage +1) +"' aria-label='Next' id = 'paginationNext'><span aria-hidden='true'>&raquo;</span></a></li>");
        $("#paginationNext").click(function(event) {
            event.preventDefault();
            history.pushState(null, null, $(this).attr('href'));
            getBlogPosts(currentPage + 1);
            
        });
    }
}
/*
    This grabs the post contents of a blog post.
*/
function getPost(postID) {
    $("#content").fadeOut(function() {
        $("#content").removeClass();
        $("#content").addClass("container");
    
    $.ajax({
        url: "php/getPost.php",
        dataType: "json",
        type: "POST",
        data: {
            postID2: postID
        }
    })
    .done(function( data ) {
        document.title = data[0].title;
        $("#content").html(" <div class = 'well'>\
                                <div class='panel panel-default'>\
                                  <div class='panel-heading' id = 'blogHeading'></div>\
                                  <div class='panel-body' id = 'blogBody'>\
                                  </div>\
                                </div>\
                                <div id = 'comments'></div>\
                              </div>")
        $("#blogHeading").append("<h1>"+data[0].title+"</h1><br><h6>"+data[0].date+"</h6>");
        $("#blogBody").append(data[0].body);
        $("#comments").load("php/disqus.php");
        $('#content').fadeIn();
    })
    .fail(function() {
        alert("Failed to get post");
    });
    });
}
/*
    This grabs the thumbnails for a category and places them inside content and inits isotope
    @param: categoryID This is the category that we are selecting
*/
function getAlbumThumbs(categoryID) {
    $("#content").removeClass();
    $("#content").addClass("container-fluid");
    $.ajax({
        url: "php/albumThumbnails.php",
        dataType: "json",
        type: "POST",
        data:{category: categoryID}
    })
    .done(function( data ) {
        $("#content").html("<h1>Albums</hi>");
        $("#content").append("<div id ='isotopeContainer'></div>");
        $("#isotopeContainer").hide();
        if(data == ""){
            $("#isotopeContainer").append("No albums found");
        }
        for(i = 0; i < data.length; i++) {
            var result = "images/thumbnails/"+data[i].FirstPhoto;
            var albumName = data[i].albumname;
            var albumID = data[i].albumID;
            var url = "viewPhotos.php?albumID="+albumID+"&albumName="+albumName;
            $("#isotopeContainer").append(" <figure class = 'isotopeElement' id = '"+albumID+"'>\
                                                <a href = '"+url+"''>\
                                                <img src='"+result+"'>\
                                                <figcaption>"+albumName+"</figcaption>\
                                                </a>\
                                            </figure>");
            (function(albumID2, albumName2, href) {
                $("#"+albumID2).click(function(event) {
                    loadPictures(albumID2, albumName2);
                    if(history.pushState) {
                        history.pushState(null, null, href)
                        event.preventDefault();
                    }
                });
            })(albumID, albumName, url)

        }
        
        $("#content").fadeIn("fast", function() {
            initIsotope();
        });
    })
    .fail(function(data) {
        console.log(data);
        alert("Failed to get the albums");
    });
}
/*
    This grabs the server results calls show pictures to display them.
*/
function loadPictures(albumID, albumName, pageNumber,  privateID) {
    $("#content").removeClass();
    $("#content").addClass("container-fluid");
    if(privateID == "") {
        privateID = null;
    }
    $.ajax({
        url: "php/getPhotos.php",
        dataType: "json",
        type: "POST",
        data:{album: albumID,
            privateLink: privateID
        }
    })
    .done(function( data ) {
        console.log(data);
        loadPictures.data = data;
        loadPictures.currentLoadedPage = 0;
        if(privateID == null) {
            if(pageNumber != null || pageNumber == 0) {
                loadPictures.currentLoadedPage = pageNumber;
                showPictures(loadPictures.data, albumName, albumID, pageNumber);
            } else {

                showPictures(loadPictures.data, albumName, albumID);
            }
        } else {
            if(pageNumber != null || pageNumber == 0) {
                loadPictures.currentLoadedPage = pageNumber;
                showPrivatePhotos(loadPictures.data, albumName, albumID, pageNumber, privateID);
            } else {

                showPrivatePhotos(loadPictures.data, albumName, albumID, 0, privateID);
            }
        }

    })
    .fail(function(data) {
        console.log(data);
        alert("Failed to get the albums");
    });
}
/*
    This displays the picture based on the json response. If no page is specified it will default to 
    0
*/
function showPictures(data, albumName, albumID, page) {
    $("#content").removeClass();
    $("#content").addClass("container-fluid");
    $("#content").html("<h1>"+albumName+"</hi>");
    $("#content").append("<div id ='isotopeContainer'></div>");
    $("#content").append("<nav><ul id = 'pageNavigation' class = 'pagination'></ul?</nav>");
    $("#isotopeContainer").hide();
    $("#pageNavigation").hide();
    if(page == null) {
        var currentPage = 0;
    } else {
        var currentPage = parseInt(loadPictures.currentLoadedPage);
    }
    /**
        This section sets up loading the images to load
    */
    if(page == null || page == 0){
        loadTo = 14;
        startFrom = 0;
        if (loadTo > data.length) {
            loadTo = data.length;
        }
        var page = 0;
    } else {
        startFrom = page * 14;
        loadTo = startFrom + 14;
        if (loadTo > data.length) {
            loadTo = data.length;
        }
    }
    if(data == ""){ 
        $("#isotopeContainer").append("No pictures found");
    }
    for(i = startFrom; i < loadTo; i++) {
        var thumbnail = "images/thumbnails/"+data[i].location;
        var photoName = data[i].photoName;
        var photoID = data[i].photoID;
        if(photoName == null) {
            photoName = "";
        }
        $("#isotopeContainer").append(" <figure class = 'isotopeElement'>\
                                            <a id = '"+photoID+"'href = ''>\
                                            <img src='"+thumbnail+"'>\
                                            <figcaption>"+photoName+"</figcaption>\
                                            </a>\
                                        </figure>");
        (function(j, object) {
            $("#"+j).click(function(event) {
                dateTaken = (object.dateTaken == null)?  "Unknown": object.dateTaken;
                description = (object.description == null)?  "": "<br />" + object.description;
                iso = (object.ISO == null)? "Unknown" : object.ISO;
                camera = (object.camera == null)? "Unknown" : object.camera;
                focalLength = (object.focalLength == null)? "Unknown" : object.focalLength + " mm";
                aperture = (object.aperture == null)? "Unknown" : object.aperture;
                var image = "images/photos/"+object.location;
                var caption = "<div class = 'caption'>Date Taken: " + dateTaken 
                    + description
                    + "<br />ISO: " + iso 
                    + "<br />Camera: " + camera
                    + "<br />Focal Length: " + focalLength
                    +"<br />Aperture: " + aperture + "</div>";
                $.featherlight("<img class = 'lightboxImage' src = "+image+"><br />" + caption, {type: "html"});
                event.preventDefault();
            });
        })(photoID, data[i])
    }
    var maxPage = 5 + currentPage;
    var maxPossiblePages = Math.ceil((data.length / 14) + 1);
    for(i = currentPage - 3; i < maxPage && i < Math.floor(maxPossiblePages); i++) {
        var url = "viewPhotos.php?albumID=" + albumID +"&albumName="+albumName+"&pageNumber="+(i-1); 
        if(i > 0 ) {
            $("#pageNavigation").append("<li id='navContainer"+i+"'><a id = 'nav"+i+"' href='"+url+"'>"+i+"</a></li>");
            (function(j, href) {
                $("#nav"+i).click(function(event){
                    loadPictures(albumID, albumName, j-1)
                    history.pushState(null, null, href)
                    event.preventDefault();
                });
            })(i, url);
        }
        if(i == (currentPage + 1)) {
            $("#navContainer"+i).addClass("active");
        }
    }
    $("#content").fadeIn("fast",function() {initIsotope()});
}
function showPrivatePhotos(data, albumName, albumID, page, private) {
    $("#content").removeClass();
    $("#content").addClass("container-fluid");
    $("#content").html("<h1>"+albumName+"</hi>");
    $("#content").append("<div id ='isotopeContainer'></div>");
    $("#content").append("<nav><ul id = 'pageNavigation' class = 'pagination'></ul?</nav>");
    $("#isotopeContainer").hide();
    $("#pageNavigation").hide();
    if(page == null) {
        var currentPage = 0;
    } else {
        var currentPage = parseInt(loadPictures.currentLoadedPage);
    }
    /**
        This section sets up loading the images to load
    */
    if(page == null || page == 0){
        loadTo = 14;
        startFrom = 0;
        if (loadTo > data.length) {
            loadTo = data.length;
        }
        var page = 0;
    } else {
        startFrom = page * 14;
        loadTo = startFrom + 14;
        if (loadTo > data.length) {
            loadTo = data.length;
        }
    }
    if(data == ""){ 
        $("#isotopeContainer").append("No albums found");
    }
    for(i = startFrom; i < loadTo; i++) {
        var thumbnail = "images/thumbnails/"+data[i].location;
        var photoName = data[i].photoName;
        var photoID = data[i].photoID;
        if(photoName == null) {
            photoName = "";
        }
        $("#isotopeContainer").append(" <figure class = 'isotopeElement'>\
                                            <a id = '"+photoID+"'href = ''>\
                                            <img src='"+thumbnail+"'>\
                                            <figcaption>"+photoName+"</figcaption>\
                                            </a>\
                                        </figure>");
        (function(j, object) {
            $("#"+j).click(function(event) {
                dateTaken = (object.dateTaken == null)?  "Unknown": object.dateTaken;
                iso = (object.ISO == null)? "Unknown" : object.ISO;
                camera = (object.camera == null)? "Unknown" : object.camera;
                focalLength = (object.focalLength == null)? "Unknown" : object.focalLength + " mm";
                aperture = (object.aperture == null)? "Unknown" : object.aperture;
                var image = "images/photos/"+object.location;
                var caption = "<div class = 'caption'>Date Taken: " + dateTaken 
                    + "<br />ISO: " + iso 
                    + "<br />Camera: " + camera
                    + "<br />Focal Length: " + focalLength
                    +"<br />Aperture: " + aperture + "</div>";
                $.featherlight("<img class = 'lightboxImage' src = "+image+"><br />" + caption, {type: "html"});
                event.preventDefault();
            });
        })(photoID, data[i])
    }
    var maxPage = 5 + currentPage;
    var maxPossiblePages = Math.ceil((data.length / 14) + 1);
    for(i = currentPage - 3; i < maxPage && i < Math.floor(maxPossiblePages); i++) {
        var url = "viewPrivate.php?albumID=" + albumID +"&albumName="+albumName+"&pageNumber="+(i-1)+"&privateLink="+private; 
        if(i > 0 ) {
            $("#pageNavigation").append("<li id='navContainer"+i+"'><a id = 'nav"+i+"' href='"+url+"'>"+i+"</a></li>");
            (function(j, href) {
                $("#nav"+i).click(function(event){
                    loadPictures(albumID, albumName, j-1, private);
                    history.pushState(null, null, href);
                    event.preventDefault();
                });
            })(i, url);
        }
        if(i == (currentPage + 1)) {
            $("#navContainer"+i).addClass("active");
        }
    }
    $("#content").fadeIn("fast",function() {initIsotope()});
}
/*
    This grabs the categories to be shown.
*/
function getCategories() {
    $("#content").removeClass();
    $("#content").addClass("container-fluid");
    $.ajax({
        url: "php/getCategories.php",
        dataType: "json"
    })
    .done(function( data ) {
        if(data == ""){
            $("#content").html("No categories found");
        } else {
            $("#content").html("<div id = 'categories'><h1>Categories</hi></div>");
            for(i = 0; i < data.length; i++) {
                var url = "viewAlbum.php?categoryID="+data[i]['categoryID'];
                $("#categories").hide().append("<a href = '"+url+"' id ='"+data[i]['categoryID']+"'>"+data[i]['categoryName']+"</a>").fadeIn("fast");
                (function(j) {
                    $("#"+data[j]['categoryID']).click(function(event) {
                        getAlbumThumbs(data[j]['categoryID']);
                        var href = $(this).attr('href');
                        if(history.pushState) {
                            history.pushState(null, null, href)
                            event.preventDefault();
                        }
                    });
                })(i)
                
            }
        }
    })
    .fail(function() {
        alert("Failed to get the categories");
        $("#content").html("");
    })
}
/*
    This initalizes isotope and fades in the content
*/
function initIsotope() {
    var $container = $('#isotopeContainer');
    $container.imagesLoaded( function() {
      $container.fadeIn().isotope({
          itemSelector: '.isotopeElement',
          layoutMode: "fitRows"
      });
        $("#pageNavigation").fadeIn();
    });

}
/**
 * Featherlight - ultra slim jQuery lightbox
 * Version 1.0.3 - http://noelboss.github.io/featherlight/
 *
 * Copyright 2014, Noël Raoul Bossart (http://www.noelboss.com)
 * MIT Licensed.
**/
(function($) {
	"use strict";

	if('undefined' === typeof $) {
		if('console' in window){ window.console.info('Too much lightness, Featherlight needs jQuery.'); }
		return;
	}

	/* Featherlight is exported as $.featherlight.
	   It is a function used to open a featherlight lightbox.

	   [tech]
	   Featherlight uses prototype inheritance.
	   Each opened lightbox will have a corresponding object.
	   That object may have some attributes that override the
	   prototype's.
	   Extensions created with Featherlight.extend will have their
	   own prototype that inherits from Featherlight's prototype,
	   thus attributes can be overriden either at the object level,
	   or at the extension level.
	   To create callbacks that chain themselves instead of overriding,
	   use chainCallbacks.
	   For those familiar with CoffeeScript, this correspond to
	   Featherlight being a class and the Gallery being a class
	   extending Featherlight.
	   The chainCallbacks is used since we don't have access to
	   CoffeeScript's `super`.
	*/

	function Featherlight($content, config) {
		if(this instanceof Featherlight) {  /* called with new */
			this.id = Featherlight.id++;
			this.setup($content, config);
			this.chainCallbacks(Featherlight._callbackChain);
		} else {
			var fl = new Featherlight($content, config);
			fl.open();
			return fl;
		}
	}

	/* document wide key handler */
	var keyHelper = function(event) {
		if (!event.isDefaultPrevented()) { // esc keycode
			var self = Featherlight.current();
			if (self) {
				self.onKeyDown(event);
			}
		}
	};

	Featherlight.prototype = {
		constructor: Featherlight,
		/*** defaults ***/
		/* extend featherlight with defaults and methods */
		namespace:    'featherlight',         /* Name of the events and css class prefix */
		targetAttr:   'data-featherlight',    /* Attribute of the triggered element that contains the selector to the lightbox content */
		variant:      null,                   /* Class that will be added to change look of the lightbox */
		resetCss:     false,                  /* Reset all css */
		background:   null,                   /* Custom DOM for the background, wrapper and the closebutton */
		openTrigger:  'click',                /* Event that triggers the lightbox */
		closeTrigger: 'click',                /* Event that triggers the closing of the lightbox */
		filter:       null,                   /* Selector to filter events. Think $(...).on('click', filter, eventHandler) */
		root:         'body',                 /* Where to append featherlights */
		openSpeed:    250,                    /* Duration of opening animation */
		closeSpeed:   250,                    /* Duration of closing animation */
		closeOnClick: 'background',           /* Close lightbox on click ('background', 'anywhere' or false) */
		closeOnEsc:   true,                   /* Close lightbox when pressing esc */
		closeIcon:    '&#10005;',             /* Close icon */
		otherClose:   null,                   /* Selector for alternate close buttons (e.g. "a.close") */
		beforeOpen:   $.noop,                 /* Called before open. can return false to prevent opening of lightbox. Gets event as parameter, this contains all data */
		beforeContent: $.noop,                /* Called when content is loaded. Gets event as parameter, this contains all data */
		beforeClose:  $.noop,                 /* Called before close. can return false to prevent opening of lightbox. Gets event as parameter, this contains all data */
		afterOpen:    $.noop,                 /* Called after open. Gets event as parameter, this contains all data */
		afterContent: $.noop,                 /* Called after content is ready and has been set. Gets event as parameter, this contains all data */
		afterClose:   $.noop,                 /* Called after close. Gets event as parameter, this contains all data */
		onKeyDown:    $.noop,									/* Called on key down for the frontmost featherlight */
		type:         null,                   /* Specify type of lightbox. If unset, it will check for the targetAttrs value. */
		contentFilters: ['jquery', 'image', 'html', 'ajax', 'text'], /* List of content filters to use to determine the content */

		/*** methods ***/
		/* setup iterates over a single instance of featherlight and prepares the background and binds the events */
		setup: function(target, config){
			/* all arguments are optional */
			if (typeof target === 'object' && target instanceof $ === false && !config) {
				config = target;
				target = undefined;
			}

			var self = $.extend(this, config, {target: target}),
				css = !self.resetCss ? self.namespace : self.namespace+'-reset', /* by adding -reset to the classname, we reset all the default css */
				$background = $(self.background || [
					'<div class="'+css+'">',
						'<div class="'+css+'-content">',
							'<span class="'+css+'-close-icon '+ self.namespace + '-close">',
								self.closeIcon,
							'</span>',
							'<div class="'+self.namespace+'-inner"></div>',
						'</div>',
					'</div>'].join('')),
				closeButtonSelector = '.'+self.namespace+'-close' + (self.otherClose ? ',' + self.otherClose : '');

			self.$instance = $background.clone().addClass(self.variant); /* clone DOM for the background, wrapper and the close button */

			/* close when click on background/anywhere/null or closebox */
			self.$instance.on(self.closeTrigger+'.'+self.namespace, function(event) {
				var $target = $(event.target);
				if( ('background' === self.closeOnClick  && $target.is('.'+self.namespace))
					|| 'anywhere' === self.closeOnClick
					|| $target.is(closeButtonSelector) ){
					event.preventDefault();
					self.close();
				}
			});

			return this;
		},

		/* this method prepares the content and converts it into a jQuery object or a promise */
		getContent: function(){
			var self = this,
				filters = this.constructor.contentFilters,
				readTargetAttr = function(name){ return self.$currentTarget && self.$currentTarget.attr(name); },
				targetValue = readTargetAttr(self.targetAttr),
				data = self.target || targetValue || '';

			/* Find which filter applies */
			var filter = filters[self.type]; /* check explicit type like {type: 'image'} */

			/* check explicit type like data-featherlight="image" */
			if(!filter && data in filters) {
				filter = filters[data];
				data = self.target && targetValue;
			}
			data = data || readTargetAttr('href') || '';

			/* check explicity type & content like {image: 'photo.jpg'} */
			if(!filter) {
				for(var filterName in filters) {
					if(self[filterName]) {
						filter = filters[filterName];
						data = self[filterName];
					}
				}
			}

			/* otherwise it's implicit, run checks */
			if(!filter) {
				var target = data;
				data = null;
				$.each(self.contentFilters, function() {
					filter = filters[this];
					if(filter.test)  {
						data = filter.test(target);
					}
					if(!data && filter.regex && target.match && target.match(filter.regex)) {
						data = target;
					}
					return !data;
				});
				if(!data) {
					if('console' in window){ window.console.error('Featherlight: no content filter found ' + (target ? ' for "' + target + '"' : ' (no target specified)')); }
					return false;
				}
			}
			/* Process it */
			return filter.process.call(self, data);
		},

		/* sets the content of $instance to $content */
		setContent: function($content){
			var self = this;
			/* we need a special class for the iframe */
			if($content.is('iframe') || $('iframe', $content).length > 0){
				self.$instance.addClass(self.namespace+'-iframe');
			}

			self.$content = $content.addClass(self.namespace+'-inner');

			/* replace content by appending to existing one before it is removed
			   this insures that featherlight-inner remain at the same relative
				 position to any other items added to featherlight-content */
			self.$instance.find('.'+self.namespace+'-inner')
				.slice(1).remove().end()			/* In the unexpected event where there are many inner elements, remove all but the first one */
				.replaceWith(self.$content);

			return self;
		},

		/* opens the lightbox. "this" contains $instance with the lightbox, and with the config */
		open: function(event){
			var self = this;
			if((!event || !event.isDefaultPrevented())
				&& self.beforeOpen(event) !== false) {

				if(event){
					event.preventDefault();
				}
				var $content = self.getContent();

				if($content){
					/* Add to opened registry */
					self.constructor._opened.add(self._openedCallback = function(klass, response){
						if ((self instanceof klass) &&
								(self.$instance.closest('body').length > 0)) {
							response.currentFeatherlight = self;
						}
					});

					/* attach key handler to document if needed */
					if(!Featherlight._keyHandlerInstalled) {
						$(document).on('keyup.'+Featherlight.prototype.namespace, keyHelper);
						Featherlight._keyHandlerInstalled = true;
					}

					self.$instance.appendTo(self.root).fadeIn(self.openSpeed);
					self.beforeContent(event);

					/* Set content and show */
					$.when($content).done(function($content){
						self.setContent($content);
						self.afterContent(event);
						/* Call afterOpen after fadeIn is done */
						$.when(self.$instance.promise()).done(function(){
							self.afterOpen(event);
						});
					});
					return self;
				}
			}
			return false;
		},

		/* closes the lightbox. "this" contains $instance with the lightbox, and with the config */
		close: function(event){
			var self = this;
			if(self.beforeClose(event) === false) {
				return false;
			}
			self.constructor._opened.remove(self._openedCallback);

			/* attach key handler to document if no opened Featherlight */
			if(!Featherlight.current()) {
				$(document).off('keyup.'+Featherlight.namespace, keyHelper);
				self.constructor._keyHandlerInstalled = false;
			}

			self.$instance.fadeOut(self.closeSpeed,function(){
				self.$instance.detach();
				self.afterClose(event);
			});
		},

		/* Utility function to chain callbacks
		   [Warning: guru-level]
		   Used be extensions that want to let users specify callbacks but
		   also need themselves to use the callbacks.
		   The argument 'chain' has callback names as keys and function(super, event)
		   as values. That function is meant to call `super` at some point.
		*/
		chainCallbacks: function(chain) {
			for (var name in chain) {
				this[name] = $.proxy(chain[name], this, $.proxy(this[name], this));
			}
		}
	};

	$.extend(Featherlight, {
		id: 0,                                    /* Used to id single featherlight instances */
		autoBind:       '[data-featherlight]',    /* Will automatically bind elements matching this selector. Clear or set before onReady */
		defaults:       Featherlight.prototype,   /* You can access and override all defaults using $.featherlight.defaults, which is just a synonym for $.featherlight.prototype */
		/* Contains the logic to determine content */
		contentFilters: {
			jquery: {
				regex: /^[#.]\w/,         /* Anything that starts with a class name or identifiers */
				test: function(elem)    { return elem instanceof $ && elem; },
				process: function(elem) { return $(elem).clone(true); }
			},
			image: {
				regex: /\.(png|jpg|jpeg|gif|tiff|bmp)(\?\S*)?$/i,
				process: function(url)  {
					var self = this,
						deferred = $.Deferred(),
						img = new Image();
					img.onload  = function() { deferred.resolve(
						$('<img src="'+url+'" alt="" class="'+self.namespace+'-image" />')
					); };
					img.onerror = function() { deferred.reject(); };
					img.src = url;
					return deferred.promise();
				}
			},
			html: {
				regex: /^\s*<[\w!][^<]*>/, /* Anything that starts with some kind of valid tag */
				process: function(html) { return $(html); }
			},
			ajax: {
				regex: /./,            /* At this point, any content is assumed to be an URL */
				process: function(url)  {
					var self = this,
						deferred = $.Deferred();
					/* we are using load so one can specify a target with: url.html #targetelement */
					var $container = $('<div></div>').load(url, function(response, status){
						if ( status !== "error" ) {
							deferred.resolve($container.contents());
						}
						deferred.fail();
					});
					return deferred.promise();
				}
			},
			text: {
				process: function(text) { return $('<div>', {text: text}); }
			}
		},

		functionAttributes: ['beforeOpen', 'afterOpen', 'beforeContent', 'afterContent', 'beforeClose', 'afterClose'],

		/*** class methods ***/
		/* read element's attributes starting with data-featherlight- */
		readElementConfig: function(element) {
			var Klass = this,
				config = {};
			if (element && element.attributes) {
					$.each(element.attributes, function(){
					var match = this.name.match(/^data-featherlight-(.*)/);
					if (match) {
						var val = this.value,
							name = $.camelCase(match[1]);
						if ($.inArray(name, Klass.functionAttributes) >= 0) {  /* jshint -W054 */
							val = new Function(val);                           /* jshint +W054 */
						} else {
							try { val = $.parseJSON(val); }
							catch(e) {}
						}
						config[name] = val;
					}
				});
			}
			return config;
		},

		/* Used to create a Featherlight extension
		   [Warning: guru-level]
		   Creates the extension's prototype that in turn
		   inherits Featherlight's prototype.
		   Could be used to extend an extension too...
		   This is pretty high level wizardy, it comes pretty much straight
		   from CoffeeScript and won't teach you anything about Featherlight
		   as it's not really specific to this library.
		   My suggestion: move along and keep your sanity.
		*/
		extend: function(child, defaults) {
			/* Setup class hierarchy, adapted from CoffeeScript */
			var Ctor = function(){ this.constructor = child; };
			Ctor.prototype = this.prototype;
			child.prototype = new Ctor();
			child.__super__ = this.prototype;
			/* Copy class methods & attributes */
			$.extend(child, this, defaults);
			child.defaults = child.prototype;
			return child;
		},

		attach: function($source, $content, config) {
			var Klass = this;
			if (typeof $content === 'object' && $content instanceof $ === false && !config) {
				config = $content;
				$content = undefined;
			}
			/* make a copy */
			config = $.extend({}, config);

			/* Only for openTrigger and namespace... */
			var tempConfig = $.extend({}, Klass.defaults, Klass.readElementConfig($source[0]), config);

			$source.on(tempConfig.openTrigger+'.'+tempConfig.namespace, tempConfig.filter, function(event) {
				/* ... since we might as well compute the config on the actual target */
				var elemConfig = $.extend({$source: $source, $currentTarget: $(this)}, Klass.readElementConfig($source[0]), Klass.readElementConfig(this), config);
				new Klass($content, elemConfig).open(event);
			});
			return $source;
		},

		current: function() {
			var response = {};
			this._opened.fire(this, response);
			return response.currentFeatherlight;
		},

		close: function() {
			var cur = this.current();
			if(cur) { cur.close(); }
		},

		/* Does the auto binding on startup.
		   Meant only to be used by Featherlight and its extensions
		*/
		_onReady: function() {
			var Klass = this;
			if(Klass.autoBind){
				/* First, bind click on document, so it will work for items added dynamically */
				Klass.attach($(document), {filter: Klass.autoBind});
				/* Auto bound elements with attr-featherlight-filter won't work
				   (since we already used it to bind on document), so bind these
				   directly. We can't easily support dynamically added element with filters */
				$(Klass.autoBind).filter('[data-featherlight-filter]').each(function(){
					Klass.attach($(this));
				});
			}
		},

		/* Featherlight uses the onKeyDown callback to intercept the escape key.
		   Private to Featherlight.
		*/
		_callbackChain: {
			onKeyDown: function(_super, event){
				if(27 === event.keyCode && this.closeOnEsc) {
					this.$instance.find('.'+this.namespace+'-close:first').click();
					event.preventDefault();
				} else {
					console.log('pass');
					return _super(event);
				}
			}
		},

		_opened: $.Callbacks()
	});

	$.featherlight = Featherlight;

	/* bind jQuery elements to trigger featherlight */
	$.fn.featherlight = function($content, config) {
		return Featherlight.attach(this, $content, config);
	};

	/* bind featherlight on ready if config autoBind is set */
	$(document).ready(function(){ Featherlight._onReady(); });
}(jQuery));
