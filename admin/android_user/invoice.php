<?php

include_once('dbConnect.php');
$orderid=$_REQUEST['orderid'];
$orderid = '123457890';






//$addressdata=mysqli_fetch_assoc(mysqli_query($conn,"SELECT addresses.*,users.email FROM `orders` join addresses on orders.d_address=addresses.id join users on orders.userid=users.id WHERE `orderid`='$orderid' LIMIT 1"));

// $address=$addressdata['addressline1']."<br/>";
// $address.=$addressdata['streetaddress']."<br/>";
// $address.=$addressdata['pincode']."<br/>";
// $address.=$addressdata['city'].$addressdata['state']."<br/>";
$address = "karnavati express b2-101, SURAT station";

//$contactnumber=$addressdata['contactno'];
$contactnumber = '7894561230';
//$usernamebill=$addressdata['name'];
 $usernamebill = 'sumit monapara';
 //$email=$addressdata['email'];
$email="sumitmonapara@gmail.com";
  $to =$email;
         $subject = "Added To Cart ";
         
         
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
 $message.= '<td>';
 
 
 $message.= 'Invoice #: '.$orderid.'<br>';
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
 $message.= 'contact : +91 9727516195'.'<br>';
 $message.= 'address : FF 30-31, Bakrol square, Bakrol, Anand-388120';
 $message.= 'email : hungerontrain@gmail.com'.'<br>';
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
 $message.= 'KABIR RESTAURANT'.'<br>';
 $message.= 'address of restaurant'.'<br>';
 $message.= 'contact of restaurant'.'<br>';
 $message.='<br><br>';
 $message.= '</td>';
 $message.= '</tr>';
  $message.= '<tr class="heading">';
 $message.= '<td>';
 $message.= 'Item';
 $message.= '</td>';
 $message.= '';
 $message.= '<td>';
 $message.= 'Price';
 $message.= '</td>';
 $message.= '</tr>';
 
         
        
        
     //    $result=mysqli_query($conn,"SELECT products.*,orders.quantity FROM `orders` join products on orders.productid=products.id WHERE orderid='$orderid'");
        
      
     //    $total=0;
        
     //    while(($item=mysqli_fetch_assoc($result))!=null)
    	// {
    	//     $amount = (int)$item['price'] * (int) $item['quantity'];  
     //      $message.= '<tr class="item">';
     //         $message.= '<td>';
     //         $message.= $item['productname']."(".$item['quantity'].")";
     //         $message.= '</td>';
     //         $message.= '';
     //         $message.= '<td>';
     //         $message.= $amount;
     //         $message.= '</td>';
     //         $message.= '</tr>';
            
     //       $total=$total+$amount;
           
     //    }
        
        
        
        
        $message.=$table;
        $total = '100';
        
        
        $message.= '<tr class="total">';
 $message.= '<td></td>';
 $message.= '';
 $message.= '<td>';
 $message.= 'Total: '.$total;
 $message.= '</td>';
 $message.= '</tr>';
 $message.= '</table>';
 $tomorrow = date('m/d/Y', time() + (86400*2));
 $message.= '</div>';
 $message.= '</body>';
 $message.= '</html>';
        
        
         $header = "From:hungerontrain@gjb.com \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";
         echo $message;
         echo "invoice sent to :". $email;
         //$result = mail ($to,$subject,$message,$header);
// if($result)
// 	echo "invoice sent to :". $email;
// else
// 	echo "error";
?>