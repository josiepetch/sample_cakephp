<link href="../../webroot/css/general.css" rel="stylesheet" type="text/css" />
<link href="../../webroot/css/tables.css" rel="stylesheet" type="text/css" />
<link href="../../webroot/css/dashboard.css" rel="stylesheet" type="text/css" />
<link href="../../webroot/css/forms.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.header {
	font-weight: bold;
}
.section .section_content .sct .sct_left .sct_right .sct_left .sct_right .table_wrapper .table_wrapper_inner strong {
	font-size: 15px;
}
</style>

<span class="header">Check invoice detail</span>
<br /><br /><br />
<?php 
if(!isset($part_order_id)) {
	$part_order_id = "";
}
?>

<!-- Search PO number (invoice that confirmed) -->
<?php echo $this->Form->create('Emc', array('action' => 'order_details', 'encoding' => 'windows-874')); 
//create form name 'emcs_controller' function 'order_details'?>  

PO number  <?php echo $ajax->autoComplete('PartOrder.PO_ID', '/part_orders/autocomplete', array('size'=>'25', 'value'=>$part_order_id)); ?>

<input type="submit" name="button" id="button" value="Search" />

<?php echo $this->Form->end(); ?>



<?php

if(isset($part_orders)){

?>

<div class="section">
  <!--[if !IE]>start title wrapper<![endif]-->
  <div class="title_wrapper">
    <h2>&nbsp;</h2>
  <span class="title_wrapper_left"></span> <span class="title_wrapper_right" style="display: block; "></span></div>
  <!--[if !IE]>end title wrapper<![endif]-->
  <!--[if !IE]>start section content<![endif]-->
  <div class="section_content">
    <!--[if !IE]>start section content top<![endif]-->
    <div class="sct">
      <div class="sct_left">
        <div class="sct_right">
          <div class="sct_left">
            <div class="sct_right">


Supplier name&nbsp; <?php echo $part_orders[0][0]['supplier_name']; ?>

&nbsp;&nbsp;&nbsp;&nbsp;
Shipment method  <?php echo $part_orders[0][0]['shipment_method_desc']; ?>
<br />
<br />

Mechanic name&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $part_orders[0][0]['mechanic_name']; ?>

&nbsp;&nbsp;&nbsp;&nbsp;
Order date&nbsp;<?php echo $part_orders[0][0]['order_date'] ?>
<br />
<br />


<?php echo $this->Form->create('PartOrder', array('action' => 'save_po', 'encoding' => 'windows-874', 'onclick' => 'submit_part()')); 
//create form name='PartOrder' action='save_po'?>

<?php 

$total_price = 0;
//Debugger::dump($part_orders[0][0]);
?>

<input name="data[PO_ID]" type="hidden" id="PO_ID" value="<?php echo $part_order_id; ?>" />

<div class="table_wrapper">
                <div class="table_wrapper_inner">
<table width="898" border="0">
  <tr>
    <th width="41" height="44">NO.</th>
    <th width="146">Part code</th>
    <th width="160">Part name</th>
    <th width="116">FOB</th>
    <th width="120">Quantity</th>
    <th width="100">Memo</th>
  </tr>
  
<?php 
	
if(isset($part_orders) && sizeof($part_orders) > 0){

	$part_count = 1;
	
	foreach ($part_orders as $part_order):
	
?>

  
  <tr>
    <td><?php echo $part_count; ?></td>
    <td><?php echo $part_order[0]['part_info_id'];?></td>
    <td><?php echo $part_order[0]['part_name_th'];?></td>
    <td align="right"><?php echo $part_order[0]['order_price'];?></td>
    <td align="right">
    
    <?php

	echo $part_order[0]['quantity'];
	$total_price += $part_order[0]['order_price'] * $part_order[0]['quantity'];
	
	?>
    </td>
    <td align="center">
    <?php 
	
	if(isset($part_choose['BO']) && $part_choose['BO'] == true){
		echo 'BO';
	}else{
		echo '-';
	}
	
	?>
   
    </td>

  </tr>
  
 <?php 
 
 	$part_count++;
	
 
 	endforeach;
	
	$tax = $total_price*0.07;
	$net = $total_price*1.07; 
	
	
}

 ?>
  
</table>
</div></div>

</p>
<table width="846" border="0">
  <tr>
    <td width="460" valign="top">Memo</td>
    <td width="189" align="right">&nbsp;</td>
    <td width="183" align="right">&nbsp;</td>
  </tr>
  <tr>
    <td width="460" rowspan="4" valign="top">
	<textarea id="PartOrderOrderNote" rows="4" cols="70" name="data[PartOrder][order_note]" ><?php echo $part_order[0]['note']; ?></textarea>
    </td>
    <td width="189" align="right">Total before tax</td>
    <td width="183" align="right"><?php echo $total_price; ?></td>
  </tr>
  <tr>
    <td align="right">Tax</td>
    <td align="right"><?php echo $tax; ?></td>
  </tr>
  <tr>
    <td align="right">Net</td>
    <td align="right"><?php echo $net; ?></td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
</table>
<p>

  
</p>
<p>&nbsp;</p>
<p>
<?php 
if($part_orders[0][0]['status'] == 'PR'){ 
//before confirm ordered invoice status is 'PR' ,after click this button invoice status is 'PO'
?>

  <input type="submit" name="button" id="button" value="Confirm order" />
  
<?php } ?>

  <br />
</p>
<?php echo $this->Form->end(); //close form ?>

</div></div></div></div></div></div>
<span class="scb"><span class="scb_left"></span><span class="scb_right"></span></span>
</div>
<?php 

}

?>
