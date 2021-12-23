<?php
class PartOrder extends AppModel {

	var $name = 'PartOrder';
	
        function view_po($begin, $end, $data){
		$filter = '';   $filter2 = ''; $filter3 = '';
        
        if ($data == null OR empty($data['PartOrder']['PO_ID']) AND
                empty($data['Emc']['start']['day']) AND empty($data['Emc']['start']['month']) AND empty($data['Emc']['start']['year']) AND
                empty($data['Emc']['end']['day']) AND empty($data['Emc']['end']['month']) AND empty($data['Emc']['end']['year']) AND
                empty($data['status'])){/*
            $filter3 = '
                AND [id] NOT IN
                (
                    SELECT [id] 
                    FROM [yontrakij_db].[dbo].[part_order_po_quantity_summary_view]
                )';*/
            $filter = "
                AND [yontrakij_db].[dbo].[part_order_receive_view].[status] = 'PO' ";
            $filter2 = "
                inner join
                [yontrakij_db].[dbo].[part_order_receive_view] on
                [Orderslist].[PO_ID] = [yontrakij_db].[dbo].[part_order_receive_view].[PO_ID] ";
        }
		if (!empty($data)) {
			
			if (!empty($data['PartOrder']['PO_ID'])) {//po number
				$filter = $filter .
				" AND [PO_ID] LIKE '%" . $data['PartOrder']['PO_ID'] . "%' ";
			}
            if (!empty($data['Emc']['start']['day']) AND !empty($data['Emc']['start']['month']) AND !empty($data['Emc']['start']['year']) 
			AND !empty($data['Emc']['end']['day']) AND !empty($data['Emc']['end']['month']) AND !empty($data['Emc']['end']['year']) ) {//วันที่เริ่มจ๊อบ
				$open_date = $data['Emc']['start']['year'].'-'.$data['Emc']['start']['month'].'-'.$data['Emc']['start']['day'];
				$stop_date = $data['Emc']['end']['year'].'-'.$data['Emc']['end']['month'].'-'.$data['Emc']['end']['day'];
				$filter = $filter .  
				" AND ([order_date] BETWEEN '$open_date' AND '$stop_date')";
			}
            if ($data['status'] == '1') {//status1 open invoice
                $filter = $filter ." AND [PR_ID] IS NOT NULL";
            }
            if ($data['status'] == '2') {//status2 order
                $filter = $filter ." AND [PO_ID] IS NOT NULL";
            }
            if ($data['status'] == '3') {//status3 deliver
                $filter = $filter ." AND [receive_id] IS NULL AND [status] = 'PO'";
                $filter2 = "INNER JOIN [yontrakij_db].[dbo].[part_order_all_id_complete_view]
                    ON [Orderslist].[PO_ID] = [part_order_all_id_complete_view].[PO_ID]";
            }
            if ($data['status'] == '4') {//status4 delivered
                $filter = $filter ." AND [SELL_ID] IS NOT NULL AND [status] = 'PO'";
                $filter2 = "INNER JOIN [yontrakij_db].[dbo].[part_order_all_id_complete_view]
                    ON [Orderslist].[PO_ID] = [part_order_all_id_complete_view].[PO_ID]";
            }
            if ($data['status'] == '5') {//status5 close invoice
                $filter = $filter ." AND [status] = 'PD'";
            }
		}
        
        $query_str =' 
            WITH Orderslist AS
            ( 
                SELECT *, ROW_NUMBER() OVER (ORDER BY [PO_ID]) as [RowNum] 
                FROM [yontrakij_db].[dbo].[part_orders_view] AS Orderslist
                WHERE NOT [PO_ID] IS NULL AND 1 = 1 '.$filter3.'
            )
            SELECT * 
            FROM [Orderslist] '.$filter2.'
            WHERE [RowNum] BETWEEN ' . $begin . ' AND ' . $end . ' 
                '. $filter .';';
		return $this->query($query_str, false);
        
	}
        function search_view_po_count($data){
        $filter = '';   $filter2 = ''; $filter3 = ''; $filter4 = '';
        
        if ($data == null OR empty($data['PartOrder']['PO_ID']) AND
                empty($data['Emc']['start']['day']) AND empty($data['Emc']['start']['month']) AND empty($data['Emc']['start']['year']) AND
                empty($data['Emc']['end']['day']) AND empty($data['Emc']['end']['month']) AND empty($data['Emc']['end']['year']) AND
                empty($data['status'])){
            $filter3 = '
                AND [id] NOT IN
                (
                    SELECT [id] 
                    FROM [yontrakij_db].[dbo].[part_order_po_quantity_summary_view]
                )';
        }
		if (!empty($data)) {
			
			if (!empty($data['PartOrder']['PO_ID'])) {//po number
				$filter = $filter .
				" AND [PO_ID] = '" . $data['PartOrder']['PO_ID'] . "' ";
			}
            if (!empty($data['Emc']['start']['day']) AND !empty($data['Emc']['start']['month']) AND !empty($data['Emc']['start']['year']) 
			AND !empty($data['Emc']['end']['day']) AND !empty($data['Emc']['end']['month']) AND !empty($data['Emc']['end']['year']) ) {//วันที่เริ่มจ๊อบ
				$open_date = $data['Emc']['start']['year'].'-'.$data['Emc']['start']['month'].'-'.$data['Emc']['start']['day'];
				$stop_date = $data['Emc']['end']['year'].'-'.$data['Emc']['end']['month'].'-'.$data['Emc']['end']['day'];
				$filter = $filter .  
				" AND ([order_date] BETWEEN '$open_date' AND '$stop_date')";
			}
            if ($data['status'] == '1') {//status1
                $filter = $filter ." AND [PR_ID] IS NOT NULL";
            }
            if ($data['status'] == '2') {//status2
                $filter = $filter ." AND [PO_ID] IS NOT NULL";
            }
            if ($data['status'] == '3') {//status3
                $filter4 = $filter4 ."WHERE [SELL_ID] IS NULL";
                $filter2 = "INNER JOIN [yontrakij_db].[dbo].[part_order_all_id_complete_view]
                    ON [Orderslist].[PO_ID] = [part_order_all_id_complete_view].[PO_ID]";
            }
            if ($data['status'] == '4') {//status4
                $filter4 = $filter4 ."WHERE [SELL_ID] IS NOT NULL";
                $filter2 = "INNER JOIN [yontrakij_db].[dbo].[part_order_all_id_complete_view]
                    ON [Orderslist].[PO_ID] = [part_order_all_id_complete_view].[PO_ID]";
            }
            if ($data['status'] == '5') {//status5
                $filter = $filter ." AND [status] = 'PD'";
            }
		}
        
        $query_str =' 
            WITH Orderslist AS
            ( 
                SELECT *
                FROM [yontrakij_db].[dbo].[part_orders_view] AS Orderslist
                WHERE NOT [PO_ID] IS NULL AND 1 = 1 '.$filter3.' '.$filter.'
            )
            SELECT COUNT(*) AS [count]
            FROM [Orderslist] '.$filter2.'  
            '.$filter4;
		return $this->query($query_str, false);
        }
        
        function find_po_order($begin, $end, $data){
		
		$filter = '';
		
		if (!empty($data)) {
			
			if (!empty($data['PartOrder']['PO_ID'])) {
				$filter = 
				"AND dbo.part_orders_detail_view.[PO_ID] = '" . $data['PartOrder']['PO_ID'] . "' ";
			}
		}
		
		$query_str =" 
			SELECT dbo.part_orders_detail_view.*,  dbo.part_order_receive_view.receive_quantity
			FROM      dbo.part_orders_detail_view INNER JOIN
                      dbo.part_order_receive_view ON dbo.part_orders_detail_view.PR_ID = dbo.part_order_receive_view.PR_ID AND 
                      dbo.part_orders_detail_view.PO_ID = dbo.part_order_receive_view.PO_ID
			WHERE 1 = 1 " . $filter . " ORDER BY dbo.part_orders_detail_view.part_code ASC;";

		return $this->query($query_str, false);
	
	}
        
        function save_PO($pr_id, $po_id){
		
		$insert_query = "
			UPDATE [yontrakij_db].[dbo].[part_orders]
			SET [PO_ID] = '" . $po_id . "' , [status] = 'PO', [confirm_date] = GETDATE()
			WHERE [PR_ID] = '" . $pr_id .  "';";
		
		$result = $this->query($insert_query, false);
		
	}
        
}
?>