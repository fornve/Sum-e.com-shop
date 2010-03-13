<?php

	class Forum_Post extends Entity
	{
		protected $schema = array( 'id', 'user', 'parent', 'created', 'forum', 'subject', 'text', 'views' );
		
		static function Retrieve( $id )
		{
			$object = parent::Retrieve( $id, __CLASS__ );
			
			if( !$object )
				return null;
				
			$object->user = User::Retrieve( $object->user );
			
			return $object;
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
		
		static function ConversationCollection( $parent_id )
		{
			$query = "SELECT id FROM forum_post WHERE parent = ? OR id = ? ORDER BY created ASC";
			$entity = new Entity();
			$objects = $entity->Collection( $query, array( $parent_id, $parent_id ), __CLASS__ );
		
			if( $objects ) foreach( $objects as $object )
			{
				$collection[] = self::Retrieve( $object->id );
			}
			
			return $collection;
		}
		
		function PreDelete()
		{
			if( !$this->parent )
			{
				$query = "DELETE FROM forum_post WHERE parent = ?";
				$this->Query( $query, $this->id );
			}
		}
	}
