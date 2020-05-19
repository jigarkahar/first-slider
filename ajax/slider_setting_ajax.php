<?php
global $wpdb;
if ($_REQUEST['process'] == "get_sliders") {
    $output = array();
    $output['msg'] = "";
    $table_name = $wpdb->prefix ."first_slider";
    $get_slider = $wpdb->get_results("SELECT * FROM $table_name");
    foreach ($get_slider as $get_sliders) {
        if($get_sliders->slider_status == 0){
            $slider_id =  $get_sliders->slider_id;
            if ($get_sliders->slider_name) {
                $slider_name = $get_sliders->slider_name;
            } else {
                $slider_name = "slider ".$slider_id;
            }
            $output['msg'] = $output['msg'].
                '<div class="mainslider'.$slider_id.' slider_lst">
                <button type="button" class="removslider qq-upload-cancel-selector qq-upload-cancel" data-slidernum="'
                .$slider_id.'"><i class="fa fa-times" aria-hidden="true"></i></button>
            <button id="'.$slider_id.'" class="slider_btn">'.$slider_name.'</button></div>';
        }

    }
    echo json_encode($output);
} elseif ($_REQUEST['process'] == "add_new_slider") {
    $output = array();
    $slider_id = $_REQUEST['slider_id'];
    $table_name = $wpdb->prefix ."first_slider";
    $get_slider_sequence = $wpdb->get_row("SELECT * FROM $table_name WHERE slider_id ='".$slider_id."'");
    $sequence = array();
    if($get_slider_sequence->slider_sequence){
        $sequence = unserialize($get_slider_sequence->slider_sequence);
    }
    if ($_FILES) {
        $countfiles = count($_FILES['files']['name']);
        $files = $_FILES["files"];
        foreach ($files['name'] as $key => $value) {
            if ($files['name'][$key]) {
                $file = array(
                    'name' => $files['name'][$key],
                    'type' => $files['type'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'error' => $files['error'][$key],
                    'size' => $files['size'][$key]
                );
                $_FILES = array ("my_file_upload" => $file);
                foreach ($_FILES as $file => $array) {
                    $newupload = my_handle_attachment($file, $pid);
                    if ($newupload) {
                            array_push($sequence,$newupload);
                    }
                }
            }
        }
    }
//    print_r($sequence);
    foreach ($sequence as $key => $sequences) {
        $post_id =  $sequences;
        $table_name = $wpdb->prefix ."posts";
        $img_path = $wpdb->get_row("SELECT * FROM $table_name WHERE id = '".$post_id."'");
        $output['msg'][] = '<li id="'.$post_id.'" class="show_slc_img">
        <button type="button" class="removeimg qq-upload-cancel-selector qq-upload-cancel" data-imgnum="'.$post_id.'">
            <i class="fa fa-times" aria-hidden="true"></i>
        </button>
        <img  src="'.$img_path->guid.'"alt="Smiley face" data-srtid="'.$key.'"></li>';
    }
    $serializeslider = serialize($sequence);
    $table_name = $wpdb->prefix ."first_slider";

    $result = $wpdb->update($table_name, array('slider_sequence' => $serializeslider),array(slider_id => $slider_id));

    echo json_encode($output);
} elseif ($_REQUEST['process'] == "get_slider_data") {
    $output = array();
    $slider_id = $_REQUEST['slider_id'];
    $output['msg'] =  array();
    $table_name = $wpdb->prefix ."first_slider";
    $get_slider_sequence = $wpdb->get_row("SELECT * FROM $table_name WHERE slider_id ='".$slider_id."'");
//    echo $wpdb->last_query;
    if ($get_slider_sequence) {
        $output['title'] = $get_slider_sequence->slider_name;
        $get_slider_img = unserialize($get_slider_sequence->slider_sequence);
//        print_r($get_slider_img);
        foreach ($get_slider_img as $key => $get_slider_imgs) {
//            echo $get_slider_imgs;
            $post_id =  $get_slider_imgs;
            $table_name = $wpdb->prefix ."posts";
            $img_path = $wpdb->get_row("SELECT * FROM $table_name WHERE id = '".$post_id."'");
//            echo $wpdb->last_query;
//            echo $img_path->guid;
            $output['msg'][] = '<li id="'.$post_id.'" class="show_slc_img">
            <button type="button" class="removeimg qq-upload-cancel-selector qq-upload-cancel" data-imgnum="'.$post_id.'">
            <i class="fa fa-times" aria-hidden="true"></i>
            </button>
            <img  src="'.$img_path->guid.'"alt="Smiley face" data-srtid="'.$key.'"></li>';
        }
    }

    echo json_encode($output);
} elseif ($_REQUEST['process'] == "update_slider_sequence") {
    $output = array();
    $sortedsequence = $_REQUEST['sortedsequence'];
    $slider_id = $_REQUEST['slider_id'];
    $serializeslider = serialize($sortedsequence);
    $table_name = $wpdb->prefix ."first_slider";

    $result = $wpdb->update($table_name, array('slider_sequence' => $serializeslider),array(slider_id => $slider_id));

    echo json_encode($output);
} elseif ($_REQUEST['process'] == "create_new_slider") {
    $output = array();
    $new_slider_name = $_REQUEST['new_slider_name'];
//    echo $new_slider_name;
    $table_name = $wpdb->prefix ."first_slider";
    $rows_affected = $wpdb->insert( $table_name, array( 'slider_name' => $new_slider_name));
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($rows_affected);
    $output['msg'] = $wpdb->insert_id;

    echo json_encode($output);
} elseif ($_REQUEST['process'] == "show_slct_imgs") {
    $output = array();
    $slider_id = $_REQUEST['slider_id'];
    $selected_img_id = $_REQUEST['selected_img_id'];
    $table_name = $wpdb->prefix ."first_slider";
    $slider_data = $wpdb->get_row("SELECT * FROM $table_name WHERE slider_id = '".$slider_id."'");
//    echo "data = ".$slider_data->slider_sequence;
//    exit();
    if($slider_data){
        if($slider_data->slider_sequence != ""){
            $new_array = array_merge(unserialize($slider_data->slider_sequence),$selected_img_id);
        }else{
//            echo "inside";
            $new_array = $selected_img_id;
//            print_r($new_array);
        }
    }

//    echo $slider_id;
    $serializeslider = serialize($new_array);
    $table_name = $wpdb->prefix ."first_slider";
    $result = $wpdb->update($table_name, array('slider_sequence' => $serializeslider),array(slider_id => $slider_id));
//    print_r($selected_img_id);
    foreach ($selected_img_id as $key => $selected_img_ids) {
        $post_id =  $selected_img_ids;
        $table_name = $wpdb->prefix ."posts";
        $img_path = $wpdb->get_row("SELECT * FROM $table_name WHERE id = '".$post_id."'");
//            echo $wpdb->last_query;
//            echo $img_path->guid;
        $output['msg'][] = '<li id="'.$post_id.'" class="show_slc_img">
        <button type="button" class="removeimg qq-upload-cancel-selector qq-upload-cancel" data-imgnum="'.$post_id.'">
        <i class="fa fa-times" aria-hidden="true"></i>
        </button>
        <img  src="'.$img_path->guid.'"alt="Smiley face" data-srtid="'.$key.'"></li>';
    }
    echo json_encode($output);
} elseif ($_REQUEST['process'] == "rmsldimg") {
    $output = array();
    $slider_id = $_REQUEST['slider_id'];
    $imgid = $_REQUEST['imgid'];
    $table_name = $wpdb->prefix ."first_slider";
    $slider_data = $wpdb->get_row("SELECT * FROM $table_name WHERE slider_id = '".$slider_id."'");
//    echo $wpdb->last_query;
//    exit();
    $imgarray = unserialize($slider_data->slider_sequence);
//    print_r($imgarray);
    if(in_array($imgid,$imgarray)){
//        echo "aave chhe";
        if (($key = array_search($imgid, $imgarray)) !== false) {
//            echo "<".$key.">";
            unset($imgarray[$key]);
            $rearr = array_values($imgarray);
        }
    }
    $serializeslider = serialize($rearr);
    $table_name = $wpdb->prefix ."first_slider";
    $result = $wpdb->update($table_name, array('slider_sequence' => $serializeslider),array(slider_id => $slider_id));
    echo json_encode($output);
} elseif ($_REQUEST['process'] == "rmslider") {
    $output = array();
    $slider_id = $_REQUEST['slider_id'];
    $table_name = $wpdb->prefix ."first_slider";
    $result = $wpdb->update($table_name, array('slider_status' => 1),array(slider_id => $slider_id));
    echo json_encode($output);
}
wp_die();
