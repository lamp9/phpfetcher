<?php
namespace Phpfetcher\widget;

use yii\base\Widget;
use Phpfetcher\logic\Params;
class AdminList extends Widget
{
	public $type;
	public $table;
	public $search;
	public $pagination;
	public $model;

	public function init(){
		parent::init();
	}

	public function run()
	{
		return $this->render(
			$this->returnFile($this->type),
			[
				'table' => $this->table,
				'pagination' => $this->pagination,
				'search' => $this->search,
				'model' => $this->model,
			]
		);
	}

	public function returnFile($type){
		$file = Params::getParamsChild(['viewPublicFile', 'public-list', $type]);
		return $file;
	}
}
?>