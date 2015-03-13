<!DOCTYPE html>
<html lang="en">
<head>

<?php $this->partial("shared/header") ?>

</head>
    <body>
        
        <?php $this->partial("shared/navs") ?>

        <div class="container-fluid">
            {{ content() }}
        </div>

        <?php $this->partial("shared/footer") ?>

	</body>
</html>

<SCRIPT TYPE="text/javascript">

$(document).ready(function() {

    $('.fancybox').fancybox({
        type: 'iframe',
        afterClose: function () { // USE THIS IT IS YOUR ANSWER THE KEY WORD IS "afterClose"
            parent.location.reload(true);
        }
    });

    $('[data-toggle="popover"]').popover({trigger: 'hover','placement': 'top'});

});

</SCRIPT>
