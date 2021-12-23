<?php

class EmcsController extends AppController {
   
	var $helpers = array ('Html','Form','Ajax','Javascript');
 	var $name = 'Emcs';
	var $components = array('Autocomplete');
	var $navigation_for_layout = "MENU_50000";
	
    function index()
    {
        $this->redirect('order_histories');
    }
	
	function order_histories() {//view at order_histories.ctp
		set_time_limit(0);
		$this->loadModel('PartOrder');
		
		$part_order = $this->PartOrder->view_po(1, 50, null);//call model name part_order.php->function view_po()
		$this->set('part_order', $part_order);//set parameter name part_order to show at view name order_histories.ctp
        
        $query_str ='SELECT * FROM [yontrakij_db].[dbo].[part_receive_check_view] ORDER BY PO_ID';
        $part_check = $this->PartOrder->query($query_str, false);//query sql statement
        $this->set('part_check', $part_check);
        
        $query_str =' 
            WITH Orderslist AS
            ( 
                SELECT *, ROW_NUMBER() OVER (ORDER BY [PO_ID]) as [RowNum] 
                FROM [yontrakij_db].[dbo].[part_orders_view] AS Orderslist
                WHERE NOT [PO_ID] IS NULL AND 1 = 1
            )
            SELECT [PO_ID]
            FROM [Orderslist] ';
        $part_order_id = $this->PartOrder->query($query_str, false);
        $this->set('part_order_id', $part_order_id);
        
        $filter = '';	
		if(isset($this->data) && sizeof($this->data) > 0){

		$page_size 	= 20;
		
		$part_order = $this->PartOrder->view_po(1, $page_size, $this->data);
		$this->set('part_order', $part_order);
		
		$obj_num = $this->PartOrder->search_view_po_count($this->data);	
		
		$page_num  = ceil(floatval($obj_num)/floatval($page_size));
		
		$this->Session->write('Paging.data', $this->data);
		$this->Session->write('Paging.obj_nums', $obj_num);//page next
		
		$this->set('obj_nums', $obj_num);//page next
		$this->set('page_num', $page_num);
		$this->set('curpage', 1);
		}
	}
    function paging_order_histories($page = 1) {//call this when change page
		$this->data = $this->Session->read('Paging.data');
		
		$page_size 	= 20;
				
		$obj_num = $this->PartOrder->search_view_po_count($this->data);
		$obj_nums 	= $this->Session->read('Paging.obj_nums');//page next
		$page_num  = ceil(floatval($obj_num)/floatval($page_size));
		
		$begin 	= ($page-1) * $page_size + 1;
		$this->set('begin', $begin);
		$end 	= $begin + $page_size - 1;
		
		$part_order = $this->PartOrder->view_po($begin, $end, $this->data);
		//Debugger::dump($$this->data);
		$this->set('part_order', $part_order);
		$this->set('obj_nums', $obj_nums);//page next
				
		$this->set('page_num', $page_num);
		$this->set('curpage', $page);
		$this->Session->write('Paging.obj_nums', $obj_nums);//page next
		

	}

	function order_details($id = 0) {
		
		if($id == '0' && isset($this->data['PartOrder']['PO_ID'])){
			
			if($this->data['PartOrder']['PO_ID'] != null && $this->data['PartOrder']['PO_ID'] != '0') {
				$id = $this->data['PartOrder']['PO_ID'];
				unset($this->data['PartOrder']['PO_ID']);
			}
		
		}
		
		if($id != '0'){
			
			$this->loadModel('PartOrder');
			
			$this->data['PartOrder']['PO_ID'] = $id;
			$part_order = $this->PartOrder->find_po_order(1, 1, $this->data);
			$this->set('part_orders', $part_order);
			$this->set('part_order_id', $id);
			unset($this->data['PartOrder']['PO_ID']);
						
		}
		
		
	}
	
	function order_data($id = 0) {
		
		if($id == '0' && isset($this->data['PartOrder']['PO_ID'])){
			
			if($this->data['PartOrder']['PO_ID'] != null && $this->data['PartOrder']['PO_ID'] != '0') {
				$id = $this->data['PartOrder']['PO_ID'];
				unset($this->data['PartOrder']['PO_ID']);
			}
		
		}
		
		if($id != '0'){
			
			$this->loadModel('PartOrder');
			
			$this->data['PartOrder']['PO_ID'] = $id;
			$part_order = $this->PartOrder->find_po_order(1, 1, $this->data);
			$this->set('part_orders', $part_order);
			$this->set('part_order_id', $id);
			unset($this->data['PartOrder']['PO_ID']);
						
		}
		
	}
	
	function save_order_data() {
		
		$insert_query = "
		UPDATE [yontrakij_db].[dbo].[part_orders] 
		SET [order_no] = '" . $this->data['Emc']['order_no'] . "', [sap_no] = '" . $this->data['Emc']['sap_no'] . "' 
		WHERE [PO_ID] = '" . $this->data['Emc']['PO_ID'] . "'";
		
		$result = $this->Emc->query($insert_query, false);
		
		$user = $this->Session->read('User.username');
		
		$this->loadModel('UserAction');
		$sub_result = $this->UserAction->save_log($user, Configure::read('UserAction.set_sap'), 'part_orders', array($this->data['Emc']['PO_ID']));
		
		$this->redirect('order_histories');
		

	}
	
	
}
?>