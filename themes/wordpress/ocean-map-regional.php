<?php /*
Template Name: Ocean-Map-Regional
*/
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->


<?php get_template_part('header'); ?>

<body class="<?php foreach( get_the_category() as $cat ) { echo $cat->slug . '  '; } ?> single page">

<div class="menu-container">
    <div class="header-next-top" >


        <?php get_template_part('navigation'); ?>



    </div>
</div>


<!-- Header Background Color, Image, or Visualization
================================================== -->

<div class="next-header category <?php foreach( get_the_category() as $cat ) { echo $cat->slug . '  '; } ?>">
</div>


<!-- Navigation & Search
================================================== -->

<div class="container">
    <div class="next-top category <?php foreach( get_the_category() as $cat ) { echo $cat->slug . '  '; } ?>">


        <?php get_template_part('category-search'); ?>

    </div> <!-- top -->

</div>

<div class="page-nav">
</div>

<div class="container">


    <div class="sixteen columns page-nav-items">


        <?php
        $category = get_the_category(  );
        $cat_name=$category[0]->cat_name;


        $args = array(
            'category_name'=>$cat_name, 'categorize'=>0, 'title_li'=>0,'orderby'=>'rating');

        wp_list_bookmarks($args);

        // Pagination variables. the display settings shoule be read from global variable
        $display_count = (get_option('arcgis_maps_per_page') != '') ? get_option('arcgis_maps_per_page') : '8';
        $page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
        $offset = ( $page - 1 ) * $display_count;
        ?>
    </div>

    <!-- WordPress Content
    ================================================== -->
    <div class="category-content">

        <div class="content">
            <div class="sixteen columns">

                <div class="technical-wrapper">
                    <div id="regionalimg" class="imagearea-mapgallery" >
                        <div class="inner">
                            <h2 class="pane-title block-title">Featured Maps</h2>
                            <div id="regionalcontent" >
                                <p><span style="color: #666666; font-family: Arial, Helvetica, Verdana, 'Bitstream Vera Sans', sans-serif; font-size: 13px; line-height: 19px;">The following maps are from data sources that are regional in scope.These are some of the data sources available in the Ocean Community that are most useful in the identified region.This includes several of the human use atlases now available.</span></p>
                            </div>
                            <h3 class="fieldcontentregion" style="margin:10px; ">Regional/State Maps</h3>
                            <div class="map-gallery-wrap">
                                <?php
                                $args = array(
                                    'meta_key'         => 'map_category',
                                    'meta_value'       => 'regional',
                                    'post_type'        => 'arcgis_maps',
                                    'post_status'      => 'publish',
                                    'posts_per_page'   => $display_count,
                                    'page'             => $page,
                                    'offset'           => $offset
                                );
                                $query = new WP_Query($args);
                                $count = 0;
                                if( $query->have_posts() ) {
                                    while ($query->have_posts()) : $query->the_post();
                                        $map_category = get_post_meta($post->ID, 'map_category',TRUE);
                                        $server = get_post_meta($post->ID, 'arcgis_server_address',TRUE);
                                        $map_id = get_post_meta($post->ID, 'map_id',TRUE);
                                        $group_id = get_post_meta($post->ID, 'group_id',TRUE);
                                        $request = arcgis_map_process_info($server, $map_id, $group_id, 1);
                                        $res = sizeof($request['map_info']);
                                        //echo "The value of size is ".$res."<br/></br>";
                                        for($i=0; $i< $res; $i++){
                                            if(!empty($request["map_info"][$i]["img_src"])){
                                                $output .= '<div class="map-align">';
                                                $output .= '<a target=_blank href="'. $request["map_info"][$i]["img_href"] . '">';
                                                $output .= '<img class="map-gallery-thumbnail" src="'. $request["map_info"][$i]["img_src"] . '" title="' . $request["map_info"][$i]["title"] .'">';

                                                $output .= '<div class="map-gallery-caption">'. $request["map_info"][$i]["title"] . '</div>';
                                                $output .= '</a>';
                                                $output .= '</div>';
                                            }
                                        }
                                        $count++;
                                    endwhile;
                                    wp_reset_query();
                                }
                                print $output;
                                ?>
                            </div>
                        </div>
                    </div>
                    &nbsp;
                </div>
                <ul id="pagination">
                    <li id="previous-posts">
                        <?php previous_posts_link( '<< Previous', $query->max_num_pages ); ?>
                    </li>
                    <li id="next-posts">
                        <?php next_posts_link( 'Next >>', $query->max_num_pages ); ?>
                    </li>
                </ul>
                <?php get_template_part('footer'); ?>
            </div> <!-- content -->
        </div>    <script type="text/javascript" src="<?php echo get_bloginfo('template_directory'); ?>/js/jquery.cookie.js"></script>
        <script type="text/javascript" src="<?php echo get_bloginfo('template_directory'); ?>/js/modernizr.mq.js"></script>

        <script>
            $(window).load(function(){
                $('#posts').masonry({
                    // options
                    columnWidth: 287,
                    itemSelector : '.post',
                    isResizable: true,
                    isAnimated: true,
                    gutterWidth: 25
                });

                $("#joyRideTipContent").joyride({
                    autoStart: true,
                    modal: true,
                    cookieMonster: true,
                    cookieName: 'datagov',
                    cookieDomain: 'next.data.gov'
                });
            });
        </script>


        <script>
            $(function () {
                var
                        $demo = $('#rotate-stats'),
                        strings = JSON.parse($demo.attr('data-strings')).targets,
                        randomString;

                randomString = function () {
                    return strings[Math.floor(Math.random() * strings.length)];
                };

                $demo.fadeTo(randomString());
                setInterval(function () {
                    $demo.fadeTo(randomString());
                }, 15000);
            });
        </script>

        <script src="<?php echo get_bloginfo('template_directory'); ?>/js/v1.js"></script>
        <script src="<?php echo get_bloginfo('template_directory'); ?>/js/autosize.js"></script>

        <!-- End Document
        ================================================== -->
</body>


</html>