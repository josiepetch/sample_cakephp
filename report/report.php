<?php
class Report extends AppModel {

	var $name = 'Report';
        
        function search_recrivepart($data){
        if (!empty($data['start']['day']) AND !empty($data['start']['month']) AND !empty($data['start']['year']) 
			AND !empty($data['end']['day']) AND !empty($data['end']['month']) AND !empty($data['end']['year']) ) {//วันที่เริ่มจ๊อบ
				$open_date = $data['start']['year'].'-'.$data['start']['month'].'-'.$data['start']['day'].' 06:00:00.001';
				$stop_date = $data['end']['year'].'-'.$data['end']['month'].'-'.$data['end']['day'].' 23:00:00.193';
		}else{ 
                $open_date = date('Y')."-".date('m')."-".date('d').' 06:00:00.001';
                $stop_date = date('Y')."-".date('m')."-".date('d').' 23:00:00.193';
		}
        if(!empty($data['part']['sort'])){
            $order = $data['part']['sort'];
        }else{
            $order = 'main_location';
        }
        if (!empty($data['part']['brand'])) {//ยี่ห้อ
            $brand = "AND carBrand  = '" . $data['part']['brand'] . "' ";
        }else{
            $brand = "";
        }
		$filter = $this->get_recrivepart_filter($data);
		$query_str ="
        WITH PartReceive AS
		(
            SELECT [part_on_stock_YAs].warehouse_id, [part_infos].id, [part_infos].part_name_th, [part_on_stock_YAs].main_location,
                dbo.GetCarModelText([part_infos].id) as carModel
                ,dbo.GetCarBrandText([part_infos].id) as carBrand1,
                ROW_NUMBER() OVER (ORDER BY [part_infos].id) as [RowNum],

                ISNULL((
                    SELECT SUM(quantity) FROM [part_transcaction_YA] 
                    WHERE [type] = 12 AND [part_transcaction_YA].part_info_id = [part_infos].id AND 
                        [part_transcaction_YA].update_timestamp BETWEEN '".$open_date."' AND '".$stop_date."'
                ), 0) as take_over_quan,
                ISNULL((
                    SELECT SUM(quantity) FROM [part_transcaction_YA] 
                    WHERE [type] = 6 AND [part_transcaction_YA].part_info_id = [part_infos].id AND 
                        [part_transcaction_YA].update_timestamp BETWEEN '".$open_date."' AND '".$stop_date."'
                ), 0) as receive_quan,
                ISNULL((
                    SELECT SUM(quantity) FROM [part_transcaction_YA] 
                    WHERE [type] = 2 AND [part_transcaction_YA].part_info_id = [part_infos].id AND 
                        [part_transcaction_YA].update_timestamp BETWEEN '".$open_date."' AND '".$stop_date."'
                ), 0) as disposit_quan,
                ISNULL((
                    SELECT SUM(quantity) FROM [part_transcaction_YA] 
                    WHERE ([type] = 2 OR [type] = 12 OR [type] = 6 OR [type] = 8 OR [type] = 9) AND [part_transcaction_YA].part_info_id = [part_infos].id AND 
                        [part_transcaction_YA].update_timestamp BETWEEN '".$open_date."' AND '".$stop_date."'
                ), 0) as other_rec_quan,
                (
                    ISNULL((
                        SELECT SUM(quantity) FROM [part_transcaction_YA] 
                        WHERE [type] = 6 AND [part_transcaction_YA].part_info_id = [part_infos].id AND 
                            [part_transcaction_YA].update_timestamp BETWEEN '".$open_date."' AND '".$stop_date."'
                    ), 0) +
                    ISNULL((
                        SELECT SUM(quantity) FROM [part_transcaction_YA] 
                        WHERE [type] = 2 AND [part_transcaction_YA].part_info_id = [part_infos].id AND 
                            [part_transcaction_YA].update_timestamp BETWEEN '".$open_date."' AND '".$stop_date."'
                    ), 0) +
                    ISNULL((
                        SELECT SUM(quantity) FROM [part_transcaction_YA] 
                        WHERE ([type] = 2 OR [type] = 12 OR [type] = 6 OR [type] = 8 OR [type] = 9) AND [part_transcaction_YA].part_info_id = [part_infos].id AND 
                            [part_transcaction_YA].update_timestamp BETWEEN '".$open_date."' AND '".$stop_date."'
                    ), 0) 
                ) as sum_rec_quan,

                ISNULL((
                    SELECT SUM(quantity) FROM [part_transcaction_YA], [work_orders]
                    WHERE [type] = 1 AND 
                        [work_orders].id = [part_transcaction_YA].work_order_id AND
                        [work_orders].work_type_id = 0 AND
                        [part_transcaction_YA].part_info_id = [part_infos].id AND 
                        [part_transcaction_YA].update_timestamp BETWEEN '".$open_date."' AND '".$stop_date."'
                ), 0) as sell_quan,

                ISNULL((
                    SELECT SUM(quantity) FROM [part_transcaction_YA] 
                    WHERE [type] = 1 AND [part_transcaction_YA].part_info_id = [part_infos].id AND 
                        [part_transcaction_YA].update_timestamp BETWEEN '".$open_date."' AND '".$stop_date."'
                ), 0) as withdraw_quan,

                ( 
                    ISNULL((
                        SELECT SUM(quantity) FROM [part_transcaction_YA], [work_orders]
                        WHERE [type] = 1 AND 
                            [work_orders].id = [part_transcaction_YA].work_order_id AND
                            [work_orders].work_type_id = 0 AND
                            [part_transcaction_YA].part_info_id = [part_infos].id AND 
                            [part_transcaction_YA].update_timestamp BETWEEN '".$open_date."' AND '".$stop_date."'
                    ), 0) +

                    ISNULL((
                        SELECT SUM(quantity) FROM [part_transcaction_YA] 
                        WHERE [type] = 1 AND [part_transcaction_YA].part_info_id = [part_infos].id AND 
                            [part_transcaction_YA].update_timestamp BETWEEN '".$open_date."' AND '".$stop_date."'
                    ), 0)
                ) as total_out_quan
				,(SELECT [car_brand_id] FROM [yontrakij_db].[dbo].[part_infos]
     			 where [yontrakij_db].[dbo].[part_infos].[part_code]=[yontrakij_db].[dbo].[part_on_stock_YAs].[part_info_id])as carBrand


            FROM [part_on_stock_YAs], [part_infos]
            WHERE [part_on_stock_YAs].part_info_id = [part_infos].id ".$filter."
		)
		SELECT *, ROW_NUMBER() OVER (ORDER BY ".$order.") as [RowNum] FROM PartReceive WHERE [total_out_quan] <> 0 ".$brand." ";

		$job_repairs = $this->query($query_str, false);		
			
		return $job_repairs;
	}
    
    function search_recrivepart_pdf($data,$brand){
		if (!empty($data['start']['day']) AND !empty($data['start']['month']) AND !empty($data['start']['year']) 
			AND !empty($data['end']['day']) AND !empty($data['end']['month']) AND !empty($data['end']['year']) ) {//วันที่เริ่มจ๊อบ
				$open_date = $data['start']['year'].'-'.$data['start']['month'].'-'.$data['start']['day'].' 06:00:00.001';
				$stop_date = $data['end']['year'].'-'.$data['end']['month'].'-'.$data['end']['day'].' 23:00:00.193';
		}else{ 
                $open_date = date('Y')."-".date('m')."-".date('d').' 06:00:00.001';
                $stop_date = date('Y')."-".date('m')."-".date('d').' 23:00:00.193';
		}
		if(!empty($data['part']['sort'])){
            $order = $data['part']['sort'];
        }else{
            $order = 'main_location';
        }
		$filter = $this->get_recrivepart_filter($data);
		 
		$query_str ="
        WITH PartReceive AS
		(
            SELECT [part_on_stock_YAs].warehouse_id, [part_infos].id, [part_infos].part_name_th, [part_on_stock_YAs].main_location,
                dbo.GetCarModelText([part_infos].id) as carModel
                ,dbo.GetCarBrandText([part_infos].id) as carBrand1,
                ROW_NUMBER() OVER (ORDER BY [part_infos].id) as [RowNum],

                ISNULL((
                    SELECT SUM(quantity) FROM [part_transcaction_YA] 
                    WHERE [type] = 12 AND [part_transcaction_YA].part_info_id = [part_infos].id AND 
                        [part_transcaction_YA].update_timestamp BETWEEN '".$open_date."' AND '".$stop_date."'
                ), 0) as take_over_quan,
                ISNULL((
                    SELECT SUM(quantity) FROM [part_transcaction_YA] 
                    WHERE [type] = 6 AND [part_transcaction_YA].part_info_id = [part_infos].id AND 
                        [part_transcaction_YA].update_timestamp BETWEEN '".$open_date."' AND '".$stop_date."'
                ), 0) as receive_quan,
                ISNULL((
                    SELECT SUM(quantity) FROM [part_transcaction_YA] 
                    WHERE [type] = 2 AND [part_transcaction_YA].part_info_id = [part_infos].id AND 
                        [part_transcaction_YA].update_timestamp BETWEEN '".$open_date."' AND '".$stop_date."'
                ), 0) as disposit_quan,
                ISNULL((
                    SELECT SUM(quantity) FROM [part_transcaction_YA] 
                    WHERE ([type] = 2 OR [type] = 12 OR [type] = 6 OR [type] = 8 OR [type] = 9) AND [part_transcaction_YA].part_info_id = [part_infos].id AND 
                        [part_transcaction_YA].update_timestamp BETWEEN '".$open_date."' AND '".$stop_date."'
                ), 0) as other_rec_quan,
                (
                    ISNULL((
                        SELECT SUM(quantity) FROM [part_transcaction_YA] 
                        WHERE [type] = 6 AND [part_transcaction_YA].part_info_id = [part_infos].id AND 
                            [part_transcaction_YA].update_timestamp BETWEEN '".$open_date."' AND '".$stop_date."'
                    ), 0) +
                    ISNULL((
                        SELECT SUM(quantity) FROM [part_transcaction_YA] 
                        WHERE [type] = 2 AND [part_transcaction_YA].part_info_id = [part_infos].id AND 
                            [part_transcaction_YA].update_timestamp BETWEEN '".$open_date."' AND '".$stop_date."'
                    ), 0) +
                    ISNULL((
                        SELECT SUM(quantity) FROM [part_transcaction_YA] 
                        WHERE ([type] = 2 OR [type] = 12 OR [type] = 6 OR [type] = 8 OR [type] = 9) AND [part_transcaction_YA].part_info_id = [part_infos].id AND 
                            [part_transcaction_YA].update_timestamp BETWEEN '".$open_date."' AND '".$stop_date."'
                    ), 0) 
                ) as sum_rec_quan,

                ISNULL((
                    SELECT SUM(quantity) FROM [part_transcaction_YA], [work_orders]
                    WHERE [type] = 1 AND 
                        [work_orders].id = [part_transcaction_YA].work_order_id AND
                        [work_orders].work_type_id = 0 AND
                        [part_transcaction_YA].part_info_id = [part_infos].id AND 
                        [part_transcaction_YA].update_timestamp BETWEEN '".$open_date."' AND '".$stop_date."'
                ), 0) as sell_quan,

                ISNULL((
                    SELECT SUM(quantity) FROM [part_transcaction_YA] 
                    WHERE [type] = 1 AND [part_transcaction_YA].part_info_id = [part_infos].id AND 
                        [part_transcaction_YA].update_timestamp BETWEEN '".$open_date."' AND '".$stop_date."'
                ), 0) as withdraw_quan,

                ( 
                    ISNULL((
                        SELECT SUM(quantity) FROM [part_transcaction_YA], [work_orders]
                        WHERE [type] = 1 AND 
                            [work_orders].id = [part_transcaction_YA].work_order_id AND
                            [work_orders].work_type_id = 0 AND
                            [part_transcaction_YA].part_info_id = [part_infos].id AND 
                            [part_transcaction_YA].update_timestamp BETWEEN '".$open_date."' AND '".$stop_date."'
                    ), 0) +

                    ISNULL((
                        SELECT SUM(quantity) FROM [part_transcaction_YA] 
                        WHERE [type] = 1 AND [part_transcaction_YA].part_info_id = [part_infos].id AND 
                            [part_transcaction_YA].update_timestamp BETWEEN '".$open_date."' AND '".$stop_date."'
                    ), 0)
                ) as total_out_quan
				,(SELECT [car_brand_id] FROM [yontrakij_db].[dbo].[part_infos]
     			 where [yontrakij_db].[dbo].[part_infos].[part_code]=[yontrakij_db].[dbo].[part_on_stock_YAs].[part_info_id])as carBrand

            FROM [part_on_stock_YAs], [part_infos]
            WHERE [part_on_stock_YAs].part_info_id = [part_infos].id ".$filter."
		)
		SELECT * FROM PartReceive WHERE [total_out_quan] <> 0 ".$brand." ORDER BY ".$order."";

		$job_repairs = $this->query($query_str, false);		
			
		return $job_repairs;
	}
        
        function get_avgsale_filter($data){
		$filter = "";
		if (!empty($data['avgSale'])) {
			if (!empty($data['avgSale']['id_part'])) {//รหัสอะไหล่
				$filter = $filter .  
				" AND a1.part_info_id = '" . $data['avgSale']['id_part'] . "' ";
			}
			if (!empty($data['avgSale']['brand'])) {//ยี่ห้อรถ
                $filter = $filter .  
				" AND a3.car_brand_id = '" . $data['avgSale']['brand'] . "' ";
			}
			if ($data['avgSale']['work_type'] == '0' || $data['avgSale']['work_type'] == '1') {//ประเภทงาน
				$filter = $filter .  
				" AND a2.work_type_id = " . $data['avgSale']['work_type'] . " ";
			}	
		}
		
		return $filter;
		
	}
    
    function search_avgsale_detail($part_code,$work_type_id,$start,$end){
        /*$query_str ="
		WITH WorkOrders AS
		(
		SELECT part_infos1.part_code, part_infos1.part_name_th, dbo.part_transcaction_YA.id, 
               dbo.part_transcaction_YA.work_order_id, dbo.work_orders.work_type_id, dbo.work_orders.work_date, 
               YEAR(dbo.work_orders.work_date)AS work_year , MONTH(dbo.work_orders.work_date)AS work_month, 
               dbo.part_info_stock_price_view.price, 
               dbo.report_brand_for_recrive.car_brand_name_th,dbo.part_on_stock_YAs.quantity,
					(					                   
                    SELECT AVG(dbo.part_transcaction_YA.sell_price) AS Expr1
					FROM  dbo.part_infos INNER JOIN
								   dbo.part_transcaction_YA ON dbo.part_infos.id = dbo.part_transcaction_YA.part_info_id AND 
								   dbo.part_infos.id = dbo.part_transcaction_YA.part_info_id INNER JOIN
								   dbo.work_orders ON dbo.part_transcaction_YA.work_order_id = dbo.work_orders.id
					WHERE (dbo.work_orders.work_type_id = '0')and(dbo.part_transcaction_YA.part_info_id = part_infos1.id)                 
                    ) AS AVG_PRICE,
                    (					                   
                    SELECT SUM(dbo.part_transcaction_YA.sell_price) AS Expr1
					FROM  dbo.part_infos INNER JOIN
								   dbo.part_transcaction_YA ON dbo.part_infos.id = dbo.part_transcaction_YA.part_info_id AND 
								   dbo.part_infos.id = dbo.part_transcaction_YA.part_info_id INNER JOIN
								   dbo.work_orders ON dbo.part_transcaction_YA.work_order_id = dbo.work_orders.id
					WHERE (dbo.work_orders.work_type_id = '0')and(dbo.part_transcaction_YA.part_info_id = part_infos1.id) 
                     ) AS SUM_PRICE 
                , ROW_NUMBER() OVER (ORDER BY [part_infos1].[id] ) as [RowNum] 
		
        FROM dbo.part_infos AS part_infos1 INNER JOIN
               dbo.part_transcaction_YA ON part_infos1.id = dbo.part_transcaction_YA.part_info_id INNER JOIN
               dbo.work_orders ON dbo.part_transcaction_YA.work_order_id = dbo.work_orders.id INNER JOIN
               dbo.part_info_stock_price_view ON part_infos1.id = dbo.part_info_stock_price_view.id LEFT OUTER JOIN
               dbo.part_on_stock_YAs ON part_infos1.id = dbo.part_on_stock_YAs.part_info_id LEFT OUTER JOIN
               dbo.report_brand_for_recrive ON part_infos1.part_code = dbo.report_brand_for_recrive.part_id
		 
        WHERE (dbo.work_orders.work_type_id = '0') 
        ) SELECT * FROM [WorkOrders]  WHERE [part_code] = '".$part_code."' ";*/
        $filter = "";
        if($start && $end){ $filter =" AND work_date BETWEEN '{$start}' AND '{$end}'";}
        $query_str ="        
            WITH WorkOrders AS
            (
            SELECT * ,YEAR(work_date)AS work_year , MONTH(work_date)AS work_month
            FROM part_avg_sell_detail
            ) SELECT * FROM [WorkOrders]  
            WHERE [part_info_id] = '".$part_code."' AND [work_type_id] = '".$work_type_id."' ".$filter.";";
		$job_repairs = $this->query($query_str, false);
        
        return $job_repairs;
    }
	function search_avgsale($begin, $end, $data){
		$filter = $this->get_avgsale_filter($data);
        $filter_date = "";
        if (!empty($data['start']['day']) AND !empty($data['start']['month']) AND !empty($data['start']['year']) 
        AND !empty($data['end']['day']) AND !empty($data['end']['month']) AND !empty($data['end']['year']) ) {//วันที่เริ่มจ๊อบ
            $open_date = $data['start']['year'].'-'.$data['start']['month'].'-'.$data['start']['day'];
            $stop_date = $data['end']['year'].'-'.$data['end']['month'].'-'.$data['end']['day'];
            $filter_date = $filter_date .  
            "AND dbo.work_orders.work_date BETWEEN '$open_date' AND '$stop_date'";
        }	
		/*$query_str ="
		WITH WorkOrders AS
		(
		SELECT part_infos1.part_code, part_infos1.part_name_th, dbo.part_transcaction_YA.id, dbo.part_transcaction_YA.work_order_id, 
               dbo.work_orders.work_type_id, dbo.work_orders.work_date, dbo.part_info_stock_price_view.price, dbo.report_brand_for_recrive.car_brand_name_th,dbo.part_on_stock_YAs.quantity,
					(					                   
                    SELECT AVG(dbo.part_transcaction_YA.sell_price) AS Expr1
					FROM  dbo.part_infos INNER JOIN
								   dbo.part_transcaction_YA ON dbo.part_infos.id = dbo.part_transcaction_YA.part_info_id AND 
								   dbo.part_infos.id = dbo.part_transcaction_YA.part_info_id INNER JOIN
								   dbo.work_orders ON dbo.part_transcaction_YA.work_order_id = dbo.work_orders.id
					WHERE (dbo.work_orders.work_type_id = '0')and(dbo.part_transcaction_YA.part_info_id = part_infos1.id) 
					" . $filter . "                 
                    ) AS AVG_PRICE,
                    (					                   
                    SELECT SUM(dbo.part_transcaction_YA.sell_price) AS Expr1
					FROM  dbo.part_infos INNER JOIN
								   dbo.part_transcaction_YA ON dbo.part_infos.id = dbo.part_transcaction_YA.part_info_id AND 
								   dbo.part_infos.id = dbo.part_transcaction_YA.part_info_id INNER JOIN
								   dbo.work_orders ON dbo.part_transcaction_YA.work_order_id = dbo.work_orders.id
					WHERE (dbo.work_orders.work_type_id = '0')and(dbo.part_transcaction_YA.part_info_id = part_infos1.id) 
					" . $filter . "             
                    ) AS SUM_PRICE 
		, ROW_NUMBER() OVER (ORDER BY [part_infos1].[id] ) as [RowNum] 
		
		FROM dbo.part_infos AS part_infos1 INNER JOIN
               dbo.part_transcaction_YA ON part_infos1.id = dbo.part_transcaction_YA.part_info_id INNER JOIN
               dbo.work_orders ON dbo.part_transcaction_YA.work_order_id = dbo.work_orders.id INNER JOIN
               dbo.part_info_stock_price_view ON part_infos1.id = dbo.part_info_stock_price_view.id LEFT OUTER JOIN
               dbo.part_on_stock_YAs ON part_infos1.id = dbo.part_on_stock_YAs.part_info_id LEFT OUTER JOIN
               dbo.report_brand_for_recrive ON part_infos1.part_code = dbo.report_brand_for_recrive.part_id
		 
		WHERE (dbo.work_orders.work_type_id = '0')
		" . $filter . "
		)
		SELECT * FROM [WorkOrders] WHERE [RowNum] BETWEEN {$begin} AND {$end}";*/
        $query_str ="
            WITH Report AS(
            SELECT     a1.part_info_id ,a2.work_type_id
                        ,(SELECT SUM(dbo.part_transcaction_YA.quantity) 
                        FROM  dbo.part_transcaction_YA INNER JOIN
                            dbo.work_orders ON dbo.part_transcaction_YA.work_order_id = dbo.work_orders.id
                        WHERE dbo.part_transcaction_YA.part_info_id = a1.part_info_id AND dbo.part_transcaction_YA.type = 1 
                            AND dbo.work_orders.work_type_id = a2.work_type_id ".$filter_date."
                        GROUP BY dbo.part_transcaction_YA.part_info_id, dbo.part_transcaction_YA.type, dbo.work_orders.work_type_id)AS sell_quan
                        ,a3.price ,a3.quantity ,a3.part_code ,a3.part_name_th ,a3.car_brand_id
                        ,a4.car_brand_name_th 
                        ,ROW_NUMBER() OVER (ORDER BY a1.part_info_id) as RowNum
            FROM         dbo.part_transcaction_YA as a1 INNER JOIN
                                dbo.work_orders as a2 ON a1.work_order_id = a2.id  INNER JOIN
                                dbo.part_info_sum_stock_view as a3 ON a3.id = a1.part_info_id INNER JOIN
                                dbo.car_brands a4 ON a3.car_brand_id = a4.id
            WHERE 1 = 1 ".$filter."
            GROUP BY a1.part_info_id, a1.type, a2.work_type_id, a3.price ,a3.quantity ,a3.part_code ,a3.part_name_th ,a3.car_brand_id ,a4.car_brand_name_th
            HAVING      (a1.type = 1)
            )
            SELECT * ,(sell_quan*price) AS SUM_PRICE
            FROM Report WHERE RowNum BETWEEN {$begin} AND {$end} ORDER BY Report.part_info_id";
		$job_repairs = $this->query($query_str, false);		
			
		return $job_repairs;
	}
    
    function search_avgsale_count($data){
		
		$filter = $this->get_avgsale_filter($data);
		$filter_date = "";
        if (!empty($data['reports']['start']['day']) AND !empty($data['reports']['start']['month']) AND !empty($data['reports']['start']['year']) 
        AND !empty($data['reports']['end']['day']) AND !empty($data['reports']['end']['month']) AND !empty($data['reports']['end']['year']) ) {//วันที่เริ่มจ๊อบ
            $open_date = $data['reports']['start']['year'].'-'.$data['reports']['start']['month'].'-'.$data['reports']['start']['day'];
            $stop_date = $data['reports']['end']['year'].'-'.$data['reports']['end']['month'].'-'.$data['reports']['end']['day'];
            $filter_date = $filter_date .  
            "AND dbo.work_orders.work_date BETWEEN '$open_date' AND '$stop_date'";
        } 
		$query_str ="
            WITH Report AS(
            SELECT     a1.part_info_id ,a2.work_type_id
                        ,(SELECT SUM(dbo.part_transcaction_YA.quantity) 
                        FROM  dbo.part_transcaction_YA INNER JOIN
                            dbo.work_orders ON dbo.part_transcaction_YA.work_order_id = dbo.work_orders.id
                        WHERE dbo.part_transcaction_YA.part_info_id = a1.part_info_id AND dbo.part_transcaction_YA.type = 1 
                            AND dbo.work_orders.work_type_id = a2.work_type_id ".$filter_date."
                        GROUP BY dbo.part_transcaction_YA.part_info_id, dbo.part_transcaction_YA.type, dbo.work_orders.work_type_id)AS sell_quan
                        ,a3.price ,a3.quantity ,a3.part_code ,a3.part_name_th ,a3.car_brand_id
                        ,a4.car_brand_name_th 
                        ,ROW_NUMBER() OVER (ORDER BY a1.part_info_id) as RowNum
            FROM         dbo.part_transcaction_YA as a1 INNER JOIN
                                dbo.work_orders as a2 ON a1.work_order_id = a2.id  INNER JOIN
                                dbo.part_info_sum_stock_view as a3 ON a3.id = a1.part_info_id INNER JOIN
                                dbo.car_brands a4 ON a3.car_brand_id = a4.id
            WHERE 1 = 1 ".$filter."
            GROUP BY a1.part_info_id, a1.type, a2.work_type_id, a3.price ,a3.quantity ,a3.part_code ,a3.part_name_th ,a3.car_brand_id ,a4.car_brand_name_th
            HAVING      (a1.type = 1)
            )
            SELECT * ,(sell_quan*price) AS SUM_PRICE
            FROM Report ORDER BY Report.part_info_id";

		$job_repairs = $this->query($query_str, false);		
			
		return $job_repairs;
	}
        
}
?>