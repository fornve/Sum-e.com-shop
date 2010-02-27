<?php

	class Forum extends Entity
	{
		static function Retrieve( $id )
		{
			return parent::Retrieve( $id, __CLASS__ );
		}
		
		static function IndexCollection()
		{
			$query = "SELECT * FROM forum";
			$entity = new Entity();
			return $entity->Collection( $query, null, __CLASS__ );
		}
		
		function LastPost()
		{
			return Forum_Post::LastForumPostRetrieve( $this->id );
		}
	}
