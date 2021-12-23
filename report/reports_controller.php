<?php
class ReportsController extends AppController {

	var $helpers = array ('Html','Form','Ajax','Javascript');
	var $name = 'Reports';
	var $components = array('Autocomplete');
	var $navigation_for_layout = "MENU_60000";	
	
	//var $uses = null;
	
	function index() {
				
	}
	
        function avg_sale_detail($part_code,$work_type_id,$start=null,$end=null){
        /////////////////////JOB DETAIL/////////////////////////////////
        $job_details = $this->Report->search_avgsale_detail($part_code,$work_type_id,$start,$end);
        
        $this->set('job_details', $job_details);
        
        $this->layout = 'ajax';
    }
    function avg_sale() {//รายงานยอดขายอะไหล่เฉลี่ย
		set_time_limit(0);
        $this->loadModel('WorkOrder');
		///////
		$model_query = "SELECT * FROM [yontrakij_db].[dbo].[car_brands] ;";
		$work_brands=$this->WorkOrder->query($model_query, false);
		$this->set('work_brands', $work_brands);	
		//////
		$filter = '';	
		if(isset($this->data) && sizeof($this->data) > 0){

		$page_size 	= 20;
		
		$avgsales = $this->Report->search_avgsale(1, $page_size, $this->data);
		$this->set('avgsales', $avgsales);
		
        $pdf = $this->Report->search_avgsale_count($this->data);
		$obj_num = sizeof($pdf);
		
		$page_num  = ceil(floatval($obj_num)/floatval($page_size));
		
		$this->Session->write('Paging.data', $this->data);
		$this->Session->write('Paging.obj_nums', $obj_num);//page next
		
		$this->set('obj_nums', $obj_num);//page next
		$this->set('page_num', $page_num);
		$this->set('curpage', 1);
        $this->set('id_part', $this->data['avgSale']['id_part']);
        $this->set('brand', $this->data['avgSale']['brand']);
        $this->set('work_type', $this->data['avgSale']['work_type']);
		
		// เขียน session สำหรับ print
		//$pdf = $this->Report->search_avgsale_count($this->data);
		$this->Session->Write('PrintDatas', $pdf);		
		}
		
	}	
	function paging_avg_sale($page = 1) { 
            set_time_limit(0);
            $model_query = "SELECT * FROM [yontrakij_db].[dbo].[car_brands] ;";
		$work_brands=$this->Report->query($model_query, false);
		$this->set('work_brands', $work_brands);	
	
		$this->data = $this->Session->read('Paging.data');
		
		$page_size 	= 20;
				
		$pdf = $this->Report->search_avgsale_count($this->data);
		$obj_num = sizeof($pdf);
		$obj_nums 	= $this->Session->read('Paging.obj_nums');//page next
		$page_num  = ceil(floatval($obj_num)/floatval($page_size));
		
		$begin 	= ($page-1) * $page_size + 1;
		$this->set('begin', $begin);
		$end 	= $begin + $page_size - 1;
		
		$avgsales = $this->Report->search_avgsale($begin, $end, $this->data);
		//Debugger::dump($$this->data);
		$this->set('avgsales', $avgsales);
		$this->set('obj_nums', $obj_nums);//page next
				
		$this->set('page_num', $page_num);
		$this->set('curpage', $page);
		$this->Session->write('Paging.obj_nums', $obj_nums);//page next
                $this->set('id_part', $this->data['avgSale']['id_part']);
                $this->set('brand', $this->data['avgSale']['brand']);
                $this->set('work_type', $this->data['avgSale']['work_type']);
        
		// เขียน session สำหรับ print
                //$pdf = $this->Report->search_avgsale_pdf($this->data);
		$this->Session->Write('PrintDatas', $pdf);
		$this -> render('avg_sale');
        
                $this->layout = 'ajax';
	}
        
        function p_recrive_part(){//รายงานการรับจ่ายอะไหล่ประจำวัน
		if(isset($_GET['o'])){$oderby=$_GET['o']; /*Debugger::dump($oderby);*/ }else{$oderby="";}
		if(isset($this->data) && sizeof($this->data) > 0){
			 if (!empty($this->data['start']['day'])) {
				$this->set('s_day', $this->data['start']['day']);	
			 }
			 if (!empty($this->data['start']['month'])) {
				$this->set('s_month', $this->data['start']['month']);	
			 }
			 if (!empty($this->data['start']['year'])) {
				$this->set('s_year', $this->data['start']['year']);	
			 }
			 if (!empty($this->data['end']['day'])) {
				$this->set('s_fday', $this->data['end']['day']);	
			 }
			 if (!empty($this->data['end']['month'])) {
				$this->set('s_fmonth', $this->data['end']['month']);	
			 }
			 if (!empty($this->data['end']['year'])) {
				$this->set('s_fyear', $this->data['end']['year']);	
			 }
			 if (!empty($this->data['part']['warehouse'])) {
				$this->set('s_wh', $this->data['part']['warehouse']);	
			 }
			 if (!empty($this->data['part']['brand'])) {
				$this->set('s_brand', $this->data['part']['brand']);	
			 }
			 if (!empty($this->data['part']['partID'])) {
				$this->set('s_partid', $this->data['part']['partID']);	
			 }
			 if($oderby!=""){
				 $this->data['part']['sort']=$oderby;
				 $this->set('s_sort', $oderby);
			 }else{
				 if (!empty($this->data['part']['sort'])) {
					$this->set('s_sort', $this->data['part']['sort']);	
				 }}
		 }else{ $this->data=$this->Session->read('wheredata'); /*Debugger::dump($oderby);*/
		 	if($oderby!=""){
				 $this->data['part']['sort']=$oderby;
				 $this->set('s_sort', $oderby);
			 }else{
				 if (!empty($this->data['part']['sort'])) {
					$this->set('s_sort', $this->data['part']['sort']);	
				 }}
			if (!empty($this->data['start']['day'])) {
				$this->set('s_day', $this->data['start']['day']);	
			 }
			 if (!empty($this->data['start']['month'])) {
				$this->set('s_month', $this->data['start']['month']);	
			 }
			 if (!empty($this->data['start']['year'])) {
				$this->set('s_year', $this->data['start']['year']);	
			 }
			 if (!empty($this->data['end']['day'])) {
				$this->set('s_fday', $this->data['end']['day']);	
			 }
			 if (!empty($this->data['end']['month'])) {
				$this->set('s_fmonth', $this->data['end']['month']);	
			 }
			 if (!empty($this->data['end']['year'])) {
				$this->set('s_fyear', $this->data['end']['year']);	
			 }
			 if (!empty($this->data['part']['warehouse'])) {
				$this->set('s_wh', $this->data['part']['warehouse']);	
			 }
			 if (!empty($this->data['part']['brand'])) {
				$this->set('s_brand', $this->data['part']['brand']);	
			 }
			 if (!empty($this->data['part']['partID'])) {
				$this->set('s_partid', $this->data['part']['partID']);	
			 }
			
		 }
        $this->loadModel('WorkOrder');
		///////
		$model_query = "SELECT * FROM [yontrakij_db].[dbo].[warehouses] where type = 1;";
		$warehouses=$this->WorkOrder->query($model_query, false);
		$this->set('warehouses', $warehouses);	
		$model_query = "SELECT * FROM [yontrakij_db].[dbo].[car_brands] ;";
		$work_brands=$this->WorkOrder->query($model_query, false);
		$this->set('work_brands', $work_brands);	
		//////
		$filter = '';	
		if(isset($this->data) && sizeof($this->data) > 0){
            if (!empty($this->data['part']['brand'])) {//ยี่ห้อ
                $brand = "AND carBrand  = '" . $this->data['part']['brand'] . "' ";
            }else{
                $brand = "";
            }
            $page_size 	= 20;

            $recriveparts = $this->Report->search_recrivepart($this->data);
            $this->set('recriveparts', $recriveparts);

            $obj_num = sizeof($recriveparts);	

            $page_num  = ceil(floatval($obj_num)/floatval($page_size));

            $this->Session->write('Paging.data', $this->data);
            $this->Session->write('Paging.brand', $brand);
            $this->Session->write('Paging.obj_nums', $obj_num);//page next
			//$this->Session->delete('wheredata');
			$this->Session->write('wheredata', $this->data);
            $this->set('obj_nums', $obj_num);//page next
            $this->set('page_num', $page_num);
            $this->set('curpage', 1);
            $this->set('begin', 1);
            $this->set('end', $page_size);
		}
		
	}	

	function paging_p_recrive_part($page = 1) {
		$model_query = "SELECT * FROM [yontrakij_db].[dbo].[warehouses] where type = 1;";
		$warehouses=$this->Report->query($model_query, false);
		$this->set('warehouses', $warehouses);
		$model_query = "SELECT * FROM [yontrakij_db].[dbo].[car_brands] ;";
		$work_brands=$this->Report->query($model_query, false);
		$this->set('work_brands', $work_brands);	
        
        $recriveparts = $this->Report->search_recrivepart($this->data);
        $this->set('recriveparts', $recriveparts);
		
		$this->data = $this->Session->read('Paging.data');
		
		$page_size 	= 20;
				
		$obj_num = sizeof($recriveparts);
		$obj_nums 	= $this->Session->read('Paging.obj_nums');//page next
		$page_num  = ceil(floatval($obj_num)/floatval($page_size));
		
		$begin 	= ($page-1) * $page_size + 1;
		$this->set('begin', $begin);
		$end 	= $begin + $page_size - 1;
		$this->set('end', $end);
		
		/////
		$this->set('obj_nums', $obj_nums);//page next
				
		$this->set('page_num', $page_num);
		$this->set('curpage', $page);
		$this->Session->write('Paging.obj_nums', $obj_nums);//page next
		
		// เขียน session สำหรับ print
		//$pdf = $this->Report->search_recrivepart_pdf($this->data);
		//$this->Session->Write('PrintDatas', $pdf);	
		$this -> render('p_recrive_part');
	}
    function print_p_recrive_part(){
        $filterData = $this->Session->read('Paging.data');
        $brand = $this->Session->read('Paging.brand');
        
        set_time_limit(0);
        
        $job_pdf = $this->Report->search_recrivepart_pdf($filterData,$brand);
        
        $this->Session->Write('PrintDatas', $job_pdf);
        $this->Session->Write('start', $filterData['start']['day']."-".$filterData['start']['month']."-".$filterData['start']['year']);
        $this->Session->Write('end', $filterData['end']['day']."-".$filterData['end']['month']."-".$filterData['end']['year']);
        
        $this->redirect('/pdfs/print_dailypartsreport');
    }
        
}
?>