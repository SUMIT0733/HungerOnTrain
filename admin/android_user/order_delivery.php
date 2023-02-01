<?php

    include_once('dbConnect.php');
    $id = $_REQUEST['id'];
    //$id = '20190417100';

    function sendInvoice($con,$order){
        
            $addressdata = mysqli_fetch_assoc(mysqli_query($con,"SELECT customer.customer_email,orders.*,restaurants.*,city_master.city_name FROM orders JOIN restaurants ON orders.restaurant_id = restaurants.restaurant_id JOIN city_master ON restaurants.restaurant_city = city_master.city_id JOIN customer ON customer.customer_id = orders.customer_id WHERE `order_id`={$order}"));
           
            $address = $addressdata['delivery_address'].'<br>'.$addressdata['delivery_station'];
            $contactnumber = $addressdata['end_customer_contact'];
            $usernamebill = $addressdata['end_customer_name'];
            $email= $addressdata['customer_email'];
            $to =$email;

            $rest_name = $addressdata['restaurant_name'];
            $rest_address = $addressdata['restaurant_address'];
            $rest_contact = $addressdata['contact_no'];

            $total = $addressdata['order_amount'];
            $effect = $addressdata['effect_amount'];
            $discount = $total - $effect;

            $subject = "HungerOnTrain Order";
            $message="";
            $message.= '<html>';
            $message.= '<head>';
            $message.= '<meta charset="utf-8">';
            $message.= '<title>'.'Invoice of Hunger on train'.'</title>';
            $message.= '';
            $message.= '<style>';
            $message.= '.invoice-box {';
            $message.= 'max-width: 800px;';
            $message.= 'margin: auto;';
            $message.= 'padding: 30px;';
            $message.= 'border: 1px solid #eee;';
            $message.= 'box-shadow: 0 0 10px rgba(0, 0, 0, .15);';
            $message.= 'font-size: 16px;';
            $message.= 'line-height: 24px;';

            $message.= 'color: #555;';
            $message.= '}';
            $message.= '';
            $message.= '.invoice-box table {';
            $message.= 'width: 100%;';
            $message.= 'line-height: inherit;';
            $message.= 'text-align: left;';
            $message.= '}';
            $message.= '';
            $message.= '.invoice-box table td {';
            $message.= 'padding: 5px;';
            $message.= 'vertical-align: top;';
            $message.= '}';
            $message.= '';
            $message.= '.invoice-box table tr td:nth-child(2) {';
            $message.= 'text-align: right;';
            $message.= '}';
            $message.= '';
            $message.= '.invoice-box table tr.top table td {';
            $message.= 'padding-bottom: 20px;';
            $message.= '}';
            $message.= '';
            $message.= '.invoice-box table tr.top table td.title {';
            $message.= 'font-size: 45px;';
            $message.= 'line-height: 45px;';
            $message.= 'color: #333;';
            $message.= '}';
            $message.= '';
            $message.= '.invoice-box table tr.information table td {';
            $message.= 'padding-bottom: 40px;';
            $message.= '}';
            $message.= '';
            $message.= '.invoice-box table tr.heading td {';
            $message.= 'background: #eee;';
            $message.= 'border-bottom: 1px solid #ddd;';
            $message.= 'font-weight: bold;';
            $message.= '}';
            $message.= '';
            $message.= '.invoice-box table tr.details td {';
            $message.= 'padding-bottom: 20px;';
            $message.= '}';
            $message.= '';
            $message.= '.invoice-box table tr.item td{';
            $message.= 'border-bottom: 1px solid #eee;';
            $message.= '}';
            $message.= '';
            $message.= '.invoice-box table tr.item.last td {';
            $message.= 'border-bottom: none;';
            $message.= '}';
            $message.= '';
            $message.= '.invoice-box table tr.total td:nth-child(2) {';
            $message.= 'border-top: 2px solid #eee;';
            $message.= 'font-weight: bold;';
            $message.= '}';
            $message.= '';
            $message.= '@media only screen and (max-width: 600px) {';
            $message.= '.invoice-box table tr.top table td {';
            $message.= 'width: 100%;';
            $message.= 'display: block;';
            $message.= 'text-align: center;';
            $message.= '}';
            $message.= '';
            $message.= '.invoice-box table tr.information table td {';
            $message.= 'width: 100%;';
            $message.= 'display: block;';
            $message.= 'text-align: center;';
            $message.= '}';
            $message.= '}';
            $message.= '';
            $message.= '/** RTL **/';
            $message.= '.rtl {';
            $message.= 'direction: rtl;';

            $message.= '}';
            $message.= '';
            $message.= '.rtl table {';
            $message.= 'text-align: right;';
            $message.= '}';
            $message.= '';
            $message.= '.rtl table tr td:nth-child(2) {';
            $message.= 'text-align: left;';
            $message.= '}';
            $message.= '</style>';
            $message.= '</head>';
            $message.= '';
            $message.= '<body>';
            $message.= '<div class="invoice-box">';
            $message.= '<table cellpadding="0" cellspacing="0">';
            $message.= '<tr class="top">';
            $message.= '<td colspan="2">';
            
            $message.= '';
            $message.= '';
            $message.= '<table>';
            $message.= '<tr>';
            $message.= '<td class="title">';
            $message.= '<img src="http://hungerontrain.ml/android_user/HOT.png" style="width:100%; max-width:100px;">';
            $message.= '</td>';
            $message.= '';
            $message.= '<td style="margin-left:100px">';
            
            
            $message.= 'Invoice #: '.$order.'<br>';
            $message.= 'Created:'.date("m/d/Y h:i:s a", time()).' <br>';

            $message.= '</td>';
            $message.= '</tr>';
            $message.= '</table>';
            $message.= '</td>';
            $message.= '</tr>';
            $message.= '';
            $message.= '<tr class="information">';
            $message.= '<td colspan="2">';
            $message.= '<table>';
            $message.= '<tr>';
            $message.= '<td>';
            $message.= 'Hunger On Train'.'<br>';
            $message.= 'Contact : +91 9727516195'.'<br>';
            $message.= 'Address : FF 30-31, Bakrol square, Bakrol, Anand-388120'.'<br>';
            $message.= 'Email : hungerontrain@gmail.com'.'<br>';
            $message.= 'GSTIN : 24AAACB5343E1Z7';

            $message.= '</td>';
            $message.= '';
            $message.= '<td>';
            $message.= $usernamebill.'<br>';
            $message.= $contactnumber.'<br>';
            $message.= $email.'<br>';
            $message.= $address;
            $message.= '</td>';
            $message.= '</tr>';
            $message.= '</table>';
            $message.= '</td>';
            $message.= '</tr>';
            $message.= '<tr>';
            $message.= '<td>';
            $message.= $rest_name.'<br>';
            $message.= $rest_address.'<br>';
            $message.= $rest_contact.'<br>';
            $message.='<br><br>';
            $message.= '</td>';
            $message.= '</tr>';
            $message.= '<tr class="heading">';
            $message.= '<td>';
            $message.= 'Item';
            $message.= '</td>';
            $message.= '';
            $message.= '<td style="text-align:right">';
            $message.= 'Price';
            $message.= '</td>';
            $message.= '</tr>';
            
                    
                $result=mysqli_query($con,"SELECT orders_inventory.unit,orders_inventory.subtotal,menu.food_item_name,menu.Veg FROM orders_inventory JOIN menu ON menu.food_item_id = orders_inventory.food_id WHERE `order_id`={$order}");
                        
                   while(($item=mysqli_fetch_assoc($result)))
                    {
                        $message.= '<tr class="item">';
                        $message.= '<td>';
                        $message.= $item['unit'].' * '.$item['food_item_name'];
                        $message.= '</td>';
                        $message.= '';
                        $message.= '<td style="text-align:right">';
                        $message.= $item['subtotal'];
                        $message.= '</td>';
                        $message.= '</tr>';
                   }
                    
                    
                    // $message.=$table;
                    // $total = '100';
                    
                    $message.= '<tr class="total">';
            $message.= '<td></td>';
            $message.= '';
            $message.= '<td style="text-align:right">';
            $message.= 'Total: '.$total;
            $message.= '</td>';
            $message.= '</tr>';
            $message.= '<tr class="total">';
            $message.= '<td></td>';
            $message.= '';
            $message.= '<td style="text-align:right">';
            $message.= 'Discount : '.$discount;
            $message.= '</td>';
            $message.= '</tr>';
            $message.= '<tr class="total">';
            $message.= '<td></td>';
            $message.= '';
            $message.= '<td style="text-align:right">';
            $message.= 'Delivery charge: 10';
            $message.= '</td>';
            $message.= '</tr>';
            $message.= '<tr class="total">';
            $message.= '<td></td>';
            $message.= '';
            $message.= '<td style="text-align:right">';
            $message.= 'Total Paid amount: '.($effect + 10);
            $message.= '</td>';
            $message.= '</tr>';
            $message.= '</table>';
            $message.= '<hr>';
            $message.= '<div style="text-align:center">Terms and Condition</div>';
            $message.= '<hr>';
            $message.= '<div style="text-align:center">Subject to Anand Judicial</div>';
            $message.= '<div style="text-align:center">This is System generated Invoice</div>';
            $message.= '</div>';
            $message.= '</body>';
            $message.= '</html>';
                    
                    
                    $header = "From:hungerontrain@gjb.com \r\n";
                    $header .= "MIME-Version: 1.0\r\n";
                    $header .= "Content-type: text/html\r\n";
                    echo $message;
                    $result = mail ($to,$subject,$message,$header);
                    
            if($result)
            	echo "invoice sent to :". $email;
            else
            	echo "error";
    }
    
    $sql1 = "UPDATE `orders` SET `order_status`= 4 WHERE `order_id` = '$id'";
    $res1 = mysqli_query($con,$sql1) or die("Error in 1");
    if($res1){
        $val['responce'] = "success";
        // $sql2 = "update notifications set `notification_type`= 4, status=0 where `order_id`='$id'";
        // $res2 = mysqli_query($con,$sql2) or die("Error in 2");
        sendInvoice($con,$id);
    }else{
        
        $val['responce'] = "error";
    }

    echo json_encode($val);
    mysqli_close($con);
?>