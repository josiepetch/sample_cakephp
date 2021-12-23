<link href="../../webroot/css/general.css" rel="stylesheet" type="text/css" />
<link href="../../webroot/css/tables.css" rel="stylesheet" type="text/css" />
<link href="../../webroot/css/dashboard.css" rel="stylesheet" type="text/css" />
<link href="../../webroot/css/forms.css" rel="stylesheet" type="text/css" />


<!--[if !IE]>start section<![endif]-->	
<div class="section table_section">
    <!--[if !IE]>start title wrapper<![endif]-->
    <div class="title_wrapper">
        <h2>Average part sale report</h2>
        <span class="title_wrapper_left"></span>
        <span class="title_wrapper_right"></span>
    </div>
    <!--[if !IE]>end title wrapper<![endif]-->
    <!--[if !IE]>start section content<![endif]-->
    <div class="section_content">
        <!--[if !IE]>start section content top<![endif]-->
        <div class="sct">
            <div class="sct_left">
                <div class="sct_right">
                    <div class="sct_left">
                        <div class="sct_right">
                            
                             
                            <!--[if !IE]>start table_wrapper<![endif]-->
                            <div class="table_wrapper">
                                <div class="table_wrapper_inner">
                               <div style="margin-left:100px;  ">
					<?php echo $this->Form->create('reports', array('action' => 'avg_sale', 'encoding' => 'windows-874', 'name' => 'fromavgsale', 'onsubmit' => 'return chkdata()')); 		?>	 <br/>
                                Avg part sale from date :
             <?php
        
            $start_date = date("d/m/Y");
			$start_date = explode("/",$start_date);
            $start_day = "1"; 
            $start_month = $start_date[1]; 
            $start_year = $start_date[2]; 
            
           /* if (isset($customer_detail)) {
                $start_date = $customer_detail['registration_date'];
                
                $start_day = date('d', strtotime($start_date));
                $start_month = date('m', strtotime($start_date));
                $start_year = date('Y', strtotime($start_date));
        
            }*/
           
            echo $this->Form->day('start', intval($start_day)); echo '-';
            echo $this->Form->month('start', intval($start_month)); echo '-';
            echo $this->Form->year('start', '1900', date('Y'), intval($start_year)); echo ' ';
            
        ?>
                                To
             <?php 
        	
			$end_date = date("d/m/Y");
			$end_date = explode("/",$end_date);
            $end_day = $end_date[0]; 
            $end_month = $end_date[1]; 
            $end_year = $end_date[2]; 
            
/*            if (isset($customer_detail)) {
                $end_date = $customer_detail['registration_date'];
                
                $end_day = date('d', strtotime($end_date));
                $end_month = date('m', strtotime($end_date));
                $end_year = date('Y', strtotime($end_date));
        
            }
*/            
            echo $this->Form->day('end', intval($end_day)); echo '-';
            echo $this->Form->month('end', intval($end_month)); echo '-';
            echo $this->Form->year('end', '1900', date('Y'), intval($end_year)); echo ' ';
            
        ?>
                               Brand
                                <select style="margin-left:9px; margin-right:14px;" name="data[avgSale][brand]" id="avgBrand" >
                                <option></option>
                                <?php 
                                 foreach ($work_brands as $work_brands) {
									 if($brand == $work_brands[0]['id']){
										 echo "<option value=\"" . $work_brands[0]['id'] . "\"  selected='selected'> " . $work_brands[0]['car_brand_name_th'] . "</option>";
									 }else{
                                    echo "<option value=\"" . $work_brands[0]['id'] . "\" > " . $work_brands[0]['car_brand_name_th'] . "</option>";
                                 }}
                                ?>
           					 </select>
                               
                               Part code
                                <input style="margin-left:9px; margin-right:14px;" type="text" name="data[avgSale][id_part]"  value="<?php if(isset($id_part)){echo $id_part;}?>"/>
                                Work type 
                                <select style="margin-left:9px; margin-right:14px;" name="data[avgSale][work_type]" id="avgWorkType" >
                                <option></option>
                                <option value="1" <?php if(isset($work_type) && $work_type == 1){ echo "selected='selected'"; }?>>อู่ซ่อม</option>
                                <option value="0" <?php if(isset($work_type) && $work_type == '0'){ echo "selected='selected'";  }?>>ขายหน้าร้าน</option>
           					 </select>
                                <br /><br />
                                <input style="margin-left:300px; margin-right:14px;" type="submit"  value="Search"><!--
                                <input style="margin-left:9px; margin-right:14px;" type="submit"  value="Clear">-->
                                </div><?php echo $this->Form->end(); ?>
                                <label style="float:right; color:blue;">Double Click to show sale detail</label><br/> 
                                <?php
if(!empty($this->data['start']['year']) && !empty($this->data['start']['month']) && !empty($this->data['start']['day'])){					
	$start = $this->data['start']['year']."-".$this->data['start']['month']."-".$this->data['start']['day'];
}
	
if(!empty($this->data['end']['year']) && !empty($this->data['end']['month']) && !empty($this->data['end']['day'])){	
	$end = $this->data['end']['year']."-".$this->data['end']['month']."-".$this->data['end']['day'];}

if(isset($end) && isset($start)){
	$date = date(strtotime($end))-date(strtotime($start));
	$date = round($date / (60 * 60 * 24));
}else{
	$date = 365;
}
	if(isset($avgsales)){
	echo $this->element('page_navigator_ex', 
		array('controller' => 'reports', 'action' => 'paging_avg_sale', 'heade' => 'Average part sale', 'foot'=>'items'));
	}
?>
                              <hr />
                                <table cellpadding="0" cellspacing="0" width="100%">
                                    <tbody><tr>
                                        <th style=" text-align:center;">NO.</th>
                                        <th style=" text-align:center;">Brand</th>
                                        <th style=" text-align:center;">Part code</th>
                                        <th style="text-align:center;">Part name</th>
                                        <th style="text-align:center;">Work type</th>
                                        <th style=" text-align:center;">Baht/Unit</a></th>
                                        <th style=" text-align:center;">Circulation(item)</th>
                                        <th style=" text-align:center;">Avg Circulation</th>
                                    <th style=" text-align:center;">Stock(item)</th>
                                        <th style=" text-align:center;">Total(Baht)</th>
                                    </tr>
 <?php //Debugger::dump($avgsales[2][0]);?>
 <?php if(isset($avgsales) && sizeof($avgsales) > 0){  if(isset($begin)){$row=$begin;}else{$row=1;} $sum_pair_cost=0; $sum_labor_cost=0; $all_sum=0;
							foreach ($avgsales as $job_repair){ ?>                                   
                                    
                                    <tr>
                                        <td class="white" style=" text-align:center;">
                                        <?php if(isset($start) && isset($end)){?>
											<?php echo $ajax->link($row, array('controller'=>'reports', 'action'=>'avg_sale_detail', $job_repair[0]['part_info_id'],$job_repair[0]['work_type_id'],$start,$end), array('update'=>'detail_avg_sale'));?>
                                        <?php }else{?>
                                        	<?php echo $ajax->link($row, array('controller'=>'reports', 'action'=>'avg_sale_detail', $job_repair[0]['part_info_id'],$job_repair[0]['work_type_id']), array('update'=>'detail_avg_sale'));?>
										<?php }?></td>
                                        <td style=" text-align:center;"><?php echo $job_repair[0]['car_brand_name_th']; ?></td>
                                        <td style=" text-align:center;"><?php echo $job_repair[0]['part_code']; ?></td>
                                        <td style=" text-align:left;"><?php echo $job_repair[0]['part_name_th']; ?></td>
                                        <?php if($job_repair[0]['work_type_id'] == 1){
											$work_type_id = 'Garage';
										}else{
											$work_type_id = 'Part shop';
										}?>
                                         <td style=" text-align:left;"><?php echo $work_type_id;?></td>
                                        <td style=" text-align:right;"><?php echo number_format($job_repair[0]['price'],2); ?></td>
                                        <td style=" text-align:right;"><?php echo $job_repair[0]['sell_quan']; ?></td>
                                        <td style=" text-align:right;"> <?php echo round($job_repair[0]['sell_quan']/$date,2); ?></td>
                                        <td style=" text-align:right;"> <?php echo $job_repair[0]['quantity']; ?></td>
                                        <td style=" text-align:right;"> <?php echo number_format($job_repair[0]['SUM_PRICE'],2); ?></td>
                    <?php  $row++; }  } else {echo "<tr><td colspan=\"13\"><center><strong>Not found data</strong></center></td></tr>";} ?>                   
                                    </tr>
                                    
                                    
                                </tbody></table>
                                <br/>
                               <?php if(isset($avgsales)){?>
                                <input style="margin-left:300px; margin-right:14px;" type="button"  value="Print report" onclick="window.open('/yontrakij/reports/print_averagesalereport?type=2&page=<?php echo $curpage; ?>')" >
                                <br/>
								<?php } ?>
                              </div>
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
</div>
<!--[if !IE]>end section<![endif]-->

<div id="detail_avg_sale">
</div>