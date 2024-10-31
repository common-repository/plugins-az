<?php


class PluginsAZOptions {

	function pluginsaz_radiobutton( $name, $value, $text, $set) {
		echo sprintf( '<label><input type="radio" name="%s" value="%s" %s>%s</label>',
			$name, $value, ( $this->options['length'] == $set ? ' checked="checked"' : '' ), $text );
	} //pluginsaz_radiobutton

	function save_options() {
		if (!empty($_POST ['pluginsaz_update'])) {
			$options = array();
			$options['length'] = $_POST['length'];
			update_option('pluginsaz', $options);
			echo sprintf('<div id="message" class="updated fade"><p>%s</p></div>', __('Options updated.' , 'plugins-az'));
		} //if
	} //save_options

	function __construct() {
		$this->save_options();
		$this->options = get_option( 'pluginsaz' );
		if ( $this->options['length'] == FALSE )
			$this->options['length'] = 'short';
		load_plugin_textdomain('plugins-az', false, dirname(plugin_basename(__FILE__)) . '/lang');
		$this->form();
	} //constructor


	function form() {
		?>
		<div class="wrap">
		<h2>
			<?php _e( 'Plugins A to Z', 'pluginsaz' ); ?>
		</h2>

		<form method="post" id="pluginsaz" action="<?php echo $_SERVER ['REQUEST_URI'];	?>">
		<table class="form-table">

			<tr class="pluginsaz-wrap">

				<th scope="row"><? _e('Alphabet length', 'plugins-az'); ?></th>

				<td> <?php $this->pluginsaz_radiobutton( 'length', 'short', __('Short alphabet', 'plugins-az'), 'short'); ?>
					 <br/>
					 <?php $this->pluginsaz_radiobutton( 'length', 'long', __('Long alphabet', 'plugins-az'), 'long'); ?>
				</td>
			</tr>

		</table>
		<p class="submit">
			<input type="submit" name="pluginsaz_update" class="button-primary"
			       value="<?php echo __( 'Update Options', 'plugins-az' ); ?>"/>
		</p>
		</form>
		<?php
	} //form

} //class

