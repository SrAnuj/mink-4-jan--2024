<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html dir="ltr" lang="eng">

<head>
    <title>TODO supply a title</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400;1,700&display=swap"
        rel="stylesheet">
    <style>
    *,
    ::after,
    ::before {
        box-sizing: border-box;
    }

    body {
        padding: 0;
        font-family: Lato, "Helvetica Neue", Arial, Helvetica, sans-serif;
    }
    </style>
</head>

<body>
    <div class="invoice-wrap" id="printableArea"
        style="max-width:272.12598425px;background:#fff;margin-right:auto;margin-left:auto;font-size:14px;color:#5b5b5b">
        <link href="<?php echo MOD_URL . 'dashboard/assets/css/print.css'; ?>" rel="stylesheet" type="text/css" />
        <div style="text-align: center; margin-bottom: 10px;">
            <div style="border: 1px solid #000;font-weight: 700;font-size: 17px;color: #000;">
                <?php echo html_escape($company_info[0]['company_name']); ?> </div>
        </div>

        <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center"
            style="color: #000;font-size: 11px;margin-bottom: 10px;">

            <tbody>
                <tr>
                    <th style="text-align: left;">Date</th>
                    <th style="text-align: center;" dir="ltr" lang="eng"> <?php echo html_escape($final_date) ?> </th>
                </tr>
                <tr>
                    <th style="text-align: left;">Invoice No</th>
                    <th style="text-align: center;" dir="ltr" lang="eng"><?php echo html_escape($invoice_no); ?></th>
                </tr>
                <tr>
                    <th style="text-align: left;">Customer</th>
                    <th style="text-align: center;"> <?php echo html_escape($customer_name); ?> </th>
                </tr>
                <tr>
                    <th style="text-align: left;">Customer Phone</th>
                    <th style="text-align: center;"> <?php echo html_escape($customer_mobile); ?> </th>
                </tr>
                 <tr>
                    <th style="text-align: left;">Token no #</th>
                    
                      <?php
	$this->db->select('invoice', 'invoice_no');
		$query = $this->db->get('invoice');
		$result = $query->result_array();
		$invoice_no = count($result);
		if ($invoice_no >= 1  && $invoice_no < 2) {
			$invoice_no = 1000 + (($invoice_no == 1) ? 0 : $invoice_no) + 1;
		} elseif ($invoice_no >= 2) {
			$invoice_no = 1000 + (($invoice_no == 1) ? 0 : $invoice_no);
		} else {
			$invoice_no = 1000;
		}

// $this->db->select('*');
// $this->db->from('product_purchase');
// $this->db->order_by('invoice_no', 'desc');
// $this->db->limit(1);  

// $number = $this->db->get()->result();
// // $lastid = $number[0]['invoice_no'];
// // $am = $reward_amount[0]['amount'];
// foreach($number as $n){
// $lastid = $n->invoice_no;
// // $lastid = '100';

// }
// // var_dump($lastid);
// // die();
// if(empty($lastid)){
//     $inv = "E-000001";

   
// }


// else {
//     $idd = str_replace("E-",""  ,$lastid);
  
//     $id = str_pad($idd + 1,3,'0',STR_PAD_LEFT);
    
//     $inv = 'mink-inv' .$id;

// }
        ?>
                    <th style="text-align: center;"> <?php echo html_escape($token); ?> </th>
                </tr>
            </tbody>
        </table>

        <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center"
            style="color: #000;font-size: 11px;border-collapse: collapse;margin-bottom: 10px;">
            <thead>
                <tr>
                    <th style="background-color: #ccc;border: 1px solid #000;">
                        <div>Item</div>

                    </th>
                    <th style="background-color: #ccc;border: 1px solid #000;">
                        <div>Qty</div>

                    </th>
                    <th style="background-color: #ccc;border: 1px solid #000;">
                        <div>Batch</div>

                    </th>
                    <th style="background-color: #ccc;border: 1px solid #000;">
                        <div>Price</div>

                    </th>
                    <th style="background-color: #ccc;border: 1px solid #000;">
                        <div>Disc</div>

                    </th>
                    <th style="background-color: #ccc;border: 1px solid #000;">
                        <div>Tax</div>

                    </th>
                    <th style="background-color: #ccc;border: 1px solid #000;">
                        <div>Total Price</div>

                    </th>
                </tr>
                <tr>
                    <th colspan="7" style="border: 1px solid #000;">
                        <div style="position: relative;">
                            <div style="height: 3px;background-color: #000;width: 99%;margin: auto;"></div>
                            <div
                                style="height: 1px;background-color: #fff;width: 100%;position: absolute;z-index: 9;top: 1px;left: 0;">
                            </div>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_quantity = $total_return_amount = $i_grand_discount = $i_total_discount_price_amount = $i_total_discount_price = $i_grand_amount = 0;
				$totalAmount = 0;
				$cgst_tax= 0;
				$sgst_tax = 0;
				$igst_tax = 0;
				$discount = 0; 
				
				$totalCGST = 0;
				$totalSGST = 0;
				
                foreach ($invoice_all_data as $item) {
                ?>
                <tr>
                    <th style="border: 1px solid #000;"><?php echo html_escape($item['product_model']); ?></th>
                    <th style="border: 1px solid #000;"><?php echo html_escape($item['quantity']); ?></th>
                    <th style="border: 1px solid #000;"><?php echo html_escape($item['batch_no']); ?></th>
                    <th style="border: 1px solid #000;"><?php echo html_escape($item['rate']); ?></th>
                    <th style="border: 1px solid #000;"><?php echo html_escape($item['invoice_discount']); ?></th>
                    <th style="border: 1px solid #000;"><?php echo html_escape($item['total_vat']); ?></th>
                    <th style="border: 1px solid #000;">
                        <?php echo (($position == 0) ? $currency . '' . $item['total_price'] : $item['total_price'] . '' . $currency) ?>
                    </th>
                </tr>
                <tr>
                    <th colspan="7" style="border: 1px solid #000;">
                        <?php
                            $arabic_name = $this->db->select('trans_name')->from('product_translation')->where('language', 'Arabic')->where('product_id', $item['product_id'])->get()->row();
                            if (!empty($arabic_name->trans_name)) { ?>
                        <div><?php echo html_escape($arabic_name->trans_name); ?></div>
                        <?php
                            }

                            ?>

                        <div dir="ltr" lang="eng"><?php echo html_escape($item['product_name']); ?> -
                            (<?php echo html_escape($item['product_model']); ?>)</div>
                    </th>
                </tr>
                <tr>
                    <th colspan="7" style="border: 1px solid #000;">
                        <div style="position: relative;">
                            <div style="height: 3px;background-color: #000;width: 99%;margin: auto;"></div>
                            <div
                                style="height: 1px;background-color: #fff;width: 100%;position: absolute;z-index: 9;top: 1px;left: 0;">
                            </div>
                        </div>
                    </th>
                </tr>
                <?php
				
				/* 
					My Calculation 
				*/
				
				 if (!empty($item['sgst'])) {
                                $sgst =  ($item['sgst'] ) * 2;
                                   $base_price = ($item['price'] * 100)/(100+$sgst);
                                    //  $base_price=  number_format($base_price, 2, '.', '');
                                     $sgst_tax = ($item['price'] * $sgst)/(100+$sgst)*$item['qty'];
                            }
						$totalSGST += $sgst_tax;
						
						$totalPrice = $item['quantity'] * $base_price;
							$totalAmount += $totalPrice + $sgst_tax + $item['igst'];
							$sub_total = $totalAmount - $totalSGST;
					/* 
					End My Calculation 
					*/
                    $item['price']    = ($item['rate']);
                    $i_total_price    = $item['quantity'] * ($item['price']);
                    $i_total_discount_price = $item['quantity'] * ($item['price'] - $item['invoice_discount']);
                    $i_discount_amount = $item['invoice_discount'] * ($item['quantity']);
                    $i_grand_discount += $i_discount_amount;
                    $i_total_discount_price_amount += $i_total_discount_price;
                    $i_grand_amount   += $i_total_price;
                    $taxAmount = ($item['total_vat']);
					
					
                }
                ?>
				<?php 
				if (($state === 'Punjab' || $ship_state === 'Punjab') || ($state == null || $ship_state == null)) {
					$cg_tax = $taxAmount / 2;
				} else {
					$cg_tax = $taxAmount;
				}

				 
				?>
            </tbody>
        </table>

        <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center"
            style="color: #000;font-size: 11px;margin-bottom: 20px;">
            <tbody>
			<tr>
                    <th style="text-align: left;padding: 3px 10px 3px 0px">Discount</th>
                    <th style="text-align: center;">
                        <?php echo (($position == 0) ? $currency . "" . number_format($i_grand_discount,2) : number_format($i_grand_discount,2) . "" . $currency); ?>
                    </th>
                </tr>
               <!-- <tr>
                    <th style="text-align: left;padding: 3px 10px 3px 0px">Total Before Discount</th>
                    <th style="text-align: center;">
                        <?php echo (($position == 0) ? $currency . "" . $i_grand_amount : $i_grand_amount . "" . $currency); ?>
                    </th>
                </tr>
                
                 <tr>
                    <th style="text-align: left;padding: 3px 10px 3px 0px">Total with Discount</th>
                    <th style="text-align: center;">
                        <?php echo (($position == 0) ? $currency . "" . $i_total_discount_price_amount : $i_total_discount_price_amount . "" . $currency); ?>
                    </th>
                </tr>
				
                <tr>
                    
                    <th style="text-align: left;padding: 3px 10px 3px 0px">Items with Tax</th>
                    <th style="text-align: center;">
                        <?php echo (($position == 0) ? $currency . "" . ($i_total_discount_price_amount + $taxAmount) : ($i_total_discount_price_amount + $taxAmount) . "" . $currency) ?>
                    </th>
                </tr>
                <tr> 
                    <th style="text-align: left;padding: 3px 10px 3px 0px">Items without Tax</th>
                    <th style="text-align: center;">
                        <?php echo (($position == 0) ? $currency . "" . $i_total_discount_price_amount : $i_total_discount_price_amount . "" . $currency) ?>
                    </th>
                </tr>-->
				<?php if (($state === 'Punjab' || $ship_state === 'Punjab') || ($state == null || $ship_state == null)): 
				?>
				<tr>
                    <th style="text-align: left;padding: 3px 10px 3px 0px">Total CGST</th>
                    <th style="text-align: center;">
                        <?php echo (($position == 0) ? $currency . "" . number_format($cg_tax,2) : number_format($cg_tax,2) . "" . $currency); ?>
                    </th>
                </tr>
                <tr>
                    <th style="text-align: left;padding: 3px 10px 3px 0px">Total SGST</th>
                    <th style="text-align: center;">
                        <?php echo (($position == 0) ? $currency . "" . number_format($cg_tax,2) : number_format($cg_tax,2) . "" . $currency); ?>
                    </th>
                </tr>
				<?php
				else:
				?>
				<tr>
                    <th style="text-align: left;padding: 3px 10px 3px 0px">Total IGST</th>
                   <th style="text-align: center;">
                        <?php echo (($position == 0) ? $currency . "" . number_format($cg_tax,2) : number_format($cg_tax,2) . "" . $currency); ?>
                    </th>
                </tr>
				<?php
				endif;
				?>
                <tr>
                    <th style="text-align: left;padding: 3px 10px 3px 0px">Total with Tax</th>
                    <th style="text-align: center;">
                        <?php echo (($position == 0) ? $currency . "" . $total_amount : $total_amount . "" . $currency) ?>
                    </th>
                </tr>
            </tbody>
        </table>
        <ul style="font-size: 13px;color: #000;font-weight: 700;padding-right: 15px;">
            <?php
            if (!empty($invoice_text_details)) {
                foreach ($invoice_text_details as $key => $invoice_text) {

            ?>
            <li> <?php echo html_escape($invoice_text->invoice_text); ?> </li>
            <?php
                }
            }
            ?>
        </ul>
        <div style="text-align: center;">
            <?php
            $company_vat = $this->db->select('vat_no')->from('company_information')->where('status', 1)->get()->row();
            $base_encoded = base64_encode($company_info[0]['company_name'] . '  ' . $company_vat->vat_no . '  ' . $invoice_all_data[0]['created_at'] . '  ' . $total_amount . '  ' . $invoice_all_data[0]['total_vat']);
            ?>
            <?php
            $checkQr = $this->db->select("isActive")->from("captcha_print_setting")->get()->row();
            if (@$checkQr->isActive == 1) {
            ?>
            <img src="https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl=<?php echo $base_encoded; ?>"
                alt="Invoice QR code">
            <?php } ?>
        </div>
    </div>
    <input type="hidden" id="pos_place" value="<?php echo @$this->input->get('place', TRUE); ?>">
    <input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
    <script src="<?php echo MOD_URL . 'dashboard/assets/js/pos_invoice_html_redirect.js'; ?>"></script>
</body>

</html>