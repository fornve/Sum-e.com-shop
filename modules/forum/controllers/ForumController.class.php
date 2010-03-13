<?php

	class ForumController extends Controller
	{
		function Index()
		{
			$this->breadcrumbs[] = array( 'link' => null, 'name' => 'Forum' );
			$this->assign( 'breadcrumbs', $this->breadcrumbs );
			$this->assign( 'forums', Forum::IndexCollection() );
			echo $this->DecorateModule( 'index.tpl', 'forum' );
		}

		function View( $id )
		{
			$forum = Forum::Retrieve( $id );

			$this->breadcrumbs[] = array( 'link' => '/Forum', 'name' => 'Forum' );
			$this->breadcrumbs[] = array( 'link' => null, 'name' => $forum->name );
			$this->assign( 'breadcrumbs', $this->breadcrumbs );
			$this->assign( 'forum', $forum );
			echo $this->DecorateModule( 'forum.tpl', 'forum' );
		}

		function Post( $forum_id, $id = null )
		{
			$forum = Forum::Retrieve( $forum_id );

			if( !$forum )
				self::Redirect( '/Error/NotFound' );

			if( $id )
			{
				$post = Forum_Post::Retrieve( $id );
				if( !$_SESSION[ 'visited_posts' ][ $post->id ] )
				{
					$post->views++;
					$post->Save();
					$_SESSION[ 'visited_posts' ][ $post->id ] = true;
				}
				
			}

			if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )
			{
				if( !$_SESSION['logged_user'] )
					self::Redirect( "/User/Login" );
					
				$input = Common::Inputs( array( 'post_subject', 'post_content' ), INPUT_POST );

				$post_reply = new Forum_Post();
				$post_reply->parent = $post->id;
				$post_reply->user = $_SESSION[ 'logged_user' ]->id;
				$post_reply->text = nl2br( strip_tags( $input->post_content, "<a><pre><quote><strong><code>" ) );
				$post_reply->created = time();
				$post_reply->subject = strip_tags( $input->post_subject );
				$post_reply->forum = $forum_id;
				$post_reply->Save();

				if( !$post )
					$post = & $post_reply;
				
				$link = "/Forum/Post/{$forum->id}/{$post->id}/{$post->subject}";
				//self::AdminMail( "New post on sum-e.com", $link );
				self::Redirect( $link );
			}

			$this->breadcrumbs[] = array( 'link' => '/Forum', 'name' => 'Forum' );
			$this->breadcrumbs[] = array( 'link' => "/Forum/View/{$forum->id}/{$forum->name}", 'name' => $forum->name );
			$this->breadcrumbs[] = array( 'link' => null, 'name' => $post->subject );
			$this->assign( 'breadcrumbs', $this->breadcrumbs );
			$this->assign( 'user', $_SESSION[ 'logged_user' ] );
			$this->assign( 'forum', $forum );
			$this->assign( 'posts', Forum_Post::ConversationCollection( $id ) );
			$this->assign( 'post', $post );
			echo $this->DecorateModule( 'post.tpl', 'forum' );
		}

		function DeleteReply( $post_id )
		{
			$post = Forum_Post::Retrieve( $post_id );
			$user = User::Retrieve( $_SESSION[ 'logged_user' ] );

			if( $post && $user && ( $user->id == $post->user->id || $user->HasRole('admin') ) )
			{
				$parent = $post->parent;
				
				$post->Delete();
				
				$conversation = Forum_Post::ConversationCollection( $parent );

				if( !$conversation )
				{
					$_SESSION[ 'user_notification' ][] = array( 'text' => "Conversation deleted.", 'type' => 'notice' );
					self::Redirect( "/Forum/View/{$subject->forum}" );
				}
				else
					$_SESSION[ 'user_notification' ][] = array( 'text' => "Post deleted.", 'type' => 'notice' );

			}
			else
				$_SESSION[ 'user_notification' ][] = array( 'text' => "Post not deleted.", 'type' => 'error' );

			self::RedirectReferer();
		}

		function DeletePost( $post_id )
		{
			$post = Forum_Post::Retrieve( $post_id );
			$user = User::Retrieve( $_SESSION[ 'logged_user' ] );

			if( $post && $user && $user->HasRole('admin') )
			{
				$post->DeleteConversation();
				$_SESSION[ 'user_notification' ][] = array( 'text' => "Conversation deleted.", 'type' => 'notice' );
			}
			else
				$_SESSION[ 'user_notification' ][] = array( 'text' => "Conversation not deleted.", 'type' => 'error' );

			self::RedirectReferer();
		}
	}
