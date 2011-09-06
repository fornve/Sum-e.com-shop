{assign var=basedir value=$smarty.const.SMARTY_DEFAULT_TEMPLATES_DIR}
{assign var=filename value=/var/www/shop/templates/default/checkout/checkout_stages.tpl}{include file=$filename checkout_stage=$checkout_stage}
