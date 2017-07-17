<?php

class BWGEViewInstructions_bwge extends BWGEView{

	////////////////////////////////////////////////////////////////////////////////////////
	// Events                                                                             //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Constants                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Variables                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Constructor & Destructor                                                           //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Public Methods                                                                     //
	////////////////////////////////////////////////////////////////////////////////////////
	public function display() {
	?>			
        <form method="post" action="" id="adminForm">
            <div class="bwge">
                <h2><?php _e("How to create a gallerry with ecommerce","bwge_back"); ?></h2>
                <ol> 
                    <li><?php _e("Configure your","bwge_back"); ?> <a href="admin.php?page=options_bwge" target="_blank"><?php _e("ecommerce options","bwge_back"); ?></a>.</li>
                    <li><?php _e("Create one or more","bwge_back"); ?> <a href="admin.php?page=pricelists_bwge" target="_blank"><?php _e("pricelists","bwge_back"); ?></a>.</li>
                    <li><?php _e("Using the Gallery","bwge_back"); ?> <a href="admin.php?page=galleries_bwge" target="_blank"><?php _e("Add Galleries/Images","bwge_back"); ?></a> <?php _e("page, associate a pricelist with any gallery or image you would like to sell","bwge_back"); ?>.</li>
                    <li><?php _e("When adding or editing a gallery , be sure to enable ecommerce in ","bwge_back"); ?><a href="admin.php?page=options_bwge" target="_blank">Gallery Options</a> -> Lightbox -> Enable Ecommerce button.</li>
                </ol>  
                <h2><?php _e("Additional documentation on","bwge_back"); ?> <a href="https://galleryecommerce.com/" target="_blank">  Gallery Ecommerece website</a></h2>
                <ol> 
                    <li><a href="https://galleryecommerce.com/#" target="_blank"><?php _e("Ecommerce overview.","bwge_back"); ?></a></li>
                    <li><a href="https://galleryecommerce.com/gallery-ecommerce-set-up/ecommerce-options/" target="_blank"><?php _e("How to configure Ecommerce options.","bwge_back"); ?></a></li>
                    <li><a href="https://galleryecommerce.com/gallery-ecommerce-set-up/adding-pricelist/" target="_blank"><?php _e("How to create and assign a pricelist.","bwge_back"); ?></a></li>
                    <li><a href="https://galleryecommerce.com/gallery-set-up/generating-shortcode-publishing-galleries-albums/" target="_blank"><?php _e("How to add Ecommerce to a Gallery.","bwge_back"); ?></a></li>
       
                </ol>  
            				
            </div>	
        </form>
	<?php  
	}

	////////////////////////////////////////////////////////////////////////////////////////
	// Getters & Setters                                                                  //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Private Methods                                                                    //
	////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////
	// Listeners                                                                          //
	////////////////////////////////////////////////////////////////////////////////////////
}