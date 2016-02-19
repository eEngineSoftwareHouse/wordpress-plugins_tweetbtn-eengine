<div class="wrap">
	<a href="http://www.eengine.pl"><img style="display: inline-block; vertical-align: middle" src="<?php echo plugins_url('eengine.png', __FILE__) ?>" alt="eengine.pl"></a>
	<h2 style="display: inline-block; vertical-align: middle;margin-left: 25px;">Tweet Button<br><small>by <a style="text-decoration:none" href="http://www.eengine.pl" target="_blank">eEngine.pl</a></small></h2>
	<hr>
	<p>Remember that selected text to tweet will be automatically cutted to 145 characters</p>
	<p><b>If you want to tweet selected content with link to post, just select 'Include post link' option. Shortened URL (by Google) will be then added to tweet content.</b></p>
	<p><b>If you want to also include a custom hashtag or follow link, just type it in the textfield below</b></p>
	<p>E.g.  "#test", "@Test" or "#test @Test"</p>
	<p><b>Notice that links and tags will cause the selected text to be cutted (tweet can't have more than 145 characters)<br>Enjoy!</b></p>
	<hr>
	<form method="post" action="options.php">

		<?php settings_fields( 'tweetbtn-settings-group' ); ?>

		<?php do_settings_sections( 'tweetbtn-settings-group' ); ?>

		<?php $include_link = get_option( 'tweetbtn_include_link' ); ?>

		<?php $include_tag = get_option( 'tweetbtn_include_tag' ); ?>

		<table class="form-table">

			<tr valign="top">

				<th scope="row">Include Post Link</th>

				<td>
					<input type="checkbox" name="tweetbtn_include_link" value="1"<?php checked( '1' == $include_link); ?> />
				</td>

			</tr>

			<tr valign="top">

				<th scope="row">Include hashtag / follow </th>

				<td>
					<input type="text" name="tweetbtn_include_tag" value="<?php echo $include_tag ?>" />
				</td>

			</tr>

		</table>

		<?php submit_button(); ?>

	</form>
</div>