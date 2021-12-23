<link href="../../webroot/css/general.css" rel="stylesheet" type="text/css" />
<link href="../../webroot/css/tables.css" rel="stylesheet" type="text/css" />
<link href="../../webroot/css/dashboard.css" rel="stylesheet" type="text/css" />
<link href="../../webroot/css/forms.css" rel="stylesheet" type="text/css" />

<style type="text/css">
.section .section_content .sct .sct_left .sct_right .sct_left .sct_right .table_wrapper .table_wrapper_inner table tr td table tr td {
	font-size: 13px;
	font-weight: bold;
	color: #000;
}
</style>

<p><strong>Save order invoice detail</strong></p>

<p>
  <?php 
if(!isset($part_order_id)) {
	$part_order_id = "";
}
?>
  
  <?php echo $this->Form->create('Emc', array('action' => 'order_data', 'encoding' => 'windows-874')); ?>  
  
  PO number  <?php echo $ajax->autoComplete('PartOrder.PO_ID', '/part_orders/autocomplete', array('size'=>'25', 'value'=>$part_order_id)); ?>
  
  <input type="submit" name="button" id="button" value="Search" />
  
<?php echo $this->Form->end(); ?></p>

<?php
if(isset($part_orders)){
?>
<div class="section">
  <!--[if !IE]>start title wrapper<![endif]-->
  <div class="title_wrapper">
    <h2>Invoice detail</h2>
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
              <div class="table_wrapper">
                <div class="table_wrapper_inner">
                  <table width="100%" height="95" border="0">
                    <tr>
                      <th width="133">PO number</th>
                      <th width="136">Order data</th>
                      <th width="146">Shipment method</th>
                      <th width="449">Supplier name</th>
                      <th width="136">Order quantity</th>
                      <th width="137">Receive quantity</th>
                    </tr>
                    <?php 
  
  	//Debugger::dump($part_order[0][0]);
  


?>
                    <tr>
                      <td><?php echo $part_orders[0][0]['PO_ID'] ?></td>
                      <td><?php echo $part_orders[0][0]['order_date'] ?></td>
                      <td><?php echo $part_orders[0][0]['shipment_method_desc'] ?></td>
                      <td><?php echo $part_orders[0][0]['supplier_name'] ?></td>
                      <td><?php echo $part_orders[0][0]['quantity'] ?></td>
                      <td><?php echo is_null($part_orders[0][0]['receive_quantity']) ? 0 : $part_orders[0][0]['receive_quantity'] ?></td>
                    </tr>
                  </table>
                </div>
                <!--[if !IE]>end table wrapper<![endif]-->
              </div>
              <!--[if !IE]>end table wrapper<![endif]-->
              <br />
              
              <?php echo $this->Form->create('Emc', array('action' => 'save_order_data', 'encoding' => 'windows-874')); ?>  

<input type="hidden" name="data[Emc][PO_ID]" id="EmcPOID" value="<?php echo $part_order_id ?>"  />
<?php //Debugger::dump($part_orders[0][0]);?>
<p>Order No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;
<?php echo $this->Form->Text('order_no',array('value' => $part_orders[0][0]['order_no'])); ?> </p>
<p>SAP No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp; 
<?php echo $this->Form->Text('sap_no',array('value' => $part_orders[0][0]['sap_no'])); ?></p>

<p>

  <input type="button" name="button2" id="button2" value="Save" onclick="submitFormConfirm(this.form, 'Confirm to order?', '/yontrakij/emcs/save_order_data')"/>
  &nbsp;&nbsp;&nbsp;
  <input type="reset" name="button5" id="button5" value="Clear" />
</p>

<?php echo $this->Form->end(); ?>

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

<?php }//end of if isset part order?>