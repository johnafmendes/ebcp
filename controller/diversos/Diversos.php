<?php
class Diversos{
	public function montaQuery($query, $data) {
		$indexed = $data == array_values($data);
		foreach($data as $k=>$v) {
			if(is_string($v)) {
				$v="'$v'";
			}
			if($indexed) {
				$query = preg_replace('/\?/', $v, $query, 1);
			} else {
				$query = str_replace(":$k", $v, $query);
			}
		}
		return $query;
		
	}
}
?>