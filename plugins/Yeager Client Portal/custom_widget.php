<?php
class ClientPortal_Widget extends WP_Widget {
 
    public function __construct() {
     
        parent::__construct(
            'clientportal_widget',
            __( 'Client Portal Widget', 'yeagercommunity' ),
            array(
                'classname'   => 'client-portal-widget',
                'description' => __( 'show Client Portal .', 'yeagercommunity' )
                )
        );
       
        load_plugin_textdomain( 'yeagercommunity', false, basename( dirname( __FILE__ ) ) . '/languages' );
       
    }
 
    
    public function widget( $args, $instance ) {    
         
        extract( $args );
         
        $title      = apply_filters( 'widget_title', $instance['title'] );
        $message    = $instance['message'];
         
        echo $before_widget;
         
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }
                             
      echo do_shortcode('[client-portal-event]');
        echo $after_widget;
         
    }
 
     public function update( $new_instance, $old_instance ) {        
         
        $instance = $old_instance;
         
        $instance['title'] = strip_tags( $new_instance['title'] );
             
        return $instance;
         
    }
  
   
    public function form( $instance ) {    
     
        $title      = esc_attr( $instance['title'] );
             ?>
         
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
       
    <?php 
    }
        
}
 
/* Register the widget */
add_action( 'widgets_init', function(){
     register_widget( 'ClientPortal_Widget' );
});

class ClientPortalSlider_Widget extends WP_Widget {
 
    public function __construct() {
     
        parent::__construct(
            'clientportalslider_widget',
            __( 'Client Portal Slider Widget', 'yeagercommunity' ),
            array(
                'classname'   => 'client-portal-slider-widget',
                'description' => __( 'show Client Portal Slider .', 'yeagercommunity' )
                )
        );
       
        load_plugin_textdomain( 'yeagercommunity', false, basename( dirname( __FILE__ ) ) . '/languages' );
       
    }
 
    
    public function widget( $args, $instance ) {    
         
        extract( $args );
         
        $title      = apply_filters( 'widget_title', $instance['title'] );
        $message    = $instance['message'];
         
        echo $before_widget;
         
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }
                             
      echo do_shortcode('[client-portal-slider]');
        echo $after_widget;
         
    }
 
     public function update( $new_instance, $old_instance ) {        
         
        $instance = $old_instance;
         
        $instance['title'] = strip_tags( $new_instance['title'] );
             
        return $instance;
         
    }
  
   
    public function form( $instance ) {    
     
        $title      = esc_attr( $instance['title'] );
             ?>
         
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
       
    <?php 
    }
        
}
 
/* Register the widget */
add_action( 'widgets_init', function(){
     register_widget( 'ClientPortalSlider_Widget' );
});