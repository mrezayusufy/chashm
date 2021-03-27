
<script type="text/javascript">
// show ajax modal
function showAjaxModal(url, title="")
{
    // SHOWING AJAX PRELOADER IMAGE
    jQuery('#modal_ajax .modal-body').html('<div class="loading"><div class="spinner-border"></div></div>');
    jQuery('#modal_ajax .modal-body').css('height', screen.height - 250);
    jQuery('#modal_ajax .modal-body').css('overflow', "auto");
    jQuery('.modal-title').text(title);
    // LOADING THE AJAX MODAL
    jQuery('#modal_ajax').modal('show', {backdrop: 'true'});
    // SHOW AJAX RESPONSE ON REQUEST SUCCESS
    $.ajax({
    url: url,
        success: function(response)
    {
        jQuery('#modal_ajax .modal-body').html(response);
    }
    });
}
</script>

<!-- (Ajax Modal) -->
<div class="modal fade customized-modal" id="modal_ajax">

    <div class="modal-dialog" >

        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo $system_name;?></h4>
            </div>

            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function confirm_modal(delete_url, title="")
{
    jQuery('#modal_ajax .modal-body').css('height', screen.height - 300);
    jQuery('#modal_ajax .modal-body').css('overflow', "auto");
    jQuery('#modal_ajax .modal-body').css('height', screen.height - 300);
    jQuery('#modal-4').modal('show', {backdrop: 'false'});
    jQuery('#modal-4 .title').text(title);
    document.getElementById('delete_link').setAttribute('href' , delete_url);
}
</script>

<!-- (Normal Modal)-->
<div class="modal fade customized-modal" id="modal-4">

    <div class="modal-dialog" role="modal-dialog">
    
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" style="text-align:center;"><?= get_phrase('Are_you_sure_to_delete_this_information_?')?></h4>
                <h4 style="text-align:center;" class="title"></h4>
            </div>

            <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                <a href="#" class="btn btn-danger" id="delete_link"><?php echo get_phrase('delete');?></a>
                <button type="button" class="btn btn-info" data-dismiss="modal"><?php echo get_phrase('cancel');?></button>
            </div>
        </div>
    </div>
</div>

<!-- custom width modal -->
<script type="text/javascript">
    function showCustomWidthModal(url)
    {
        // SHOWING AJAX PRELOADER IMAGE
        jQuery('#modal-2 .modal-body').html('<div class="loading"><div class="spinner-border"></div></div>');

        // LOADING THE AJAX MODAL
        jQuery('#modal-2').modal('show', {backdrop: 'true'});

        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
        $.ajax({
            url: url,
            success: function(response)
            {
                jQuery('#modal-2 .modal-body').html(response);
            }
        });
    }
</script>

<div class="modal fade custom-width" id="modal-2">
    <div class="modal-dialog" style="width: 75%;">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo $system_name;?></h4>
            </div>

            <div class="modal-body">

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
