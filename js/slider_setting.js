jQuery(function ($) {
    GetURLParameter();
    $("#file_list2,#show_slider_img").sortable({
        axis: 'y',
        stop: function (event, ui) {
            var sortedIDs = $(this).sortable("toArray");
            // console.log(sortedIDs);crt_new
            var sPageURL = window.location.search.substring(1);
            var sURLVariables = sPageURL.split('&');
            if (sURLVariables.length==2) {
                for (var i = 1; i < sURLVariables.length; i++) {
                    var sParameterName = sURLVariables[i].split('=');
                    var slider_id = sParameterName[1];
                }
            }
            var data ={
                action : "create_slider",
                slider_id : slider_id,
                sortedsequence : sortedIDs,
                process:"update_slider_sequence"
            };
            $.ajax({
                url: myAjax.ajaxurl,
                type: 'post',
                data: data,
                beforeSend: function () {
                    // Handle the beforeSend event
                    // $("#upload_img_ldr").show();
                },
                success: function (response) {
                    var result = JSON.parse(response);
                    // for (var i = 0; i<= result['msg'].length; i++) {
                    //     $("#show_slider_img").append(result['msg'][i]);
                    // }
                }
            });
        }
    });

    $(document).on('click', '.slider_btn', function () {
        var slider_id = $(this).attr("id");
        openWin(slider_id);
    });

    $(document).on('click', '#back_btn', function () {
        window.history.back();
    });

    $(document).on('click', '#crt_new', function () {
        $("#myModal").modal();
    });

    $(document).on('click', '#create_slider', function () {
        var new_slider_name = $("#slider_name").val();
        // alert(new_slider_name);
        if(new_slider_name == ""){
            $(".error_field").html("*Enter slider name");
        }else{
            $("#myModal").modal("hide");
            $(".error_field").html("");
            var data ={
                action : "create_slider",
                new_slider_name : new_slider_name,
                process:"create_new_slider"
            };
            $.ajax({
                url: myAjax.ajaxurl,
                type: 'post',
                data: data,
                beforeSend: function () {
                    // Handle the beforeSend event
                    // $("#upload_img_ldr").show();
                },
                success: function (response) {
                    var result = JSON.parse(response);
                    console.log(result['msg']);
                    openWin(result['msg']);

                }
            });
        }

    });

    $("#save_slider").click(function () {
        var form_data = new FormData();
        var totalfiles = document.getElementById('files1').files.length;
        for (var index = 0; index < totalfiles; index++) {
            form_data.append("files[]", document.getElementById('files1').files[index]);
        }
        form_data.append("action","create_slider");
        form_data.append("process","add_new_slider");
        $.ajax({
            url: myAjax.ajaxurl,
            type: 'post',
            data: form_data,
            dataType: 'json',
            contentType: false,
            processData: false,
            beforeSend: function () {
                // $("#upload_img_ldr").show();
            },
            success: function (response) {
                $("#upload_img_ldr").hide();
                $("#file_list2").html("");
                for (var index = 0; index < response.length; index++) {
                    var src = response[index];
                }
            }
        });
    });
    // Uploading files
    var file_frame;

    jQuery('.upload_image_button').on('click', function( event ){

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( file_frame ) {
            file_frame.open();
            return;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: jQuery( this ).data( 'uploader_title' ),
            button: {
                text: jQuery( this ).data( 'uploader_button_text' ),
            },
            multiple: true  // Set to true to allow multiple files to be selected
        });
        var slc_imgs = [] ;
        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
            var selection = file_frame.state().get('selection');
            selection.map( function( attachment ) {
                attachment = attachment.toJSON();
                slc_imgs.push(attachment.id);
                    // console.log(attachment.id);
                // Do something with attachment.id and/or attachment.url here
            });
            var sPageURL = window.location.search.substring(1);
            var sURLVariables = sPageURL.split('&');
            if (sURLVariables.length==2) {
                for (var i = 1; i < sURLVariables.length; i++) {
                    var sParameterName = sURLVariables[i].split('=');
                    $("#slider_id").html(sParameterName[1]);
                    if (sParameterName[0] == "id") {
                        var data ={
                            action : "create_slider",
                            slider_id : sParameterName[1],
                            selected_img_id : slc_imgs,
                            process:"show_slct_imgs"
                        };
                        $.ajax({
                            url: myAjax.ajaxurl,
                            type: 'post',
                            data: data,
                            beforeSend: function () {
                                // Handle the beforeSend event
                                // $("#upload_img_ldr").show();
                            },
                            success: function (response) {
                                var result = JSON.parse(response);
                                $("#slider_title").html(result['title']);
                                for (var i = 0; i<= result['msg'].length; i++) {
                                    $("#show_slider_img").append(result['msg'][i]);
                                }
                                swal("Good job!", "Image uploaded Successfully!", "success");
                            }
                        });
                    }
                }
            }

        });

        // Finally, open the modal
        file_frame.open();

    });

    $(document).on('click', '.removeimg', function () {
        var imgid = $(this).data('imgnum');
        var sliderid = $("#glbsldid").val();
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this imaginary file!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $("#show_slider_img #"+imgid).remove();
                var data ={
                    action : "create_slider",
                        slider_id : sliderid,
                        imgid : imgid,
                        process:"rmsldimg"
                    };
                    $.ajax({
                        url: myAjax.ajaxurl,
                        type: 'post',
                        data: data,
                        beforeSend: function () {
                        },
                        success: function (response) {
                        }
                    });
                    swal("Poof! Your imaginary file has been deleted!", {
                        icon: "success",
                    });
                } else {
                    swal("Your imaginary file is safe!");
                }
            });
    });

    $(document).on('click', '.removslider', function () {
        var sliderid = $(this).data('slidernum');
        // var sliderid = $("#glbsldid").val();

        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this slider !",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $("#sliders_lst .mainslider"+sliderid).remove();
                    var data ={
                        action : "create_slider",
                        slider_id : sliderid,
                        process:"rmslider"
                    };
                    $.ajax({
                        url: myAjax.ajaxurl,
                        type: 'post',
                        data: data,
                        beforeSend: function () {
                        },
                        success: function (response) {
                            var result = JSON.parse(response);
                            $("#slider_title").html(result['title']);
                            for (var i = 0; i<= result['msg'].length; i++) {
                                $("#show_slider_img").append(result['msg'][i]);
                            }
                        }
                    });
                    swal("Poof! Your slider has been deleted!", {
                        icon: "success",
                    });
                } else {
                    swal("Your slider is safe!");
                }
            });
    });

});

function openWin(slider_id)
{
    window.open(window.location.href+"&id="+slider_id,"_self");
}

function GetURLParameter(sParam)
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    if (sURLVariables.length==2) {
        for (var i = 1; i<sURLVariables.length; i++) {
            var sParameterName = sURLVariables[i].split('=');
            $("#glbsldid").val(sParameterName[1]);
            $(".slider_id").html(sParameterName[1]);
            if (sParameterName[0] == "id") {
                var data ={
                    action : "create_slider",
                    slider_id : sParameterName[1],
                    process:"get_slider_data"
                };
                $.ajax({
                    url: myAjax.ajaxurl,
                    type: 'post',
                    data: data,
                    beforeSend: function () {
                        // Handle the beforeSend event
                        // $("#upload_img_ldr").show();
                    },
                    success: function (response) {
                        var result = JSON.parse(response);
                        $("#slider_title").html(result['title']);


                        for (var i = 0; i<= result['msg'].length; i++) {
                            $("#show_slider_img").append(result['msg'][i]);
                        }
                    }
                });
            }
        }
        jQuery("#crt_new_slider").show();
    } else {
        jQuery("#show_sliders").show();
        var data ={action : "create_slider",process:"get_sliders"};
        $.ajax({
            url: myAjax.ajaxurl,
            type: 'post',
            data: data,
            beforeSend: function () {
                // Handle the beforeSend event
                // $("#upload_img_ldr").show();
            },
            success: function (response) {
                var result = JSON.parse(response);
                $("#sliders_lst").html(result['msg']);
                // $("#upload_img_ldr").hide();
                // $("#file_list2").html("");
                // for (var index = 0; index < response.length; index++) {
                //     var src = response[index];
                //     console.log(src);
                //     // Add img element in <div id='preview'>
                //     // $('#preview').append('<img src="'+src+'" width="200px;" height="200px">');
                // }
            }
        });
    }
}

$("#files1").change(function () {
    if (typeof (FileReader) != "undefined") {
        var dvPreview = $("#file_list2");
        dvPreview.html("");
        var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
        $($(this)[0].files).each(function () {
            var file = $(this);
            if (regex.test(file[0].name.toLowerCase())) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    // $("#file_list2").append("<li>");
                    // var img = $("<img />");
                    // img.attr("style", "height:100px;width: 100px");
                    // img.attr("src", e.target.result);
                    $("#file_list2").append("<li><img class='sldr_img_prv' src='"+e.target.result+"'></li>");
                    // $("#file_list2").append("</li>");
                    // dvPreview.append(img);
                }
                reader.readAsDataURL(file[0]);
            } else {
                alert(file[0].name + " is not a valid image file.");
                dvPreview.html("");
                return false;
            }
        });
    } else {
        alert("This browser does not support HTML5 FileReader.");
    }
});



// Setup the Drag n' Drop listeners.
var dropZone = document.getElementById('drop_zone');
dropZone.addEventListener('dragover', handleDragOver, false);
dropZone.addEventListener('drop', handleFileSelect2, false);

// DROP ZONE
function handleFileSelect2(evt)
{
    var output = [];
    var form_data = new FormData();
    evt.stopPropagation();
    evt.preventDefault();
    var files = evt.dataTransfer.files; // FileList object.
    // files is a FileList of File objects. List some properties.
    var sPageURL = window.location.search.substring(1);
    // alert(sPageURL);
    var sURLVariables = sPageURL.split('&');
    if (sURLVariables.length==2) {
        for (var i = 1; i < sURLVariables.length; i++) {
            var sParameterName = sURLVariables[i].split('=');
            var slider_id = sParameterName[1];
            // alert(slider_id);
        }
    }
    form_data.append("slider_id", slider_id);
    for (var i = 0, f; f = files[i]; i++) {
        form_data.append("files[]", files[i]);
    }
    form_data.append("action","create_slider");
    form_data.append("process","add_new_slider");
    $.ajax({
        url: myAjax.ajaxurl,
        type: 'post',
        data: form_data,
        dataType: 'json',
        contentType: false,
        processData: false,
        beforeSend: function () {
            $("#upload_img_ldr").show();
        },
        success: function (response) {
            var result = JSON.parse(JSON.stringify(response));
            $("#show_slider_img").html("");
            for (var i = 0; i<= result['msg'].length; i++) {
                $("#show_slider_img").append(result['msg'][i]);
            }

            swal("Good job!", "Image uploaded Successfully!", "success");
        }
    });
}
function handleDragOver(evt)
{
    evt.stopPropagation();
    evt.preventDefault();
    evt.dataTransfer.dropEffect = 'copy'; // Explicitly show this is a copy.
}
