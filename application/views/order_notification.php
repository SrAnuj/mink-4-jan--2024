<!DOCTYPE html>
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="x-apple-disable-message-reformatting">
      <!--[if !mso]><!-->
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!--<![endif]-->
      <!-- Your title goes here -->
      <title>Delivery Notification</title>
      <!-- End title -->
      <!-- Start stylesheet -->
      <style type="text/css">
         a,a[href],a:hover, a:link, a:visited {
         /* This is the link colour */
         text-decoration: none!important;
         color: #0000EE;
         }
         .link {
         text-decoration: underline!important;
         }
         p, p:visited {
         /* Fallback paragraph style */
         font-size:15px;
         line-height:24px;
         font-family:'Helvetica', Arial, sans-serif;
         font-weight:300;
         text-decoration:none;
         color: #000000;
         }
         h1 {
         /* Fallback heading style */
         font-size:22px;
         line-height:24px;
         font-family:'Helvetica', Arial, sans-serif;
         font-weight:normal;
         text-decoration:none;
         color: #000000;
         }
         .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td {line-height: 100%;}
         .ExternalClass {width: 100%;}
      </style>
      <!-- End stylesheet -->
   </head>
   <!-- You can change background colour here -->
   <body style="text-align: center; margin: 0; padding-top: 10px; padding-bottom: 10px; padding-left: 0; padding-right: 0; -webkit-text-size-adjust: 100%;background-color: #f2f4f6; color: #000000" align="center">
      <!-- Fallback force center content -->
      <div style="text-align: center;">
         <table align="center" style="text-align: center; vertical-align: top; width: 600px; max-width: 600px; background-color: #ffffff;" width="600">
            <tbody>
               <tr>
                  <td style="width: 596px; vertical-align: top; padding-left: 0; padding-right: 0; padding-top: 15px; padding-bottom: 15px;" width="596">
                     <!-- Your logo is here -->
                     <img style="width: 180px; max-width: 180px; height: 85px; max-height: 85px; text-align: center; color: #ffffff;" alt="Logo" src="<?php echo $web_logo; ?>" align="center" width="180" height="85">
                  </td>
               </tr>
            </tbody>
         </table>
         <!-- End container for logo -->
         <!-- Start single column section -->
         <table align="center" style="text-align: center; vertical-align: top; width: 600px; max-width: 600px; background-color: #ffffff;padding:20px;" width="600">
            <tbody>
               <tr>
                  <td style="text-align:justify;">
                     <h1 style="font-size: 20px; line-height: 24px; font-family: 'Helvetica', Arial, sans-serif; font-weight: 600; text-decoration: none; color: #000000;">Dear <?php echo $delivery_boy_name.' '.$delivery_boy_last_name; ?>!</h1>
                    
					<p>I wanted to inform you that a new daily subscription has been assigned with the following details:<br/>
					<strong>Order ID:</strong> <?php echo $order_id; ?><br/>
					<strong>Customer Name:</strong> <?php echo (!empty($customer_name)) ? $customer_name : $first_name.' '.$last_name; ?><br/>
					
					<strong>E-mail ID:</strong> <?php echo $email; ?><br/>
					<strong>Phone Number:</strong> <?php echo $customer_mobile; ?><br/>
					<strong>Delivery Address:</strong> <br/>
					<?php echo $customer_short_address.'<br>'.$customer_address_1.'<br/>'.$customer_address_2.'<br/>'.$zip.'<br/>'.$city.''; ?><br/>
					<strong>Start Date:</strong><?php echo date('d-m-Y',strtotime($start_date)); ?><br/>
					<strong>End Date:</strong><?php echo date('d-m-Y',strtotime($end_date)); ?><br/>
					<strong>Total Days:</strong><?php echo $total_days.' Days'; ?>
					</p>
					<table align="center" style="text-align: center; vertical-align: top; width: 100%;padding: 10px;">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
							<th>Product Description</th>
                            <th>Product Price</th>
							<th>Product Image</th>
                        </tr>
						<thead>
						<tbody>
                            <tr>
                                <td><?php echo $product_id; ?></td>
                                <td><?php echo $product_name.' ('.$product_model.')'; ?></td>
								<td><?php echo $description ?? $product_details; ?></td>
								<td><?php echo $price; ?></td>
								<td>
								<img style="width: 180px; max-width: 180px; height: 85px; max-height: 85px; text-align: center; color: #ffffff;" alt="Logo" src="<?php echo $image_thumb; ?>" align="center" width="180" height="85"></td>
                            </tr>
                    </tbody>
                </table>
					<p>
					 Daily Subscription is a feature used to [briefly describe the purpose or benefits of daily subscription, e.g., specify daily delivery schedules or special instructions].<br/>
					   Please coordinate with each other to ensure a smooth and satisfactory delivery experience.<br/>
					   If you have any questions or need further information, please feel free to contact me at <strong><?php echo $customer_mobile; ?></strong>.<br/>
					<?php echo $customer_mobile; ?></strong>.<br/>
					Thank you for your understanding and cooperation.<br/>
					Sincerely
					<br/>
					<strong>Mink CEO</strong><br/>
					<?php echo $sincerly;  ?>
					</p>
                   </td>
               </tr>
            </tbody>
         </table>
      </div>
   </body>
</html>