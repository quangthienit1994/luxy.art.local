<?php
$nameLang = ['ja'=>'Japan','vi'=>'Vietnam','en'=>'English'];
?>
<table class="table table-hover bg-white">
    <thead>
        <tr>
           <th class="text-center">ID</th>
           <th>言語</th>
           <th class="text-center">操作</th>
       </tr>
    </thead>
    <tbody>
    	<?php foreach ($list as $key => $value) { ?>
     	<tr>
        	<td class="text-center"><?=$key+1?></td>
        	<td><a class="fwb" href="admin/page_add_edit_lang?path=<?=$value?>"><?=$nameLang[str_replace('application/language/', '', $value)]?></a> </td>

        	<td class="text-center">
        		<div class="btn-group">
  				  	<button onclick="location.href='admin/page_add_edit_lang?path=<?=$value?>'" type="button" class="btn btn-warning left"><i class="fa fa-pencil"></i></button>
				    </div>
        	</td>
       	</tr>
       	<?php } ?>
    </tbody>
</table>