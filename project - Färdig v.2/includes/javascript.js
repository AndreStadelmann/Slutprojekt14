function onLoad() {
	$(window).resize(onResize);
	onResize();
}

function onResize() {
	if( !/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
			$("body").css({"font-size": "1.5vw"});
	}
	else {
	$("body").css({"font-size": "1.2vw"});
	}
	//$("footer").css({position: "absolute", top: $(document).height() - $("footer").height(), display: "block"});
}