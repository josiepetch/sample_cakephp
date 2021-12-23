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
        <h2>รายงานระเอียดการขาย</h2>
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
                    
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tbody><tr>
                                        <th rowspan="1" style=" text-align:center;">ยี่ห้อ</th>
                                        <th rowspan="1" style=" text-align:center;">เลขที่จ็อบ</th>
                                <th rowspan="1" style=" text-align:center;">รหัสอะไหล่</th>
                                        <th rowspan="1" style=" text-align:center">ชื่ออะไหล่</th>
                                        <th rowspan="1" style=" text-align:center;">ปี</th>
                                        <th rowspan="1" style=" text-align:center;">เดือน</th>
                                        <th rowspan="1" style=" text-align:center;">จำนวนขาย</th>
                                        <th rowspan="1" style=" text-align:center;">ลดหนี้(%)</th>
                                        </tr>
					
					<?php if(isset($job_details) && sizeof($job_details) > 0){ if(isset($begin)){$row=$begin;}else{$row=1;}
						$numrow = sizeof($job_details);	
						foreach ($job_details as $job_detail){ ?>
                                    <tr class="second">
                                        <td style="text-align:center"><?php echo $job_detail[0]['car_brand_name_th']; ?></td>
                                        <td style="text-align:center"><?php echo $job_detail[0]['work_code']; ?></td>
                                        <td style="text-align:left"><?php echo $job_detail[0]['part_code']; ?></td>
                                    <td style="text-align:left"><?php echo $job_detail[0]['part_name_th']; ?></td>
                                   <td style="text-align:right">
                                     <div align="center"><?php echo $job_detail[0]['work_year'];?></div></td>
                                    <td style="text-align:right">
                                      <div align="center">
                                        <?php   echo $job_detail[0]['work_month'];		?>
                                      </div></td>
                                        <td style="text-align:right">
                                         <div align="center"><?php echo $job_detail[0]['quantity']?></div></td>
                                       <td style="text-align:center">
                                         <?php echo $job_detail[0]['part_discount'];?></td>
                                    </tr>  
                    <?php $row++; } } else {echo "<tr><td colspan=\"15\"><center><strong>ไม่พบข้อมูล</strong></center></td></tr>";}  ?>                                                       
                      </tbody></table>
                                <br/>
                                </div><!--table_wrapper_inner-->
                           		</div> <!--[if !IE]>end table_wrapper<![endif]-->
                           		</div> <!--sct_right-->        
                                </div><!--sct_left-->      						
                			 	</div>	<!--sct_right-->
                   				</div><!--sct_left-->        
                                </div><!--sct-->                  
                        </div><!--section_content -->
        <span class="scb"><span class="scb_left"></span><span class="scb_right"></span></span>
       </div> <!--[if !IE]>end section content bottom<![endif]-->
    </div>
    <!--[if !IE]>end section content<![endif]
<!--////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
								