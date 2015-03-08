<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <title>Compatibility Project</title>

    <!-- Bootstrap core CSS -->
    {{ stylesheet_link("css/bootstrap.min.css") }}    

    <!-- Adjustment bootstrap CSS -->
    {{ stylesheet_link("css/style.css") }}    

    <!--DataTables CSS-->
    {{ stylesheet_link("lib/datatables/css/jquery.dataTables.min.css") }}

    <!--JQuery JS-->
    {{ javascript_include("js/jquery.min.js") }}
    <!--DataTables JS-->
    {{ javascript_include("lib/datatables/js/jquery.dataTables.min.js") }}
    <!--JQuery UI JS-->
    {{ javascript_include("js/jquery-ui.js") }}
    <!--Editable DataTables JS-->
    {{ javascript_include("lib/editable/jquery.jeditable.js") }}
    {{ javascript_include("lib/editable/jquery.validate.js") }}
    {{ javascript_include("lib/editable/jquery.dataTables.editable.js") }}
    <!--Reodering DataTables JS-->
    <!--{{ javascript_include("lib/editable/jquery.dataTables.rowReordering.js") }}
    {{ javascript_include("lib/editable/jquery.dataTables.rowGrouping.js") }}-->

    <!-- Add fancyBox main JS and CSS files -->
    {{ javascript_include("lib/fancybox/jquery.fancybox.js?v=2.1.5") }}
    {{ stylesheet_link("lib/fancybox/jquery.fancybox.css?v=2.1.5") }}

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    {{ javascript_include("js/html5shiv.js") }}
    {{ javascript_include("js/respond.min.js") }}
    <![endif]-->

    <link rel="shortcut icon" href="{{ static_url("files/favicon.ico") }}" />

</head>
	<body>
        
		<div class="container-fluid">
			{{ content() }}
		</div>


        <footer class="footer">
          <div class="container-fluid">
                <p class="text-muted">Compatibility project &copy; ABMX Servers.</p>
          </div>
        </footer>

	</body>
</html>

<script>$('.fancybox').fancybox();</script>
