"function"!=typeof Object.create&&(Object.create=function(t){function e(){}return e.prototype=t,new e}),function(l,p,m){var o={init:function(t,e){var o=this;o.$elem=l(e),o.options=l.extend({},l.fn.owlCarousel.options,o.$elem.data(),t),o.userOptions=t,o.loadContent()},loadContent:function(){var t,i=this;"function"==typeof i.options.beforeInit&&i.options.beforeInit.apply(this,[i.$elem]),"string"==typeof i.options.jsonPath?(t=i.options.jsonPath,l.getJSON(t,function(t){var e,o="";if("function"==typeof i.options.jsonSuccess)i.options.jsonSuccess.apply(this,[t]);else{for(e in t.owl)t.owl.hasOwnProperty(e)&&(o+=t.owl[e].item);i.$elem.html(o)}i.logIn()})):i.logIn()},logIn:function(){var t=this;t.$elem.data("owl-originalStyles",t.$elem.attr("style")),t.$elem.data("owl-originalClasses",t.$elem.attr("class")),t.$elem.css({opacity:0}),t.orignalItems=t.options.items,t.checkBrowser(),t.wrapperWidth=0,t.checkVisible=null,t.setVars()},setVars:function(){var t=this;if(0===t.$elem.children().length)return!1;t.baseClass(),t.eventTypes(),t.$userItems=t.$elem.children(),t.itemsAmount=t.$userItems.length,t.wrapItems(),t.$owlItems=t.$elem.find(".owl-item"),t.$owlWrapper=t.$elem.find(".owl-wrapper"),t.playDirection="next",t.prevItem=0,t.prevArr=[0],t.currentItem=0,t.customEvents(),t.onStartup()},onStartup:function(){var t=this;t.updateItems(),t.calculateAll(),t.buildControls(),t.updateControls(),t.response(),t.moveEvents(),t.stopOnHover(),t.owlStatus(),!1!==t.options.transitionStyle&&t.transitionTypes(t.options.transitionStyle),!0===t.options.autoPlay&&(t.options.autoPlay=5e3),t.play(),t.$elem.find(".owl-wrapper").css("display","block"),t.$elem.is(":visible")?t.$elem.css("opacity",1):t.watchVisibility(),t.onstartup=!1,t.eachMoveUpdate(),"function"==typeof t.options.afterInit&&t.options.afterInit.apply(this,[t.$elem])},eachMoveUpdate:function(){var t=this;!0===t.options.lazyLoad&&t.lazyLoad(),!0===t.options.autoHeight&&t.autoHeight(),t.onVisibleItems(),"function"==typeof t.options.afterAction&&t.options.afterAction.apply(this,[t.$elem])},updateVars:function(){var t=this;"function"==typeof t.options.beforeUpdate&&t.options.beforeUpdate.apply(this,[t.$elem]),t.watchVisibility(),t.updateItems(),t.calculateAll(),t.updatePosition(),t.updateControls(),t.eachMoveUpdate(),"function"==typeof t.options.afterUpdate&&t.options.afterUpdate.apply(this,[t.$elem])},reload:function(){var t=this;p.setTimeout(function(){t.updateVars()},0)},watchVisibility:function(){var t=this;if(!1!==t.$elem.is(":visible"))return!1;t.$elem.css({opacity:0}),p.clearInterval(t.autoPlayInterval),p.clearInterval(t.checkVisible),t.checkVisible=p.setInterval(function(){t.$elem.is(":visible")&&(t.reload(),t.$elem.animate({opacity:1},200),p.clearInterval(t.checkVisible))},500)},wrapItems:function(){var t=this;t.$userItems.wrapAll('<div class="owl-wrapper">').wrap('<div class="owl-item"></div>'),t.$elem.find(".owl-wrapper").wrap('<div class="owl-wrapper-outer">'),t.wrapperOuter=t.$elem.find(".owl-wrapper-outer"),t.$elem.css("display","block")},baseClass:function(){var t=this,e=t.$elem.hasClass(t.options.baseClass),o=t.$elem.hasClass(t.options.theme);e||t.$elem.addClass(t.options.baseClass),o||t.$elem.addClass(t.options.theme)},updateItems:function(){var t,e,o=this;if(!1===o.options.responsive)return!1;if(!0===o.options.singleItem)return o.options.items=o.orignalItems=1,o.options.itemsCustom=!1,o.options.itemsDesktop=!1,o.options.itemsDesktopSmall=!1,o.options.itemsTablet=!1,o.options.itemsTabletSmall=!1,o.options.itemsMobile=!1;if((t=l(o.options.responsiveBaseWidth).width())>(o.options.itemsDesktop[0]||o.orignalItems)&&(o.options.items=o.orignalItems),!1!==o.options.itemsCustom)for(o.options.itemsCustom.sort(function(t,e){return t[0]-e[0]}),e=0;e<o.options.itemsCustom.length;e+=1)o.options.itemsCustom[e][0]<=t&&(o.options.items=o.options.itemsCustom[e][1]);else t<=o.options.itemsDesktop[0]&&!1!==o.options.itemsDesktop&&(o.options.items=o.options.itemsDesktop[1]),t<=o.options.itemsDesktopSmall[0]&&!1!==o.options.itemsDesktopSmall&&(o.options.items=o.options.itemsDesktopSmall[1]),t<=o.options.itemsTablet[0]&&!1!==o.options.itemsTablet&&(o.options.items=o.options.itemsTablet[1]),t<=o.options.itemsTabletSmall[0]&&!1!==o.options.itemsTabletSmall&&(o.options.items=o.options.itemsTabletSmall[1]),t<=o.options.itemsMobile[0]&&!1!==o.options.itemsMobile&&(o.options.items=o.options.itemsMobile[1]);o.options.items>o.itemsAmount&&!0===o.options.itemsScaleUp&&(o.options.items=o.itemsAmount)},response:function(){var t,e,o=this;if(!0!==o.options.responsive)return!1;e=l(p).width(),o.resizer=function(){l(p).width()!==e&&(!1!==o.options.autoPlay&&p.clearInterval(o.autoPlayInterval),p.clearTimeout(t),t=p.setTimeout(function(){e=l(p).width(),o.updateVars()},o.options.responsiveRefreshRate))},l(p).resize(o.resizer)},updatePosition:function(){var t=this;t.jumpTo(t.currentItem),!1!==t.options.autoPlay&&t.checkAp()},appendItemsSizes:function(){var o=this,i=0,s=o.itemsAmount-o.options.items;o.$owlItems.each(function(t){var e=l(this);e.css({width:o.itemWidth}).data("owl-item",Number(t)),t%o.options.items!=0&&t!==s||s<t||(i+=1),e.data("owl-roundPages",i)})},appendWrapperSizes:function(){var t=this,e=t.$owlItems.length*t.itemWidth;t.$owlWrapper.css({width:2*e,left:0}),t.appendItemsSizes()},calculateAll:function(){var t=this;t.calculateWidth(),t.appendWrapperSizes(),t.loops(),t.max()},calculateWidth:function(){var t=this;t.itemWidth=Math.round(t.$elem.width()/t.options.items)},max:function(){var t=this,e=-1*(t.itemsAmount*t.itemWidth-t.options.items*t.itemWidth);return t.options.items>t.itemsAmount?(e=t.maximumItem=0,t.maximumPixels=0):(t.maximumItem=t.itemsAmount-t.options.items,t.maximumPixels=e),e},min:function(){return 0},loops:function(){var t,e,o=this,i=0,s=0;for(o.positionsInArray=[0],o.pagesInArray=[],t=0;t<o.itemsAmount;t+=1)s+=o.itemWidth,o.positionsInArray.push(-s),!0===o.options.scrollPerPage&&(e=l(o.$owlItems[t]).data("owl-roundPages"))!==i&&(o.pagesInArray[i]=o.positionsInArray[t],i=e)},buildControls:function(){var t=this;!0!==t.options.navigation&&!0!==t.options.pagination||(t.owlControls=l('<div class="owl-controls"/>').toggleClass("clickable",!t.browser.isTouch).appendTo(t.$elem)),!0===t.options.pagination&&t.buildPagination(),!0===t.options.navigation&&t.buildButtons()},buildButtons:function(){var e=this,t=l('<div class="owl-buttons"/>');e.owlControls.append(t),e.buttonPrev=l("<div/>",{class:"owl-prev",html:e.options.navigationText[0]||""}),e.buttonNext=l("<div/>",{class:"owl-next",html:e.options.navigationText[1]||""}),t.append(e.buttonPrev).append(e.buttonNext),t.on("touchstart.owlControls mousedown.owlControls",'div[class^="owl"]',function(t){t.preventDefault()}),t.on("touchend.owlControls mouseup.owlControls",'div[class^="owl"]',function(t){t.preventDefault(),l(this).hasClass("owl-next")?e.next():e.prev()})},buildPagination:function(){var e=this;e.paginationWrapper=l('<div class="owl-pagination"/>'),e.owlControls.append(e.paginationWrapper),e.paginationWrapper.on("touchend.owlControls mouseup.owlControls",".owl-page",function(t){t.preventDefault(),Number(l(this).data("owl-page"))!==e.currentItem&&e.goTo(Number(l(this).data("owl-page")),!0)})},updatePagination:function(){var t,e,o,i,s,n,a=this;if(!1===a.options.pagination)return!1;for(a.paginationWrapper.html(""),t=0,e=a.itemsAmount-a.itemsAmount%a.options.items,i=0;i<a.itemsAmount;i+=1)i%a.options.items==0&&(t+=1,e===i&&(o=a.itemsAmount-a.options.items),s=l("<div/>",{class:"owl-page"}),n=l("<span></span>",{text:!0===a.options.paginationNumbers?t:"",class:!0===a.options.paginationNumbers?"owl-numbers":""}),s.append(n),s.data("owl-page",e===i?o:i),s.data("owl-roundPages",t),a.paginationWrapper.append(s));a.checkPagination()},checkPagination:function(){var t=this;if(!1===t.options.pagination)return!1;t.paginationWrapper.find(".owl-page").each(function(){l(this).data("owl-roundPages")===l(t.$owlItems[t.currentItem]).data("owl-roundPages")&&(t.paginationWrapper.find(".owl-page").removeClass("active"),l(this).addClass("active"))})},checkNavigation:function(){var t=this;if(!1===t.options.navigation)return!1;!1===t.options.rewindNav&&(0===t.currentItem&&0===t.maximumItem?(t.buttonPrev.addClass("disabled"),t.buttonNext.addClass("disabled")):0===t.currentItem&&0!==t.maximumItem?(t.buttonPrev.addClass("disabled"),t.buttonNext.removeClass("disabled")):t.currentItem===t.maximumItem?(t.buttonPrev.removeClass("disabled"),t.buttonNext.addClass("disabled")):0!==t.currentItem&&t.currentItem!==t.maximumItem&&(t.buttonPrev.removeClass("disabled"),t.buttonNext.removeClass("disabled")))},updateControls:function(){var t=this;t.updatePagination(),t.checkNavigation(),t.owlControls&&(t.options.items>=t.itemsAmount?t.owlControls.hide():t.owlControls.show())},destroyControls:function(){this.owlControls&&this.owlControls.remove()},next:function(t){var e=this;if(e.isTransition)return!1;if(e.currentItem+=!0===e.options.scrollPerPage?e.options.items:1,e.currentItem>e.maximumItem+(!0===e.options.scrollPerPage?e.options.items-1:0)){if(!0!==e.options.rewindNav)return e.currentItem=e.maximumItem,!1;e.currentItem=0,t="rewind"}e.goTo(e.currentItem,t)},prev:function(t){var e=this;if(e.isTransition)return!1;if(!0===e.options.scrollPerPage&&0<e.currentItem&&e.currentItem<e.options.items?e.currentItem=0:e.currentItem-=!0===e.options.scrollPerPage?e.options.items:1,e.currentItem<0){if(!0!==e.options.rewindNav)return e.currentItem=0,!1;e.currentItem=e.maximumItem,t="rewind"}e.goTo(e.currentItem,t)},goTo:function(t,e,o){var i,s=this;return!s.isTransition&&("function"==typeof s.options.beforeMove&&s.options.beforeMove.apply(this,[s.$elem]),t>=s.maximumItem?t=s.maximumItem:t<=0&&(t=0),s.currentItem=s.owl.currentItem=t,!1!==s.options.transitionStyle&&"drag"!==o&&1===s.options.items&&!0===s.browser.support3d?(s.swapSpeed(0),!0===s.browser.support3d?s.transition3d(s.positionsInArray[t]):s.css2slide(s.positionsInArray[t],1),s.afterGo(),s.singleItemTransition(),!1):(i=s.positionsInArray[t],!0===s.browser.support3d?(!(s.isCss3Finish=!1)===e?(s.swapSpeed("paginationSpeed"),p.setTimeout(function(){s.isCss3Finish=!0},s.options.paginationSpeed)):"rewind"===e?(s.swapSpeed(s.options.rewindSpeed),p.setTimeout(function(){s.isCss3Finish=!0},s.options.rewindSpeed)):(s.swapSpeed("slideSpeed"),p.setTimeout(function(){s.isCss3Finish=!0},s.options.slideSpeed)),s.transition3d(i)):!0===e?s.css2slide(i,s.options.paginationSpeed):"rewind"===e?s.css2slide(i,s.options.rewindSpeed):s.css2slide(i,s.options.slideSpeed),void s.afterGo()))},jumpTo:function(t){var e=this;"function"==typeof e.options.beforeMove&&e.options.beforeMove.apply(this,[e.$elem]),t>=e.maximumItem||-1===t?t=e.maximumItem:t<=0&&(t=0),e.swapSpeed(0),!0===e.browser.support3d?e.transition3d(e.positionsInArray[t]):e.css2slide(e.positionsInArray[t],1),e.currentItem=e.owl.currentItem=t,e.afterGo()},afterGo:function(){var t=this;t.prevArr.push(t.currentItem),t.prevItem=t.owl.prevItem=t.prevArr[t.prevArr.length-2],t.prevArr.shift(0),t.prevItem!==t.currentItem&&(t.checkPagination(),t.checkNavigation(),t.eachMoveUpdate(),!1!==t.options.autoPlay&&t.checkAp()),"function"==typeof t.options.afterMove&&t.prevItem!==t.currentItem&&t.options.afterMove.apply(this,[t.$elem])},stop:function(){this.apStatus="stop",p.clearInterval(this.autoPlayInterval)},checkAp:function(){"stop"!==this.apStatus&&this.play()},play:function(){var t=this;if(!(t.apStatus="play")===t.options.autoPlay)return!1;p.clearInterval(t.autoPlayInterval),t.autoPlayInterval=p.setInterval(function(){t.next(!0)},t.options.autoPlay)},swapSpeed:function(t){var e=this;"slideSpeed"===t?e.$owlWrapper.css(e.addCssSpeed(e.options.slideSpeed)):"paginationSpeed"===t?e.$owlWrapper.css(e.addCssSpeed(e.options.paginationSpeed)):"string"!=typeof t&&e.$owlWrapper.css(e.addCssSpeed(t))},addCssSpeed:function(t){return{"-webkit-transition":"all "+t+"ms ease","-moz-transition":"all "+t+"ms ease","-o-transition":"all "+t+"ms ease",transition:"all "+t+"ms ease"}},removeTransition:function(){return{"-webkit-transition":"","-moz-transition":"","-o-transition":"",transition:""}},doTranslate:function(t){return{"-webkit-transform":"translate3d("+t+"px, 0px, 0px)","-moz-transform":"translate3d("+t+"px, 0px, 0px)","-o-transform":"translate3d("+t+"px, 0px, 0px)","-ms-transform":"translate3d("+t+"px, 0px, 0px)",transform:"translate3d("+t+"px, 0px,0px)"}},transition3d:function(t){this.$owlWrapper.css(this.doTranslate(t))},css2move:function(t){this.$owlWrapper.css({left:t})},css2slide:function(t,e){var o=this;o.isCssFinish=!1,o.$owlWrapper.stop(!0,!0).animate({left:t},{duration:e||o.options.slideSpeed,complete:function(){o.isCssFinish=!0}})},checkBrowser:function(){var t,e,o,i,s="translate3d(0px, 0px, 0px)",n=m.createElement("div");n.style.cssText="  -moz-transform:"+s+"; -ms-transform:"+s+"; -o-transform:"+s+"; -webkit-transform:"+s+"; transform:"+s,t=/translate3d\(0px, 0px, 0px\)/g,o=null!==(e=n.style.cssText.match(t))&&1===e.length,i="ontouchstart"in p||p.navigator.msMaxTouchPoints,this.browser={support3d:o,isTouch:i}},moveEvents:function(){!1===this.options.mouseDrag&&!1===this.options.touchDrag||(this.gestures(),this.disabledEvents())},eventTypes:function(){var t=this,e=["s","e","x"];t.ev_types={},!0===t.options.mouseDrag&&!0===t.options.touchDrag?e=["touchstart.owl mousedown.owl","touchmove.owl mousemove.owl","touchend.owl touchcancel.owl mouseup.owl"]:!1===t.options.mouseDrag&&!0===t.options.touchDrag?e=["touchstart.owl","touchmove.owl","touchend.owl touchcancel.owl"]:!0===t.options.mouseDrag&&!1===t.options.touchDrag&&(e=["mousedown.owl","mousemove.owl","mouseup.owl"]),t.ev_types.start=e[0],t.ev_types.move=e[1],t.ev_types.end=e[2]},disabledEvents:function(){this.$elem.on("dragstart.owl",function(t){t.preventDefault()}),this.$elem.on("mousedown.disableTextSelect",function(t){return l(t.target).is("input, textarea, select, option")})},gestures:function(){var n=this,a={offsetX:0,offsetY:0,baseElWidth:0,relativePos:0,position:null,minSwipe:null,maxSwipe:null,sliding:null,dargging:null,targetElement:null};function s(t){if(void 0!==t.touches)return{x:t.touches[0].pageX,y:t.touches[0].pageY};if(void 0===t.touches){if(void 0!==t.pageX)return{x:t.pageX,y:t.pageY};if(void 0===t.pageX)return{x:t.clientX,y:t.clientY}}}function r(t){"on"===t?(l(m).on(n.ev_types.move,e),l(m).on(n.ev_types.end,o)):"off"===t&&(l(m).off(n.ev_types.move),l(m).off(n.ev_types.end))}function e(t){var e,o,i=t.originalEvent||t||p.event;n.newPosX=s(i).x-a.offsetX,n.newPosY=s(i).y-a.offsetY,n.newRelativeX=n.newPosX-a.relativePos,"function"==typeof n.options.startDragging&&!0!==a.dragging&&0!==n.newRelativeX&&(a.dragging=!0,n.options.startDragging.apply(n,[n.$elem])),(8<n.newRelativeX||n.newRelativeX<-8)&&!0===n.browser.isTouch&&(void 0!==i.preventDefault?i.preventDefault():i.returnValue=!1,a.sliding=!0),(10<n.newPosY||n.newPosY<-10)&&!1===a.sliding&&l(m).off("touchmove.owl"),e=function(){return n.newRelativeX/5},o=function(){return n.maximumPixels+n.newRelativeX/5},n.newPosX=Math.max(Math.min(n.newPosX,e()),o()),!0===n.browser.support3d?n.transition3d(n.newPosX):n.css2move(n.newPosX)}function o(t){var e,o,i,s=t.originalEvent||t||p.event;s.target=s.target||s.srcElement,!(a.dragging=!1)!==n.browser.isTouch&&n.$owlWrapper.removeClass("grabbing"),n.newRelativeX<0?n.dragDirection=n.owl.dragDirection="left":n.dragDirection=n.owl.dragDirection="right",0!==n.newRelativeX&&(e=n.getNewPosition(),n.goTo(e,!1,"drag"),a.targetElement===s.target&&!0!==n.browser.isTouch&&(l(s.target).on("click.disable",function(t){t.stopImmediatePropagation(),t.stopPropagation(),t.preventDefault(),l(t.target).off("click.disable")}),i=(o=l._data(s.target,"events").click).pop(),o.splice(0,0,i))),r("off")}n.isCssFinish=!0,n.$elem.on(n.ev_types.start,".owl-wrapper",function(t){var e,o=t.originalEvent||t||p.event;if(3===o.which)return!1;if(!(n.itemsAmount<=n.options.items)){if(!1===n.isCssFinish&&!n.options.dragBeforeAnimFinish)return!1;if(!1===n.isCss3Finish&&!n.options.dragBeforeAnimFinish)return!1;!1!==n.options.autoPlay&&p.clearInterval(n.autoPlayInterval),!0===n.browser.isTouch||n.$owlWrapper.hasClass("grabbing")||n.$owlWrapper.addClass("grabbing"),n.newPosX=0,n.newRelativeX=0,l(this).css(n.removeTransition()),e=l(this).position(),a.relativePos=e.left,a.offsetX=s(o).x-e.left,a.offsetY=s(o).y-e.top,r("on"),a.sliding=!1,a.targetElement=o.target||o.srcElement}})},getNewPosition:function(){var t=this,e=t.closestItem();return e>t.maximumItem?(t.currentItem=t.maximumItem,e=t.maximumItem):0<=t.newPosX&&(e=0,t.currentItem=0),e},closestItem:function(){var o=this,i=!0===o.options.scrollPerPage?o.pagesInArray:o.positionsInArray,s=o.newPosX,n=null;return l.each(i,function(t,e){s-o.itemWidth/20>i[t+1]&&s-o.itemWidth/20<e&&"left"===o.moveDirection()?(n=e,!0===o.options.scrollPerPage?o.currentItem=l.inArray(n,o.positionsInArray):o.currentItem=t):s+o.itemWidth/20<e&&s+o.itemWidth/20>(i[t+1]||i[t]-o.itemWidth)&&"right"===o.moveDirection()&&(!0===o.options.scrollPerPage?(n=i[t+1]||i[i.length-1],o.currentItem=l.inArray(n,o.positionsInArray)):(n=i[t+1],o.currentItem=t+1))}),o.currentItem},moveDirection:function(){var t;return this.newRelativeX<0?(t="right",this.playDirection="next"):(t="left",this.playDirection="prev"),t},customEvents:function(){var o=this;o.$elem.on("owl.next",function(){o.next()}),o.$elem.on("owl.prev",function(){o.prev()}),o.$elem.on("owl.play",function(t,e){o.options.autoPlay=e,o.play(),o.hoverStatus="play"}),o.$elem.on("owl.stop",function(){o.stop(),o.hoverStatus="stop"}),o.$elem.on("owl.goTo",function(t,e){o.goTo(e)}),o.$elem.on("owl.jumpTo",function(t,e){o.jumpTo(e)})},stopOnHover:function(){var t=this;!0===t.options.stopOnHover&&!0!==t.browser.isTouch&&!1!==t.options.autoPlay&&(t.$elem.on("mouseover",function(){t.stop()}),t.$elem.on("mouseout",function(){"stop"!==t.hoverStatus&&t.play()}))},lazyLoad:function(){var t,e,o,i,s=this;if(!1===s.options.lazyLoad)return!1;for(t=0;t<s.itemsAmount;t+=1)"loaded"!==(e=l(s.$owlItems[t])).data("owl-loaded")&&(o=e.data("owl-item"),"string"==typeof(i=e.find(".lazyOwl")).data("src")?(void 0===e.data("owl-loaded")&&(i.hide(),e.addClass("loading").data("owl-loaded","checked")),(!0!==s.options.lazyFollow||o>=s.currentItem)&&o<s.currentItem+s.options.items&&i.length&&s.lazyPreload(e,i)):e.data("owl-loaded","loaded"))},lazyPreload:function(t,e){var o,i=this,s=0;function n(){t.data("owl-loaded","loaded").removeClass("loading"),e.removeAttr("data-src"),"fade"===i.options.lazyEffect?e.fadeIn(400):e.show(),"function"==typeof i.options.afterLazyLoad&&i.options.afterLazyLoad.apply(this,[i.$elem])}"DIV"===e.prop("tagName")?(e.css("background-image","url("+e.data("src")+")"),o=!0):e[0].src=e.data("src"),function t(){s+=1,i.completeImg(e.get(0))||!0===o?n():s<=100?p.setTimeout(t,100):n()}()},autoHeight:function(){var e,o=this,i=l(o.$owlItems[o.currentItem]).find("img");function s(){var t=l(o.$owlItems[o.currentItem]).height();o.wrapperOuter.css("height",t+"px"),o.wrapperOuter.hasClass("autoHeight")||p.setTimeout(function(){o.wrapperOuter.addClass("autoHeight")},0)}void 0!==i.get(0)?(e=0,function t(){e+=1,o.completeImg(i.get(0))?s():e<=100?p.setTimeout(t,100):o.wrapperOuter.css("height","")}()):s()},completeImg:function(t){return!!t.complete&&("undefined"===typeof t.naturalWidth||0!==t.naturalWidth)},onVisibleItems:function(){var t,e=this;for(!0===e.options.addClassActive&&e.$owlItems.removeClass("active"),e.visibleItems=[],t=e.currentItem;t<e.currentItem+e.options.items;t+=1)e.visibleItems.push(t),!0===e.options.addClassActive&&l(e.$owlItems[t]).addClass("active");e.owl.visibleItems=e.visibleItems},transitionTypes:function(t){this.outClass="owl-"+t+"-out",this.inClass="owl-"+t+"-in"},singleItemTransition:function(){var t,e=this,o=e.outClass,i=e.inClass,s=e.$owlItems.eq(e.currentItem),n=e.$owlItems.eq(e.prevItem),a=Math.abs(e.positionsInArray[e.currentItem])+e.positionsInArray[e.prevItem],r=Math.abs(e.positionsInArray[e.currentItem])+e.itemWidth/2,l="webkitAnimationEnd oAnimationEnd MSAnimationEnd animationend";e.isTransition=!0,e.$owlWrapper.addClass("owl-origin").css({"-webkit-transform-origin":r+"px","-moz-perspective-origin":r+"px","perspective-origin":r+"px"}),n.css((t=a,{position:"relative",left:t+"px"})).addClass(o).on(l,function(){e.endPrev=!0,n.off(l),e.clearTransStyle(n,o)}),s.addClass(i).on(l,function(){e.endCurrent=!0,s.off(l),e.clearTransStyle(s,i)})},clearTransStyle:function(t,e){var o=this;t.css({position:"",left:""}).removeClass(e),o.endPrev&&o.endCurrent&&(o.$owlWrapper.removeClass("owl-origin"),o.endPrev=!1,o.endCurrent=!1,o.isTransition=!1)},owlStatus:function(){var t=this;t.owl={userOptions:t.userOptions,baseElement:t.$elem,userItems:t.$userItems,owlItems:t.$owlItems,currentItem:t.currentItem,prevItem:t.prevItem,visibleItems:t.visibleItems,isTouch:t.browser.isTouch,browser:t.browser,dragDirection:t.dragDirection}},clearEvents:function(){this.$elem.off(".owl owl mousedown.disableTextSelect"),l(m).off(".owl owl"),l(p).off("resize",this.resizer)},unWrap:function(){var t=this;0!==t.$elem.children().length&&(t.$owlWrapper.unwrap(),t.$userItems.unwrap().unwrap(),t.owlControls&&t.owlControls.remove()),t.clearEvents(),t.$elem.attr("style",t.$elem.data("owl-originalStyles")||"").attr("class",t.$elem.data("owl-originalClasses"))},destroy:function(){this.stop(),p.clearInterval(this.checkVisible),this.unWrap(),this.$elem.removeData()},reinit:function(t){var e=l.extend({},this.userOptions,t);this.unWrap(),this.init(e,this.$elem)},addItem:function(t,e){var o,i=this;return!!t&&(0===i.$elem.children().length?(i.$elem.append(t),i.setVars(),!1):(i.unWrap(),(o=void 0===e||-1===e?-1:e)>=i.$userItems.length||-1===o?i.$userItems.eq(-1).after(t):i.$userItems.eq(o).before(t),void i.setVars()))},removeItem:function(t){var e;if(0===this.$elem.children().length)return!1;e=void 0===t||-1===t?-1:t,this.unWrap(),this.$userItems.eq(e).remove(),this.setVars()}};l.fn.owlCarousel=function(e){return this.each(function(){if(!0===l(this).data("owl-init"))return!1;l(this).data("owl-init",!0);var t=Object.create(o);t.init(e,this),l.data(this,"owlCarousel",t)})},l.fn.owlCarousel.options={items:5,itemsCustom:!1,itemsDesktop:[1199,4],itemsDesktopSmall:[979,3],itemsTablet:[768,2],itemsTabletSmall:!1,itemsMobile:[479,1],singleItem:!1,itemsScaleUp:!1,slideSpeed:200,paginationSpeed:800,rewindSpeed:1e3,autoPlay:!1,stopOnHover:!1,navigation:!1,navigationText:["prev","next"],rewindNav:!0,scrollPerPage:!1,pagination:!0,paginationNumbers:!1,responsive:!0,responsiveRefreshRate:200,responsiveBaseWidth:p,baseClass:"owl-carousel",theme:"owl-theme",lazyLoad:!1,lazyFollow:!0,lazyEffect:"fade",autoHeight:!1,jsonPath:!1,jsonSuccess:!1,dragBeforeAnimFinish:!0,mouseDrag:!0,touchDrag:!0,addClassActive:!1,transitionStyle:!1,beforeUpdate:!1,afterUpdate:!1,beforeInit:!1,afterInit:!1,beforeMove:!1,afterMove:!1,afterAction:!1,startDragging:!1,afterLazyLoad:!1}}(jQuery,window,document);