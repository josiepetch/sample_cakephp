<?php
	
	App::import('Vendor', 'Fpdf', array('file' => 'fpdf/fpdf.php'));
	App::import('Lib','utility');

class PDF extends FPDF
{
	function do_print($PrintDatas, $start, $end, $begin, $page, $total, $vat,  &$total_take_over_quan, &$total_receive_quan, &$total_disposit_quan, &$total_other_rec_quan, &$total_sum_rec_quan, &$total_sell_quan, &$total_withdraw_quan, &$total_total_out_quan){
		
		$finish = false;
		
		$pdf = $this;
		
		$pdf->SetTopMargin(10);
		$pdf->SetAutoPageBreak(false,5);
		$pdf->AddFont('angsa','','angsa.php');
		$pdf->AddFont('angsab','B','angsab.php');	
		$pdf->AddPage('L');	 //Landscape max width 297 - (10*2 sides)
		$lineheight = 6;
		$my_t=getdate(date("U"));
		//$page=$_GET['page'];
		
		//<<----------Header---------->>
		$pdf->SetFont('angsab','B',20);
		$pdf->MultiCell(0,$lineheight,"����ѷ ¹�áԨ���������� �ӡѴ",0,'C');	
		//$pdf->Image('Logo_Yontrakit_logo(final)_.jpg',170,7,20,20,'','http://www.select2web.com');	
		
		//<<----------Title---------->>
		$pdf->SetFont('angsa','',18);
		$pdf->MultiCell(0,8,"��§ҹ����Ѻ�����������Ш��ѹ",0,'C');
	
		//<<----------Date---------->>
		$pdf->SetFont('angsa','',14);
		$start_date = explode("-",$start);
		$start_date[1]=getFullDate($start_date[1]);
		$start_date[2]+=543;
		$end_date = explode("-",$end);
		$end_date[1]=getFullDate($end_date[1]);
		$end_date[2]+=543;
		$pdf->MultiCell(0,8,"�����ҧ�ѹ��� $start_date[0] $start_date[1] $start_date[2] - $end_date[0] $end_date[1] $end_date[2]",0,'C');
		//$pdf->MultiCell(0,8,"��ѧ : (�Ţ) ���������� : (����) ���ö : (���)",0,'C');
		
		//<<----------Page Count---------->>
		$pdf->SetFont('angsa','',12);
		$pdf->Cell(0,$lineheight,"˹�ҷ�� $page",0,1,'R');
				
		//<<---------Create Detail's Table---------->>
		$y = $pdf->GetY();
		$pdf->MultiCell(9,$lineheight,"�ӴѺ\n\n",1,'C'); $pdf->SetXY(19,$y);
		$pdf->MultiCell(10,$lineheight,"��ѧ\n\n",1,'C'); $pdf->SetXY(29,$y);
		$pdf->MultiCell(8,$lineheight,"������\n\n",1,'C'); $pdf->SetXY(37,$y);
		$pdf->MultiCell(30,$lineheight,"����������\n\n",1,'C'); $pdf->SetXY(67,$y);
		$pdf->MultiCell(48,$lineheight,"����������\n\n",1,'C'); $pdf->SetXY(115,$y);
		$pdf->MultiCell(22,$lineheight,"ʶҹ�����\n\n",1,'C'); $pdf->SetXY(137,$y);
		$pdf->MultiCell(15,$lineheight,"¡��\n\n",1,'C'); $pdf->SetXY(152,$y); 
		$x = $pdf->GetX();
		$pdf->Cell(60,$lineheight,"�Ѻ���",1,0,'C'); $pdf->SetXY(212,$y);
		$pdf->Cell(60,$lineheight,"�����͡",1,0,'C');  $pdf->SetXY(272,$y);
		$pdf->MultiCell(15,$lineheight,"¡�\n\n",1,'C');	$pdf->SetXY($x,$y+$lineheight);
		
		$inputdata = array("�Ѻ","�׹��ͺ","�Ѻ����","����Ѻ","���","���¨�ͺ","��������","�������");
		for($i = 0; $i < 8; $i ++)
		{
			$pdf->Cell(15,$lineheight,"$inputdata[$i]",1,0,'C');
		}	
		$pdf->Ln();
		
		$size = sizeof($PrintDatas); //sizeof data
			
		for($i = $begin+0;$i < $begin+20;$i++){
			$row = $i+1;
			$pdf->Cell(9,$lineheight,"$row",1,0,'C');
			$pdf->Cell(10,$lineheight,"{$PrintDatas[$i][0]['warehouse_id']}",1,0,'L');
			$pdf->Cell(8,$lineheight,"{$PrintDatas[$i][0]['carBrand']}",1,0,'C');
			$pdf->Cell(30,$lineheight,"{$PrintDatas[$i][0]['id']}",1,0,'C');
			$pdf->Cell(48,$lineheight,"{$PrintDatas[$i][0]['part_name_th']}",1,0,'L');
			$pdf->Cell(22,$lineheight,"{$PrintDatas[$i][0]['main_location']}",1,0,'C');
			$pdf->Cell(15,$lineheight,"{$PrintDatas[$i][0]['take_over_quan']}",1,0,'R');
			$pdf->Cell(15,$lineheight,"{$PrintDatas[$i][0]['receive_quan']}",1,0,'R');
			$pdf->Cell(15,$lineheight,"{$PrintDatas[$i][0]['disposit_quan']}",1,0,'R');
			$pdf->Cell(15,$lineheight,"{$PrintDatas[$i][0]['other_rec_quan']}",1,0,'R');
			$pdf->Cell(15,$lineheight,"{$PrintDatas[$i][0]['sum_rec_quan']}",1,0,'R');
			$pdf->Cell(15,$lineheight,"{$PrintDatas[$i][0]['sell_quan']}",1,0,'R');
			$pdf->Cell(15,$lineheight,"{$PrintDatas[$i][0]['withdraw_quan']}",1,0,'R');
			$pdf->Cell(15,$lineheight,"",1,0,'R');
			$pdf->Cell(15,$lineheight,"{$PrintDatas[$i][0]['total_out_quan']}",1,0,'R');
			$pdf->Cell(15,$lineheight,"",1,0,'R');/*
			$total1+=$PrintDatas[$i][0]['take_over_quan'];
			$total2+=$PrintDatas[$i][0]['receive_quan'];
			$total3+=$PrintDatas[$i][0]['disposit_quan'];
			$total4+=$PrintDatas[$i][0]['other_rec_quan'];
			$total5+=$PrintDatas[$i][0]['sum_rec_quan'];
			$total6+=$PrintDatas[$i][0]['sell_quan'];
			$total7+=$PrintDatas[$i][0]['withdraw_quan'];
			$total9+=$PrintDatas[$i][0]['total_out_quan'];*/
			$total_take_over_quan+=$PrintDatas[$i][0]['take_over_quan'];
			$total_receive_quan+=$PrintDatas[$i][0]['receive_quan'];
			$total_disposit_quan+=$PrintDatas[$i][0]['disposit_quan'];
			$total_other_rec_quan+=$PrintDatas[$i][0]['other_rec_quan'];
			$total_sum_rec_quan+=$PrintDatas[$i][0]['sum_rec_quan'];
			$total_sell_quan+=$PrintDatas[$i][0]['sell_quan'];
			$total_withdraw_quan+=$PrintDatas[$i][0]['withdraw_quan'];
			$total_total_out_quan+=$PrintDatas[$i][0]['total_out_quan'];
			$pdf->Ln();
			
			if($i >= ($size-1)){
				$finish = true;
				break; //���ҹ
			}
		}
		
		if (sizeof($PrintDatas) <= $row){
		$pdf->Cell(127,$lineheight,"���",1,0,'C');
		$pdf->Cell(15,$lineheight,number_format($total_take_over_quan),1,0,'R');
		$pdf->Cell(15,$lineheight,number_format($total_receive_quan),1,0,'R');
		$pdf->Cell(15,$lineheight,number_format($total_disposit_quan),1,0,'R');
		$pdf->Cell(15,$lineheight,number_format($total_other_rec_quan),1,0,'R');
		$pdf->Cell(15,$lineheight,number_format($total_sum_rec_quan),1,0,'R');
		$pdf->Cell(15,$lineheight,number_format($total_sell_quan),1,0,'R');
		$pdf->Cell(15,$lineheight,number_format($total_withdraw_quan),1,0,'R');
		$pdf->Cell(15,$lineheight,"",1,0,'R');
		$pdf->Cell(15,$lineheight,number_format($total_total_out_quan),1,0,'R');
		$pdf->Cell(15,$lineheight,"",1,0,'R');
		$pdf->Ln();
		}
		//<<---------Extra Details---------->>
		$pdf->SetFontSize(12);
		$pdf->Cell(0,$lineheight,"",'B',1,'C');
		$pdf->Cell(0,$lineheight,"�ѹ������� $my_t[mday] $my_t[month] $my_t[year] $my_t[hours]:$my_t[minutes]",0,1,'L');
		return $finish;
	}
}

	$pdf = new PDF();		
	
	$begin = 0;	$page = 1; $total = 0;	$vat = 0;
	$total_take_over_quan = 0;
	$total_receive_quan = 0;
	$total_disposit_quan = 0;
	$total_other_rec_quan = 0;
	$total_sum_rec_quan = 0;
	$total_sell_quan = 0;
	$total_withdraw_quan = 0;
	$total_total_out_quan  = 0;
	//Debugger::dump($PrintDatas[1]);
	do{
		$finish = $pdf->do_print($PrintDatas, $start, $end, $begin, $page, $total, $vat, $total_take_over_quan, $total_receive_quan, $total_disposit_quan, $total_other_rec_quan, $total_sum_rec_quan, $total_sell_quan, $total_withdraw_quan, $total_total_out_quan);
		$begin += 20;
		$page++;
	}while(!$finish);
	
	//<<----------Send Output---------->>
	$pdf->Output();	
?>






