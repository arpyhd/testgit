<?php
class UtilityManager {
	public static function getStatus() {
		return array(
						'y' => 'Activated',
						'n' => 'Deactivated',
		);
	}
	
	public static function getWordFilters() {
		$sql = "SELECT word_filter FROM word_filter WHERE status = 'y'";
		$filters = Yii::app()->db->createCommand($sql)->queryAll();		
		$fls = array();
		foreach((array)$filters as $filter) {
			$fls[] = $filter['word_filter'];
		}
		return implode('|', $fls);
		
	}
}
?>