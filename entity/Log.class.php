<?php

    class Log extends Entity
    {
        protected $schema = array( 'id', 'type', 'section', 'user', 'comment', 'created' );

        static function Retrieve( $id )
        {
            if( !$id )
                return null;

            $query = "SELECT * FROM log WHERE id = ?";
            $entity = new Entity();
            $object = $entity->GetFirstResult( $query, $id, __CLASS__ );

            return $object;
		}

		static function Add( $type, $section, $user = null, $comment = null )
		{
			$log = new Log();
			$log->type = $type;
			$log->section = $section;
			$log->user = $user;
			$log->comment = $comment;
			$log->created = time();
			$log->Save();
		}

    }
