<link href="../../webroot/css/general.css" rel="stylesheet" type="text/css" />
<link href="../../webroot/css/tables.css" rel="stylesheet" type="text/css" />
<link href="../../webroot/css/dashboard.css" rel="stylesheet" type="text/css" />
<link href="../../webroot/css/forms.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
</script>
<!--[if !IE]>start section<![endif]-->	
<div class="section table_section">
    <!--[if !IE]>start title wrapper<![endif]-->
    <div class="title_wrapper">
        <h2>Daily part receive and sell report</h2>
        <span class="title_wrapper_left"></span>
        <span class="title_wrapper_right"></span>
    </div>    
    <div class="section_content">
        <div class="sct">
            <div class="sct_left">
                <div class="sct_right">
                    <div class="sct_left">
                        <div class="sct_right">
    
                            <!--[if !IE]>start table_wrapper<![endif]-->
                            <div class="table_wrapper">
                                <div class="table_wrapper_inner">
                                <div class="table_wrapper_inner">
 							<br/>	
                            <?php echo $this->Form->create('reports', array('action' => 'p_recrive_part', 'encoding' => 'windows-874', 'name' => 'fromRecrivePart', 'onsubmit' => 'return chkdata()')); ?>      
                             <span style="margin-left:200px;" > From :</span>    
   <?php                //dorpDownDate  							
                            $t1_day = date('d');
                            $t1_month = date('m');
                            $t1_year = date('Y');
                            $t2_day = date('d');
                            $t2_month = date('m');
                            $t2_year = date('Y');
							if(isset($s_day)){$t1_day = $s_day;}
							if(isset($s_month)){$t1_month = $s_month;}
							if(isset($s_year)){$t1_year = $s_year;}
							if(isset($s_fday)){$t2_day = $s_fday;}
							if(isset($s_fmonth)){$t2_month = $s_fmonth;}
							if(isset($s_fyear)){$t2_year = $s_fyear;}
							if(isset($s_wh)){$wareh = $s_wh;}else{$wareh = "";}
							if(isset($s_brand)){$brandh = $s_brand;}else{$brandh = "";}
							if(isset($s_partid)){$partidh = $s_partid;}else{$partidh = "";}
							if(isset($s_sort)){$sorth = $s_sort;}else{$sorth = "";}
			?>
                            <?php 
			echo $this->Form->day('start', $t1_day); 
			echo ' '; echo $this->Form->month('start', $t1_month); echo ' '; 
            echo $this->Form->year('start', '1900', date('Y'), $t1_year); echo ' '; 
			?>             
					   <!--<?php     
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
            
        ?>-->
                                To
                                <?php
            echo $this->Form->day('end', $t2_day); 
			echo ' '; echo $this->Form->month('end', $t2_month); echo ' '; 
            echo $this->Form->year('end', '1900', date('Y'), $t2_year); echo ' '; 
			?>
             <!--<?php 
        
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
            
        ?>-->                     
          <span style="margin-left:15px;" > Warehouse :</span> 
            <select name="data[part][warehouse]" id="warehouse">
          	<option value="" ></option>
            <?php foreach($warehouses as $warehouse){
							if($wareh==$warehouse[0]['id']){$sel = "selected=\"selected\"";}else{$sel="";}
							echo "<option value=\"".$warehouse[0]['id']."\" ".$sel.">".$warehouse[0]['warehouse_name']."</option>";
						}
             ?>
          </select>
                      <span style="margin-left:10px;" >Brand</span> 
                      <select name="data[part][brand]" id="brand">
                        <option value="" ></option>
                        <?php foreach($work_brands as $work_brand){
										if($brandh==$work_brand[0]['id']){$sel = "selected=\"selected\"";}else{$sel="";}
                                        echo "<option value=\"".$work_brand[0]['id']."\" ".$sel.">".$work_brand[0]['car_brand_name_th']."</option>";
                                    }
                         ?>
         		 </select>                   
                     <span style="margin-left:10px;" >part code</span>
          <input type="text" id="" name="data[part][partID]" value="<?php echo$partidh; ?>"  /> 
          
           <span style="margin-left:10px;" >sort by</span> 
              <select name="data[part][sort]" id="sort">
              		<option value="" ></option>
                     <option value="warehouse_id" <?php if($sorth=="warehouse_id"){ echo"selected=\"selected\"";} ?>>warehouse</option>
                      <option value="id" <?php if($sorth=="id"){ echo"selected=\"selected\"";} ?>>part code</option>
                      <option value="carBrand" <?php if($sorth=="carBrand"){ echo"selected=\"selected\"";} ?>>brand</option>
                      <option value="main_location" <?php if($sorth=="main_location"){ echo"selected=\"selected\"";} ?>>warehouse</option>
         		 </select>      
           <br />  <br />       				
                   <input style="margin-left:650px; margin-right:14px;" type="submit"  value="search">
                   <input style="margin-left:10px; margin-right:14px;" type="button"  value="clear" onclick="window.location.href='/yontrakij/reports/p_recrive_part'">
                  <?php echo $this->Form->end(); ?><br />              
  <?php
	
	/*echo $this->element('page_navigator_ex', 
		array('controller' => 'reports', 'action' => 'paging_p_recrive_part', 'heade' => 'Disbursed spare', 'foot'=>'item'));*/
	echo "Disbursed spare ".sizeof($recriveparts)." item";
?>                            
                              <hr />
                                <table cellpadding="0" cellspacing="0" width="100%">
                                    <tbody><tr>
                                        <th rowspan="2" style=" text-align:center;"><a href="/yontrakij/reports/p_recrive_part?o=warehouse_id" >warehouse</a></th>
                                        <!--onclick="window.location.href='/yontrakij/reports/p_recrive_part?o=warehouse_id'"onclick="window.location.href='/yontrakij/reports/p_recrive_part?o=carBrand'"
                                        onclick="window.location.href='/yontrakij/reports/p_recrive_part?o=carModel'"onclick="window.location.href='/yontrakij/reports/p_recrive_part?o=id'"
                                        onclick="window.location.href='/yontrakij/reports/p_recrive_part?o=part_name_th'"onclick="window.location.href='/yontrakij/reports/p_recrive_part?o=main_location'" -->
                                        <th rowspan="2" style=" text-align:center"><a href="/yontrakij/reports/p_recrive_part?o=carBrand" >brand</a></th>
                                        <th rowspan="2" style=" text-align:center;"><a href="/yontrakij/reports/p_recrive_part?o=carModel" >model</a></th>
                                        <th rowspan="2" style=" text-align:center;"><a href="/yontrakij/reports/p_recrive_part?o=id" >part code</a></th>
                                        <th rowspan="2" style=" text-align:center;"><a href="/yontrakij/reports/p_recrive_part?o=part_name_th" >part name</a></th>                                    	
                                        <th rowspan="2" style=" text-align:center;"><a href="/yontrakij/reports/p_recrive_part?o=main_location" >store</a></th>
                                        <th rowspan="2" style=" text-align:center;">balance beginning</th>
                                        <th rowspan="1" colspan="4" style=" text-align:center;">Receive</th>
                                        <th rowspan="1" colspan="4" style=" text-align:center;">Pay</th>
                                        <th rowspan="2" style=" text-align:center;">balance ending</th>
                                        <th rowspan="2" style=" text-align:center;">revenue</th>
                                        </tr>
                              		 <tr>
                                        <th rowspan="1" style=" text-align:center;">sell receive</th>
                                        <th rowspan="1" style=" text-align:center">withdraw</th>
                                        <th rowspan="1" style=" text-align:center;">other receive</th>
                                        <th rowspan="1" style=" text-align:center;">sum receive</th>
                                        <th rowspan="1" style=" text-align:center;">sell</th>
                                        <th rowspan="1" style=" text-align:center;">withdraw quan</th>
                                        <th rowspan="1" style=" text-align:center;">other sell</th>
                                       <th rowspan="1" style=" text-align:center;">total sell</th>                                  
                                        </tr>
                                <?php //Debugger::dump($recriveparts[0][0]);?>
								<?php if(isset($recriveparts) && sizeof($recriveparts) > 0){  if(isset($begin)){$row=$begin;}else{$row=1;} $sum_pair_cost=0; $sum_labor_cost=0; $all_sum=0;
							foreach ($recriveparts as $recrivepart){ ?>
                                    <tr class="second">
                                        <!--คลัง--><td style="text-align:center"><?php echo $recrivepart[0]['warehouse_id']; ?></td>
                                        <!--ยี่ห้อ--><td style="text-align:left"><?php echo $recrivepart[0]['carBrand']; ?></td>
                                        <!--รุ่นรถ--><td style="text-align:left"><?php echo $recrivepart[0]['carModel']; ?></td>
                                        <!--เบอร์อะไหล่--><td style="text-align:left"><?php echo $recrivepart[0]['id']; ?></td>
                                        <!--ชื่ออะไหล่--><td style="text-align:left"><?php echo $recrivepart[0]['part_name_th']; ?></td>
                                        <!--สถานที่เก็บ--><td style="text-align:left"><?php echo $recrivepart[0]['main_location']; ?></td>
                                        <!--ยกมา--><td style="text-align:right"><?php echo $recrivepart[0]['take_over_quan']; ?></td>
                                        <!--รับ--><td style="text-align:right"><?php echo $recrivepart[0]['receive_quan']; ?></td>
                                        <!--คืนจ๊อบ--><td style="text-align:right"><?php echo $recrivepart[0]['disposit_quan']; ?></td>
                                        <!--รับอื่น ๆ--><td style="text-align:right"><?php echo $recrivepart[0]['other_rec_quan']; ?></td>
                                        <!--รวมรับ--><td style="text-align:right"><?php echo $recrivepart[0]['sum_rec_quan']; ?></td>
                                        <!--ขาย--><td style="text-align:right"><?php echo $recrivepart[0]['sell_quan']; ?></td>
                                        <!--จ่ายจ๊อบ--><td style="text-align:right"><?php echo $recrivepart[0]['withdraw_quan']; ?></td>
                                        <!--จ่ายอื่น ๆ--><td style="text-align:right"><?php //echo $recrivepart[0]['work_date']; ?></td>
                                        <!--รวมจ่าย--><td style="text-align:right"><?php echo $recrivepart[0]['total_out_quan']; ?></td>
                                        <!--ยกไป--><td style="text-align:right"><?php //echo $recrivepart[0]['work_date']; ?></td>
                                        <!--รายได้--><td style="text-align:right"><?php //echo $recrivepart[0]['car_brand_name_en']; ?></td>
                                    </tr>     
                                     <?php  $row++; }  } else {echo "<tr><td colspan=\"17\"><center><strong>ไม่พบข้อมูล</strong></center></td></tr>";} ?>           
                                </tbody></table>
                                <br/>
                                 <input style="margin-left:300px; margin-right:14px;" type="button"  value="พิมพ์รายการรับจ่าย" onclick="window.open('/yontrakij/reports/print_p_recrive_part')" >
                                <center>
                                </center>
                                </div><!--table_wrapper_inner-->
                           		</div> <!--[if !IE]>end table_wrapper<![endif]-->
                           		</div> <!--sct_right-->        
                                </div><!--sct_left-->      						
                			 	</div>	<!--sct_right-->
                   				</div><!--sct_left-->        
                                </div><!--sct-->                  
                        </div><!--section_content -->
        <span class="scb"><span class="scb_left"></span><span class="scb_right"></span>
        </span>
       </div> <!--[if !IE]>end section content bottom<![endif]-->
    </div>
    <!--[if !IE]>end section content<![endif]
<!--////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
								