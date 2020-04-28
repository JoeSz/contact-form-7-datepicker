<?php

class ContactForm7Datepicker_Admin {

	function __construct() {
		add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
		add_action('admin_footer', array($this, 'theme_js'));
	}

	function enqueue_assets() {
		if (is_admin() && ! self::is_wpcf7_page())
			return;

		wp_enqueue_script('jquery-ui-datepicker');

		ContactForm7Datepicker::enqueue_js();

		wp_enqueue_style(
			'jquery-ui-timepicker',
			plugins_url('js/jquery-ui-timepicker/jquery-ui-timepicker-addon.min.css', __FILE__)
		);
	}

	function theme_js() {
		if (! self::is_wpcf7_page())
			return;
	?>
		<script>
		jQuery(function($){
			var $spinner = $(new Image()).attr('src', '<?php echo admin_url('images/wpspin_light.gif'); ?>');
			var old_style = false;

			$('#jquery-ui-theme').change(function(){
				var theme = $(this).val();

				var style = $('#cf7dp-jquery-ui-theme');

				if (theme == 'disabled') {
					old_style = style;
                    style.html('');

					return;
				} else if (style.html() === '') {
					var style = old_style;
				}

                var html = style.html();
				html = html.replace(/\/themes\/[-a-z]+\//g, '/themes/' + theme + '/');
                style.html(html);
			});

			$('#save-ui-theme').click(function(){
				var data = {
					action: 'cf7dp_save_settings',
					ui_theme: $('#jquery-ui-theme').val()
				};

				var $this_spinner = $spinner.clone();

				$(this).after($this_spinner.show());

				$.post(ajaxurl, data, function(response) {
					var $prev = $( '.wrap > .updated, .wrap > .error' );
					var $msg = $(response).hide().insertAfter($('.wrap h2'));
					if ($prev.length > 0)
						$prev.fadeOut('slow', function(){
							$msg.fadeIn('slow');
						});
					else
						$msg.fadeIn('slow');

					$this_spinner.hide();
				});

				return false;
			});
		});
		</script>
	<?php
	}

	private static function is_wpcf7_page() {
		global $current_screen, $pagenow;

		if (is_object($current_screen) && strpos($current_screen->id, 'page_wpcf7'))
			return true;

		return false;
	}
}

new ContactForm7Datepicker_Admin;
