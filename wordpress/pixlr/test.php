<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Pixlr API Example</title>

	<script type="text/javascript" src="pixlr.js"></script> 
    <script type="text/javascript">
		pixlr.settings.target = 'http://coursera.challinge.net/editor-tools/test.php';
		pixlr.settings.exit = 'http://coursera.challinge.net/editor-tools/test.php';
		pixlr.settings.credentials = false;
		pixlr.settings.method = 'post';
	</script>
</head>
<body>
    <!-- example of opening the editor with onclick on IMG tag using ff_setup(URL) function -->
    <!-- via thumbnail -->
    <!-- <img src="http://coursera.challinge.net/editor-tools/image_thumb.jpg" 
	    id="http://www.yourpage.com/image.jpg" 
	    onclick="ff_setup(this.id)" /> -->
	<!-- plain -->
	
    <b>Open image editor overlay</b><br />
<a href="javascript:pixlr.overlay.show({image:'', title:'Example image 1', service:'editor'});"><img src="http://coursera.challinge.net/editor-tools/koala.jpg" title="Edit in pixlr" /></a><br /><br />
<br />

</body>
</html>

