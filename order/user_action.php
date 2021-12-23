<?php
class UserAction extends AppModel {

	var $name = 'UserAction';
	
	function save_log($user_name, $action_type, $table_name, $ref_ids){
		
		$ref_keys = array('NULL', 'NULL', 'NULL');
		
		for ($i=0; $i<sizeof($ref_ids); $i++){
			$ref_keys[$i] = "'{$ref_ids[$i]}'";
		}
		
		$insert_query = "
		INSERT INTO [yontrakij_db].[dbo].[user_actions](
			[username]
		  ,[timestamp]
		  ,[action_types_id]
		  ,[table_name]
		  ,[ref_id_1]
		  ,[ref_id_2]
		  ,[ref_id_3])
		VALUES(
			'{$user_name}',
			GETDATE(),
			{$action_type},
			'{$table_name}',
			{$ref_keys[0]},
			{$ref_keys[1]},
			{$ref_keys[2]})";
			
		$result = $this->query($insert_query, false);
		
		return $result;
		
	}
	
}
?>