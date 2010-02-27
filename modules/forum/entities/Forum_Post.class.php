<?php

	class Forum_Post extends Entity
	{
		protected $schema = array( 'id', 'user', 'parent', 'created', 'forum', 'text', 'views' );
		
		static function Retrieve( $id )
		{
			return parent::Retrieve( $id, __CLASS__ );
		}
		
		static function ForumCollection( $forum_id, $offset = null, $limit = null )
		{
			$query = "SELECT * FROM forum_post WHERE forum = ? AND parent IS NULL ORDER BY created DESC";
			
			$entity = new Entity();
			
			return $entity->Collection( $query, $forum_id, __CLASS__, $offset, $limit );
		}
		
		static function LastForumPostRetrieve( $forum_id )
		{
			$query = "SELECT * FROM forum_post WHERE forum = ? ORDER BY created DESC";
			$entity = new Entity();
			return $entity->Collection( $query, $forum_id, __CLASS__ );
		}
	}
