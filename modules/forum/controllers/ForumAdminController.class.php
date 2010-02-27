<?php

	class ForumAdminController extends AdminController
	{
        public $breadcrumbs = array( array( 'link' => '/Admin/', 'name' => 'Admin' ), array( 'link' => '/forumAdmin/', 'name' => 'forum Admin' ) );

		function Index()
		{
		}

        function Edit( $id )
        {

			if( $id )
					$forum = Forum::Retrieve( $id, true );

			if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
			{
				if( !$forum )
						$forum = new Forum();

				$input = Common::Inputs( array( 'forum_name', 'forum_description' ), INPUT_POST );

				$forum->name = $input->forum_name ? $input->forum_name : 'unnamed';
				$forum->description = $input->forum_description;
                $forum->Save();

                $forum = Forum::Retrieve( $forum->id, true );
				$_SESSION['user_notification'][] = array( 'text' => "Forum {$forum->name} saved.", 'type' => 'notice'  );
			}

			if( $id )
			{
					if( $forum->name )
						$this->breadcrumbs[] = array( 'name' => "forum edit: {$forum->name}" );
					else
						$this->breadcrumbs[] = array( 'name' => "New forum" );

					$this->Assign( 'breadcrumbs', $this->breadcrumbs );
					$this->Assign( 'forum', $forum );
					echo $this->Decorate( 'admin/forum/edit.tpl' );
			}
			else
					self::RedirectReferer();
		}

		function Delete( $id )
		{
			$forum = forum::Retrieve( $id, true );

			if( $forum )
			{
				$forum->Delete();
				$_SESSION[ 'user_notification' ][] = array( 'text' => "forum deleted.", 'type' => 'notice' );
				self::Redirect( "/" );
			}
		}
	}
