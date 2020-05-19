<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<div id="show_sliders" style="display: none">
    <div class="plin_head"><label class="plname">First Slider</label></div>
    <div class="crt_slider">
        <button id="crt_new">Create new slider</button>

        <div id="sliders_lst"></div>
    </div>
    <!-- The Modal -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Add New Slider</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <input type="text" id="slider_name" placeholder="New Slider Name" required>
                    <div class="error_field"></div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" id="create_slider" class="btn btn-primary">Create</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--add new slider-->
<div id="crt_new_slider" style="display: none">
    <input type="hidden" id="glbsldid">
    <div id="back_btn"><button class="bacl_btn"> <i class="fa fa-arrow-left"></i>  Back</button> </div>
    <div class="slider_info_main">

        <div class="shortcode">
            <div class="sc_info">
                <label class="sc_title">Slider Name</label>
                <label class="sc_desc" id="slider_title"></label>
            </div>
            <div class="sc_info">
                <label class="sc_title">Shortcode</label>
                <label class="sc_desc">Copy and paste this shortcode into your posts or pages:</label>
                <label class="sc_appcode">[firstslider slider_id = '<label class="slider_id"></label>']</label>
            </div>
            <div class="sc_info">
                <label class="sc_title">PHP code</label>
                <label class="sc_desc">Paste the PHP code into your theme's file:</label>
                <label class="sc_appcode">
                    echo do_shortcode ([firstslider slider_id = '<label class="slider_id"></label>'])
                    </label>
            </div>
        </div>
    </div>
    <form class="add_img_form">
        <div class="add_img_main">
            <div class="example">
                <input type="button" class="upload_image_button" value="Add Slide">
<!--                <input type="file" id="files1" name="files1[]" multiple />-->
            </div>
            <div class="example">
                <div id="drop_zone">Drop files here
                </div>
                <ul id="file_list2" class="connectedSortable"></ul>
            </div>
        </div>

<!--        <input type="button" id="save_slider" value="Add Image To Slider">-->
<!--        <button id="save_slider">Save Slider</button>-->
    </form>
    <div id="upload_img_ldr" style="display: none"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i></div>
    <ul id="show_slider_img"></ul>


<!--    <ul id="sortable1" class="connectedSortable">
        <li class="ui-state-default">Item 1</li>
        <li class="ui-state-default">Item 2</li>
        <li class="ui-state-default">Item 3</li>
        <li class="ui-state-default">Item 4</li>
        <li class="ui-state-default">Item 5</li>
    </ul>-->

</div>


<?php
//	 echo "Admin Page Test";

?>