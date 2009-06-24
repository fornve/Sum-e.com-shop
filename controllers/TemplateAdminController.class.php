<?php

    class TemplateAdminController extends AdminController
    {
        public $breadcrumbs = array( array( 'link' => '/TemplateAdmin/', 'name' => 'Template Admin' ) );

		function Index()
		{
			$this->assign( 'content', $this->smarty->fetch( 'admin/template/3rows.tpl' ) );
			echo $this->smarty->fetch( 'admin/template/decoration.tpl' );
		}
    }
