<?php
namespace MaxInbound;
defined('ABSPATH') or die('No direct access permitted');

$template = MI()->templates()->get();
?>
<div>
<?php _e("Name:","maxinbound"); ?> &nbsp;
<?php echo ucfirst($template->getTemplateName()); ?><br>

<?php _e("Fields:","maxinbound"); echo "&nbsp";  echo basename($template->getFieldsPath() ) ?>
</div>
