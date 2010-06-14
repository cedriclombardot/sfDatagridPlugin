/**
freeze panes / jquery Datagrid
**/
$(document).ready(function(){
    freezePanes();	
});


function freezePanes(dgid){
    if(dgid){
    	grid=$('#'+dgid).find('.grid');
    }else{
    	grid=$('.grid');
    }
	cols= $(grid).find('tr.lt:first').find('td').length;
    c_width=new Array();
    $(grid).find('tr td.filter').each(function(i){
    	c_width[i]=$(this).width();
    	$(this).attr('style','width:'+(c_width[i])+'px');
    });
    $(grid).find('tr th').each(function(i){
    	$(this).attr('style','width:'+(c_width[i])+'px');
    });
	$(grid).find('tbody').append('<tr><td style="padding:0; border:0 none;" colspan="'+cols+'"><div class="grid-fixed-height"><table cellspacing="1" border="0" style="width:100%" cellspadding="0"></table></div></td></tr>');
	$(grid).find('tr').each(function(){
			if($(this).hasClass('lt') || $(this).hasClass('dr')){
				//Resize cols
				$(this).find('td').each(function(i){
					$(this).attr('style','width:'+(c_width[i])+'px');
				});
				//Replace row
				$(this).parent().parent().find('.grid-fixed-height table').append($(this));
			}
		})
	;
}
