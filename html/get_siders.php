<?php
add_shortcode('firstslider', 'show_slider_id_wise');
function show_slider_id_wise($sliderid)
{
    global $wpdb;
    $sid = shortcode_atts(array('slider_id' => 'world'), $sliderid);
//    print_r($sid);
//    echo $sid['slider_id'];

    $output = array();
    $slider_id = $sid['slider_id'];
    $output['msg'] =  "<div id='first_sliders_imgs' class='slider'>";
    $table_name = $wpdb->prefix ."first_slider";
    $get_slider_sequence = $wpdb->get_row("SELECT * FROM $table_name WHERE slider_id ='".$slider_id."'");
//    echo $wpdb->last_query;
    if ($get_slider_sequence) {
        if($get_slider_sequence->slider_status == 0){

            if($get_slider_sequence->slider_sequence != ""){
                $get_slider_img = unserialize($get_slider_sequence->slider_sequence);
                foreach ($get_slider_img as $get_slider_imgs) {
                    $post_id =  $get_slider_imgs;
                    $table_name = $wpdb->prefix ."posts";
                    $img_path = $wpdb->get_row("SELECT * FROM $table_name WHERE id = '".$post_id."'");
//            echo $wpdb->last_query;
//            echo $img_path->guid;
                    $output['msg'] = $output['msg'].'<div><img id="'.$post_id.'" src="'.$img_path->guid.'" alt="Smiley face"></div>';
                }
            }

        }

    }
    $output['msg'] = $output['msg']."</div>";
//    echo json_encode($output);
    $output = $output['msg'] ;


    return $output;
}
?>

