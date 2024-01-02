<div class="moduleTitle">
	<h2 class="module-title-text">{$MOD.LBL_STIC_TEST_DATA_LINK_TITLE}</h2>
</div>
<div class="clear"></div>


<div class="actions">
	<div class="well col-md-6 text-center">
		<a href="index.php?module=Administration&action=insertSticData"><button type='button' class='button'><span
					class='glyphicon glyphicon-flash text-success'></span>
				{$MOD.LBL_STIC_TEST_DATA_INSERT_LINK_TITLE}</button></a>
		<p>{$MOD.LBL_STIC_TEST_DATA_INSERT_DESCRIPTION}
	</div>
	<div class="well col-md-6 text-center">
		<a href="index.php?module=Administration&action=removeSticData"><button type='button' class='button'><span
					class='glyphicon glyphicon-trash text-danger'></span>
				{$MOD.LBL_STIC_TEST_DATA_REMOVE_LINK_TITLE}</button></a>
		<p>{$MOD.LBL_STIC_TEST_DATA_REMOVE_DESCRIPTION}
	</div>
</div>
<div class="alert alert-danger"><span class="glyphicon glyphicon-exclamation-sign"></span> {$MOD.LBL_STIC_TEST_DATA_NOTICE}</div>
<script type="text/javascript">
	{literal}
		$(document).ready(function() {

			// Select and loop the container element of the elements you want to equalise
			$('.actions').each(function() {

				// Cache the highest
				var highestBox = 0;

				// Select and loop the elements you want to equalise
				$('.well', this).each(function() {

					// If this box is higher than the cached highest then store it
					if ($(this).height() > highestBox) {
						highestBox = $(this).height();
					}

				});

				// Set the height of all those children to whichever was highest 
				$('.well', this).height(highestBox);

			});

		});

	{/literal}
</script>
