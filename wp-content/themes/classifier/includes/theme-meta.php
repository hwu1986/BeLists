<?php
/**
 * Taxonomy Meta Class
 * 
 * **/

//include the main class file
require_once( TEMPLATEPATH . '/includes/' . "tax-meta-class/tax-meta-class.php");
if (is_admin()){
  /* 
   * prefix of meta keys, optional
   */
  $prefix = 'colabs_';
  /* 
   * configure your meta box
   */
  $config = array(
    'id' => 'colabs_meta_box',          // meta box id, unique per meta box
    'title' => 'Colabs Meta Box',          // meta box title
    'pages' => array( COLABS_TAX_CAT ),        // taxonomy name, accept categories, post_tag and custom taxonomies
    'context' => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
    'fields' => array(),            // list of meta fields (can be added by field arrays)
    'local_images' => true,          // Use local or hosted images (meta box images for add/remove)
    'use_with_theme' => true          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
  );
  
  
  /*
   * Initiate your meta box
   */
  $my_meta =  new Tax_Meta_Class($config);
  
  /*
   * Add fields to your meta box
   */

  //text field
  $my_meta->addText($prefix.'cat_price_id',array('name'=> 'Category Price', 'std' => '0'));
  
  //category image
  $my_meta->addImage($prefix.'cat_icon_id',array('name'=> 'Category Icon'));

  /*
   * Don't Forget to Close up the meta box decleration
   */
  //Finish Meta Box Decleration
  $my_meta->Finish();
}