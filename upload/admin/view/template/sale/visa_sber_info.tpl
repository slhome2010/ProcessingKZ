<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">        
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
        <h1> <img height="22" src="view/image/payment/visa.png" alt="" /> <?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>

   <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-info"></i> <?php echo $text_verified; ?></h3>
      </div>
      <div class="panel-body">
	  
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
		<?php foreach ($orders as $order) { ?>
		  <tr>
			<td class="left"><?php echo $column_visa; ?></td>
            <td><?php echo $order['visa_id']; ?></td>
          </tr>
          <tr>
            <td><?php echo $column_order; ?></td>
            <td><?php echo $order['order_id']; ?></td>
          </tr>
          <tr>
            <td><?php echo $column_total; ?></td>
            <td><?php echo $order['total']; ?></td>
			<?php if ((float)$order['total'] > (float)$order['visa_big_total']) { ?>
				<td style="color: red;"> <?php echo $text_visa_big_total; ?>  </td>
			<?php } ?>		
          </tr>
		  <tr>
            <td><?php echo $column_customer; ?></td>
            <td><?php echo $order['customer_reference']; ?></td>
          </tr>
        
          <tr>
            <td><?php echo $column_status; ?></td>
            <td><?php echo $order['transaction_status']; ?></td>
          </tr>
          <tr>
            <td><?php echo $column_date_added; ?></td>
            <td><?php echo $order['date_added']; ?></td>
          </tr>
          <tr>
            <td><?php echo $column_ip; ?></td>
            <td><?php echo $order['ip_address']; ?>	
			<?php if ($order['ip_address']) { ?>
				 [ <a class="text-right" href="http://whatismyipaddress.com/ip/<?php echo $order['ip_address']; ?>" target="_blank"><?php echo $text_geoip; ?></a> ]
		    <?php } ?>			  
			</td>
          </tr>
          <tr>
            <td><?php echo $column_country; ?></td>
            <td><?php echo $order['card_country']; ?></td>
          </tr>
          <tr>
            <td><?php echo $column_number; ?></td>
            <td><?php echo $order['card_number']; ?></td>
          </tr>
          <tr>
            <td><?php echo $column_verified; ?></td>
            <td><?php echo $order['verified3d']; ?></td>
          </tr>  
		 <?php } ?>	
        </table>
      </div>
	  <div > <p style="color: red; margin-left: 20px; fontsize: 16px" >
		  Справка: Список стран с высоким уровнем мошенничества! </p>
		  <p style="color: #003A88; margin-left: 20px; fontsize: 14px"> Ангола, Египет, Гана, Кот-д’Ивуар, Литва, Таиланд, Малайзия, Сингапур, Испания, Австралия, Индонезия, Бразилия, Нигер, 
		  Нигерия, Гонконг, Тайвань, Филиппины, Болгария, Великобритания, Украина, Намибия, Румыния, Аргентина, Чили, Албания, 
		  Перу, Узбекистан, Республика Конго, Мозамбик, Танзания </p>
	  </div>	  
     </div>
    </div>
   </div>
</div>
<?php echo $footer; ?>