function dg_send(form, datagridName, type, url)
{
	
	
    var oForm = $("form#"+form);
    
    switch(type)
    {
        case 'search':
        	dg_hide_show(datagridName);
        	$.ajax({
        		url: url,
        		global: false,
        		type: 'POST',
        		data: (oForm.serialize()),
        		success: function(msg){
        			$('div#'+datagridName).empty().append(msg);
        		 	$('div#loader-' + datagridName).hide();	
        		 }
        	});
            break;
        
        case 'reset':
        	oForm=document.getElementById(form);
        	el=oForm.elements;
        
        	
        	for(i in el){
        		
        			el[i].value='';
        			
        		
        	}
        	url+='/reset/1';
        	dg_send(form, datagridName, 'search', url);
            break;
           
        case 'action':
        	dg_hide_show(datagridName);
        	$.ajax({
        		url: $('#'+datagridName + '_select').val(),
        		global: false,
        		type: 'POST',
        		data: (oForm.serialize()),
        		success: function(msg){
        			$('div#'+datagridName).empty().append(msg);
        		 	$('div#loader-' + datagridName).hide();	
        		 }
        	});
           
            
            break;
    }
}

function dg_check_all(chk){
    var checked_status = chk.checked;
    $(chk).parent().parent().parent().find("input.gridline_chk[type='checkbox']").attr('checked',checked_status);
}
function dg_keydown(form, datagridName, type, url, e)
{
    if(e.keyCode == 13)
    {
        dg_send(form, datagridName, type, url);
    }
    
    return false;
}

function dg_hide_show(name)
{    
    if($('div#loader-' + name))
    {
        $('div#loader-' + name).show();
    }
}