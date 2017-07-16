<?php
namespace App\Controller;

use App\Config\View as View;
use App\Model\GradeModel as Grade;
use App\Config\Session as Session;
use App\Model\PeriodModel as Period;
use App\Model\AsignatureModel as Asignature;
use App\Model\PerformanceModel as Performance;
/**
* 
*/
class PerformanceController
{
	
	private $_performance;
	private $_asignature;
	private $_period;
	private $_grade;

	function __construct()
	{
		if(Session::check('authenticated')):
			$this->_performance = new Performance(Session::get('db'));
			$this->_asignature = new Asignature(Session::get('db'));
			$this->_period = new Period(Session::get('db'));
			$this->_grade = new Grade(Session::get('db'));
		endif;
	}

	/**
	 *
	 *
	 *
	*/
	public function getCodesByIdAction($position, $id_group, $id_asignature, $period, $id_grade='')
	{
		$codes = $this->_performance->getCodesByPosition($position, $id_group, $id_asignature, $period);

		if($codes['state'])
			echo $codes['data'][0]['cod_desemp'];
	}

	/**
	 *
	 *
	 *
	*/
	public function getIndicatorsAction($id_grade, $id_asignature, $category, $period)
	{
		$indicadores = $this->_performance->getPerformances($id_grade, $id_asignature, $category, $period);
		

		if($indicadores['state']):
			$asignaturas = $this->_asignature->all()['data'];
			$grados = $this->_grade->all()['data'];
			$periodos = $this->_period->all()['data'];

			$view = new View(
				'teacher/partials/evaluation/evaluatedPeriod', 
				'Performancestable', 
				[
					'indicadores'=>$indicadores['data'], 
					'asignaturas' => $asignaturas,  
					'grado' => $id_grade, 
					'grados' => $grados, 
					'asignatura' => $id_asignature, 
					'periodo' => $period, 
					'periodos' => $periodos
				]
			);
			$view->execute();
		endif;
	}
}
?>