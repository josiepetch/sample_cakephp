<p>&nbsp;</p>

	<!--add here-->
<link href="../../webroot/css/general.css" rel="stylesheet" type="text/css" />
<link href="../../webroot/css/tables.css" rel="stylesheet" type="text/css" />
<link href="../../webroot/css/dashboard.css" rel="stylesheet" type="text/css" />
<link href="../../webroot/css/forms.css" rel="stylesheet" type="text/css" />
	<!--end here-->
    
<div class="section">
<!--[if !IE]>start title wrapper<![endif]-->
	<div class="title_wrapper">
	<h2>บันทึกข้อมูลการสั่งซื้อ</h2><!--Store order detail-->
	<span class="title_wrapper_left"></span>
	<span class="title_wrapper_right" style="display: block; "></span></div>
	<!--[if !IE]>end title wrapper<![endif]-->
	<!--[if !IE]>start section content<![endif]-->
		<div class="section_content">
		<!--[if !IE]>start section content top<![endif]-->
			<div class="sct">
				<div class="sct_left">
					<div class="sct_right">
						<div class="sct_left">
							<div class="sct_right">
                            <?php echo $this->Form->create('Emc', array('action' => 'order_histories', 'encoding' => 'windows-874')); ?>
							PO number <!--<input type="text" name="data[po_id]" id="po_id" />-->
                             <?php echo $ajax->autoComplete('PartOrder.PO_ID', '/part_orders/autocomplete', array('size'=>'25', 'value'=>$part_order_id)); ?>&nbsp;&nbsp;
							Date 
                            <?php
				            $start_date = "";
            				$start_day = ""; 
           					$start_month = ""; 
            				$start_year = ""; 
            
            				if (isset($customer_detail)) {
                				$start_date = $customer_detail['registration_date'];
                
                				$start_day = date('d', strtotime($start_date));
                				$start_month = date('m', strtotime($start_date));
                				$start_year = date('Y', strtotime($start_date));
				            }
            
  				          echo $this->Form->day('start', intval($start_day)); echo '-';
    				      echo $this->Form->month('start', intval($start_month)); echo '-';
            			echo $this->Form->year('start', '1900', date('Y'), intval($start_year)); echo ' ';
				        ?>&nbsp;&nbsp;
                         ถึง
                         <?php 
						$end_date = "";
						$end_day = ""; 
						$end_month = ""; 
						$end_year = ""; 
            
						if (isset($customer_detail)) {
							$end_date = $customer_detail['registration_date'];
                
							$end_day = date('d', strtotime($end_date));
							$end_month = date('m', strtotime($end_date));
							$end_year = date('Y', strtotime($end_date));
						}
            
						echo $this->Form->day('end', intval($end_day)); echo '-';
						echo $this->Form->month('end', intval($end_month)); echo '-';
						echo $this->Form->year('end', '1900', date('Y'), intval($end_year)); echo ' ';
						?>&nbsp;&nbsp;
                        Invoice status
                        <select name="data[status]">
                        	<option value=""></option>
                            <option value="1">เปิดใบสั่งซื้อ-Open invoice</option>
                            <option value="2">สั่งซื้อแล้ว-Order</option>
                            <option value="3">เริ่มรับสินค้า-Deliver</option>
                            <option value="4">รับสินค้าครบแล้ว-Delivered</option>
                            <option value="5">ปิดการสั่งซื้อ-Close invoice</option>
                         </select>&nbsp;&nbsp;
                        <input type="submit" value="Search" />
                        <?php echo $this->Form->end(); ?>
                        <br />
                        <?php
						//Debugger::dump($obj_nums[0][0]);
	echo $this->element('page_navigator_ex', 
		array('controller' => 'emcs', 'action' => 'paging_order_histories', 'heade' => 'Work in process', 'foot'=>'items'));
						?>
                        <div class="table_wrapper">
                             <div class="table_wrapper_inner">

<table width="100%" height="95" border="0">
  <tr>
    <th width="75" height="44">NO.</th>
    <th width="144">Order date</th>
    <th width="158">PO number</th>
    <th width="170">Shipment method</th>
    <th width="439">Supplier name</th>
    <th width="133">Order quantity</th>
    <th width="133">Receive quantity</th>
    <th width="157">Status</th>
    <th width="113">Action</th>
  </tr>
  <?php 
  
  	//Debugger::dump($part_order[0][0]);
	$i = 0;	$arr = array();
	$size_check = sizeof($part_check);
	$check = $part_check[0][0]['PO_ID'];
	$arr[0][0]['PO_ID'] = $check;
  	 for($j=1; $j<$size_check; $j++){
		if($check <> $part_check[$j][0]['PO_ID']){
			$i++;	$check = $part_check[$j][0]['PO_ID'];
			$arr[$i][0]['PO_ID'] = $part_check[$j][0]['PO_ID'];
		}
	 }	//Debugger::dump($arr[0]['PO_ID']);
	$row = 1;		$j = 0;
	$size = sizeof($part_order);
	$size_check = sizeof($arr);
	for($i=0; $i<$size; $i++){
	
	$row_ref_id = "work_" . $part_order[$i][0]['id'];
	$row_ref_id_open = $row_ref_id . "_open";
	$row_ref_id_close = $row_ref_id . "_close";

?>

	
  <tr>
    <td align="center"><?php echo $row;?></td>
    <td><?php echo $part_order[$i][0]['order_date'] ?></td>
    <td><?php echo $part_order[$i][0]['PO_ID'] ?></td>
    <td><?php echo $part_order[$i][0]['shipment_method_desc'] ?></td>
    <td><?php echo $part_order[$i][0]['supplier_name'] ?></td>
    <td align="right"><?php echo $part_order[$i][0]['quantity'] ?></td>
    <td align="right"><?php echo $part_order[$i][0]['receive_quantity'] ?></td>
    <td>&nbsp;</td>
    <td>
      <div class="actions">
        <ul>
          <?php
            echo "<li id=\"" . $row_ref_id_open . "\">"; 
				echo $ajax->link(
					'1',
					array( 'controller' => 'part_orders', 'action' => 'detail', $part_order[$i][0]['PO_ID']),
					array( 
					'update' => $row_ref_id, 
					'class' => 'action6', 
					'complete' => "switch_button('" . $row_ref_id_open . "', '" . $row_ref_id_close . "')")); 
					
			echo "</li>"; 
			
			echo "<li id=\"" . $row_ref_id_close . "\" style=\"display:none\">";
				echo $ajax->link(
					'1',
					array( 'controller' => 'part_orders', 'action' => 'no_detail', $part_order[$i][0]['PO_ID']),
					array( 
					'update' => $row_ref_id, 
					'class' => 'action1', 
					'complete' => "switch_button('" . $row_ref_id_close . "', '" . $row_ref_id_open . "')")); 
			echo "</li>";
			?>
          <li>
            <?php      
                echo $this->Html->link(
                    'Order detail',
                    array('controller' => 'emcs', 'action' => 'order_details', 
                    $part_order[$i][0]['PO_ID']),array('escape' => false, 'class' => 'action7'));
            ?>            
          </li>
          <?php
		 if($part_order[$i][0]['PO_ID'] == $arr[$j][0]['PO_ID']){
			echo "<img src=\"/yontrakij/img/iconhi07.gif\" >";
			$j++;
		 }
          ?>
          </ul>
      </div>
      
    </td>
  </tr>
  <tr id="<?php echo $row_ref_id; ?>">
  </tr>
  <?php 
  
  $row++;
	}
  
//}
 ?>
</table>
</div>		
                                                        <!--[if !IE]>end table wrapper<![endif]-->
														</div>
														<!--[if !IE]>end table wrapper<![endif]-->													
										  </div>	
									  </div>
								  </div>
							  </div>
						  </div>
   </div>
							<!--[if !IE]>end section content top<![endif]-->
							<!--[if !IE]>start section content bottom<![endif]-->
  <span class="scb"><span class="scb_left"></span><span class="scb_right"></span></span>
							<!--[if !IE]>end section content bottom<![endif]-->
							
</div>
						<!--[if !IE]>end section content<![endif]-->
<p>&nbsp;</p>
