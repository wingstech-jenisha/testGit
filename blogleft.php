<div class="category-detail-left">
  <div class="dropdowns">
    <button onClick="myFunction()" class="dropbtn">News And Industry Info <span class="responsive-icon"><i class="fa fa-angle-down" aria-hidden="true"></i></span></button>
    <div id="myDropdown" class="dropdown-content"> 
		<? 
			$BlogQur="select * from flagging_blog order by createdate desc limit 0,5"; 
			$BlogRes=mysql_query($BlogQur);
			$Blogtot= mysql_affected_rows();
			if($Blogtot>0)
			{
				while($BlogRow=mysql_fetch_array($BlogRes))
				{
		?>
		<div class="left-blog-box">
            <div class="left-blog-box-title"><a href="<?php echo GetUrl_Blog($BlogRow['id']);?>"><?php echo stripslashes($BlogRow['title']);?></a></div>
            <p><?php echo substr(strip_tags(stripslashes($BlogRow['description']),"<a><b><i><strong></a></b></i></strong><br></br><u></u>"),0,150); ?>...</p>
			<div class="left-blog-read-more-btn"><a href="<?php echo GetUrl_Blog($BlogRow['id']);?>">Read More >></a></div>
          </div>
		  <? } }?>
	 </div>
  </div>
</div>