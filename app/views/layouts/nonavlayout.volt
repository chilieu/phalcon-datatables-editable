<!DOCTYPE html>
<html lang="en">
<head>

<?php $this->partial("shared/header") ?>

</head>
    <body>
        
        <div class="container-fluid">
            {{ content() }}
        </div>

	</body>
</html>

<SCRIPT TYPE="text/javascript">

$('.fancybox').fancybox({
    type: 'iframe',
    afterClose: function () { // USE THIS IT IS YOUR ANSWER THE KEY WORD IS "afterClose"
        parent.location.reload(true);
    }
});

</SCRIPT>
