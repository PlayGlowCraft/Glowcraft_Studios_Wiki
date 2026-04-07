<?php
/**
 * Recommended Plugins Panel
 *
 * @package Newses
 */
?>
<div id="recommended-plugins-panel" class="panel-left">
	<?php 
	$newses_free_plugins = array(

		'shortbuild' => array(
		    'name'     	=> 'Shortbuild',
			'slug'     	=> 'shortbuild',
			'filename' 	=> 'shortbuild.php',
		),

		'one-click-demo-import' => array(
		    'name'     	=> 'Ansar Import',
			'slug'     	=> 'ansar-import',
			'filename' 	=> 'ansar-import.php',
		),
	);
	if( !empty( $newses_free_plugins ) ) { ?>
		<div class="recomended-plugin-wrap">
		<?php
		foreach( $newses_free_plugins as $newses_plugin ) {
			$info 		= newses_call_plugin_api( $newses_plugin['slug'] ); ?>
			<div class="recom-plugin-wrap w-2-col">
				<div class="plugin-title-install clearfix">
					<span class="title">
						<?php echo esc_html( $newses_plugin['name'] ); ?>	
					</span>
					
					<?php if($newses_plugin['slug'] == 'shortbuild') : ?>
					<p><?php printf( esc_html__('To display Newses Frontpage widget, please install the %1$s then go to Widgets menu under Appearance and drag widget in Front-page content Section.','newses'), '<a target="_blank" href="'.esc_url('https://wordpress.org/plugins/shortbuild/').'">'.esc_html__('Shortbuild plugin', 'newses').'</a>'); ?></p>
					<?php endif; ?>


					<?php if($newses_plugin['slug'] == 'ansar-import') : ?>
					<p>
						<?php
							printf(
								/* translators: %s: plugin name with link. */
								esc_html__( 'First, install and activate the %s plugin. After that, import sample demo content by visiting the Ansar Demo Importer under the "Ansar Import" menu.', 'newses' ),
								'<a target="_blank" href="' . esc_url( 'https://wordpress.org/plugins/ansar-import/' ) . '">' . esc_html__( 'Ansar Import Plugin', 'newses' ) . '</a>'
							);
						?>
					</p>
					<?php endif; ?>



					<?php echo '<div class="button-wrap">';
					echo Newses_Getting_Started_Page_Plugin_Helper::instance()->get_button_html( $newses_plugin['slug'], array('redirect' => esc_url( admin_url('admin.php?page=ansar-demo-import') ))  );
					echo '</div>';
					?>
				</div>
			</div>
			</br>
			<?php
		} ?>
		</div>
	<?php
	} ?>
</div>